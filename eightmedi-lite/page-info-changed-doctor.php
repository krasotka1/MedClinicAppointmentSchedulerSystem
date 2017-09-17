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
function getRandomBytes($nbBytes = 32)
{
    $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
    if (false !== $bytes && true === $strong) {
        return $bytes;
    }
    else {
        throw new \Exception("Unable to generate secure token from OpenSSL.");
    }
}
function generatePassword($length){
    return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode(getRandomBytes($length+1))),0,$length);
}

$fname = $_POST['user_fname'];
$id = get_current_user_id();
$lname = $_POST['user_lname'];
$dni = $_POST['user_dni'];
$email= $_POST['user_email'];
$pass = '';
$userdata = '';
    if (isset($_POST['user_pass'])) {
        // pass generate checked. 
        //$pass = wp_generate_password();
        $pass = generatePassword(9);
    }
global $wpdb;
if($pass == '') $userdata = array(
    'ID'  =>  $id,
    'user_login'  =>  $dni,
    'display_name' =>  $fname.' '.$lname,
    'first_name' => $fname,
    'last_name' => $lname,
    'role' => 'author',
    'user_email' => $email
);
else $userdata = array(
    'ID'  =>  $id,
    'user_login'  =>  $dni,
    'display_name' =>  $fname.' '.$lname,
    'user_pass'   =>  $pass,
    'first_name' => $fname,
    'last_name' => $lname,
    'role' => 'author',
    'user_email' => $email
);
$user_id = wp_insert_user( $userdata ) ;

//On failure
if ( is_wp_error( $user_id ) ) {
    echo "User could not be created";
}else{
// Now we'll add all the optional stuff that the user filled in
$wpdb->update($wpdb->users, array('user_login' => $dni), array('ID' => $id));
if (isset($_POST['user_snum']) && !empty($_POST['user_snum'])) update_user_meta( $user_id, 'description', $_POST['user_snum']);
if (isset($_POST['user_city']) && !empty($_POST['user_city'])) update_user_meta( $user_id, 'city', $_POST['user_city']);
if (isset($_POST['user_addr']) && !empty($_POST['user_addr'])) update_user_meta( $user_id, 'address', $_POST['user_addr']);
if (isset($_POST['user_age']) && !empty($_POST['user_age'])) update_user_meta( $user_id, 'age', $_POST['user_age']);
$mes = 'A new password has been created by your request. 
Password: '.$pass.' 
Your password and other information can be changed in Change Profile menu';
wp_mail( $email, 'New password in MedClinic', $mes);
}
?>
<div class="successApp">Doctor's info was updated successfully!</div>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
