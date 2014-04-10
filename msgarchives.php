<?php
include_once("checksession.php");
function getImage($jobType){
	if($jobType == 'household')		
		$img = 'map-icon/household2.png';
	else if($jobType == 'fixing')	
		$img = 'map-icon/fixing2.png';
	else if($jobType == 'auto')	
		$img = 'map-icon/auto2.png';
	else if($jobType == 'travel')	
		$img = 'map-icon/travel2.png';
	else if($jobType == 'online')	
		$img = 'map-icon/online2.png';
	else if($jobType == 'other')	
		$img = 'map-icon/others2.png';
	return $img;
}
?>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title> Welcome to Caarya - Home </title>
		<?php $headerstart = gettime(); ?>
		<?php include_once("menu_styles.php"); ?>
		<link rel="stylesheet" href="style/msgarchives.css"  media="screen, projection" type="text/css" /> 
		<link rel="stylesheet" href="style/jobsposted.css"  media="screen, projection" type="text/css" /> 
		<link rel="stylesheet" href="style/appliedjobs.css"  media="screen, projection" type="text/css" /> 

		<?php include_once("menu_scripts.php"); ?>
		<?php include_once("analyticstracking.php"); ?>
		<?php include_once("global_head_footer.php"); ?>
		<?php $headerfinish = gettime();?>
	</head>
	<body>
	<?php 	include_once("menubar.php"); ?>
	<div id='archivesoc'>
		<div id='archiveslp'>
			<div id='archivespos'>
				<p> Jobs Posted </p>
				<?php 
				$postedjobsquery = "select * from jobs where employerID='$uid'";
				$postedresult = mysql_query($postedjobsquery);
				if (!$postedresult) {
					die("Invalid query: " . mysql_error());
				} 
				$i=1; 
				while($postedrow = @mysql_fetch_assoc($postedresult)){ 
					$posjid= $postedrow['jobID']; 
					$posjobName= $postedrow['jobName'];
					$posjobAddress= $postedrow['jobAddress'];
					$posdueDate = $postedrow['dueDate'];
					$posjobAmt = $postedrow['jobAmt'];
					$posjobdesc = $postedrow['jobDescription'];
					$posjobType = $postedrow['jobType'];
					$posimg = getImage($posjobType);
				?>
				<div id="jobdiv<?php echo $posjid ?>" class="jobdiv"> 
					<div class='jobs'>
						<div class='jobtype'>
							<img src=<?php echo $posimg ?> alt='Not Found' height='30px' width='30px'>
						</div>
						<div class='jdetails'>
							<div class='jname'><?php echo $posjobName?></div>
							<div class='jadd'><?php echo $posjobAddress?></div>
						<!--	<div class='jdue'><?php// echo $dueDate?></div> -->
						</div>
						<div class='jextras'>
							<div class='jamt'>$<?php echo $posjobAmt?></div>
							<div class='time'><img src='style/img/clock_icon.jpg' alt='Not Found'><p class='timeval'>4hrs</p></div>
						</div>
						<div id='jobdesc<?php echo $posjid ?>' class = 'jdesc'> 
						<!--	<img  src='style/img/timeicon.png' alt='Not Found' height='10px' width='10px'>-->
						<!--	<p> Description : </p> -->
							<div class="desc"> <?php echo $posjobdesc ?> </div>
						</div>
					</div>
				</div>
				<?php } ?>

			</div>
			<div id='archivesapp'>
				<p> Jobs Applied For </p>
				<?php
					// Select all jobIDs where prospectiveEmployeeID = userID 
					$ajquery = "select * from joblog where prospectiveEmployeeID='$uid'";
					$appliedresult = mysql_query($ajquery);
					if (!$appliedresult) {
						die("Invalid query: " . mysql_error());
					} 
					while($appliedrow = @mysql_fetch_assoc($appliedresult))
					{
						$ajblgid = $appliedrow['joblogID'];
						$ajbid= $appliedrow['jobID']; 	
						// for each jobID retireve job information
						$ajobinfoquery = "select * from jobs where jobID='$ajbid'";
						$ajiqresult = mysql_query($ajobinfoquery);
						if (!$ajiqresult) {
							die("Invalid query: " . mysql_error());
						} 
						while($ajiqrow = @mysql_fetch_assoc($ajiqresult))
						{ 
							$appjobName= $ajiqrow['jobName'];
							$appjobType=$ajiqrow['jobType'];
							$appjobdesc= $ajiqrow['jobDescription'];
							$appjobAddress=$ajiqrow['jobAddress'];
							$appjobAmt= $ajiqrow['jobAmt']; 
							$appdueDate = $ajiqrow['dueDate'];
							$appeid= $ajiqrow['employerID'];
							$appimg = getImage($appjobType);
				?>

				<div id="job<?php echo $ajbid ?>" class="appliedjobdiv"> 
					<div class='jobs'>
						<div class='jobtype'>
							<img src=<?php echo $appimg ?> alt='Not Found' height='30px' width='30px'>
						</div>
						<div class='jdetails'>
							<div class='jname'><?php echo $appjobName?></div>
							<div class='jadd'><?php echo $appjobAddress?></div>
							<div class='jdue'><?php echo $appdueDate?></div>
						</div>
						<div class='jextras'>
							<div class='jamt'>$<?php echo $appjobAmt?></div>
							<div class='time'><img src='style/img/clock_icon.jpg' alt='Not Found'><p class='timeval'>4hrs</p></div>
						</div>
						<div id='jobdesc<?php echo $ajid ?>' class = 'jdesc'> 
						<!--	<img  src='style/img/timeicon.png' alt='Not Found' height='10px' width='10px'>-->
						<!--	<p> Description : </p> -->
							<div class="desc"> <?php echo $appjobdesc ?> </div>
						</div>
					</div>
				</div>

				<?php } } ?>

			</div>
		</div>
		<div id='archivesrp'>
		</div>
	</div>
	</body>