<div id="jobspostedcontainer">

	<div id='noposted' class='noappliedjobs'> 
		<img src='style/img/tasksicon.png' alt='not found'/>
		<p> You havent posted  any jobs yet. <br/><span> New to Caarya? Click here for more info about how to post jobs</span> </p>
	</div>

	<div id='noapplicants' class='noappliedjobs' style='display:none'> 
		<img src='style/img/tasksicon.png' alt='not found'/>
		<p> Seems no one has applied for your task. <br/><span> Try editing your task?</span> <br/>EDIT TASK</p>
	</div>

	<div id="jposted" class="colleft scrollbar simple">
	<?php 
		$userjobsquery = "select * from jobs where employerID='$uid'";
		$result = mysql_query($userjobsquery);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 
		$i=1; 
		while($row = @mysql_fetch_assoc($result)){ 
			$jid= $row['jobID']; 
			$jobName= $row['jobName'];
			$jobAddress= $row['jobAddress'];
			$dueDate = $row['dueDate'];
			$jobAmt = $row['jobAmt'];
			$jobdesc = $row['jobDescription'];
			$jobType = $row['jobType'];
					
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
				$img = 'map-icon/others2.png';
		?>
				
			<div id="jobdiv<?php echo $jid ?>" class="jobdiv" onclick="showapplicants(<?php echo $jid?>);"> 
				<div class='jobs'>
					<div class='jobtype'>
						<img src= <?php echo $img ?>  height='30px' width='30px'>
					</div>
					<div class='jdetails'>
						<div class='jname'><?php echo $jobName?></div>
						<div class='jadd'><?php echo $jobAddress?></div>
					<!--	<div class='jdue'><?php// echo $dueDate?></div> -->
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
				</div>
			</div>
			
		<?php 
			// For each job retrieve information about prospective employers
				$jobsemployerquery = "select * from joblog where jobID='$jid'";
				$jeqresult = mysql_query($jobsemployerquery);
				if (!$jeqresult) {
				die("Invalid query: " . mysql_error());
				} ?>
				<div id="candidatesfor<?php echo $jid;?>" class="candidatesfor scrollbar simple" style="width: 380px;"> 											
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
						$pic= $alt;
					else  $pic = $propic;
			?> 
					<div id="candidate<?php echo $peid;?>" class='canddiv' onclick="showinfodiv(<?php echo $peid; ?> , <?php echo $jlid; ?>)">
						<div id='candidates' style="border-bottom: 1px solid #c7c7c7;">
						<!--	<div class='picholder'>-->
								<div class='propic left'>
									<img src=<?php echo $pic;?> alt='Not Found'>
								</div>
							<!--	<ul class='star-rating' style='background-size: 11px; height: 11px; width: 55px; '><li class="current-rating" id="rate<?php echo stringencrypt($pemail) ?>"></li></ul> -->
						<!--	</div> -->
							<div class='jname'>
								<?php echo $pename;?>
							</div>
							<input type="submit" id="assignbutton" value="assign">
						</div>
					</div>	
			<?php } ?>
			</div>
			<?php } ?>
			
	</div>

	<div id="appinfo">
			<img src='style/img/users.png' alt='Not Found'><br/>
			<p>click on a job to know applicants for it</p>
	</div> 

	<div class="colmiddle" style="display:none;">
		<div id="aheader"> <p>Applicants</p> </div> 
		<div id="applicants" style="display:none"></div>
	</div> 

	<div id="profinfo" class="colright" style="display:none;">
		<input id="hiddenid" hidden="hidden">
		<input id="postedscrollhght" hidden="hidden" value="0"/>
		<ul class="proftopbar">
			<li id="msgicon"> <img src='style/img/messageicon.png' alt='Not Found' height='30px' width='30px'> </li>
			<li id="proficon"> <img src='style/img/message.png' alt='Not Found' height='30px' width='30px'> </li>
			<li id="testiicon"> <img src='style/img/testimonial2.png' alt='Not Found' height='30px' width='30px'> </li>
		</ul>
		<div id="msgsexchanged">
				<div id="showmsg" class='scrollbar simple'></div>
				<form id="msgform" action="msgform.php" method="post">
					<textarea  id="msgarea"  name="msgarea" rows="4" cols="50"></textarea><br/>
					<a class='sendmsgbtn' onclick="addmsg()" style='margin-top:-8px;'>Send Msg</a>
				</form>
		</div> 
	</div>

</div>

<!-- Scripts for scrollbar Wont work if moved up  --> 

<!--[if IE]>
  <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
<![endif]-->
<script type="text/javascript" src="scripts/jquery.scroll.min.js"></script>
<script type="text/javascript">
	$('.scrollbar').scrollbar();
</script>