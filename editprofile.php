<?php
include_once("checksession.php");
if($user)
{
	$email= $user_info['email'];
	$checkconfirmcodequery = "Select userid, confirmcode from user where email= '$email'  ";
												
	$result = mysql_query($checkconfirmcodequery);
	if (!$result) {
		die("Invalid query: " . mysql_error());
	} 
	
	$row = mysql_fetch_assoc($result); 
	if($row['confirmcode']!='y')
	{
	echo "Confirm code is not y ";
	$uid=$row['userid'];
	$updatequery= "update user set confirmcode='y' where userid= '$uid'";
	$result = mysql_query($updatequery);
	if (!$result) {
		die("Invalid query: " . mysql_error());
		} 
	$urllocation = "https://graph.facebook.com/".$user."/picture?type=large";
	$updateurllocation = "update user set profileurllocation = '$urllocation' where userid = '$uid' ";
	$result = mysql_query($updateurllocation);
	if (!$result) {
		die("Invalid query: " . mysql_error());
		} 
	}
}
//unique id for progress bar
$up_id = uniqid(); 

?>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title> Caarya - Edit your profile </title>
		<?php include_once("menu_styles.php"); ?>
		<link rel="stylesheet" href="style/editprofile.css"  media="screen, projection" type="text/css" /> 

		<?php include_once("menu_scripts.php"); ?>
		<?php include_once("analyticstracking.php"); ?>
		<?php include_once("global_head_footer.php"); ?>
		
		
		<!--Address Geocode-->
		<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
		<script src="scripts/jquery.geocomplete.js"></script>

				<!-- scripts for post job included for compatibility-->
        <!-- <script src="http://maps.google.com/maps/api/js?sensor=false"></script> -->
	<!--	<script src="scripts/postjob/jquery-1.4.4.min.js"></script> -->
		<script src="scripts/postjob/jquery-ui-1.8.7.min.js"></script>
		<script src="scripts/postjob/jquery.ui.addresspicker.js"></script> 

	
		<script>
			var noofclicks=0;

			function showdiv(divname)
			{	
				$('.middlecol ul').hide();
				$('#'+divname).show();
			}

			function editfields(name){
				var btntxt = $('#edit'+name).text();

				if(btntxt==="edit"){
					$('#edit'+name).html('save');
					$('#save'+name+' input').removeAttr("disabled");
					if(name=='account')
						$('#email').focus();
					else
						$('#address').focus();
				}
				else if(btntxt=="save"){	
					if($('#confpass').val()!=$('#pass').val())
						return false;
					$('#save'+name+' input').not(':input[type=submit]').attr("disabled",true);
					$('#sec'+name).fadeIn();
					$('#edit'+name).html('cancel');
				}
				else{
					$('#sec'+name).fadeOut('fast');
					$('#edit'+name).html('edit');
				} 
			} 

			$(function() {
				$(".pro-pic").hover(
					function() {
						$(this).children("img").fadeTo(200, 1).end().children(".hoverdiv").fadeIn();
					},
					function() {
						$(this).children("img").fadeTo(200, 1).end().children(".hoverdiv").fadeOut("2000");
					}
				);
			}); 
 
			$(document).ready(function(){

				<?php if($tempmail==""){ ?>
					$('#fortempmail').hide();
				<?php } ?>

				$('form').submit(function(){
					$('form input').removeAttr("disabled");
				});
	
				<?php if(isset($_GET['ft'])){ ?>
					editfields('account');
				<?php } ?>

				$('#pass').focus(function() {
					$('#forconfpassword').fadeIn();
				});

				$('#confpass').blur(function() {
					if($('#confpass').val()==$('#pass').val()){
						$('#checkconf').attr('src','style/img/right.png');
						$('.error').fadeOut();
					}
					else{
						$('#checkconf').attr('src','style/img/wrong.png');
						$('.error').fadeIn();
					}
					$('#checkconf').fadeIn();
				}); 

				$('#clickme').click(function(){
					$('#uploadme').click();
				}); 
		
				 $(".geocomplete").geocomplete();

				$( "#dob" ).datepicker({ 
						dateFormat: 'mm-dd-yy' ,
						changeYear: true, 
						yearRange: "1900:2010",
						changeMonth:true,
					});

				getRating('<?php echo stringencrypt($email) ?>');
			});
		</script>
	</head>

	<body>
		<?php 	include_once("menubar.php"); ?>
		<div id="container">
			<div id="pagewrap">
				<div class="leftcol effect7">
					<div id='picnurl'> 
						<form id="uploadform" action="imgupload.php" method="post" enctype='multipart/form-data'>
							<input type="hidden" name="upload" id="upload" /> 
							<input type="file"  onchange="this.form.submit();" style="visibility:hidden;" id="uploadme" name='myfile'/>
							<div class="pro-pic">
								<img id="cropbox" src="<?php $rand = rand(); $alt = "profilepictures/unknown2.png";
																			 if($pic=="")
																			 echo $alt."?r=" . $rand;
																			 else  echo $pic."?r=" . $rand;
																?>" style='width: 100%; height: 100%;' alt='No pic available' /> 
								<div id="clickme" class="hoverdiv">Upload Picture</div>
							</div>
						</form>
						<div id='ratingholder'>
							<ul class='star-rating'><li class="current-rating" id="rate<?php echo stringencrypt($email) ?>" ><!-- will show current rating --></li></ul>
						</div>
						<div id="urllink">http://www.google.com</div>
					</div>
					<ul id="sections">
						<li onclick='showdiv("account")'>Account </li>
						<li onclick='showdiv("personal")'>Personal</li>
						<li onclick='showdiv("professional")'>Professional </li>
						<li onclick='showdiv("testimonials and reviews")'>Reviews </li> 
					</ul>
				</div>
				<div class="middlecol">
					<ul id="account" style="display:block;">
						<form id="saveaccount" class='iform' action='allfields.php' method='post' accept-charset='UTF-8'>
							<input type='hidden' name='accountsubmitted' id='accountsubmitted' value='1'/>
							<li class="heading"> 
								<label> Account Settings </label>	
								<a id="editaccount" class='editbtn' onclick="editfields('account')">edit</a>
							</li>
							<li id='secaccount' class="forpassword" style="display:none;">
							 	<label>Please enter your password to save changes</label> 
								<input id="checkpassacc" name="checkpass" value=""/>
								<input id='saveaccdetails' class='confirmbtn' type='submit' name='accsubmit' value='confirm submission' /> 
							</li>
							<li id='fortempmail'>
								<p> An email has been sent to <?php echo $tempmail ?> for verification. Please confirm the link to change your email.</p>
								<a href='#'> Resend confirmation mail </a>
							</li>
							<li id="foremail"> 
								<label>Email </label>
								<input id="email" name="email" value="<?php echo $email; ?>" disabled="disabled"/>
							</li>
							<li id="forname"> 
								<label>Name </label>
								<input id="currname" name="currname" value="<?php echo $name; ?>" disabled="disabled"/>
							</li>
							<li id="foruname"> 
								<label>User Name</label>
								<input id="uname" name="uname" value="<?php echo $uname; ?>" disabled="disabled"/>
							</li>
							<li id="forpassword"> 
								<label>Password</label>
								<input  type="password" id="pass" name="pass"  disabled="disabled"/>
							</li>
							<li id="forconfpassword" style="display: none;"> 
								<label>Confirm Password</label>
								<input type="password" id="confpass" name="confpass" disabled="disabled"/> 
								<img id="checkconf" src="style/img/right.png" width="26px" height="26px" style="display: none;">
							</li>
							<p class='error'> The two passwords entered donot match </p>
						</form>
					</ul>
					<ul id="personal">
						<form id="savepersonal" class='iform' action='allfields.php' method='post' accept-charset='UTF-8'>
							<input type='hidden' name='personalsubmitted' id='personalsubmitted' value='1'/>
							<li class="heading"> 
								<label> Personal Settings </label>	
								<a id="editpersonal" class='editbtn' onclick="editfields('personal')">edit</a>
							</li>
							<li id='secpersonal' class="forpassword" style="display:none;">
							 	 <label>Please enter your password to save changes</label> 
								 <input id="checkpassper" name="checkpass" value=""/>
								 <input id='saveperdetails' class="confirmbtn" type='submit' name='personalsubmit' value='confirm submission' /> 
							</li>
							<li id="foraddress"> 
								<label> Address </label>
								 <input id="address" class='geocomplete' name="address" value="<?php echo $address; ?>" disabled="disabled"/>
							</li>
							<li id="forphoneno"> 
								<label> Phone No </label>
								 <input id="phnno" name="phnno"  disabled="disabled"/>
							</li>
							<li id="fordob"> 
								<label> Date Of Birth </label>
								 <input id="dob" name="dob"  disabled="disabled"/>
							</li>
							<li id="forgender"> 
								<label> Gender </label>
								<input type="radio" name="sex" value="male"> Male
								<input type="radio" name="sex" value="female" style='margin-left:10px;'> Female
							</li>
							<li id="forjobpref"> 
								<label> Job Preference </label>
								<input id="jobpref" name="jobpref"  disabled="disabled"/>
							</li>
						</form>
					</ul>
					<ul id="professional">
						<form id="saveprofessional" class='iform' action='allfields.php' method='post' accept-charset='UTF-8'>
							<input type='hidden' name='professionalsubmitted' id='professionalsubmitted' value='1'/>
							<li class="heading"> 
								<label> Professional Settings </label>	
							</li>
							<li id="fornojp"> 
								<label> No of jobs posted </label>
								 <input id="nojp" name="nojp" disabled="disabled" value="<?php echo $nojp; ?>"/>
							</li>
							<li id="fornoja"> 
								<label> No of jobs applied for </label>
								 <input id="noja" name="phnno"  disabled="disabled" value="<?php echo $noja; ?>"/>
							</li>
							<li id="fornojc"> 
								<label> No of jobs completed </label>
								 <input id="nojc" name="nojc"  disabled="disabled" value="<?php echo $nojc; ?>"/>
							</li>
							<li id="forprofvalue"> 
								<label> Overall professional value </label>
								<input id="profvalue" name="profvalue"  disabled="disabled" value="<?php echo $value; ?>/100"/>
							</li>
						</form>
					</ul>
					<ul id="testnrev">
						If testimonials is Clicked
						Testimonials And Reviews<br/><br/>
					</ul>
					<ul id="privacy">
						If privacy is Clicked
						Privacy and Security<br/><br/>
					</ul>
				</div>
				<div class="rightcol">
					<div id='profcompleteness'>
						<label> Profile Completeness 
						<img src="style/img/question.jpg"  alt="doesnt exist" style='height: 20px; width: 20px;'/>
						<br/><?php echo $count; ?>% </label> 
					</div>
				</div> 
			</div>
		</div>
	<?php// include 'footer.php' ?>
	</body>
</html>