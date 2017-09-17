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
<?php
$patientID = get_current_user_id();
global $wpdb;
$time = current_time( 'mysql' ); 
$visits = $wpdb->get_results("SELECT id, date_visit, doctor_id, description  FROM wp_appointments WHERE patient_id=". $patientID ." AND date_visit> STR_TO_DATE('".$time."', '%Y-%m-%d %H:%i:%s')");
// get all doctors
$args = array(
'role' => 'author',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
 $docNames = [];
 $users = get_users($args);
 $aux = 0;
 foreach ($users as $user) {
   $docNames[$aux]['id'] = $user->ID;
   $docNames[$aux]['name'] = $user->display_name;
   $docNames[$aux]['speciality'] = get_user_meta($user->ID,'specialization',true);   
   $aux = $aux + 1;
   }
?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
  
  jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    var visits = <?php echo json_encode($visits); ?>;
    var docs = <?php echo json_encode($docNames); ?>;
    var spanid = 0;
    var butid = 0;
        var mainCont = document.getElementById('main');
        var form= document.createElement("form"); 
        form.setAttribute("method", "POST");
        form.setAttribute("id", "apps");
        form.setAttribute("action", "request-completed");
        mainCont.appendChild(form);
        var aux = document.createElement("input");   
        aux.setAttribute("type", "submit");
        aux.setAttribute("name", 'buttonAll');
        aux.setAttribute("value", 'Export all events');
        if(visits.length > 0) form.appendChild(aux);
    for(var i = 0; i<visits.length; i++){
    var css = document.createElement("style");
css.type = "text/css";
css.innerHTML = "*.{box-sizing: border-box;}div{display: block;margin-bottom: 5px;}body{    font-size: 13px;    line-height: 19px;    color: #111;}.app-box{  margin-top: 18px!important;position: relative;}.both-sides{position: relative;    padding: 0;}.left-side{width: 100%;    position: relative;    overflow: visible;    zoom: 1;    min-height: 1px;}.right-side{width: 250px;    margin-right: -250px;    float: left;position: relative;    overflow: visible;    zoom: 1;    min-height: 1px;}.a-row {    width: 100%;}.inside-left{margin-bottom: 0!important;position: relative}.button-span{      display: inline-block;    padding: 0;    text-align: center;    text-decoration: none!important;    vertical-align: middle;   }";
document.body.appendChild(css);
        var appbox = document.createElement("div");   
        appbox.setAttribute("class", "app-box");
        form.appendChild(appbox);
        var bothsides = document.createElement("div");   
        bothsides.setAttribute("class", "both-sides");
        bothsides.style = "padding-right:250px";
        appbox.appendChild(bothsides);
        var leftside = document.createElement("div");   
        leftside.setAttribute("class", "left-side");
        leftside.style = "padding-right:3.2%;*width:96.4%;float:left;";
        bothsides.appendChild(leftside);
        var arow = document.createElement("div");   
        arow.setAttribute("class", "a-row");
        leftside.appendChild(arow);
        var insideleft = document.createElement("div");   
        insideleft.setAttribute("class", "inside-left");
        arow.appendChild(insideleft);
        var arow2 = document.createElement("div");   
        arow2.setAttribute("class", "a-row");
        insideleft.appendChild(arow2);
        var aux = new Date(visits[i].date_visit);
        var t = "Appointment for day ";
        var text = document.createTextNode(t.concat(aux.getDate(), "/", aux.getMonth()+1,"/",aux.getFullYear()));
        arow2.appendChild(text);
        var arow3 = document.createElement("div");   
        arow3.setAttribute("class", "a-row");
        insideleft.appendChild(arow3);
        t = "Time: ";
        if(aux.getHours() <= 9) t=t+'0'+aux.getHours()+':';
        else t=t+aux.getHours()+':';
        if(aux.getMinutes() <= 9) t=t+'0'+aux.getMinutes();
        else t=t+aux.getMinutes();
        text = document.createTextNode(t);
        arow3.appendChild(text);
        var arow4 = document.createElement("div");   
        arow4.setAttribute("class", "a-row");
        insideleft.appendChild(arow4);
        t = "With doctor: ";
        var s = "Specialization: ";
        for(var y = 0; y<docs.length; y++){ //search for that doctor's name
            if(docs[y].id == visits[i].doctor_id) {
                t = t + docs[y].name;
                s = s + docs[y].speciality;
                break;
            }
        }
        text = document.createTextNode(t);
        arow4.appendChild(text);
        var arow6 = document.createElement("div");   
        arow6.setAttribute("class", "a-row");
        insideleft.appendChild(arow6);
        text = document.createTextNode(s);
        arow6.appendChild(text);
        var rightside = document.createElement("div");   
        rightside.setAttribute("class", "right-side");
        rightside.style = "width:250px;margin-right:-250px;float:left;";
        bothsides.appendChild(rightside);
        var arow5 = document.createElement("div");   
        arow5.setAttribute("class", "a-row");
        rightside.appendChild(arow5);
        var buttonstack = document.createElement("div");   
        buttonstack.setAttribute("class", "a-button-stack");
        arow5.appendChild(buttonstack);
        var span = document.createElement("span");   
        span.setAttribute("class", "button-span");
        span.setAttribute("id", 'id-' + spanid);
        buttonstack.appendChild(span);
        spanid++;
        var input = document.createElement("input");   
        input.setAttribute("type", "submit");
        input.setAttribute("name", 'button'+butid);
        butid++;
        input.setAttribute("value", 'Export iCal');
        span.appendChild(input);
        var span1 = document.createElement("span");   
        span1.setAttribute("class", "button-span");
        span1.setAttribute("id", 'id-' + spanid);
        buttonstack.appendChild(span1);
        spanid++;
        var input1 = document.createElement("input");   
        input1.setAttribute("type", "submit");
        input1.setAttribute("name", 'button'+butid);
        butid++;
        input1.setAttribute("value", 'Cancel an appointment');
        span1.appendChild(input1);
        var input2 = document.createElement("input");   
        input2.setAttribute("type", "hidden");
        input2.setAttribute("name", 'hidden_id'+butid);
        input2.setAttribute("value", visits[i].id);
        buttonstack.appendChild(input2);
        var hr= document.createElement("hr"); 
        leftside.appendChild(hr);
    }
    if(visits.length == 0){
        //No visits to show
        var css = document.createElement("style");
css.type = "text/css";
css.innerHTML = "*.{box-sizing: border-box;}div{display: block;margin-bottom: 5px;}body{    font-size: 13px;    line-height: 19px;    color: #111;}.app-box{  margin-top: 18px!important;position: relative;}.both-sides{position: relative;    padding: 0;}.left-side{width: 100%;    position: relative;    overflow: visible;    zoom: 1;    min-height: 1px;}.right-side{width: 250px;    margin-right: -250px;    float: left;position: relative;    overflow: visible;    zoom: 1;    min-height: 1px;}.a-row {    width: 100%;}.inside-left{margin-bottom: 0!important;position: relative}.button-span{      display: inline-block;    padding: 0;    text-align: center;    text-decoration: none!important;    vertical-align: middle;   }";
document.body.appendChild(css);
        var appbox = document.createElement("div");   
        appbox.setAttribute("class", "app-box");
        form.appendChild(appbox);
        var bothsides = document.createElement("div");   
        bothsides.setAttribute("class", "both-sides");
        bothsides.style = "padding-right:250px";
        appbox.appendChild(bothsides);
        var arow = document.createElement("div");   
        arow.setAttribute("class", "a-row");
        bothsides.appendChild(arow);
        var t = "You don't have any appointments made.";
        var text = document.createTextNode(t);
        arow.appendChild(text);
    }
    var input= document.createElement("input");   
        input.setAttribute("type", "hidden");
        input.setAttribute("name", 'num_visits');
        input.setAttribute("value", visits.length);
        form.appendChild(input);
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
