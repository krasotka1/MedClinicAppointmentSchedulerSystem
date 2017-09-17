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
$id = $_GET['id'];
$visit = $wpdb->get_results("SELECT id,date_visit, patient_id, description, namePatient,email,result,private_result FROM wp_appointments WHERE id=". $id);
echo '<form action="result-update" method="post" name="form3" id="result">';
echo "<h2> Appointment information </h2>";
echo "<p> <strong>Date:</strong>";
echo " ".$visit[0]->date_visit."</p>";
$desc = "";
if($visit[0]->description == null) $desc = "No problem description was submitted";
else $desc = $visit[0]->description;
if($visit[0]->namePatient == null){
    $user = get_user_by( 'id', $visit[0]->patient_id );
    echo "<p> <strong>Patient name: </strong> ".$user->display_name."</p>";
    echo "<p><strong>DNI: </strong> ".$user->user_login."</p>";
    echo "<p><strong>Description: </strong>".$desc."</p>";
}
else {
    echo "<p><strong>Patient name: </strong> ".$visit[0]->namePatient."</p>";
    echo " <p> <strong>Email: </strong> Guest user </p>";
    echo "<p><strong>Description: </strong> ".$desc."</p>";
}
?>

<label> Public result </label>
<textarea name="myTextBox" cols="50" rows="5"></textarea>
<label> Private result </label>
<textarea name="myTextBox2" cols="50" rows="5"></textarea>
<input type="hidden" id="hid" name="hid" value=''>

<br /><input type="submit" style="margin-right: 40%; float: right;" value="Update results" form="result"/>
</form>
<script>
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    var visit = <?php echo json_encode($visit); ?>;
    if(visit[0].result != null) document.getElementById('myTextBox').value = visit[0].result;
    if(visit[0].private_result != null) document.getElementById('myTextBox2').value = visit[0].private_result;
    document.getElementById('hid').value = visit[0].id;
});
</script>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>






            

