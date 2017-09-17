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

<form action="choose-a-time-slot-3" method="post" name="form2" id="specanddoc">
<div id="appMake">

<?php
echo '<h2> Please select specialization </h2><br><br><label> Specialization: </label><select name="specName" id="spec" form="specanddoc" onchange="changeCalendar();">';
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
   $spec = get_user_meta($user->id, 'specialization', true);
   $result[$aux][id] = $user->id;
   $result[$aux][name] = $user->display_name;
   $result[$aux][specialization] = $spec;
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
</div>
<script>
function changeCalendar() {
var mybr = document.createElement('br');
var selectBox = document.getElementById("spec");
var sv= selectBox.options[selectBox.selectedIndex].text;
var rs = <?php echo json_encode($result); ?>;
console.log(rs);
console.log("wtf");
var myDiv = document.getElementById("appMake"); 
var myDoct = document.getElementById("doct");
if (myDoct) {
 var but = document.getElementById("Submit");
 but.parentElement.removeChild(but);
 myDoct.parentElement.removeChild(myDoct);
 var myDoctL = document.getElementById("doctlabel");
 myDoctL.parentElement.removeChild(myDoctL); 
} 
else myDiv.appendChild(mybr);
var myLabel = document.createElement("label");
myLabel.setAttribute("id","doctlabel");
myLabel.innerHTML = "Doctor: "; 
myDiv.appendChild(myLabel);
var selectList = document.createElement("select");
selectList.setAttribute("name", "doctID");
selectList.setAttribute("id", "doct");
selectList.setAttribute("form","specanddoc");
myDiv.appendChild(selectList);
var butt= document.createElement("input");
butt.setAttribute("name", "Submit");
butt.setAttribute("id", "Submit");
butt.setAttribute("form","specanddoc");
butt.setAttribute("value","Choose date");
butt.setAttribute("type","submit");
myDiv.appendChild(butt);
for(var i=0;i<rs.length;i++){
var msg = JSON.stringify(rs[i].specialization);
if(('"'+sv+'"') == msg.toString()) {
var option = document.createElement("option");
option.setAttribute("value", rs[i].id);
option.text = JSON.stringify(rs[i].name).replace(/"/g,"");
selectList.appendChild(option);
} 
} 
}
</script>
</form>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
