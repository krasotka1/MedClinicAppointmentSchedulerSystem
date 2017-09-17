<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package 8medi lite
 */

get_header(); ?>
<div class="ed-container">
	<?php 
	global $post;
	$sidebar = get_post_meta($post->ID, 'eightmedi_lite_sidebar_layout', true);
	if($sidebar=='both-sidebar' || $sidebar=='left-sidebar'){
		get_sidebar('left');
	}
	?>
	<div id="primary" class="content-area <?php echo $sidebar;?>">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>

			<?php endwhile; // end of the loop. ?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
            <?php
$patID = get_current_user_id();
global $wpdb;
$permV = get_permalink(333); //Result of a visit url
$time = current_time( 'mysql' ); 
$visits = $wpdb->get_results("SELECT id, date_visit, doctor_id, result  FROM wp_appointments WHERE patient_id=". $patID ." AND DATE(date_visit) < STR_TO_DATE('".$time."', '%Y-%m-%d') AND result IS NOT NULL");
 $aux = 0;
 $visitsAll = [];
 foreach ($visits as $visit) {
   $visitsAll[$aux]['name'] =  get_userdata($visit->doctor_id)-> display_name;
   $visitsAll[$aux]['visit'] = $visit -> date_visit;
   $visitsAll[$aux]['result'] = $visit -> result;
   $visitsAll[$aux]['id'] = $visit -> id;
   $aux = $aux + 1;
   }
?>

<body>
<form id="myform">
<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Visit date</th>
            <th>Doctor's name</th>
            <th>Result</th>
        </tr>
    </thead>
    <tbody id="tbodyid">
    </tbody>
</table>
</form>
</body>
<script>
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    var t = $('#table_id').DataTable();
    var visits = <?php echo json_encode($visitsAll); ?>;
    var permV = <?php echo json_encode($permV); ?>;
    
    var tRows = [];
    for (var i = 0; i < visits.length; i++) {
        var arr = [];
        var url = add_query_arg( permV,'id',visits[i].id);
        arr.push(visits[i].visit,visits[i].name, '<a href="' + url + '">' + 'See result' + '</a>');
        tRows.push(arr);
    }
  t.rows.add(tRows).draw();
  
  function add_query_arg(purl, key,value){
    key = escape(key); value = escape(value);

    var s = purl;
    var pair = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^&]*");

    s = s.replace(r,"$1"+pair);
    //console.log(s, pair);
    if(s.indexOf(key + '=')>-1){


    }else{
        if(s.indexOf('?')>-1){
            s+='&'+pair;
        }else{
            s+='?'+pair;
        }
    }
    //if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};

    return s;
}
});
</script>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
