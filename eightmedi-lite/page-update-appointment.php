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

$doctorID = $_POST['doctorID'];
$date = $_POST['dateSelec'];
$time = $_POST['timeChosen'];
$vid = $_POST['vid'];
$prob = "No problem description specified";
$dateVisit= $date.' '.$time;
$dateVisistNF = date('d-m-Y H:i:s',strtotime($dateVisit));
$endTime = strtotime("+15 minutes", strtotime($dateVisit));
$dateUntil = date('Y-m-d H:i:s',$endTime);
if(isset($_POST['txtArea'])) $prob = $_POST['txtArea'];
$wpdb->query($wpdb->prepare("UPDATE wp_appointments SET description= %s WHERE id=%d",$prob,$vid));
$wpdb->query($wpdb->prepare("UPDATE wp_appointments SET date_visit= %s WHERE id=%d",$dateVisit,$vid));
$wpdb->query($wpdb->prepare("UPDATE wp_appointments SET untilTime= %s WHERE id=%d",$dateUntil,$vid));
?>
<div class="successApp">Appointment was updated successfully!</div>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
