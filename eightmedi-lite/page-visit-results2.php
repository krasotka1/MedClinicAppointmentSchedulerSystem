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
            <?php
$patID = get_current_user_id();
global $wpdb;
$time = current_time( 'mysql' ); 
$visits = $wpdb->get_results("SELECT id, date_visit, doctor_id, result  FROM wp_appointments WHERE patient_id=". $patID ." AND DATE(date_visit) < STR_TO_DATE('".$time."', '%Y-%m-%d') AND result IS NOT NULL");
 $aux = 0;
 $visitsAll = [];
 foreach ($visits as $visit) {
   $visitsAll[$aux]['name'] =  get_userdata($visit->doctor_id)-> display_name;
   $visitsAll[$aux]['visit'] = $visit -> date_visit;
   $visitsAll[$aux]['result'] = $visit -> result;
   $aux = $aux + 1;
   }
?>

<body>
<form id="myform">
<p><h1> See a result to a visit. Visits without result won't appear in a list</h1>
<label for="visitName"><h2>Choose a visit:</h2></label> 
<input type="text" id="visitName" placeholder="Input doctor's Name or/and visit date" required> 

<textarea id="myTextBox" name="myTextBox" cols="50" rows="5" placeholder="Input the result of the visit" readonly style="display:none;"></textarea>
<p>
</form>
</body>
<script>
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    
    var visits = <?php echo json_encode($visitsAll); ?>;
    var visitNames = [];
    for (var i = 0; i < visits.length; i++) {
        visitNames[i] = visits[i].name + ' ' + visits[i].visit;
    }
    
$(function() {
    var accentMap = {
      "á": "a",
      "à": "a",
      "ó": "o",
      "ò": "o", 
      "é": "e",
      "è": "e",
      "í": "i",
      "ì": "i",
      "ú": "u",
      "ù": "u"
    };
    var normalize = function( term ) {
      var ret = "";
      for ( var i = 0; i < term.length; i++ ) {
        ret += accentMap[ term.charAt(i) ] || term.charAt(i);
      }
      return ret;
    };
    
    
    $( "#visitName" ).autocomplete({
      source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( visitNames, function( value ) {
          value = value.label || value.value || value;
          return matcher.test( value ) || matcher.test( normalize( value ) );
        }) );
      },select: function (event, ui) {
          var label = ui.item.label;
          var value = ui.item.value;
          document.getElementById("myTextBox").value = visits[visitNames.indexOf(value)].result;
          document.getElementById("myTextBox").style.display = 'inline';
      }
    });
    
    $('#myform').submit(function() {
    
    var n = $('#visitName').val();
    if (visitNames.indexOf(n) < 0){
    alert("A visit was not selected properly. Please try selecting again");
    return false; // return false to cancel form action
    }else {
    document.getElementById("myTextBox").value = visits[visitNames.indexOf(n)].result;
    }

});
});
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
