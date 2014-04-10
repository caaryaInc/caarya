<?php
require_once("allfields.php");
if(isset($_POST['passsubmitted']))
{
	$pass = $_POST['newpass'];
	$passqry = "Update user Set password='".md5($pass)."' where  email='$email'";
	$result = mysql_query($passqry);
	if (!$result) {
		die("Invalid query: " . mysql_error());         
	} 
	echo "<script>window.top.location='homepage.php'</script>";
}
?>

<html>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
		<title>Caarya- Change Password</title>
		<link rel="stylesheet" href="style/pwd-link-sent.css"  media="screen, projection" type="text/css" /> 
		<link rel="stylesheet" type="text/css" href="style/masterstyle.css"/>
		<link rel="stylesheet" type="text/css" href="style/menubar.css"/> 
		<script type='text/javascript' src='http://code.jquery.com/jquery-1.5.2.js'></script>
		<style>
			<!--
				#pwdcontainer{
					position: absolute;
					margin-top: 50px;
					width: 100%;
					height: 80%;
				}
			
				.resetcontainer{
					margin-top: 80px;
				}

				.inputholder{
					margin-top: 15px;
					margin-bottom: 10px;
				}

				.frgtlabel{
					float: left;
					width: 140px;
				}
				.inputholder input{
					border: 1px solid #ddd;
					height: 25px;
					display: inline-block;
				}

				.cancel{
					height: 17px;
					margin-left: 20px;
					padding: 4px 12px 5px;
				}

				#footer{
					margin-top: 90px;
				}

				.error{
					display: inline-block;
					margin-left: 20px;
				}
			-->
		</style>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#newpass').blur(function(){
					if($(this).val()=="")
						$('#error_pass').show();
					else
						$('#error_pass').hide();
				});

				$('#confpass').blur(function(){
					if($(this).val()==""){
						$('#error_confpass').show();
						$('#error_nomatch').hide();
					}
					else
						$('#error_confpass').hide();
				});
	
				$('#passform').submit(function(){
					var error;
					var newpass =  $('#newpass').val();
					var confpass = $('#confpass').val();
					if(newpass==""){
						$('#error_pass').html('Please enter a password');
						error=true;
					}
					if(confpass==""){
						$('#error_confpass').html('Please confirm your password');
						error=true; 
					}
					if(newpass!=confpass){
						$('#error_nomatch').html('The passwords entered dont match');
						error=true;
					}
					if(error)
						return false;
				});
			});
		</script>
	</head>
	<body>
	<header id="menucontainer" role="banner">
		<div class="defaultContentWidth clearfix">
				<a id="logo" class="left" href="homepage.php"> </a>
		</div>
	</header>
	<div id='pwdcontainer'>
		<form id='passform' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
			<input type='hidden' name='passsubmitted' id='passsubmitted' value='1'/>
			<div class='resetcontainer'>
				<p class='frgttitle'> Choose a new password </p>
				<div class='inputholder'>
						<label for='newpass' class='frgtlabel'> New Password</label>
						<input type='password' name='newpass' id='newpass' maxlength="50" /> 
						<div id='error_pass' class='error' style='color: red; font-size: 12px;'></div>
				</div>
				<div class='inputholder'>
						<label for='confpass' class='frgtlabel'> Confirm Password</label>
						<input type='password' name='confpass' id='confpass' maxlength="50" /> 
						<div id='error_confpass' class='error' style='color: red; font-size: 12px;'></div>
						<div id='error_nomatch' class='error' style='color: red; font-size: 12px;'></div>
				</div>
			</div>
			<div class='ccfooter'>
				<a  class='codesubmit cancel' href='logout.php'>Cancel</a>
				<input  class='codesubmit' type='submit' name='Submit' value='Continue' />
			</div>
			<div id="footer">
				<p><strong>@ 2012 Copyrights by Caarya. </strong>
						 All rights reserved.</p>
			</div>
		</form>
	</div>
	</body>
</html>