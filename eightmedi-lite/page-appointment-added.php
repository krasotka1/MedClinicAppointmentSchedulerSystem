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
$doctorID = $_POST['doctorID'];
$date = $_POST['dateSelec'];
$time = $_POST['timeChosen'];
$prob = "No problem description specified";
if(isset($_POST['txtArea'])) $prob = $_POST['txtArea'];
$forWho= $_POST['personChosen'];
$forWhoName = null;
if (isset($_POST['persName'])) $forWhoName = $_POST['persName'];
$dateVisit= $date.' '.$time;
$user_id = get_current_user_id();
$em = wp_get_current_user() -> user_email;
$endTime = strtotime("+15 minutes", strtotime($dateVisit));
$dateUntil = date('Y-m-d H:i:s',$endTime);
 $wpdb->insert('wp_appointments', array("doctor_id" => $doctorID, "patient_id" => $user_id, "date_visit" => $dateVisit, "untilTime" => $dateUntil,"description" => $prob, "assisted" => false, "result" => null, "private_result" => null, "namePatient" => $forWhoName, "ip_address" => null), array("%d","%d", "%s","%s","%s","%s","%s","%s","%s","%s"));
echo "<div class='successApp'>Appointment created successfully! You can review the appointment information in My appointment menu</div><br>";
echo "<h2> Appointment information </h2>";
echo "<p> <h2>Date:</h2>";
echo " ".$dateVisit."</p>";
echo "<p> Doctor's name: ".get_userdata( $doctorID )->display_name."</p>";
echo "<p><label> Specialization: </label>".get_user_meta( $doctorID, "specialization", true )."</p>";
if($prob != null) echo "<label> Description: </label><p>".$prob."</p>";
else echo "<p><label> Description: Not specified </label></p>";
wp_mail( $em, 'Appointment confirmation at MedClinic', 'Thank you for making an appointment at MedClinic. You made an appointment with '.get_userdata( $doctorID )->display_name.' '.$date.' at '.$time);
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
