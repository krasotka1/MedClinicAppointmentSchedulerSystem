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
        <title>Sign Up Form</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>

      <form action="../doctors-listing" id="doctor_search" method="post">
      
        <h1>Doctor search</h1>
        
        <fieldset>
          <legend>Fill at least 1 of the following fields. If none are filled, all doctors will be displayed</legend>
          <label for="name" name="fn">First name:</label>
          <input type="text" id="name" name="first_name">
          <label for="name">Last name:</label>
          <input type="text" id="lname" name="last_name">
          
          <label>Gender:</label>
          <input type="radio" id="fem" value="female" name="doc_gen"><label for="fem" class="light">Female</label><br>
          <input type="radio" id="male" value="male" name="doc_gen"><label for="male" class="light">Male</label>
        </fieldset>
        
        
        <fieldset>  
        <label for="spec">Specialization:</label> 
        <?php
echo '<select name="spec" id="spec" form="doctor_search">';
global $wpdb;
$args = array(
'role' => 'author',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
$specs = [];
$result = [];
$aux = 0;
$users = get_users($args);
foreach ($users as $user) {
   $spec = get_user_meta($user->ID, 'specialization', true);
   $result[$aux]['id'] = $user->ID;
   $result[$aux]['name'] = $user->display_name;
   $result[$aux]['specialization'] = $spec;
   array_push($specs, $spec);
   $aux = $aux + 1;
   }
$specs = array_unique($specs,SORT_REGULAR);
echo '<option label=" "></option>';
foreach ($specs as $speci) {
   echo '<option value="' . $speci . '">' . $speci . '</option>';
   }
echo '</select>';
?>
        
          <label for="dow">Day of the week:</label>
          <select id="dow" name="day_of_week">
          
            <option value="dnm"></option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday"> Thursday</option>
            <option value="Friday">Friday</option>
          
        </select>
    
      <label for="tofd">Time of the day:</label>
        <select id="tofd" name="time_of_day">
          
            <option value="either"></option>
            <option value="Morning">Morning</option>
            <option value="Afternoon">Afternoon</option>
            <option value="Evening"> Evening</option>
          
        </select>
    
        
        </fieldset>
        <button type="submit" id="search-submit" name="search_doc" value="Sign Up">Search</button>
      </form>
      
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
