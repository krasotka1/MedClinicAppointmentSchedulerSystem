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
require('wp-content/themes/eightmedi-lite/ICS.php');
function dateToCal($time) {
	 $temp = DateTime::createFromFormat('Y-m-d H:i:s', $time);
    $eventStart = $temp->format('Ymd\THis');
    return $eventStart;
}
$cancelPressed = false;
$allPressed = false;
$numVisits = $_POST['num_visits'];
$numVisits = (int)$numVisits;
$vid = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //something posted
    if (isset($_POST['buttonAll'])) {
    $allPressed = true;
    }else if (isset($_POST['button0'])) {
    $vid = $_POST['hidden_id2'];
    }else{
    for( $i = 1; $i < $numVisits*2; $i++ ) {
    if (isset($_POST['button'.$i])) {
echo "button".$i;
        // check if it's ical export or cancel an appointment button
        if($i & 1){
        // odd, that is it's cancel appointment button
        $cancelPressed = true;
        $vid = $_POST['hidden_id'.($i+1)];
        }else{
        // even, that is it's a ical export button
        $vid = $_POST['hidden_id'.($i*2)];
        }
        break;
    } 
  } //for
  }
} //server request
global $wpdb;
if($allPressed){
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=appointments.ics');
$visits = $wpdb->get_results("SELECT id,date_visit,untilTime,doctor_id FROM wp_appointments WHERE patient_id =".get_current_user_id()." AND date_visit> STR_TO_DATE('".current_time( 'mysql' )."', '%Y-%m-%d %H:%i:%s')");
$ical = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN';
foreach ($visits as $visit) {
    $ical = $ical.'
BEGIN:VEVENT
DTEND:' . dateToCal($visit->untilTime) . '
UID:' . md5('Appointment with '.$visit->id) . '
DTSTAMP:' . time() . '
LOCATION:' . addslashes('MedClinic') . '
DESCRIPTION:' . addslashes('Appointment with '.get_userdata($visit->doctor_id)->display_name) . '
URL;VALUE=URI: http://medclinic.x10host.com
SUMMARY:' . 'Appointment with '.get_userdata($visit->doctor_id)->display_name . '
DTSTART:' . dateToCal($visit->date_visit) . '
END:VEVENT';
}
$ical = $ical.'
END:VCALENDAR';
echo $ical;
die();
}
//Now we execute one of the actions
if($cancelPressed){
$wpdb->query('DELETE FROM '.$wpdb->prefix.'appointments WHERE id = "'.$vid.'"');
get_header(); 
}else{
//ical
$visits = $wpdb->get_results("SELECT date_visit,untilTime,doctor_id FROM wp_appointments WHERE id =". $vid);
$display_name = 

header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=appointment.ics');
$properties = array(
  'dtstart' => $visits[0]->date_visit,
  'summary' => 'Appointment with '.get_userdata($visits[0]->doctor_id)->display_name,
  'location' => 'MedClinic',
  'dtend' => $visits[0]->untilTime
);

$ics = new ICS($properties);
$ics_file_contents = $ics->to_string();
echo $ics_file_contents;
die();
}?>
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

?>
<div class="successApp">Appointment was cancelled successfully!</div>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
