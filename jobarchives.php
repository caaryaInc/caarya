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
		<link rel="stylesheet" href="style/jobarchives.css"  media="screen, projection" type="text/css" /> 
		<?php include_once("menu_scripts.php"); ?>
		<?php include_once("analyticstracking.php"); ?>
		<script src="scripts/jobarchives.js"></script> 
		<?php include_once("global_head_footer.php"); ?>
		<?php $headerfinish = gettime();?>
	</head>
	<body>
	<?php 	include_once("menubar.php"); ?>
	<div id='archivesoc'>
		<div id='archiveslp'>
			<p class='jheader'> Jobs Posted </p>
			<div id='archivespos' class='scrollbar simple'>
				<?php 
				if($pic=="")
					$pospic = "profilepictures/unknown2.png";
				else
					$pospic = $pic;
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
					$phpposdate = strtotime($posdueDate);
					$formatedposDate = date("F j, Y", $phpposdate);
				?>
				<div id="jobdiv<?php echo $posjid ?>" class="jobdiv"  onclick="displaycand(<?php echo $posjid?>);"> 
					<div class='jobs'>
						<div class='jobtype'>
							<img src=<?php echo $posimg ?> alt='Not Found' height='30px' width='30px'>
						</div>
						<div class='jdetails'>
							<div class='jname'><?php echo $posjobName?></div>
							<div class='jadd'><?php echo $posjobAddress?></div>
						</div>
						<div class='jextras'>
							<div class='jamt'>$<?php echo $posjobAmt?></div>
							<div class='time'><img src='style/img/clock_icon.jpg' alt='Not Found'><p class='timeval'>4hrs</p></div>
							<div class='jdue'><?php echo $formatedposDate ?></div>
						</div>
						<div id='jobdesc<?php echo $posjid ?>' class = 'jdesc'> 
						<!--	<img  src='style/img/timeicon.png' alt='Not Found' height='10px' width='10px'>-->
						<!--	<p> Description : </p> -->
							<div class="desc"> <?php echo $posjobdesc ?> </div>
						</div>
					</div>
				</div>
		
				<!-- for middle top -->
				<div id='appjholder<?php echo $posjid ?>' class='jholder'>
					<div class='jhead'><p> Job Description </p></div>
					<div class='picrow'> 
						<div class='jbpropic left propicshadow'><img src='<?php echo $pospic ?>' alt='Not Found'></div>
						<div class='jbname'><?php echo $posjobName ?></div> 
					</div>
					<div class='jbadd'><img class = 'addicon' src='style/img/maps_pin.png' alt='Not Found'><?php echo $posjobAddress ?></div>
					<div class='extras'>
						<div class='jbtype left'><img src=<?php echo $posimg ?> alt='Not Found' height='30px' width='30px'></div>
						<div class='jbamt'>$<?php echo $posjobAmt ?></div>	
						<div class='jbdatetime'>
							<div class='jbtime '><img src='style/img/clock_icon.jpg' alt='Not Found'><p class='timeval'>4hrs</p></div>
							<div class='jbdate'> Due: <?php echo $formatedposDate ?> </div>
						</div>
					</div>
					<div class='jbdesc'><p class='label'>Description:</p><?php echo $posjobdesc ?></div> 
				 </div>

				<?php 
				// For each job retrieve information about prospective employers
				$jobsemployerquery = "select * from joblog where jobID='$posjid'";
				$jeqresult = mysql_query($jobsemployerquery);
				if (!$jeqresult) {
				die("Invalid query: " . mysql_error());
				} ?>
				<div id="candidatesfor<?php echo $posjid;?>" class='candfor scrollbar simple'>											

					<?php while($jeqrow = @mysql_fetch_assoc($jeqresult)){
					$jlid= $jeqrow['joblogID']; 
					$peid=$jeqrow['prospectiveEmployeeID'];
					$pestatus= $jeqrow['prospectiveEmployeeStatus'];
					// For each prospectiveEmployeeID return the profile picture 	
					$pedetails=  "Select name,email, profileurllocation from user where userid= '$peid'  ";
					$peresult = mysql_query($pedetails);
					if (!$peresult) {
						die("Invalid query: " . mysql_error());
					} 
					$perow = mysql_fetch_assoc($peresult); 
					$propic= $perow['profileurllocation'];
					$pemail =  $perow['email'];
					$pename =  $perow['name'];
					$alt = "profilepictures/unknown2.png";
					if($propic=="")
						$candpic= $alt;
					else  $candpic = $propic;
					?> 	

					<div id="candidate<?php echo $peid;?>" class='canddiv'>
						<div id='candidates' style="border-bottom: 1px solid #c7c7c7; float: left;">
						<!--	<div class='picholder'>-->
							<div class='propic left'>
								<img src=<?php echo $candpic;?> alt='Not Found'>
							</div>
							<!--	<ul class='star-rating' style='background-size: 11px; height: 11px; width: 55px; '><li class="current-rating" id="rate<?php echo stringencrypt($pemail) ?>"></li></ul> -->
						<!--	</div> -->
							<div class='jname'>
								<?php echo $posjid;?>
							</div>
							<input type="submit" id="assignbutton" value="assign">
						</div>
					</div>	

				<?php } ?>
				</div>
				<?php } ?>

			</div>
			<p class='jheader'> Jobs Applied For </p>
			<div id='archivesapp' class='scrollbar simple'>
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
							$employerinfoquery = "select * from user where userid='$eid'";
							$eiqresult = mysql_query($employerinfoquery);
							if (!$eiqresult) {
								die("Invalid query: " . mysql_error());
							} 
							while($eiqrow = @mysql_fetch_assoc($eiqresult))
							{ 
									$ename= $eiqrow['name'];
									$epic=$eiqrow['profileurllocation'];
									$eemail = $eiqrow['email'];
							}
							if($epic=="")
								$apppic = "profilepictures/unknown2.png";
							else
								$apppic = $epic;

							$phpdate = strtotime($appdueDate);
							$formatedDate = date("F j, Y", $phpdate);
				?>

				<div id="job<?php echo $ajbid ?>" class="appliedjobdiv" onclick="displayapp(<?php echo $ajblgid; ?>);"> 
					<div class='jobs'>
						<div class='jobtype'>
							<img src=<?php echo $appimg ?> alt='Not Found' height='30px' width='30px'>
						</div>
						<div class='jdetails'>
							<div class='jname'><?php echo $appjobName?></div>
							<div class='jadd'><?php echo $appjobAddress?></div>
						</div>
						<div class='jextras'>
							<div class='jamt'>$<?php echo $appjobAmt?></div>
							<div class='time'><img src='style/img/clock_icon.jpg' alt='Not Found'><p class='timeval'>4hrs</p></div>
							<div class='jdue'><?php echo $formatedDate ?></div>
						</div>
						<div id='jobdesc<?php echo $ajid ?>' class = 'jdesc'> 
						<!--	<img  src='style/img/timeicon.png' alt='Not Found' height='10px' width='10px'>-->
						<!--	<p> Description : </p> -->
							<div class="desc"> <?php echo $appjobdesc ?> </div>
						</div>
						<ul id='eifor<?php echo $ajblgid ?>' class = 'einfolist' style="display:none;">	
							<li id='perinfo'>
								<div class='label'><p class='infocaption'>Employer Information</p> </div>
								<div class='label'><p class='labeltitle'> address: </p> <p class='labelval'> Greater Cincinnati Airport (CVG), 2939 Terminal Drive, Hebron, KY 41048, USA </p> </div>
								<div class='label'><p class='labeltitle'> phone no: </p> <p class='labelval'> not made public </p> </div>
							</li>
							<li id='proinfo'>
								<div class='label'><p class='labeltitle'> jobs posted: </p> <p class='labelval'> <?php echo noofjobsposted($eemail) ?> </p> </div>
								<div class='label'><p class='labeltitle'> jobs applied: </p> <p class='labelval'> <?php echo noofjobsapplied($eemail) ?>  </p> </div>
								<div class='label'><p class='labeltitle opv'> Overall Professional Value  </p> <p class='labelval opvval' style='width:50px;'> <?php echo profvalue($eemail) ?>/100</p> </div>
							</li>
						</ul> 
					</div>
				</div>

					<!-- for middle top -->
				<div id='appforholder<?php echo $ajblgid ?>' class='jholder'>
					<div class='jhead'><p> Job Description </p></div>
					<div class='picrow'> 
						<div class='jbpropic left propicshadow'><img src='<?php echo $apppic?>' alt='Not Found'></div>
						<div class='jbname'><?php echo $appjobName ?></div>
						<div class = 'empname'><?php echo $ename ?></div>
					</div>
					<div class='jbadd'><img class = 'addicon' src='style/img/maps_pin.png' alt='Not Found'>Greater Cincinnati Airport (CVG), 2939 Terminal Drive, Hebron, KY 41048, USA</div>
					<div class='extras'>
						<div class='jbtype left'><img src=<?php echo $posimg ?> alt='Not Found' height='30px' width='30px'></div>
						<div class='jbamt'>$5400</div>	
						<div class='jbdatetime'>
							<div class='jbtime '><img src='style/img/clock_icon.jpg' alt='Not Found'><p class='timeval'>4hrs</p></div>
							<div class='jbdate'> Due: <?php echo $formatedDate ?> </div>
						</div>
					</div>
					<div class='jbdesc'><p class='label'>Description:</p><?php echo $appjobdesc ?></div>  
				 </div>

				<?php } } ?>

			</div>
		</div>
		<div id='archivesrp'>
			<div id='tophalf'>
			</div>
			<div id='bottomhalf'>
			</div>
		</div>
		<script type="text/javascript" src="scripts/jquery.scroll.min.js"></script>
		<script type="text/javascript">
			$('.scrollbar').scrollbar();
		</script>
	</div>
	<?php // include 'footer.php' ?>
	</body>
</html>