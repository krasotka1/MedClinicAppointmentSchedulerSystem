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
 $doc_id = get_current_user_id();
 $mon = '0';
 $tue = '0';
 $wed = '0';
 $thu = '0';
 $fri = '0';
 if (isset($_POST['mon']) && $_POST['mon'] != "") $mon = $_POST['mon'];
 if (isset($_POST['tue']) && $_POST['tue'] != "") $tue = $_POST['tue'];
 if (isset($_POST['wed']) && $_POST['wed'] != "") $wed = $_POST['wed'];
 if (isset($_POST['thu']) && $_POST['thu'] != "") $thu = $_POST['thu'];
 if (isset($_POST['fri']) && $_POST['fri'] != "") $fri = $_POST['fri'];
 $hours = $mon.','.$tue.','.$wed.','.$thu.','.$fri;
 $dateFrom = '';
 if (isset($_POST['txtDate'])) $dateFrom = date("Y-m-d",strtotime($_POST['txtDate']));
 $wpdb->insert('wp_working_schedules', array("doctor_id" => $doc_id, "fromDate" => $dateFrom, "hours" => $hours), array("%d","%s","%s"));
 //success message
?>
<div class="successApp">Working schedule added successfully!</div>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
