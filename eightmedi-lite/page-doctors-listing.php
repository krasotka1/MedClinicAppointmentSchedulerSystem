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
			<head>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>Search Results</title>
				<link rel="stylesheet" href="css/normalize.css">
				<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
				<link rel="stylesheet" href="css/main.css">
			</head>
			<body>
				<?php
				if(isset($_POST['search_doc'])){
					$fn=isset($_POST['first_name'])?$_POST['first_name']:'';
					$ln=isset($_POST['last_name'])?$_POST['last_name']:'';
					$gender=isset($_POST['doc_gen'])?$_POST['doc_gen']:'';
					$spec=(isset($_POST['spec']) && !empty($_POST['spec']))?$_POST['spec']:'';
					$spec = str_replace(' ', '-', $spec);
					$day=isset($_POST['day_of_week'])?$_POST['day_of_week']:'';
					$time=isset($_POST['time_of_day'])?$_POST['time_of_day']:'';
					?>
					<?php 
					
					$searchstring = '';
					$arr = [];
					$arr[0]['value'] = 'firstname-'.$fn;
					$arr[1]['value'] = 'lastname-'.$ln;
					$arr[2]['value'] = $gender;
					$arr[3]['value'] = $spec;
					$arr[4]['value'] = $day;
					$arr[5]['value'] = $time;
					if($fn == '') $arr[0]['set'] = false; else $arr[0]['set'] = true;
					if($ln == '') $arr[1]['set'] = false; else $arr[1]['set'] = true;
					if($gender == '') $arr[2]['set'] = false; else $arr[2]['set'] = true;
					if($spec == '') $arr[3]['set'] = false; else $arr[3]['set'] = true;
					if($day == 'dnm') $arr[4]['set'] = false; else $arr[4]['set'] = true;
					if($time == 'either') $arr[5]['set'] = false; else $arr[5]['set'] = true;
					
					for($i = 0; $i<6; $i++){
					    if($arr[$i]['set'] == true) {
					        if($searchstring == '') $searchstring = $arr[$i]['value'];
					        else $searchstring = $searchstring.'+'.$arr[$i]['value'];
					    }
					}
					$query = '';
					if($searchstring == '') $query = new WP_Query( array( 'category_name' => 'Doctors' ) ); //Select all doctors
					//$searchstring=$firstString.'+'.$genderString;
					else $query = new WP_Query( array( 'tag' => $searchstring ) );
					// $query = new WP_Query( array( 'tag' => "$firstString,$lastString,$genderString,$specString,$dayString,$timeString",
					// 	'compare'=>'LIKE' ) );
                    $ct = 1;
					while($query -> have_posts()) : $query -> the_post();
                    if($ct == 1) echo '<div id="three-columns" style="width=100%;">';
                    echo '<div id="col" style="float: left;margin-right: 40px;">';
					echo '<h2>';
					echo the_title();
					echo '</h2>';
                    if ( has_post_thumbnail() ) : ?>
                    <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('thumbnail'); ?>
                    </a>
                    <?php 
                    endif; 
					echo '<p>'.the_content().'</p>';
                    echo '</div>'; //close col div
                    if($ct == 3){
                        echo '</div>'; //if 3 posts, close the row of columns
                        $ct = 1;
                    }else $ct = $ct + 1;
					endwhile;
                    wp_reset_query();
                    if($ct == 2 || $ct == 3){
                        echo '</div>'; //To close the div
                    }
				} 

				else{
					echo "<h2>No search queries submitted tset</h2>";
					echo "<p>Please go to <a href='../search-for-doctors'>Search form</a> and search for your Doctor</p>";
				}

				?>
			</body>
			</html>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>


<?php get_footer(); ?>

?>
