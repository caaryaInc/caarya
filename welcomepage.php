<?php
include_once("./include/membersite_config.php");
include_once("./include/fbaccess.php");
include_once("fbconnect.php");
if(($fgmembersite->CheckLogin()))
	echo "<script>window.top.location='homepage.php'</script>";
?>

<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title> Welcome to Caarya </title>
		<?php include_once("global_head_welcome.php"); ?>
		<?php include_once("analyticstracking.php"); ?>
	</head>

	<body onload="load(); repeat();" onunload="GUnload()">
		<header id="branding" role="banner"> 
			<div class="defaultContentWidth clearfix">
				<a href='welcomepage.php'><div id="logo" class="left"></div></a>
				<div id="loginhead" class="right">
					<?php @include('login_box.php'); ?>
				</div>
			</div>
		</header>
	
		<div id="container">
			<div id="pagewrap" class="clearfix">
				<div  id="leftpanel" class="left">
						<ul id="info">
							<li class="tasksinfo">Get your daily tasks done</li>
							<li class="moneyinfo"> Complete tasks and earn money </li>
							<li class='stats'>
								<b class="number" id="tasks"></b> tasks worth
								<b class="number" id="dollars"></b> available 
							</li>
							<li class="sfinfo">Secure and free!</li>
						</ul>

						<div id="registerbox">
							<div class="regheadmenu regbox"> <?php include_once"register_box.php"; ?> </div>
							<div class="regheadmenu connect">  
							<?php if(!$user){ ?>
									<a href="<?php echo $loginUrl; ?>"><img src="./style/img/loginicons/facebook.png" alt="Not Found"></a>
							<?php } ?>
							</div> 
							<p class='termsncond'> By signing up,or connecting through facebook you agree to our <a>Terms and Conditions</a> and <a>Cookie Use Policy.</a></p>
						</div>

				</div>
				<div id="mapholder" class="right">
						<div><select id="locationSelect" style="width:100%; visibility: hidden; display: none "></select></div> 
						<div id="map" ></div>
						<input id="jobPref" name= "jobPref" type="hidden" value=<?php // echo $jobPref ?> />
						<input id="loggedIn" name= "loggedIn" type="hidden" value=<?php //echo $loggedIn ?> />   
				</div> 
			</div>
		</div>
		<div id="footer">
			<p><strong>@ 2013 Copyrights by Caarya. </strong>
						 All rights reserved.</p>
		</div>
	</body> 
</html>
<?php// include 'footer.php' ?>