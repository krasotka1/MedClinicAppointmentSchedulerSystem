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

$docid = get_current_user_id();
//$docid = 3;
$patid = '';
global $wpdb;
$time = current_time( 'mysql' );
$visits = '';
$user = '';
if (isset($_GET["id"]))
{
    $patid = $_GET["id"];
} 
if($patid != ''){
$visits = $wpdb->get_results("SELECT date_visit, description, result  FROM wp_appointments WHERE patient_id=". $patid ." AND doctor_id=". $docid." ORDER BY date_visit ASC");
 $docNames = [];
 $user = get_userdata( $patid );
 echo '<h2> Medical file for patient '.$user->display_name.'</h2>';
}

?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
  
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    var visits = <?php echo json_encode($visits); ?>;
    var pat = <?php echo json_encode($user); ?>;
    var spanid = 0;
    var butid = 0;
        var mainCont = document.getElementById('main');
        var form= document.createElement("form"); 
        form.setAttribute("id", "apps");
        mainCont.appendChild(form);
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
        t = "Problem description: ";
        text = document.createTextNode(visits[i].description);
        arow4.appendChild(text);
        var rightside = document.createElement("div");   
        rightside.setAttribute("class", "right-side");
        rightside.style = "width:250px;margin-right:-250px;float:left;";
        bothsides.appendChild(rightside);
        var arow5 = document.createElement("p");   
        rightside.appendChild(arow5);
        if(visits[i].result == null) text = document.createTextNode('Result was not submitted for this visit');
        else text = document.createTextNode(visits[i].result);
        arow5.appendChild(text);
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
        var t = "No appointments made until now";
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
