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
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
            <?php
//$docID = get_current_user_id();
$docID = 3;
global $wpdb;
$time = current_time( 'mysql' ); 
$visits = $wpdb->get_results("SELECT id, date_visit, patient_id, namePatient,description  FROM wp_appointments WHERE doctor_id=". $docID ." AND DATE(date_visit) < STR_TO_DATE('".$time."', '%Y-%m-%d')");
$args = array(
'role' => 'subscriber',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
 $users = get_users($args);
 $aux = 0;
 $visitsAll = [];
 foreach ($visits as $visit) {
   $visitsAll[$aux]['id'] = $visit->id;
   $visitsAll[$aux]['name'] =  get_userdata($visit->patient_id)-> display_name;
   $visitsAll[$aux]['visit'] = $visit -> date_visit;
   $visitsAll[$aux]['dni'] =  get_userdata($visit->patient_id)-> user_login;
   $aux = $aux + 1;
   }
?>

<body>
<form id="myform" action="result-added" method="POST">
<p><h1> Add a result form</h1></p>
<label for="visitName"><h2>Choose a visit:</h2></label> 
<input type="text" id="visitName" placeholder="Input patient's DNI, Name or/and visit date" required> 
<label for="doc"><h2>Result type:</h2></label> 
<p><input type="radio" name="choose" value="pub">Public<br>
    <input type="radio" name="choose" value="priv">Private<br>
</p>

<textarea name="myTextBox" cols="50" rows="5" placeholder="Input the result of the visit" required></textarea>
<br /><input type="submit" style="margin-right: 10%; float: right;" />
<input type='hidden' id='hidden_name' name='hidden_name' value="docid" />
</form>
 
</body>
<script>
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    
    var visits = <?php echo json_encode($visitsAll); ?>;
    var users = <?php echo json_encode($users); ?>;
    var visitNames = [];
    for (var i = 0; i < visits.length; i++) {
        visitNames[i] = visits[i].name + '(' + visits[i].dni + ') ' + visits[i].visit;
    }
    
$(function() {
    var accentMap = {
      "á": "a",
      "à": "a",
      "ó": "o",
      "ò": "o", 
      "é": "e",
      "è": "e",
      "í": "i",
      "ì": "i",
      "ú": "u",
      "ù": "u"
    };
    var normalize = function( term ) {
      var ret = "";
      for ( var i = 0; i < term.length; i++ ) {
        ret += accentMap[ term.charAt(i) ] || term.charAt(i);
      }
      return ret;
    };
    
    
    $( "#visitName" ).autocomplete({
      source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( visitNames, function( value ) {
          value = value.label || value.value || value;
          return matcher.test( value ) || matcher.test( normalize( value ) );
        }) );
      }
    });
    
    $('#myform').submit(function() {
    
    var n = $('#visitName').val();
    if (visitNames.indexOf(n) < 0){
    alert("A visit was not selected properly. Please try selecting again");
    return false; // return false to cancel form action
    }else {
    document.getElementById("hidden_name").value = visits[visitNames.indexOf(n)].id;
    }
    if ($("input[type=radio]:checked").length > 0) {
    return true;
    }

alert("A type of result must be selected (public or private). Please select one before proceeding");
    return false; // return false to cancel form action
});
});
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
