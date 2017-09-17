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
// author = doctor role
 $doc_id = get_current_user_id();
 $timeFrom = '00:00';
 $timeTo = '00:00';
 $dateFrom = date("Y-m-d",strtotime($_POST['from']));
 $dateTo = date("Y-m-d",strtotime($_POST['to']));
 $reason = '';
 if(isset($_POST['time']) && !empty($_POST['time'])) $timeFrom = $_POST['time'];
 if(isset($_POST['time2']) && !empty($_POST['time2'])) $timeTo = $_POST['time2'];
 if(isset($_POST['myTextBox']) && !empty($_POST['myTextBox'])) $reason = $_POST['myTextBox'];
 
 //insert query to NoAttendance table
 $dateFrom = $dateFrom. ' '. $timeFrom;
 $dateTo = $dateTo. ' '. $timeTo;
 $wpdb->insert('wp_no_attendance', array("doctor_id" => $doc_id, "fromDate" => $dateFrom, "toDate" => $dateTo), array("%d","%s","%s"));
 //success message
?>
<div class="successApp">Holiday was added successfully! </div>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
