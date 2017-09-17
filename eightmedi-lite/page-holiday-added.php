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
 $doc_id = '';
 $timeFrom = '00:00';
 $timeTo = '00:00';
 $forwho = $_POST['choose'];
 if($forwho == 'doc') $doc_id = $_POST['hidden_name'];
 $dateFrom = date("Y-m-d",strtotime($_POST['from']));
 $dateTo = date("Y-m-d",strtotime($_POST['to']));
 $dayTo2 = $dateTo;
 if($_POST['from'] == $_POST['to']) {
 $dayTo2 = date("Y-m-d",strtotime("$dateTo + 1 day"));
 }
 $visits = $wpdb->get_results("SELECT id, date_visit, patient_id, email  FROM wp_appointments WHERE date_visit> STR_TO_DATE('".$dateFrom."', '%Y-%m-%d') AND date_visit< STR_TO_DATE('".$dayTo2."', '%Y-%m-%d')");
 foreach ($visits as $visit) {
 if($visit -> email === NULL) wp_mail( get_userdata($visit -> patient_id) -> user_email, 'Appointment cancelled notification with MedClinic','Your appointment was cancelled because a holiday was added.'); 
 else wp_mail($visit -> email, 'Appointment cancelled notification with MedClinic','Your appointment was cancelled because a holiday was added.'); 
 $wpdb->query('DELETE FROM '.$wpdb->prefix.'appointments WHERE id = "'.$visit -> id.'"');
 }
 if(isset($_POST['time']) && !empty($_POST['time'])) $timeFrom = $_POST['time'];
 if(isset($_POST['time2']) && !empty($_POST['time2'])) $timeTo = $_POST['time2'];
 $reason = $_POST['myTextBox'];
 if($doc_id == ''){ //the holiday is for the whole clinic
 //insert query to Holiday table
 $wpdb->insert('wp_holidays', array("fromDay" => $dateFrom, "toDay" => $dateTo, "reason" => $reason), array("%s","%s","%s"));
 }else{ //the holiday is for 1 particular doctor
 //insert query to NoAttendance table
 $dateFrom = $dateFrom. ' '. $timeFrom;
 $dateTo = $dateTo. ' '. $timeTo;
 $wpdb->insert('wp_no_attendance', array("doctor_id" => $doc_id, "fromDate" => $dateFrom, "toDate" => $dateTo), array("%d","%s","%s"));
 
 }
 //success message
 
?>
<div class="successApp">Holiday created successfully! All the appointments were deleted during this holiday and patients were notified.</div>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
