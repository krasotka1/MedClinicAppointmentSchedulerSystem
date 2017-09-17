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
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

			<?php endwhile; // end of the loop. ?>
          

<table id="table_id" class="display">
    <thead>
        <tr>
            <th>From</th>
            <th>To</th>
            <th>Reason</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="tbodyid">
<?php
global $wpdb;
$time = current_time( 'mysql' ); 
$permV = get_permalink(587);
$holidays= $wpdb->get_results("SELECT id, fromDay, toDay, reason FROM wp_holidays WHERE DATE(fromDay) > STR_TO_DATE('".$time."', '%Y-%m-%d') ORDER BY fromDay ASC");
   foreach ($holidays as $holiday) {
       echo "<tr>";
       echo '<td>'.$holiday->fromDay. '</td>';
       echo '<td>'.$holiday->toDay. '</td>';
       echo '<td>'.$holiday->reason. '</td>';
       $url = esc_url( add_query_arg( 'id', $holiday->id, $permV ) );
       echo '<td><a href="'. $url.'">Delete holiday</a></td>';
       echo '</tr>';
   }
?>
    </tbody>
</table>
<script>
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    var t = $('#table_id').DataTable();
    
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
