<?php $appliedstart = gettime(); ?>
<div id="appliedjobscontainer">
	<div id='noapplied' class='noappliedjobs'> 
		<img src='style/img/tasksicon.png' alt='not found'/>
		<p> You havent applied for any jobs yet. <br/><span> New to Caarya? Click here for more info about how to apply for jobs</span> </p>
	</div>

	<div id="japplied" class="leftcol scrollbar simple">
		<?php
			// Select all jobIDs where prospectiveEmployeeID= userID 
			$jobsappliedquery = "select * from joblog where prospectiveEmployeeID='$uid'";
			$result = mysql_query($jobsappliedquery);
			if (!$result) {
				die("Invalid query: " . mysql_error());
			} 
			while($row = @mysql_fetch_assoc($result))
			{
				$jblgid = $row['joblogID'];
				$jbid= $row['jobID']; 	
				// for each jobID retireve job information
				$jobinfoquery = "select * from jobs where jobID='$jbid'";
				$jiqresult = mysql_query($jobinfoquery);
				if (!$jiqresult) {
					die("Invalid query: " . mysql_error());
				} 
				while($jiqrow = @mysql_fetch_assoc($jiqresult))
				{ 
					$jobName= $jiqrow['jobName'];
					$jobType=$jiqrow['jobType'];
					$jobdesc= $jiqrow['jobDescription'];
					$jobAddress=$jiqrow['jobAddress'];
					$jobAmt= $jiqrow['jobAmt']; 
					$eid= $jiqrow['employerID'];
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
					else if($jobType == 'nursing')	
						$img = 'map-icon/nursing2.png';
					else if($jobType == 'other')	
						$img = 'map-icon/other2.png';
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
		?>
		<div id="job<?php echo $jbid ?>" class="appliedjobdiv" onclick="displaymsgs(<?php echo $jbid; ?>, <?php echo $jblgid; ?>);"> 
					
			<div class='jobs'>
				<div class='jobtype'>
					<img src=<?php echo $img ?> alt='Not Found' height='30px' width='30px'>
				</div>
				<div class='jdetails'>
					<div class='jname'><?php echo $jobName?></div>
					<div class='jadd'><?php echo $jobAddress?></div>
					<div class='jdue'><?php echo $dueDate?></div>
				</div>
				<div class='jextras'>
					<div class='jamt'>$<?php echo $jobAmt?></div>
					<div class='time'><img src='style/img/clock_icon.jpg' alt='Not Found'><p class='timeval'>4hrs</p></div>
				</div>
				<div id='jobdesc<?php echo $jid ?>' class = 'jdesc'> 
				<!--	<img  src='style/img/timeicon.png' alt='Not Found' height='10px' width='10px'>-->
				<!--	<p> Description : </p> -->
					<div class="desc"> <?php echo $jobdesc ?> </div>
				</div>
				<ul id='eifor<?php echo $jblgid ?>' class = 'einfolist' style="display:none;">	
					<li id='namenpic'> 
						<div id='empic'>
						<img src="<?php $alt = "profilepictures/unknown2.png";
															if($epic=="")
																echo $alt;
															else  echo $epic;
						?>"  alt='No pic available' /></div>
						<div class='en'><?php echo $ename ?><br/><p style='font-size: 10px;'> View Profile </p> </div>
					</li>
					<li id='perinfo'>
						<div class='label'><p class='infocaption'>Personal Information </p> </div>
						<div class='label'><p class='labeltitle'> address: </p> <p class='labelval'> Greater Cincinnati Airport (CVG), 2939 Terminal Drive, Hebron, KY 41048, USA </p> </div>
						<div class='label'><p class='labeltitle'> phone no: </p> <p class='labelval'> not made public </p> </div>
					</li>
					<li id='proinfo'>
						<div class='label'><p class='infocaption'>Professional Information </p> </div>
						<div class='label'><p class='labeltitle'> jobs posted: </p> <p class='labelval'> <?php echo noofjobsposted($eemail) ?> </p> </div>
						<div class='label'><p class='labeltitle'> jobs applied: </p> <p class='labelval'> <?php echo noofjobsapplied($eemail) ?>  </p> </div>
						<div class='label'><p class='labeltitle opv'> Overall Professional Value  </p> <p class='labelval opvval' style='width:50px;'> <?php echo profvalue($eemail) ?>/100</p> </div>
					</li>
				</ul>
			</div>

		</div>	

		<?php 	
		}	} ?>
	</div>

	<div id="empinfo">
			<img src='style/img/users.png' alt='Not Found'><br/>
			<p>click on a job to know employer information</p>
	</div>

	<div class='middlecol' style='display:none'>
		<div id="einfo" style="display:none;">
			<div id="eheader"> <p>Employer Information </p> </div> 
			<div id="edetails"></div>
		</div>
	</div>

	<div class="rightcol" style='display:none;'>
		<div id="eheader"> <p>Messages </p> </div> 
		<div class='nomsgdiv' style="display:none;">
			<img src='style/img/message.png' alt='Not Found' height='40px' width='40px'><br/>
			<p>No messages have been exchanged</p>
		</div> 
		<input id="hiddenjblgid" hidden="hidden"/>
		<input id="scrollhght" hidden="hidden" value="0"/>
		<div id="msgsexchanged" style='display:none'>
				<div id ='scrollappbottom' style='posiiton: absolute; display: none;'> Scroll to the bottom to view new msgs </div> 
				<div id="showapplicantmsg"></div>
				<form id="applicantmsgform" action="#" method="post">
					<textarea  id="applicantmsgarea" name="msgarea" rows="4" cols="50"></textarea>
					<a class='sendmsgbtn' onclick="addapplicantmsg()">Send Msg</a>
				</form>  
		</div>  
	</div> 

	<!-- <div id="msginfo">
		<img class='msgicon' src='style/img/message.png' alt='Not Found'><br/>
		Please click on a job to see employer information and messages exchanged
	 </div>  -->
	<?php 
		$appliedfinish = gettime();
		$applied_total_time = round(($appliedfinish - $appliedstart), 4);
	?>

</div>
