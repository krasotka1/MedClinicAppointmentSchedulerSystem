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
function getRandomBytes($nbBytes = 32)
{
    $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
    if (false !== $bytes && true === $strong) {
        return $bytes;
    }
    else {
        throw new \Exception("Unable to generate secure token from OpenSSL.");
    }
}
function generatePassword($length){
    return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode(getRandomBytes($length+1))),0,$length);
}
$fname = $_POST['user_fname'];
$lname = $_POST['user_lname'];
$spec = $_POST['spec'];
$gender = isset($_POST['doc_gen'])?$_POST['doc_gen']:'';
$email= $_POST['user_email'];
$pass = generatePassword(9);
$mon1 = '0';
 $tue1 = '0';
 $wed1 = '0';
 $thu1 = '0';
 $fri1 = '0';
global $wpdb;
$login = $fname.$lname;
$aux = 1;
while ( username_exists( $login ) ) { //in case this username already exists
    $login = $fname.$lname.$aux;
    $aux = $aux + 1;
}
$userdata = array(
    'user_login'  =>  $login,
    'display_name' =>  $fname.' '.$lname,
    'user_pass'   =>  $pass,
    'first_name' => $fname,
    'last_name' => $lname,
    'role' => 'author',
    'user_email' => $email
);
$user_id = wp_insert_user( $userdata ) ;

//On failure
if ( is_wp_error( $user_id ) ) {
    echo "Doctor could not be created";
}else{
// Now we'll add all the optional stuff that the user filled in
if (isset($_POST['mon']) && $_POST['mon'] != "") $mon1 = str_replace(',','+',$_POST['mon']);
 if (isset($_POST['tue']) && $_POST['tue'] != "") $tue1 = str_replace(',','+',$_POST['tue']);
 if (isset($_POST['wed']) && $_POST['wed'] != "") $wed1 = str_replace(',','+',$_POST['wed']);
 if (isset($_POST['thu']) && $_POST['thu'] != "") $thu1 = str_replace(',','+',$_POST['thu']);
 if (isset($_POST['fri']) && $_POST['fri'] != "") $fri1 = str_replace(',','+',$_POST['fri']);
 $hours = $mon1.','.$tue1.','.$wed1.','.$thu1.','.$fri1;
 $dateFrom = date("Y-m-d H:i:s");
 $wpdb->insert('wp_working_schedules', array("doctor_id" => $user_id, "fromDate" => $dateFrom, "hours" => $hours), array("%d","%s","%s"));
add_user_meta( $user_id, 'specialization', $spec, false );
$mes = 'Your account has been created successfully. The following are the username and password used to log in:
Username: '.$login.' 
Password: '.$pass.' 
All your information can be changed later in Change Profile menu';
wp_mail( $email, 'Account information in MedClinic', $mes);
$cont = 'Specialization: [insert_php] echo get_user_meta('.$user_id.', \'specialization\',true).\'
\'; 
global $wpdb;
$scheds = $wpdb->get_results("SELECT fromDate, hours FROM wp_working_schedules WHERE doctor_id = '.$user_id.' ORDER BY fromDate ASC");
$fin = \'\';
foreach ($scheds as $sched) {
$res = time() - strtotime($sched->fromDate);
if($res > 0) $fin = $sched->hours;
else if($res == 0){
$fin = $sched->hours;
break;
}else break;
}
$dh = explode(\',\', $fin);
if($dh[0] == 0) {echo \'Working days:
\';
echo " Monday: Doesn\'t work";
}
else{
$monh = explode(\'+\', $dh[0]);
echo \'Working days:   
Monday: \';
$first = true;
foreach ($monh as $mon) {
if($first){
echo $mon;
$first = false;
}else echo \', \'.$mon;
}
}
echo \'
\';
if($dh[1] == 0) echo " Tuesday: Doesn\'t work";
else{
$monh = explode(\'+\', $dh[1]);
echo \'Tuesday: \';
$first = true;
foreach ($monh as $mon) {
if($first){
echo $mon;
$first = false;
}else echo \', \'.$mon;
}
}
echo \'
\';
if($dh[2] == 0) echo " Wednesday: Doesn\'t work";
else{
$monh = explode(\'+\', $dh[2]);
echo \'Wednesday: \';
$first = true;
foreach ($monh as $mon) {
if($first){
echo $mon;
$first = false;
}else echo \', \'.$mon;
}
}
echo \'
\';
if($dh[3] == 0) echo " Thursday: Doesn\'t work";
else{
$monh = explode(\'+\', $dh[3]);
echo \'Thursday: \';
$first = true;
foreach ($monh as $mon) {
if($first){
echo $mon;
$first = false;
}else echo \', \'.$mon;
}
}
echo \'
\';
if($dh[4] == 0) echo " Friday: Doesn\'t work";
else{
$monh = explode(\'+\', $dh[4]);
echo \'Friday: \';
$first = true;
foreach ($monh as $mon) {
if($first){
echo $mon;
$first = false;
}else echo \', \'.$mon;
}
}
[/insert_php]';
// Gather post data.
$my_post = array(
    'post_title'    => $fname.' '.$lname,
    'post_content'  => $cont,
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'post',
    'post_category' => array( 2 )
);
 
// Insert the post into the database.
$pid = wp_insert_post( $my_post );
$tags = array();
$tags[]='firstname-'.$fname;
$tags[]='lastname-'.$lname;
if($mon1 != '0') $tags[]='monday';
if($tue1 != '0') $tags[]='tuesday';
if($thu1 != '0') $tags[]='thursday';
if($wed1 != '0') $tags[]='wednesday';
if($fri1 != '0') $tags[]='friday';
$tags[] = $spec;
$tags[] = $gender;
wp_set_post_tags( $pid, $tags, false );
}
?>
<div class="successApp">Doctor was created successfully! All the details can be changed in a Change profile menu when he loggins in</div>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php 
	if($sidebar=='both-sidebar' || $sidebar=='right-sidebar' ){
		get_sidebar('right');
	}
	?>
</div>
<?php get_footer(); ?>
