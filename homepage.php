<?php
include_once("checksession.php");
$homestart = gettime();
if(isset($_POST['applyjobsubmitted']))
{
	$jobID= $_POST['jobId'];
	$fmsg = $_POST['msgcnt'];
		
	//Query to check if employee allready applied for job 
	$checkjobexistsquery= "Select joblogID from joblog where jobID='$jobID' and prospectiveEmployeeID='$uid' ";
	$result = mysql_query($checkjobexistsquery);
	if (!$result) 
		die("Invalid query: " . mysql_error());
	if(mysql_num_rows($result)<=0)
	{
		//Query to insert into joblog and return joblogID
		$insertquery="insert into joblog (jobID, prospectiveEmployeeID, prospectiveEmployeeStatus ) values ('$jobID', '$uid', '0')";
		$result = mysql_query($insertquery);
		if (!$result) {
		die("Invalid query: " . mysql_error());
		} 
		$joblogId = mysql_insert_id();
		$fmsgstr = "~#1^b^".$fmsg."^1";

		//Query to insert into msgs
		$addappliedmsg = "INSERT INTO messages(joblogID,msgstr,msgcount) VALUES ('$joblogId','$fmsgstr','1')";  
		$appliedresult = mysql_query($addappliedmsg);
		if (!$appliedresult) {
			die("Invalid query: " . mysql_error());
		} 
		
		//Query to update job status 
		$updatequery= "UPDATE jobs SET jobStatus='2' where jobID='$jobID' ";
		$result = mysql_query($updatequery);
		if (!$result) {
		die("Invalid query: " . mysql_error());
		} 

		//Query to retrieve employer for the job
		$getemployerquery = "select employerID from jobs where jobID='$jobID'";
		$getemployerresult = mysql_query($getemployerquery);
		if (!$getemployerresult) {
		die("Invalid query: " . mysql_error());
		} 
		$row = mysql_fetch_assoc($getemployerresult);            
		$employerid= $row['employerID'];  

		//Query to send to notifications table
		$insertnotification="insert into notifications (notificationFor, notificationType, referingField,seen ) values ('$employerid','applicant', '$joblogId', '0')";
		$result = mysql_query($insertnotification);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		}  
	}
	else
		echo "<script> alert('You have allready applied for this job') </script>";
} 
?>
	
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title> Welcome to Caarya - Home </title>
		<?php $headerstart = gettime(); ?>
		<?php include_once("menu_styles.php"); ?>
		<?php include_once("home_styles.php"); ?>

		<?php include_once("menu_scripts.php"); ?>
		<?php include_once("home_scripts.php"); ?>
		<?php include_once("analyticstracking.php"); ?>
		<?php include_once("global_head_footer.php"); ?>
		<?php $headerfinish = gettime();?>
	</head>

	<body  onload="load()" onunload="GUnload()">
	<div id="container" class='fullcontainer'>
		<div id="pagewrap" class="clearfix">
			<?php 	include_once("menubar.php");
					include_once("searchform.php");  ?>
			
			<?php if(!$fgmembersite->checkConfirmCode($email)) {?>
			<div id="confirmmsg">
				An activation link has allready been sent to <?php echo $email; ?>. Please click on the link to confirm registration. Click <a id='confirmlink' href='#'> here </a> to resend activation link
			</div>	   
			<?php } ?>	
		  
			<div id="toplayer">
	
				<div id="mapholder">
					<div><select id="locationSelect" style="width:100%; visibility: hidden; display: none "></select></div>
					<div id="map" ></div>
					<input id="jobPref" name= "jobPref" type="hidden" value=<?php echo $jobPref ?> />
					<input id="loggedIn" name= "loggedIn" type="hidden" value=<?php echo $email ?> />
				</div>   
				
				<div id="applyjobholder">
					<div id="applyforjobs">  </div>
				</div> 
			
			</div>
						
			<div id="bottomlayer">
				
				<div class="tabs">
				  <ul class="tabNavigation shadoweffect">
						<li><a href="#appliedjobs">Applied Jobs</a></li>
						<li><a href="#postedjobs">Posted Jobs</a></li>
				  </ul>

				  <!-- tab containers -->
				  <div id="appliedjobs" class="tabcontent">
						<?php include_once("appliedjobs.php"); ?>
				  </div>
				  <div id="postedjobs" class="tabcontent">
						<?php include_once("jobsposted.php"); ?>
				  </div> 
				</div>  
			
			</div>  
		
		</div> 
	</div>

	<?php// include 'footer.php' ?>

	</body> 
</html>

<?php 
$homefinish = gettime();
$header_total_time = round(($headerfinish - $headerstart), 4);
$hlt = round(($fht - $sht), 4);

$home_total_time = round(($homefinish - $homestart), 4);
//echo 'Headers generated in '.$header_total_time.' seconds.<br/>';
//echo 'Home Headers generated in '.$hlt.' seconds.<br/>';


//echo 'Applied Jobs generated in '.$applied_total_time.' seconds.<br/>';
//echo 'HomePage generated in '.$home_total_time.' seconds.';
?>
