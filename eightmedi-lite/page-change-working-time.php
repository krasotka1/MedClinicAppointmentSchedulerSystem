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
 
 $user = wp_get_current_user();
 $mon = get_user_meta($user -> ID, 'Mon', true);
 $tue = get_user_meta($user -> ID, 'Tue', true);
 $wed = get_user_meta($user -> ID, 'Wed', true);
 $thu = get_user_meta($user -> ID, 'Thu', true);
 $fri = get_user_meta($user -> ID, 'Fri', true);
 $args = array(
 'orderby' => 'ID',
 'order' => 'ASC'
 );
 $patsdocs= [];
 $users = get_users($args);
 $aux = 0;
 foreach ($users as $user) {
   $patsdocs[$aux]['email'] = $user->user_email;
   $aux = $aux + 1;
   }
?>
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create a doctor Form</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>

      <form action="schedule-added" method="post" id="myform">
      
        <h1>Change working schedule</h1>
        
        
        <fieldset>
          <legend><span class="number">1</span>Working hours</legend>
        </fieldset>
        <label> Since: </label><br>
	<input placeholder="maximum 6 months" type='text' id='txtDate' name='txtDate' />
        <fieldset>
          <label for="mon">Monday:</label>
          <input type="text" id="mon" name="mon" placeholder="HH:MM-HH:MM Format. Leave empty if not working">
          <label for="tue">Tuesday:</label>
          <input type="text" id="tue" name="tue" placeholder="HH:MM-HH:MM Format. Leave empty if not working">
          <label for="wed">Wednesday:</label>
          <input type="text" id="wed" name="wed" placeholder="HH:MM-HH:MM Format. Leave empty if not working">
          <label for="thu">Thursday:</label>
          <input type="text" id="thu" name="thu" placeholder="HH:MM-HH:MM Format. Leave empty if not working">
          <label for="fri">Friday:</label>
          <input type="text" id="fri" name="fri" placeholder="HH:MM-HH:MM Format. Leave empty if not working">
        </fieldset>
        <button type="submit">Submit</button>
      </form>
      
    </body>
</html>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
  
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    var users= <?php echo json_encode($patsdocs); ?>;
    
    $( "#txtDate" ).datepicker({
          defaultDate: "1",
          changeMonth: true,
          changeYear: true,
          minDate: 1
        });
        
    $('#myform').submit(function() {
    var email =  document.getElementById("mail").value;
    var mon = document.getElementById("mon").value;
    var tue = document.getElementById("tue").value;
    var wed = document.getElementById("wed").value;
    var thu = document.getElementById("thu").value;
    var fri = document.getElementById("fri").value;
    if(mon=="" && tue=="" && wed=="" && thu=="" && fri=="") {
    //Non of the days were filled. At least one has to be filled in
    alert("Doctors must have at least one working day. Please, fill in one of the days.");
    return false;
    }
    var regtime = /^([0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]-([0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;
    
    if(!regtime.test(mon) && !(mon == '' || mon == null)){
        alert("Monday working hours format is not correct. It has to be in hh:mm-hh:mm format");
        return false;
    }else if(!regtime.test(tue) && !(tue == '' || tue == null)){
        alert("Tuesday working hours format is not correct. It has to be in hh:mm-hh:mm format");
        return false;
    }else if(!regtime.test(wed) && !(wed == '' || wed == null)){
        alert("Wednesday working hours format is not correct. It has to be in hh:mm-hh:mm format");
        return false;
    }else if(!regtime.test(thu) && !(thu == '' || thu == null)){
        alert("Thursday working hours format is not correct. It has to be in hh:mm-hh:mm format");
        return false;
    }else if(!regtime.test(fri) && !(fri == '' || fri == null)){
        alert("Fridayworking hours format is not correct. It has to be in hh:mm-hh:mm format");
        return false;
    }
     return true;
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
