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

			<?php endwhile;  // end of the loop. ?>
			<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<?php
$doctorID = $_POST['doctID'];
$spec = $_POST['specName'];
global $wpdb;
$hol= $wpdb->get_results("SELECT fromDay, toDay FROM wp_holidays");
$noatt = $wpdb->get_results("SELECT fromDate, toDate FROM wp_no_attendance WHERE doctor_id =". $doctorID);
$visits = $wpdb->get_results("SELECT date_visit FROM wp_appointments WHERE DATE(date_visit) > STR_TO_DATE('".current_time( 'mysql' )."', '%Y-%m-%d') AND doctor_id =". $doctorID);
$sched = $wpdb->get_results("SELECT fromDate, hours FROM wp_working_schedules WHERE doctor_id = ". $doctorID." ORDER BY fromDate ASC");

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
   $aux = $aux + 1;
   }
?>

<form action="choose-a-time-slot-2/appointment-added-2" method="post" name="form3" id="specanddoc">

<div id="appMake">
<h2> Please select date of a visit </h2><br><br>

<label> Date: </label><br>
<input placeholder="maximum 6 months" name="dateSelec" type='text' id='txtDate' name='txtDate' />
<script type="text/javascript">
Date.prototype.addDays = function(days) {
    var dat = new Date(this.valueOf())
    dat.setDate(dat.getDate() + days);
    return dat;
}

function addMinutes(date, minutes) {
    return new Date(date.getTime() + minutes * 60000);
}
jQuery(document).ready(function($) {
    var thisday = [];
    var thisdaynoatt = [];
    var hol = <?php echo json_encode($hol); ?>;
    var patients = <?php echo json_encode($patients); ?>;
    var visits = <?php echo json_encode($visits); ?>;
    var docID = <?php echo json_encode($doctorID); ?>;
    var noatt = <?php echo json_encode($noatt); ?>;
    var sched = <?php echo json_encode($sched); ?>;
    var wd = [];

    var nwd = [];
    
function getSchedule(dateS){
        var fin = 0;
        var aux = dateS;
        aux.setHours(0, 0, 0, 0);
        for(var i = 1; i<sched.length; i++){
        var a = new Date(sched[i].fromDate);
        a.setHours(0, 0, 0, 0);
        if(aux.getTime() == a.getTime()){ //since today schedule
            fin = i;
            break;
        }
        if(aux.getTime() < a.getTime()) break; //We found the one in question
        fin = i;
        }
        var hours = (sched[fin].hours).split(','); //array with working schedule
        var aux2 = 0;
        wd = new Array(6);
        nwd = [];
        wd[0] = new Array();
        wd[0][0] = new Array(2);
        wd[0][0].fromTime = '0';
        wd[0][0].toTime = '0';
        wd[1] = new Array();
        aux2 = hours[0].split('+');
        for(var x = 0; x<aux2.length; x++){
        wd[1][x] = new Array(2);
        aux = aux2[x].split('-');
        wd[1][x].fromTime = aux[0];
        wd[1][x].toTime = aux[1];
        }
        wd[2] = new Array();
        aux2 = hours[1].split('+'); //09:00-14:00+15:00-17:00
        for(var x = 0; x<aux2.length; x++){
        wd[2][x] = new Array(2);
        aux = aux2[x].split('-');
        wd[2][x].fromTime = aux[0];
        wd[2][x].toTime = aux[1];
        }
        wd[3] = new Array();
        aux2 = hours[2].split('+');
        for(var x = 0; x<aux2.length; x++){
        wd[3][x] = new Array(2);
        aux = aux2[x].split('-');
        wd[3][x].fromTime = aux[0];
        wd[3][x].toTime = aux[1];
        }
        wd[4] = new Array();
        aux2 = hours[3].split('+');
        for(var x = 0; x<aux2.length; x++){
        wd[4][x] = new Array(2);
        aux = aux2[x].split('-');
        wd[4][x].fromTime = aux[0];
        wd[4][x].toTime = aux[1];
        }
        wd[5] = new Array();
        aux2 = hours[4].split('+');
        for(var x = 0; x<aux2.length; x++){
        wd[5][x] = new Array(2);
        aux = aux2[x].split('-');
        wd[5][x].fromTime = aux[0];
        wd[5][x].toTime = aux[1];
        }
        wd[6] = new Array();
        wd[6][0] = new Array(2);
        wd[6][0].fromTime = '0';
        wd[6][0].toTime = '0';
        if (wd[1][0].fromTime == '0') nwd[0] = 1;
    if (wd[2][0].fromTime == '0') nwd[1] = 2;
    if (wd[3][0].fromTime == '0') nwd[2] = 3;
    if (wd[4][0].fromTime == '0') nwd[3] = 4;
    if (wd[5][0].fromTime == '0') nwd[4] = 5;
    if (wd[6][0].fromTime == '0') nwd[5] = 6;
    if (wd[0][0].fromTime == '0') nwd[6] = 0;
    }
    
    var arrDisabledDates = {};
    for (var i = 0; i < hol.length; i++) {
        var fd = new Date(hol[i].fromDay + ' 00:00:00');
        var td = new Date(hol[i].toDay + ' 00:00:00');
        while (fd <= td) {
            arrDisabledDates[fd] = fd;
            fd = fd.addDays(1);
        }
    }

    jQuery('#txtDate').datepicker({
        onSelect: function(dateText, inst) {
            var times = [];
            var selD = new Date(dateText);
            var dow = selD.getDay();
            var d = selD.getDate();
            var m = selD.getMonth();
            var y = selD.getFullYear();
            getSchedule(selD);
            for (var i = 0; i < visits.length; i++) {
                var aux = new Date(visits[i].date_visit);
                if (aux.getDate() == d && aux.getMonth() == m && aux.getFullYear() == y) {
                    thisday[aux] = aux;
                }
            }
            var ind = 0;
            for (var i = 0; i < noatt.length; i++) {
                var auxf = new Date(noatt[i].fromDate);
                auxf.setHours(0, 0, 0, 0);
                var auxt = new Date(noatt[i].toDate);
                auxt.setHours(0, 0, 0, 0);
                while (auxf.getTime() <= auxt.getTime()) {
                    if (auxf.getMonth() == m && auxf.getFullYear() == y && auxf.getDate() == d) {
                        thisdaynoatt[ind] = i;
                        ind = ind + 1;
                        break;
                    } else auxf.setDate(auxf.getDate() + 1);
                }
            }
            //Here will have to be changed in order to have multiple schedules per day (aka luch times, etc)
            for (var y2 = 0; y2 < wd[dow].length; y2++) {
                var wf = wd[dow][y2].fromTime;
                var wt = wd[dow][y2].toTime;
                var unt = new Date(dateText + ' ' + wt);
                var selD = new Date(dateText + ' ' + wf);
                while (selD < unt) {
                    var hour = selD.getHours();
                    var min = selD.getMinutes();
                    if (min < 10) min = "0" + min;
                    if (hour < 10) hour = "0" + hour;
                    var exi = thisday[selD];
                    if (exi); //there is a visit at this time
                    else if (thisdaynoatt.length > 0) {
                        var found = 0;
                        var i = 0;
                        for (i = 0; i < thisdaynoatt.length; i++) {
                            var df = new Date(noatt[thisdaynoatt[i]].fromDate);
                            var dt = new Date(noatt[thisdaynoatt[i]].toDate);
                            if (df.getTime() <= selD.getTime() && dt.getTime() > selD.getTime()) {
                                found = 1;
                                break;
                            }
                        }
                        if (!found) times.push(hour + ':' + min);
                    } else times.push(hour + ':' + min);
                    selD = addMinutes(selD, 15);
                }
            }
            var mybr = document.createElement('br');
            var myDiv = document.getElementById("appMake");
            var selectList = document.getElementById("doct");
            if (selectList) {
                $('#doct').empty();
            } else {
                myDiv.appendChild(mybr);
                var myLabel = document.createElement("label");
                myLabel.setAttribute("id", "doctlabel");
                myLabel.innerHTML = "Time: ";
                myDiv.appendChild(myLabel);
                selectList = document.createElement("select");
                selectList.setAttribute("name", "timeChosen");
                selectList.setAttribute("id", "doct");
                selectList.setAttribute("form", "specanddoc");
                myDiv.appendChild(selectList);
                myDiv.appendChild(mybr);
                var persLabel = document.createElement("label");
                persLabel.setAttribute("id", "perslabel");
                persLabel.innerHTML = "Patient's name: ";
                myDiv.appendChild(persLabel);
                var fulln = document.createElement('input');
                fulln.setAttribute('id', 'persName');
                fulln.setAttribute('name', 'persName');
                fulln.setAttribute('type', 'text');
                fulln.required = true;
                myDiv.appendChild(fulln);
                var emailLabel = document.createElement("label");
                emailLabel.setAttribute("id", "emaillabel");
                emailLabel.innerHTML = "Email: ";
                myDiv.appendChild(emailLabel);
                var em = document.createElement('input');
                em.setAttribute('id', 'em');
                em.setAttribute('name', 'em');
                em.setAttribute('type', 'text');
                em.required = true;
                myDiv.appendChild(em);
                myDiv.appendChild(mybr);
                var textArea = document.createElement('TEXTAREA');
                textArea.setAttribute('id', 'txtArea');
                textArea.setAttribute('name', 'txtArea');
                textArea.setAttribute("form", "specanddoc");
                textArea.setAttribute('placeholder', "Please formulate your problem...");
                textArea.setAttribute('cols', 30);
                textArea.setAttribute('style', "height: 168px;margin-left: 40px;width: 308px;margin-bottom: 20px;");
                textArea.setAttribute('rows', 5);
                myDiv.appendChild(textArea);
                myDiv.appendChild(mybr);
                var butt = document.createElement("input");
                butt.setAttribute("name", "Submit");
                butt.setAttribute("id", "Submit");
                butt.setAttribute("form", "specanddoc");
                butt.setAttribute("value", "Make appointment");
                butt.setAttribute("type", "submit");
                myDiv.appendChild(butt);
                butt = document.createElement("input");
                butt.setAttribute("name", "doctorID");
                butt.setAttribute("value", docID);
                butt.setAttribute("type", "hidden");
                myDiv.appendChild(butt);
            }
            for (var i = 0; i < times.length; i++) {
                var option = document.createElement("option");
                option.setAttribute("value", times[i]);
                option.text = times[i];
                selectList.appendChild(option);
            }
        },
        dateFormat: "yy-mm-dd",
        firstDay: 1,
        minDate: 1,
        maxDate: "+6m",
        beforeShowDay: function(date) {
        getSchedule(date);
            var day = date.getDay();
            bDisable = arrDisabledDates[date];
            if (bDisable) return [false, '', ''];
            else {
                if (nwd.indexOf(day) > -1) return [false, '', ''];
                else {
                    var vpd = 0;
                    var d = date.getDate();
                    var m = date.getMonth();
                    var y = date.getFullYear();
                    for (var y2 = 0; y2 < wd[day].length; y2++) {
                        var wfh = wd[day][y2].fromTime;
                        var wth = wd[day][y2].toTime;
                        //select the hour from wth and wfh
                        var wfh2 = parseInt(wfh.substring(0, 2));
                        var wth2 = parseInt(wth.substring(0, 2));
                        //select minutes from wth and wfh
                        var wth3 = parseInt(wth.substring(3, 5));
                        var wfh3 = parseInt(wfh.substring(3, 5));
                        if (wth3 == wfh3) vpd = vpd + (wth2 - wfh2) * 4 - 1;
                        else if (wth3 > wfh3) vpd = vpd + (wth2 - wfh2) * 4 + (wth3 - wfh3) / 15 - 1;
                        else vpd = vpd + (wth2 - wfh2) * 4 - (wfh3 - wth3) / 15 - 1;
                    }
                    for (var i = 0; i < noatt.length; i++) {
                        var df = new Date(noatt[i].fromDate);
                        var dt = new Date(noatt[i].toDate);
                        var auxf = new Date(noatt[i].fromDate);
                        auxf.setHours(0, 0, 0, 0);
                        var auxt = new Date(noatt[i].toDate);
                        auxt.setHours(0, 0, 0, 0);
                        if (auxf.getTime() <= date.getTime() && auxt.getTime() >= date.getTime()) { //"No attendance" is during this day
                            if (dt.getDate() == d && dt.getMonth() == m && dt.getFullYear() == y && df.getDate() == d && df.getMonth() == m && df.getFullYear() == y) { //doctor absent only during this day
                            var vpd2 = vpd;
                                if (dt.getMinutes() == df.getMinutes()) vpd = vpd - ((dt.getHours() - df.getHours()) * 4 - 1);
                                else if (dt.getMinutes() > df.getMinutes()) vpd = vpd - ((dt.getHours() - df.getHours()) * 4 + (dt.getMinutes() - df.getMinutes()) / 15 - 1);
                                else vpd = vdp - ((dt.getHours() - dt.getHours()) * 4 - (df.getMinutes() - dt.getMinutes()) / 15 - 1);
                                if(vpd>vpd2) vpd=0;
                            } else
                            if (dt.getDate() == d && dt.getMonth() == m && dt.getFullYear() == y && (df.getDate() != d || df.getMonth() != m || df.getFullYear() != y)) { //toDate is the date and strting date is previous to date
                                var wf2 = wd[day][0].fromTime;
                                var df2 = new Date(dt.getFullYear(), dt.getMonth(), dt.getDate(), parseInt(wf2.substring(0, 2)), parseInt(wf2.substring(3, 5)), 0);
                                if(df2.getTime() < dt.getTime()){
                                if (dt.getMinutes() == df2.getMinutes()) vpd = vpd - ((dt.getHours() - df2.getHours()) * 4 - 1);
                                else if (dt.getMinutes() > df2.getMinutes()) vpd = vpd - ((dt.getHours() - df2.getHours()) * 4 + (dt.getMinutes() - df2.getMinutes()) / 15 - 1);
                                else vpd = vdp - ((dt.getHours() - dt.getHours()) * 4 - (df2.getMinutes() - dt.getMinutes()) / 15 - 1);
                                }
                            } else
                            if (df.getDate() == d && df.getMonth() == m && df.getFullYear() == y && (dt.getDate() != d || dt.getMonth() != m || dt.getFullYear() != y)) { //fromDate is the date and ending date is next to date
                                var wt2 = wd[day][0].toTime;
                                var dt2 = new Date(df.getFullYear(), df.getMonth(), df.getDate(), parseInt(wt2.substring(0, 2)), parseInt(wt2.substring(3, 5)), 0);
                                if(dt2.getTime() > df.getTime()){
                                if (dt2.getMinutes() == df.getMinutes()) vpd = vpd - ((dt2.getHours() - df.getHours()) * 4 - 1);
                                else if (dt2.getMinutes() > df.getMinutes()) vpd = vpd - ((dt2.getHours() - df.getHours()) * 4 + (dt2.getMinutes() - df.getMinutes()) / 15 - 1);
                                else vpd = vdp - ((dt2.getHours() - dt2.getHours()) * 4 - (df.getMinutes() - dt2.getMinutes()) / 15 - 1);
                                }
                            } else return [false, '', ''];
                        }
                    } // end of for loop over noatt array
                    for (var i = 0; i < visits.length; i++) {
                        var aux = new Date(visits[i].date_visit);
                        if (aux.getDate() == d && aux.getMonth() == m && aux.getFullYear() == y) {
                            vpd = vpd - 1;
                        }
                    }
                    if (vpd <= 0) return [false, '', ''];
                    else return [true, '', ''];
                }
            }
        }
    });
    
    $('#specanddoc').submit(function() {
    var email = document.getElementById("em").value;
    var reg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!reg.test(email)) { 
    //not a correct email format introduced
    alert("Not a valid email. An email should be in a 'email@domain.domain' format");
    return false;
    }else{
    for(var y = 0; y<patients.length; y++){
    if(patients[y].email == email) {
    alert("There is already a user with this email. Please use another one or log in into your account");
    return false;
    }
    }
    }
    return true;
    });
});
</script>
</div>
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