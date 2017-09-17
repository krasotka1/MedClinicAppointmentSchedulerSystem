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

ob_start();
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

$cancelPressed = false;
$numVisits = $_POST['num_visits'];
$numVisits = (int)$numVisits;
$vid = 0;
global $wpdb;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    for( $i = 0; $i < $numVisits*2; $i+=2 ) {
        $vid = $_POST['hidden_id'.($i+2)];
    if (isset($_POST['button'.$i])) {
        // attended checked. See if In a visit is checked too
        if (isset($_POST['button'.($i+1)])) {
            //both checked
            $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET invisit= %d WHERE id=%d",1,$vid));
            $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET assisted= %d WHERE id=%d",1,$vid));
        } else{
            //Only attended is checked
            $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET assisted= %d WHERE id=%d",1,$vid));
            $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET invisit= %d WHERE id=%d",0,$vid));
        }
    }else{
        // attended not checked
        if (isset($_POST['button'.($i+1)])) {
        // only invisit checked
        $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET invisit= %d WHERE id=%d",1,$vid));
        $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET assisted= %d WHERE id=%d",0,$vid));
        }else{
            //nothing checked
            $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET invisit= %d WHERE id=%d",0,$vid));
            $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET assisted= %d WHERE id=%d",0,$vid));
        }
    }
  } //for
} //server request
?>
<div class="successApp">Visit status was uploaded successfully!</div>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
