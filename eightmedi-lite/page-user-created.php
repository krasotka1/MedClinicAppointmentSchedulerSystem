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
$fname = $_POST['user_fname'];
$lname = $_POST['user_lname'];
$dni = $_POST['user_dni'];
$email= $_POST['user_email'];
$pass = wp_generate_password(10);
global $wpdb;
$userdata = array(
    'user_login'  =>  $dni,
    'display_name' =>  $fname.' '.$lname,
    'user_pass'   =>  $pass,
    'first_name' => $fname,
    'last_name' => $lname,
    'role' => 'subscriber',
    'user_email' => $email
);

$user_id = wp_insert_user( $userdata ) ;

//On failure
if ( is_wp_error( $user_id ) ) {
    echo "User could not be created";
}else{
// Now we'll add all the optional stuff that the user filled in
if (isset($_POST['user_snum'])) add_user_meta( $user_id, 'description', $_POST['user_snum'], false );
if (isset($_POST['user_city'])) add_user_meta( $user_id, 'city', $_POST['user_city'], false );
if (isset($_POST['user_addr'])) add_user_meta( $user_id, 'address', $_POST['user_addr'], false );
if (isset($_POST['user_age'])) add_user_meta( $user_id, 'age', $_POST['user_age'], false );
$mes = 'Your account has been created successfully. The following are the username and password used to log in:
Username: '.$dni.' 
Password: '.$pass.' 
All your information can be changed later in Change Profile menu';
wp_mail( $email, 'Account information in MedClinic', $mes);
}

?>
<div class="successApp">Patient was created successfully! All the details can be changed in a Change profile menu</div>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
