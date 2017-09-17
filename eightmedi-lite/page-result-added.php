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
 $forwho = $_POST['choose'];
 $visit_id = $_POST['hidden_name'];
 $result = $_POST['myTextBox'];
 if($forwho == 'pub'){ 
 //update query with public result
 $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET result= %s WHERE id=%d",$result,$visit_id));
 }else{ 
 //update query with public result
 $wpdb->query($wpdb->prepare("UPDATE wp_appointments SET private_result='$result' WHERE id=$visit_id"));
 }
 //success message
?>
<div class="successApp">Result was added successfully! </div>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
