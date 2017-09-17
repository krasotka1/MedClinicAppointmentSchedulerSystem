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
 $args = array(
'role' => 'author',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
 $doctors = [];
 $users = get_users($args);
 $aux = 0;
 foreach ($users as $user) {
   $doctors[$aux]['email'] = $user->user_email;
   $aux = $aux + 1;
   }
?>
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create a doctor Form</title>
    </head>
    <body>

      <form action="doctor-created" method="post" id="myform">
      
        <h1>Doctor register form</h1>
        
        <fieldset>
          <legend><span class="number">1</span> Doctor's basic info</legend>
          <label for="fname">First name:</label>
          <input type="text" id="fname" name="user_fname" required>
          <label for="lname">Last name:</label>
          <input type="text" id="lname" name="user_lname" required>
          <label for="dni">Specialization:</label>
<?php
echo '<select name="spec" id="spec" form="myform">';
global $wpdb;
$args = array(
'role' => 'author',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
$specs = [];
$result = [];
$aux = 0;
$users = get_users($args);
foreach ($users as $user) {
   $spec = get_user_meta($user->id, 'specialization', true);
   $result[$aux][id] = $user->id;
   $result[$aux][name] = $user->display_name;
   $result[$aux][specialization] = $spec;
   array_push($specs, $spec);
   $aux = $aux + 1;
   }
$specs = array_unique($specs,SORT_REGULAR);
foreach ($specs as $speci) {
   echo '<option value="' . $speci . '">' . $speci . '</option>';
   }
echo '</select>';
?>
          <label for="mail">Email:</label>
          <input type="email" id="mail" name="user_email" required>
          <label>Gender:</label>
          <input type="radio" id="fem" value="female" name="doc_gen"><label for="fem" class="light">Female</label><br>
          <input type="radio" id="male" value="male" name="doc_gen"><label for="male" class="light">Male</label>
          
        </fieldset>
        
        <fieldset>
          <legend><span class="number">2</span>Working hours</legend>
        </fieldset>
        <fieldset>
          <label for="mon">Monday:</label>
          <input type="text" id="mon" name="mon" placeholder="HH:MM-HH:MM Format. Separate with ',' if several">
          <label for="tue">Tuesday:</label>
          <input type="text" id="tue" name="tue" placeholder="HH:MM-HH:MM Format. Separate with ',' if several">
          <label for="wed">Wednesday:</label>
          <input type="text" id="wed" name="wed" placeholder="HH:MM-HH:MM Format. Separate with ',' if several">
          <label for="thu">Thursday:</label>
          <input type="text" id="thu" name="thu" placeholder="HH:MM-HH:MM Format. Separate with ',' if several">
          <label for="fri">Friday:</label>
          <input type="text" id="fri" name="fri" placeholder="HH:MM-HH:MM Format. Separate with ',' if several">
        </fieldset>
        <button type="submit">Register</button>
      </form>
      
    </body>
</html>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
  
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    var users= <?php echo json_encode($doctors); ?>;
    $('#myform').submit(function() {
    if(!(document.getElementById('male').checked || document.getElementById('fem').checked)) {
  //gender not selected
alert("Gender not selected. Please, select it before proceeding.");
return false;
}
    var email =  document.getElementById("mail").value;
    var mon = document.getElementById("mon").value;
    var tue = document.getElementById("tue").value;
    var wed = document.getElementById("wed").value;
    var thu = document.getElementById("thu").value;
    var fri = document.getElementById("fri").value;
    if(mon=="" && tue=="" && wed=="" && thu=="" && fri=="") {
    //Non of the days were filled. At least one has to be filled in
    alert("A doctor must have at least one working day. Please, fill in one of the days.");
    return false;
    }
    var reg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var regtime = /^([0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]-([0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](,([0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]-([0[0-9]|1[0-9]|2[0-3]):[0-5][0-9])?$/;
    if (!reg.test(email)) { 
    //not a correct email format introduced
    alert("Not a valid email. An email should be in a 'email@domain.domain' format");
    return false;
    }else{
    for(var y = 0; y<users.length; y++){
    if(users[y].email == email) {
    alert("There is already a user with this email. Please use another one or log in into your account");
    return false;
    }
    }
    }
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
