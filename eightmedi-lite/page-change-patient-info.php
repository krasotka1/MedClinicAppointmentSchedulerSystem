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
 $args = array(
'role' => 'subscriber',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
 $patients = [];
 $users = get_users($args);
 $aux = 0;
 foreach ($users as $user) {
   $patients[$aux]['dni'] = $user->user_login;
   $patients[$aux]['email'] = $user->user_email;
   $patients[$aux]['id'] = $user->ID;
   $all_meta_for_user = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user-> ID ) );
   $patients[$aux]['fname'] = $all_meta_for_user['first_name'];
   $patients[$aux]['lname'] = $all_meta_for_user['last_name'];
   if (array_key_exists("description",$all_meta_for_user)) $patients[$aux]['sec_num'] = $all_meta_for_user['description'];
   if (array_key_exists("city",$all_meta_for_user)) $patients[$aux]['city'] = $all_meta_for_user['city'];
   if (array_key_exists("address",$all_meta_for_user)) $patients[$aux]['address'] = $all_meta_for_user['address'];
   if (array_key_exists("age",$all_meta_for_user)) $patients[$aux]['age'] = $all_meta_for_user['age'];
   $aux = $aux + 1;
   }
?>

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Change patient's info Form</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>

      <form action="info-changed" method="post" id="myform">
      
        <h1>Change patient's profile</h1>
        
        <fieldset>
          <legend><span class="number">1</span> Choose a patient</legend>
          <label for="pat">DNI or patient's name:</label>
          <input type="text" id="pat" name="patient" required>
        </fieldset>
        
      </form>
      
    </body>
</html>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
  
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {

    var patients = <?php echo json_encode($patients); ?>;
    var patNames = [];
    for (var i = 0; i < patients.length; i++) {
        patNames[i] = patients[i].fname+' '+patients[i].lname+' ('+patients[i].dni+')';
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
    
    
    $( "#pat" ).autocomplete({
      source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( patNames, function( value ) {
          value = value.label || value.value || value;
          return matcher.test( value ) || matcher.test( normalize( value ) );
        }) );
      },
      select: function(e, ui){
        var ind = patNames.indexOf(ui.item.value);
        var fname = document.getElementById("fname");
            if (fname) { // The form is already completely created. Only values have to be updated
                fname.value = patients[ind].fname;
                var lname = document.getElementById("lname");
                lname.value = patients[ind].lname;
                var dni = document.getElementById("dni");
                dni.value = patients[ind].dni;
                var m = document.getElementById("mail");
                m.value = patients[ind].email;
                var addr = document.getElementById("addr");
                if(patients[ind].address) addr.value = patients[ind].address;
                else addr.value = '';
                var city = document.getElementById("city");
                if(patients[ind].city) city.value = patients[ind].city;
                else city.value = '';
                var secnum = document.getElementById("snum");
                if(patients[ind].sec_num) secnum.value = patients[ind].sec_num;
                else secnum.value = '';
                var u18 = document.getElementById("under_18");
                var o18 = document.getElementById("over_18");
                if(patients[ind].age) {
                if(patients[ind].age == "under_18") u18.checked = true;
                else if(patients[ind].age == "over_18") o18.checked = true;
                else {
                o18.checked = false;
                u18.checked = false;
                }
                }
                else {
                o18.checked = false;
                u18.checked = false;
                }
                var id = document.getElementById("hidden_id");
                id.value = patients[ind].id;
            } else {
             var form = document.getElementById('myform');
             var fset = document.createElement("fieldset");   
             var legend = document.createElement("legend");
             form.appendChild(fset);
             fset.appendChild(legend);
             //2
             var span = document.createElement("span");
             span.setAttribute("class", "number");
             span.textContent = "2";
             legend.appendChild(span); 
             legend.textContent = "Patient's basic info";
             var label = document.createElement('label');
             label.setAttribute("for", 'fname');
             label.textContent = "First name:";
             fset.appendChild(label);
             var inputf = document.createElement('input');
             inputf.setAttribute("value", patients[ind].fname);
             inputf.setAttribute("type", 'text');
             inputf.setAttribute("id", 'fname');
             inputf.setAttribute("name", 'user_fname');
             inputf.required = true;
             fset.appendChild(inputf);
             var label2 = document.createElement('label');
             label2.setAttribute("for", 'lname');
             label2.textContent = "Last name:";
             fset.appendChild(label2);
             var inputl = document.createElement('input');
             inputl.setAttribute("value", patients[ind].lname);
             inputl.setAttribute("type", 'text');
             inputl.setAttribute("id", 'lname');
             inputl.setAttribute("name", 'user_lname');
             inputl.required = true;
             fset.appendChild(inputl);
             var label3 = document.createElement('label');
             label3.setAttribute("for", 'dni');
             label3.textContent = "DNI:";
             fset.appendChild(label3);
             var inputd = document.createElement('input');
             inputd.setAttribute("value", patients[ind].dni);
             inputd.setAttribute("type", 'text');
             inputd.setAttribute("id", 'dni');
             inputd.setAttribute("name", 'user_dni');
             inputd.required = true;
             fset.appendChild(inputd);
             var label4 = document.createElement('label');
             label4.setAttribute("for", 'mail');
             label4.textContent = "Email:";
             fset.appendChild(label4);
             var inpute = document.createElement('input');
             inpute.setAttribute("value", patients[ind].email);
             inpute.setAttribute("type", 'text');
             inpute.setAttribute("id", 'mail');
             inpute.setAttribute("name", 'user_email');
             inpute.required = true;
             fset.appendChild(inpute);
             var label5 = document.createElement('label');
             label5.setAttribute("for", 'pass');
             label5.textContent = "Password (Optional):";
             fset.appendChild(label5);
             var inputp = document.createElement('input');
             inputp.setAttribute("type", 'checkbox');
             inputp.setAttribute("id", 'pass');
             inputp.setAttribute("name", 'user_pass');
             var ff = document.createElement('label')
             ff.htmlFor = "pass";
             ff.appendChild(document.createTextNode('Generate new password'));
             inputp.style = "float: left; margin-top: 5px;>";
             fset.appendChild(inputp);
             fset.appendChild(ff);
             // 3
             var fset = document.createElement("fieldset");   
             var legend = document.createElement("legend");
             form.appendChild(fset);
             fset.appendChild(legend);
             var span = document.createElement("span");
             span.setAttribute("class", "number");
             span.textContent = "3";
             legend.appendChild(span); 
             legend.textContent = "Patient's optional info";
             var fset = document.createElement("fieldset");  
             form.appendChild(fset); 
             var label = document.createElement('label');
             label.setAttribute("for", 'snum');
             label.textContent = "Insurance number(optional):";
             fset.appendChild(label);
             var inputf = document.createElement('input');
             if(patients[ind].sec_num) inputf.setAttribute("value", patients[ind].sec_num);
             else inputf.setAttribute("value", '');
             inputf.setAttribute("type", 'text');
             inputf.setAttribute("id", 'snum');
             inputf.setAttribute("name", 'user_snum');
             fset.appendChild(inputf);
             var label2 = document.createElement('label');
             label2.setAttribute("for", 'city');
             label2.textContent = "City(optional):";
             fset.appendChild(label2);
             var inputl = document.createElement('input');
             if(patients[ind].city) inputl.setAttribute("value", patients[ind].city);
             else inputl.setAttribute("value", '');
             inputl.setAttribute("type", 'text');
             inputl.setAttribute("id", 'city');
             inputl.setAttribute("name", 'user_city');
             fset.appendChild(inputl);
             var label3 = document.createElement('label');
             label3.setAttribute("for", 'addr');
             label3.textContent = "Address(optional):";
             fset.appendChild(label3);
             var inputd = document.createElement('input');
             if(patients[ind].address) inputd.setAttribute("value", patients[ind].address);
             else inputd.setAttribute("value", '');
             inputd.setAttribute("type", 'text');
             inputd.setAttribute("id", 'addr');
             inputd.setAttribute("name", 'user_addr');
             fset.appendChild(inputd);
             var label4 = document.createElement('label');
             label4.textContent = "Age:";
             fset.appendChild(label4);
             var inpute = document.createElement('input');
             inpute.setAttribute("value", 'under_18');
             inpute.setAttribute("type", 'radio');
             inpute.setAttribute("id", 'under_18');
             inpute.setAttribute("name", 'user_age');
             fset.appendChild(inpute);
             var label5 = document.createElement('label');
             label5.setAttribute("for", 'under_18');
             label5.setAttribute("class", 'light');
             label5.textContent = "Under 18";
             fset.appendChild(label5);
             var br = document.createElement('br');
             fset.appendChild(br);
             var inpute1 = document.createElement('input');
             inpute1.setAttribute("value", 'over_18');
             inpute1.setAttribute("type", 'radio');
             inpute1.setAttribute("id", 'over_18');
             inpute1.setAttribute("name", 'user_age');
             fset.appendChild(inpute1);
             var label5 = document.createElement('label');
             label5.setAttribute("for", 'over_18');
             label5.setAttribute("class", 'light');
             label5.textContent = "18 or older";
             fset.appendChild(label5);
             if(patients[ind].age) {
                if(patients[ind].age == "under_18") inpute.checked = true;
                else if(patients[ind].age == "over_18") inpute1.checked = true;
                else {
                inpute.checked = false;
                inpute1.checked = false;
                }
                }
             var inputh = document.createElement('input');
             inputh.setAttribute("type", 'hidden');
             inputh.setAttribute("id", 'hidden_id');
             inputh.setAttribute("name", 'hidden_id');
             inputh.setAttribute("value", patients[ind].id);
             fset.appendChild(inputh);
             var but = document.createElement('button');
             but.setAttribute("type", 'submit');
             but.textContent = 'Update info';
             form.appendChild(but);
            }
        }
    });
  } );
  $('#pat').click(function() {
   $('#pat').empty();
});
  $('#myform').submit(function() {
    var dni =  document.getElementById("dni").value;
    var email = document.getElementById("mail").value;
    var id = document.getElementById("hidden_id").value;
    dni = dni.trim();
    var reg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!reg.test(email)) { 
    //not a correct email format introduced
    alert("Not a valid email. An email should be in a 'email@domain.domain' format");
    return false;
    }else{
    for(var y = 0; y<patients.length; y++){
    if(patients[y].email == email && !id == patients[y].id) {
    alert("There is already a user with this email. Please use another one or log in into your account");
    return false;
    }
    }
    }
    if(dni.length == 9){
        dni = dni.toUpperCase(); // so that not to have problems with lower/upper case letter at the end
        for(var i = 0; i<9; i++){
            if(i==8) {
                var c = dni.charAt(i);
                if(c.toLowerCase() != c.toUpperCase()){
                // is a char
		for(var y = 0; y<patients.length; y++){
		if(patients[y].dni.toUpperCase() == dni && !id == patients[y].id) {
		alert("There is already a user with this DNI. Please try again or log in into your account");
		return false;
		}
		}
                return true;
                }
            }else{
            var c = dni.charAt(i);
            if (!(c >= '0' && c <= '9')) {
            //is not a number
            break;
            }
        }
    }
    }else {
    alert("A DNI should have 9 characters: 8 digits and 1 letter. Format 11111111Z");
    return false;
    }
    alert("DNI's format is incorrect. Please try again with this format 11111111Z");
    return false;
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
