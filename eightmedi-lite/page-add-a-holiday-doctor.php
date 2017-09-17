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


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script>
  
  jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    
    
    $( function() {
    var dateFormat = "mm/dd/yy",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1",
          changeMonth: true,
          changeYear: true,
          minDate: 0
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1",
        changeMonth: true,
        changeYear: true,
        minDate: 0
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
    
    $('#myform').submit(function() {
    
    if (document.getElementById("docName").required){
    var n = $('#docName').val();
    if (docNames.indexOf(n) < 0){
    alert("There is no doctor with this name. Please try again");
    return false; // return false to cancel form action
    }else {
    document.getElementById("hidden_name").value = docsInfo[docNames.indexOf(n)].id;
    }
    }

    if ($("input[type=radio]:checked").length > 0) {
    return true;
    }

alert("For who this holiday is must be selected");
    return false; // return false to cancel form action
});

});
  
  </script>
</head>
<body>
<form id="myform" action="holiday-added-doctor" method="POST">
<p><h1> Add a holiday form</h1>

<label for="datepicker"><h2>Date:</h2></label> 
<label for="from">From:</label>
<input type="text" id="from" name="from" required>
<label for="time">Time:</label>
<input type="text" id="time" name="time"placeholder="HH:MM format *Leave empty if for the whole day"><br>
<label for="to">To:</label>
<input type="text" id="to" name="to" required>
<label for="time2">Time:</label>
<input type="text" id="time2" name="time2" placeholder="HH:MM format *Leave empty if for the whole day">
<textarea name="myTextBox" cols="50" rows="5" placeholder="Input the reason of the holiday" ></textarea>
<br /><input type="submit" style="margin-right: 10%; float: right;" />
<p>
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
