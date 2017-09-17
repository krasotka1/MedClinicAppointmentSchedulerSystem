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
global $wpdb;
$time = current_time( 'mysql' ); 
$visits = $wpdb->get_results("SELECT id, date_visit, doctor_id,patient_id, assisted, invisit,namePatient  FROM wp_appointments WHERE DATE(date_visit) = STR_TO_DATE('".$time."', '%Y-%m-%d') ORDER BY date_visit ASC");
$visitsAll = $wpdb->get_results("SELECT id, date_visit, doctor_id,patient_id, assisted, invisit, namePatient  FROM wp_appointments WHERE DATE(date_visit) > STR_TO_DATE('".$time."', '%Y-%m-%d') ORDER BY date_visit ASC");
// get all doctors
$permV = get_permalink( 346); //cancel-appointment page
$permVE = get_permalink( 356); //edit-appointment page
$args = array(
'role' => 'author',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
 $docNames = [];
 $users = get_users($args);
 $aux = 0;
 foreach ($users as $user) {
   $docNames[$aux]['id'] = $user->ID;
   $docNames[$aux]['name'] = $user->display_name;  
   $aux = $aux + 1;
   }
   
   $args = array(
'role' => 'subscriber',
 'orderby' => 'ID',
 'order' => 'ASC'
 );
 $patients = get_users($args);
?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<input type="radio" id="tod" name="editList" value="Today's appointments" checked>Today's appointments 
<input type="radio" id="oth" name="editList" value="Other day">Other day <br>
<input placeholder="Select a day" name="dateSelec" type='text' id='txtDate' name='txtDate' style="display:none;"/><br>
<label for="docName">Doctor's name:  </label>
<input type="text" id="docName" name="docName" required>

<form id="apps" name="apps" action="visits-status-submit" method="POST">
<script>
  
  jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    var visits = <?php echo json_encode($visits); ?>;
    var visitsAll = <?php echo json_encode($visitsAll); ?>;
    var permV = <?php echo json_encode($permV); ?>;
    var permVE = <?php echo json_encode($permVE); ?>;
    var docs = <?php echo json_encode($docNames); ?>;
    var pats = <?php echo json_encode($patients); ?>;
    var docNames = [];
    var u = "";
    var selDate = "";
    var tod = document.getElementById('tod');
    var oth = document.getElementById('oth');
    for (var i = 0; i < docs.length; i++) {
        docNames[i] = docs[i].name;
    }
    var css = document.createElement("style");
css.type = "text/css";
css.innerHTML = "*.{box-sizing: border-box;}div{display: block;margin-bottom: 5px;}body{    font-size: 13px;    line-height: 19px;    color: #111;}.app-box{  margin-top: 18px!important;position: relative;}.both-sides{position: relative;    padding: 0;}.left-side{width: 100%;    position: relative;    overflow: visible;    zoom: 1;    min-height: 1px;}.right-side{width: 250px;    margin-right: -250px;    float: left;position: relative;    overflow: visible;    zoom: 1;    min-height: 1px;}.a-row {    width: 100%;}.inside-left{margin-bottom: 0!important;position: relative}.button-span{      display: inline-block;    padding: 0;    text-align: center;    text-decoration: none!important;    vertical-align: middle;   }";
document.body.appendChild(css);
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
    
    
    $( "#docName" ).autocomplete({
      source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( docNames, function( value ) {
          value = value.label || value.value || value;
          return matcher.test( value ) || matcher.test( normalize( value ) );
        }) );
      },select: function (event, ui) {
          if(document.getElementById('oth').checked && selDate==""){
          //Selected a doctor without selecting a date. Alert that a user has to select a date
          alert("Please, select a date of visits or select today's visits");
          u = ui.item.value;
          }else{
          
          var value = ui.item.value;
          u = ui.item.value;
          if(document.getElementById('oth').checked){
          //Other day
          showOtherDays();
          }else{
          //Today days
          document.title = value;
          var spanid = 0;
          var butid = 0;
          var numv = 0;
          var doc_id = docs[docNames.indexOf(value)].id;
          var form = document.getElementById("apps"); 
          form.innerHTML = "";
          for(var i = 0; i<visits.length;i++){
              if(visits[i].doctor_id == doc_id){ //only add visits with the selected doctor
              numv++;
                var appbox = document.createElement("div");   
                appbox.setAttribute("class", "app-box");
                form.appendChild(appbox);
                var bothsides = document.createElement("div");   
                bothsides.setAttribute("class", "both-sides");
                bothsides.style = "padding-right:250px";
                appbox.appendChild(bothsides);
                var leftside = document.createElement("div");   
                leftside.setAttribute("class", "left-side");
                leftside.style = "padding-right:3.2%;*width:96.4%;float:left;";
                bothsides.appendChild(leftside);
                var arow = document.createElement("div");   
                arow.setAttribute("class", "a-row");
                leftside.appendChild(arow);
                var insideleft = document.createElement("div");   
                insideleft.setAttribute("class", "inside-left");
                arow.appendChild(insideleft);
                var aux = new Date(visits[i].date_visit);
                var arow3 = document.createElement("div");   
                arow3.setAttribute("class", "a-row");
                insideleft.appendChild(arow3);
                t = "Time: ";
                if(aux.getHours() <= 9) t=t+'0'+aux.getHours()+':';
                else t=t+aux.getHours()+':';
                if(aux.getMinutes() <= 9) t=t+'0'+aux.getMinutes();
                else t=t+aux.getMinutes();
                text = document.createTextNode(t);
                arow3.appendChild(text);
                var arow4 = document.createElement("div");   
                arow4.setAttribute("class", "a-row");
                insideleft.appendChild(arow4);
                t = "With patient: ";
                for(var y = 0; y<pats.length; y++){ //search for that patient's name
                    if(pats[y].ID == visits[i].patient_id) {
                        t = t + pats[y].data.display_name;
                        break;
                    }
                    if(y == (pats.length-1)) t = t + "Guest: "+visits[i].namePatient;
                }
                text = document.createTextNode(t);
                arow4.appendChild(text);
                var arow6 = document.createElement("div");   
                arow6.setAttribute("class", "a-row");
                arow6.style.color = "blue";
                insideleft.appendChild(arow6);
                t = "Edit";
                var a = document.createElement('a');
                text = document.createTextNode(t);
                a.appendChild(text);
                a.title = "Edit";
                a.href = add_query_arg(add_query_arg( permVE,'id',visits[i].id),'docid',visits[i].doctor_id);
                a.style = "margin-right: 5px;";
                arow6.appendChild(a);
                a = document.createElement('a');
                text = document.createTextNode("     Cancel");
                a.appendChild(text);
                a.title = "    Cancel";
                a.href = add_query_arg( permV,'id',visits[i].id);
                arow6.appendChild(a);
                var rightside = document.createElement("div");   
                rightside.setAttribute("class", "right-side");
                rightside.style = "width:250px;margin-right:-250px;float:left;";
                bothsides.appendChild(rightside);
                var arow5 = document.createElement("div");   
                arow5.setAttribute("class", "a-row");
                rightside.appendChild(arow5);
                var buttonstack = document.createElement("div");   
                buttonstack.setAttribute("class", "a-button-stack");
                arow5.appendChild(buttonstack);
                var span = document.createElement("span");   
                span.setAttribute("class", "button-span");
                span.setAttribute("id", 'id-' + spanid);
                buttonstack.appendChild(span);
                spanid++;
                var checkbox = document.createElement("input");   
                checkbox.setAttribute("type", "checkbox");
                checkbox.setAttribute("name", 'button'+butid);
                butid++;
                checkbox.setAttribute("value", 'Attended');
                text = document.createTextNode('  Attended');
                if(visits[i].assisted == 1) checkbox.checked = true;
                span.appendChild(checkbox);
                checkbox.appendChild(text);
                span.appendChild(text);
                buttonstack.appendChild(document.createElement("br"));
                var span1 = document.createElement("span");   
                span1.setAttribute("class", "button-span");
                span1.setAttribute("id", 'id-' + spanid);
                buttonstack.appendChild(span1);
                spanid++;
                var checkbox1 = document.createElement("input");   
                checkbox1.setAttribute("type", "checkbox");
                checkbox1.setAttribute("name", 'button'+butid);
                butid++;
                checkbox1.setAttribute("value", 'Completed');
                text = document.createTextNode('  Completed');
                if(visits[i].invisit == 1) checkbox1.checked = true;
                span1.appendChild(checkbox1);
                checkbox1.appendChild(text);
                span1.appendChild(text);
                var input2 = document.createElement("input");   
                input2.setAttribute("type", "hidden");
                input2.setAttribute("name", 'hidden_id'+butid);
                input2.setAttribute("value", visits[i].id);
                buttonstack.appendChild(input2);
                var hr= document.createElement("hr"); 
        leftside.appendChild(hr);
            }
        var input= document.createElement("input");   
        input.setAttribute("type", "hidden");
        input.setAttribute("name", 'num_visits');
        input.setAttribute("value", numv);
        form.appendChild(input);
            }
          if(numv==0){
                var input= document.createElement("p");   
                text = document.createTextNode('No visits today for this doctor');
        form.appendChild(input);
        input.appendChild(text);
            }else{
        while(numv>0){        
        form.appendChild(document.createElement("br"));
        form.appendChild(document.createElement("br"));
        form.appendChild(document.createElement("br"));
        form.appendChild(document.createElement("br"));
        numv--;
        }
        var input1= document.createElement("input");   
        input1.setAttribute("type", "submit");
        input1.setAttribute("name", 'submit');
        input1.setAttribute("value", 'Submit');
        form.appendChild(input1);
            }
            }
            }
          }
    });
    });
    
    $( "#txtDate" ).datepicker({
          onSelect: function(date) {
            var selD = new Date(date);
            selDate = selD;
            if(u!="") showOtherDays();
          },
          defaultDate: "1",
          changeMonth: true,
          changeYear: true,
          minDate: 0
        });
        

    tod.onclick = todayShow;
    oth.onclick = otherShow;
    
    function otherShow() {
    var input = document.getElementById('txtDate');
    input.style.display = "inline";
    input.value = '';
    var form = document.getElementById("apps"); 
    form.innerHTML = "";
    }
     function todayShow() {
    var input = document.getElementById('txtDate');
    input.style.display = "none";
    input.value = "";
    selDate = "";
    if(u!=""){
    var value = u;
    document.title = value;
          var spanid = 0;
          var butid = 0;
          var numv = 0;
          var doc_id = docs[docNames.indexOf(value)].id;
          var form = document.getElementById("apps"); 
          form.innerHTML = "";
          for(var i = 0; i<visits.length;i++){
              if(visits[i].doctor_id == doc_id){ //only add visits with the selected doctor
              numv++;
                var appbox = document.createElement("div");   
                appbox.setAttribute("class", "app-box");
                form.appendChild(appbox);
                var bothsides = document.createElement("div");   
                bothsides.setAttribute("class", "both-sides");
                bothsides.style = "padding-right:250px";
                appbox.appendChild(bothsides);
                var leftside = document.createElement("div");   
                leftside.setAttribute("class", "left-side");
                leftside.style = "padding-right:3.2%;*width:96.4%;float:left;";
                bothsides.appendChild(leftside);
                var arow = document.createElement("div");   
                arow.setAttribute("class", "a-row");
                leftside.appendChild(arow);
                var insideleft = document.createElement("div");   
                insideleft.setAttribute("class", "inside-left");
                arow.appendChild(insideleft);
                var aux = new Date(visits[i].date_visit);
                var arow3 = document.createElement("div");   
                arow3.setAttribute("class", "a-row");
                insideleft.appendChild(arow3);
                t = "Time: ";
                if(aux.getHours() <= 9) t=t+'0'+aux.getHours()+':';
                else t=t+aux.getHours()+':';
                if(aux.getMinutes() <= 9) t=t+'0'+aux.getMinutes();
                else t=t+aux.getMinutes();
                text = document.createTextNode(t);
                arow3.appendChild(text);
                var arow4 = document.createElement("div");   
                arow4.setAttribute("class", "a-row");
                insideleft.appendChild(arow4);
                t = "With patient: ";
                for(var y = 0; y<pats.length; y++){ //search for that patient's name
                    if(pats[y].ID == visits[i].patient_id) {
                        t = t + pats[y].data.display_name;
                        break;
                    }
                    if(y == (pats.length-1)) t = t + "Guest: "+visits[i].namePatient;
                }
                text = document.createTextNode(t);
                arow4.appendChild(text);
                var arow6 = document.createElement("div");   
                arow6.setAttribute("class", "a-row");
                arow6.style.color = "blue";
                insideleft.appendChild(arow6);
                t = "Edit";
                var a = document.createElement('a');
                text = document.createTextNode(t);
                a.appendChild(text);
                a.title = "Edit";
                a.href = add_query_arg(add_query_arg( permVE,'id',visits[i].id),'docid',visits[i].doctor_id);
                a.style = "margin-right: 5px;";
                arow6.appendChild(a);
                a = document.createElement('a');
                text = document.createTextNode("     Cancel");
                a.appendChild(text);
                a.title = "    Cancel";
                a.href = add_query_arg( permV,'id',visits[i].id);
                arow6.appendChild(a);
                var rightside = document.createElement("div");   
                rightside.setAttribute("class", "right-side");
                rightside.style = "width:250px;margin-right:-250px;float:left;";
                bothsides.appendChild(rightside);
                var arow5 = document.createElement("div");   
                arow5.setAttribute("class", "a-row");
                rightside.appendChild(arow5);
                var buttonstack = document.createElement("div");   
                buttonstack.setAttribute("class", "a-button-stack");
                arow5.appendChild(buttonstack);
                var span = document.createElement("span");   
                span.setAttribute("class", "button-span");
                span.setAttribute("id", 'id-' + spanid);
                buttonstack.appendChild(span);
                spanid++;
                var checkbox = document.createElement("input");   
                checkbox.setAttribute("type", "checkbox");
                checkbox.setAttribute("name", 'button'+butid);
                butid++;
                checkbox.setAttribute("value", 'Attended');
                text = document.createTextNode('  Attended');
                if(visits[i].assisted == 1) checkbox.checked = true;
                span.appendChild(checkbox);
                checkbox.appendChild(text);
                span.appendChild(text);
                buttonstack.appendChild(document.createElement("br"));
                var span1 = document.createElement("span");   
                span1.setAttribute("class", "button-span");
                span1.setAttribute("id", 'id-' + spanid);
                buttonstack.appendChild(span1);
                spanid++;
                var checkbox1 = document.createElement("input");   
                checkbox1.setAttribute("type", "checkbox");
                checkbox1.setAttribute("name", 'button'+butid);
                butid++;
                checkbox1.setAttribute("value", 'Completed');
                text = document.createTextNode('  Completed');
                if(visits[i].invisit == 1) checkbox1.checked = true;
                span1.appendChild(checkbox1);
                checkbox1.appendChild(text);
                span1.appendChild(text);
                var input2 = document.createElement("input");   
                input2.setAttribute("type", "hidden");
                input2.setAttribute("name", 'hidden_id'+butid);
                input2.setAttribute("value", visits[i].id);
                buttonstack.appendChild(input2);
                var hr= document.createElement("hr"); 
        leftside.appendChild(hr);
            }
        var input= document.createElement("input");   
        input.setAttribute("type", "hidden");
        input.setAttribute("name", 'num_visits');
        input.setAttribute("value", numv);
        form.appendChild(input);
            }
          if(numv==0){
                var input= document.createElement("p");   
                text = document.createTextNode('No visits today for this doctor');
        form.appendChild(input);
        input.appendChild(text);
            }else{
        while(numv>0){        
        form.appendChild(document.createElement("br"));
        form.appendChild(document.createElement("br"));
        form.appendChild(document.createElement("br"));
        form.appendChild(document.createElement("br"));
        numv--;
        }
        var input1= document.createElement("input");   
        input1.setAttribute("type", "submit");
        input1.setAttribute("name", 'submit');
        input1.setAttribute("value", 'Submit');
        form.appendChild(input1);
            }
    }
    }
    
    function showOtherDays() {
    var value = u;
    document.title = value;
          var spanid = 0;
          var butid = 0;
          var numv = 0;
          var doc_id = docs[docNames.indexOf(value)].id;
          var form = document.getElementById("apps"); 
          form.innerHTML = "";
          for(var i = 0; i<visitsAll.length;i++){
              if(visitsAll[i].doctor_id == doc_id){ //only add visits with the selected doctor
              var d = selDate.getDate();
              var m = selDate.getMonth();
              var year = selDate.getFullYear();
              var auxf = new Date(visitsAll[i].date_visit);
              if (auxf.getMonth() == m && auxf.getFullYear() == year && auxf.getDate() == d) {
              numv++;
                var appbox = document.createElement("div");   
                appbox.setAttribute("class", "app-box");
                form.appendChild(appbox);
                var bothsides = document.createElement("div");   
                bothsides.setAttribute("class", "both-sides");
                bothsides.style = "padding-right:250px";
                appbox.appendChild(bothsides);
                var leftside = document.createElement("div");   
                leftside.setAttribute("class", "left-side");
                leftside.style = "padding-right:3.2%;*width:96.4%;float:left;";
                bothsides.appendChild(leftside);
                var arow = document.createElement("div");   
                arow.setAttribute("class", "a-row");
                leftside.appendChild(arow);
                var insideleft = document.createElement("div");   
                insideleft.setAttribute("class", "inside-left");
                arow.appendChild(insideleft);
                var aux = new Date(visitsAll[i].date_visit);
                var arow3 = document.createElement("div");   
                arow3.setAttribute("class", "a-row");
                insideleft.appendChild(arow3);
                t = "Time: ";
                if(aux.getHours() <= 9) t=t+'0'+aux.getHours()+':';
                else t=t+aux.getHours()+':';
                if(aux.getMinutes() <= 9) t=t+'0'+aux.getMinutes();
                else t=t+aux.getMinutes();
                text = document.createTextNode(t);
                arow3.appendChild(text);
                var arow4 = document.createElement("div");   
                arow4.setAttribute("class", "a-row");
                insideleft.appendChild(arow4);
                t = "With patient: ";
                for(var y = 0; y<pats.length; y++){ //search for that patient's name
                    if(pats[y].ID == visitsAll[i].patient_id) {
                        t = t + pats[y].data.display_name;
                        break;
                    }
                    if(y == (pats.length-1)) t = t + "Guest: "+visitsAll[i].namePatient;
                }
                text = document.createTextNode(t);
                arow4.appendChild(text);
                var arow6 = document.createElement("div");   
                arow6.setAttribute("class", "a-row");
                arow6.style.color = "blue";
                insideleft.appendChild(arow6);
                t = "Edit";
                var a = document.createElement('a');
                text = document.createTextNode(t);
                a.appendChild(text);
                a.title = "Edit";
                a.href = add_query_arg(add_query_arg( permVE,'id',visitsAll[i].id),'docid',visitsAll[i].doctor_id);
                a.style = "margin-right: 5px;";
                arow6.appendChild(a);
                a = document.createElement('a');
                text = document.createTextNode("     Cancel");
                a.appendChild(text);
                a.title = "    Cancel";
                a.href = add_query_arg( permV,'id',visitsAll[i].id);
                arow6.appendChild(a);
                var rightside = document.createElement("div");   
                rightside.setAttribute("class", "right-side");
                rightside.style = "width:250px;margin-right:-250px;float:left;";
                bothsides.appendChild(rightside);
                var arow5 = document.createElement("div");   
                arow5.setAttribute("class", "a-row");
                rightside.appendChild(arow5);
                var buttonstack = document.createElement("div");   
                buttonstack.setAttribute("class", "a-button-stack");
                arow5.appendChild(buttonstack);
                var span = document.createElement("span");   
                span.setAttribute("class", "button-span");
                span.setAttribute("id", 'id-' + spanid);
                buttonstack.appendChild(span);
                spanid++;
                var checkbox = document.createElement("input");   
                checkbox.setAttribute("type", "checkbox");
                checkbox.setAttribute("name", 'button'+butid);
                butid++;
                checkbox.setAttribute("value", 'Attended');
                text = document.createTextNode('  Attended');
                if(visitsAll[i].assisted == 1) checkbox.checked = true;
                span.appendChild(checkbox);
                checkbox.appendChild(text);
                span.appendChild(text);
                buttonstack.appendChild(document.createElement("br"));
                var span1 = document.createElement("span");   
                span1.setAttribute("class", "button-span");
                span1.setAttribute("id", 'id-' + spanid);
                buttonstack.appendChild(span1);
                spanid++;
                var checkbox1 = document.createElement("input");   
                checkbox1.setAttribute("type", "checkbox");
                checkbox1.setAttribute("name", 'button'+butid);
                butid++;
                checkbox1.setAttribute("value", 'Completed');
                text = document.createTextNode('  Completed');
                if(visitsAll[i].invisit == 1) checkbox1.checked = true;
                span1.appendChild(checkbox1);
                checkbox1.appendChild(text);
                span1.appendChild(text);
                var input2 = document.createElement("input");   
                input2.setAttribute("type", "hidden");
                input2.setAttribute("name", 'hidden_id'+butid);
                input2.setAttribute("value", visitsAll[i].id);
                buttonstack.appendChild(input2);
                var hr= document.createElement("hr"); 
        leftside.appendChild(hr);
        }
            }
        var input= document.createElement("input");   
        input.setAttribute("type", "hidden");
        input.setAttribute("name", 'num_visits');
        input.setAttribute("value", numv);
        form.appendChild(input);
            }
          if(numv==0){
                var input= document.createElement("p");   
                text = document.createTextNode('No visits today for this doctor');
        form.appendChild(input);
        input.appendChild(text);
            }else{
                
        while(numv>0){        
        form.appendChild(document.createElement("br"));
        form.appendChild(document.createElement("br"));
        form.appendChild(document.createElement("br"));
        form.appendChild(document.createElement("br"));
        numv--;
        }
        var input1= document.createElement("input");   
        input1.setAttribute("type", "submit");
        input1.setAttribute("name", 'submit');
        input1.setAttribute("value", 'Submit');
        input1.setAttribute("form", 'apps');
        form.appendChild(input1);
            }
    }
    
    function add_query_arg(purl, key,value){
    key = escape(key); value = escape(value);

    var s = purl;
    var pair = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^&]*");

    s = s.replace(r,"$1"+pair);
    if(s.indexOf(key + '=')>-1){


    }else{
        if(s.indexOf('?')>-1){
            s+='&'+pair;
        }else{
            s+='?'+pair;
        }
    }

    return s;
}
});
  
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
