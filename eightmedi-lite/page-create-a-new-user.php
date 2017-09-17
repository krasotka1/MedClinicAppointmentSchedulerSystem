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
'role' => 'subscriber',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
 $patients = [];
 $users = get_users($args);
 $aux = 0;
 foreach ($users as $user) {
   $patients[$aux]['login'] = $user->user_login;
   $patients[$aux]['email'] = $user->user_email;
   $aux = $aux + 1;
   }
?>
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up Form</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>

      <form action="user-created" method="post" id="myform">
      
        <h1>Register form</h1>
        
        <fieldset>
          <legend><span class="number">1</span> Patient's basic info</legend>
          <label for="fname">First name:</label>
          <input type="text" id="fname" name="user_fname" required>
          <label for="lname">Last name:</label>
          <input type="text" id="lname" name="user_lname" required>
          <label for="dni">DNI:</label>
          <input type="text" id="dni" name="user_dni" required>
          <label for="mail">Email:</label>
          <input type="email" id="mail" name="user_email" required>
          
          
        </fieldset>
        
        <fieldset>
          <legend><span class="number">2</span>Patient's info</legend>
        </fieldset>
        <fieldset>
          <label for="snum">Insurance number(optional):</label>
          <input type="text" id="snum" name="user_snum">
          <label for="city">City(optional):</label>
          <input type="text" id="city" name="user_city">
          <label for="addr">Address(optional):</label>
          <input type="text" id="addr" name="user_addr">
          <label>Age:</label>
          <input type="radio" id="under_18" value="under_18" name="user_age"><label for="under_18" 
          class="light">Under 18</label><br>
          <input type="radio" id="over_18" value="over_18" name="user_age"><label for="over_18" class="light">18 or older</label>
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
    var users= <?php echo json_encode($patients); ?>;
    $('#myform').submit(function() {
    var dni =  document.getElementById("dni").value;
    var email = document.getElementById("mail").value;
    dni = dni.trim();
    var reg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
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
    if(dni.length == 9){
        dni = dni.toUpperCase(); // so that not to have problems with lower/upper case letter at the end
        for(var i = 0; i<9; i++){
            if(i==8) {
                var c = dni.charAt(i);
                if(c.toLowerCase() != c.toUpperCase()){
                // is a char
		for(var y = 0; y<users.length; y++){
		if(users[y].login.toUpperCase() == dni) {
		alert("There is already a user with this DNI. Please try again or log in into your account");
		return false;
		}
		}
                return true;
                }
            }else{
            var c = dni.charAt(i);
            if (!(c >= '0' && c <= '9')) {
            //is not a number
            break;
            }
        }
    }
    }else {
    alert("A DNI should have 9 characters: 8 digits and 1 letter. Format 11111111Z");
    return false;
    }
    alert("DNI's format is incorrect. Please try again with this format 11111111Z");
    return false;
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
