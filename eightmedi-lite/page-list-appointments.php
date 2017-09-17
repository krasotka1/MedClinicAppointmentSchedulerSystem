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
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

			<?php endwhile; // end of the loop. ?>
            
 <input type="radio" id="tod" name="editList" value="Today's appointments" checked>Today's appointments 
<input type="radio" id="oth" name="editList" value="Other day">Other day <br>
<input placeholder="Select a day" name="dateSelec" type='text' id='txtDate' name='txtDate' style="display:none;"/><br>

<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Time</th>
            <th>Patient name</th>
            <th>DNI</th>
        </tr>
    </thead>
    <tbody id="tbodyid">
<?php
$docID = get_current_user_id();
//$docID = 3;
global $wpdb;
$time = current_time( 'mysql' ); 
$permV = get_permalink(210);
$permVFile = get_permalink(340);
$visitsToday = $wpdb->get_results("SELECT id, date_visit, patient_id, namePatient FROM wp_appointments WHERE doctor_id=". $docID ." AND DATE(date_visit) = STR_TO_DATE('".$time."', '%Y-%m-%d') ORDER BY date_visit ASC");
$visits = $wpdb->get_results("SELECT id, date_visit, patient_id, namePatient, email  FROM wp_appointments WHERE doctor_id=". $docID ." AND DATE(date_visit) > STR_TO_DATE('".$time."', '%Y-%m-%d') ORDER BY date_visit ASC");
   foreach ($visitsToday as $visit) {
       $user = 0;
       if($visit->patient_id == null);
       else $user = get_user_by('id',$visit->patient_id);
       echo "<tr>";
       $url = esc_url( add_query_arg( 'id', $visit->id, $permV ) );
       echo '<td><a href="'. $url.'"target="_blank">'. date("H:i",strtotime($visit->date_visit)). '</a></td>';
       if($user){
       $url2 = esc_url( add_query_arg( 'id', $visit->patient_id, $permVFile ) );
       echo '<td><a href="'. $url2.'"target="_blank">'. $user->display_name. '</a></td>';
       echo '<td>'.$user->user_login. '</td>';
       }else{
           echo '<td>'. $visit->namePatient. '</td>';
           echo '<td>'.'Guest user'. '</td>';
       }
       echo '</tr>';
   }
   $args = array(
'role' => 'subscriber',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
 $users = get_users($args);
?>
    </tbody>
</table>
<script>
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    window.alert = function() {};
    var visitsToday = <?php echo json_encode($visitsToday); ?>;
    var visits = <?php echo json_encode($visits); ?>;
    var users = <?php echo json_encode($users); ?>;
    var permV = <?php echo json_encode($permV); ?>;
    var permVFile = <?php echo json_encode($permVFile); ?>;
    var t = $('#table_id').DataTable();
    $('#table_id').on('click', 'td', function () {
console.log("this");
console.log(t.cell(this).data());

});
    $( "#txtDate" ).datepicker({
          onSelect: function(date) {
            var thisday = [];
            var selD = new Date(date);
            var d = selD.getDate();
            var m = selD.getMonth();
            var year = selD.getFullYear();
            for (var i = 0; i < visits.length; i++) {
                var auxf = new Date(visits[i].date_visit);
                if (auxf.getMonth() == m && auxf.getFullYear() == year && auxf.getDate() == d) {
                    //this day visit. Check if guest or registered user
                    var url = add_query_arg( permV,'id',visits[i].id);
                    if(visits[i].patient_id == null){
                        //Guest user
                        var arr = new Array();
                        var min = auxf.getMinutes();
                        var hour = auxf.getHours();
                        //var url2 = add_query_arg( permVFile,'email',visits[i].email);
                        if(min < 10) min = '00';
                        if(hour < 10) hour = '0' + hour;
                        arr.push('<a href="' + url + '">' + hour + ':' + min + '</a>',visits[i].namePatient,"Guest user");
                        thisday.push(arr);
                    }else{
                        //Registered user
                        for(var y = 0; y<users.length;y++){
                            if(users[y].ID == visits[i].patient_id){
                                var arr = new Array();
                                var min = auxf.getMinutes();
                                var hour = auxf.getHours();
                                var url2 = add_query_arg( permVFile,'id',visits[i].patient_id);
                                if(min < 10) min = '00';
                                if(hour < 10) hour = '0' + hour;
                                arr.push('<a href="' + url + '">' + hour + ':' + min + '</a>','<a href="' + url2 + '">' + users[y].data.display_name + '</a>','<a href="' + url2 + '">' + users[y].data.user_login + '</a>');
                                thisday.push(arr);
                                break;
                            }
                        }
                    }
                    } 
                }
                //$('#table_id').DataTable( {data: thisday} );
                t.clear().rows.add(thisday).draw();
          },
          defaultDate: "0",
          changeMonth: true,
          changeYear: true,
          minDate: 0
        });
    var tod = document.getElementById('tod');
    var oth = document.getElementById('oth');

    tod.onclick = todayShow;
    oth.onclick = otherShow;
    
    function todayShow() {
    var input = document.getElementById('txtDate');
    input.style.display = "none";
    var name = '';
    var thisday = [];
    var login = '';
    var pat_id = -1;
    for(var i = 0; i<visitsToday.length; i++){ 
       var user = null;
       if(visitsToday[i].patient_id == null);
       else 
           for(var y = 0; y<users.length;y++){
               if(users[y].ID == visitsToday[i].patient_id){
                   name = users[y].display_name;
                   login = users[y].user_login;
                   user = true;
                   break;
               }
           }
           var aux = new Date(visitsToday[i].date_visit);
           var arr = [];
           var url = add_query_arg( permV,'id',visitsToday[i].id);
           if(user == null) arr.push('<a href="' + url + '">' + hour + ':' + min + '</a>',visitsToday[i].namePatient,"Guest user");
           else {
           var url2 = add_query_arg( permVFile,'id',visitsToday[i].patient_id);
           arr.push('<a href="' + url + '">' + hour + ':' + min + '</a>','<a href="' + url2 + '">' + name + '</a>','<a href="' + url2 + '">' +login + '</a>');
           }
           thisday.push(arr);
            }
        t.clear().rows.add(thisday).draw();
}
    function otherShow() {
    var input = document.getElementById('txtDate');
    t.clear().draw();
    input.style.display = "inline";
    input.value = '';
    }
    
    function add_query_arg(purl, key,value){
    key = escape(key); value = escape(value);

    var s = purl;
    var pair = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^&]*");

    s = s.replace(r,"$1"+pair);
    if(s.indexOf(key + '=')>-1){


    }else{
        if(s.indexOf('?')>-1){
            s+='&'+pair;
        }else{
            s+='?'+pair;
        }
    }

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
