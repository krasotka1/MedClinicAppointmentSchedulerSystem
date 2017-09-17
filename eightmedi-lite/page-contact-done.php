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
global $wpdb;
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$mes = $_POST['mes'];
$em = $_POST['email'];
$adem = 'admin@medclinic.x10host.com';
echo "<div class='successApp'>Message sent successfully! You will be contacted as soon as possible</div><br>";
wp_mail( $em, 'Contact confirmation at MedClinic', 'Thank you for contacting with MedClinic. We will try to reply to you as soon as possible');
$mess = 'A contact request was received. 

First name: '.$fname.'

Last name: '.$lname.'

Email: '.$em.'

Message: '.$mes;
wp_mail($adem, 'Contact from guest user', $mess); 
?>


		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
