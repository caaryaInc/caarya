<div id='jobformcontainer'>
	<a id="close-panel" class="postclose right" href="#" style="display:none;">X</a>
	<form id='jobform' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>

		<div class="leftpanel">
			<input type='hidden' name='postjobsubmitted' id='postjobsubmitted' value='1'/>
			<input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>'  style="display:none;"/> 

			<div class="jobfield" style="margin-top: 55px">
				<label for='description' >Title </label>
				<input class="title"  type='text' name='title' id='title' value='' maxlength="50" /><br/>
				<p id="error_title" class='errorjform' style="color:red; font-size:12;"></p>
			</div>
							
			<div class="jobfield">
				<label for='type' >Type </label>
				<select id="jobType" name="jobType">
						<option value="household">Household</option>
						<option value="online">Online</option>
						<option value="travel">Travel</option>
						<option value="fixing">Fixing</option>
						<option value="auto">Auto</option>
						<option value="nursing">Nursing</option>
						<option value="other">Other</option>
				</select>
				<div id="typearrow" class="arrow"> </div> 
			</div>

			<div class="jobfield">
				<label for='name' >Pay </label>
				<input type='text' name='jobAmt' id='jobAmt' value='' maxlength="50" /><br/>
				<p id="error_jobAmt" class='errorjform' style="color:red; font-size:12;"></p>
			</div>

			<div class="jobfield">
				<label for='name' >Due </label>
				<input type='text' name='jobDue' id='jobDue' value='' maxlength="50" /><br/>
				<p id="error_jobDue" class='errorjform' style="color:red; font-size:12;"></p>
			</div>

			<div class="jobfield">
				<label for='name' >Duration </label>
				<input type='text' name='jobDuration' id='jobDuration' value='' maxlength="50" /><br/>
				<p id="error_jobDuration" class='errorjform' style="color:red; font-size:12;"></p>
			</div>

			<div class="jobfield">
				<label for='description' >Description </label>
				<textarea  name='jobDesc' class="textarea" id='jobDesc' value='' cols="70" rows="50"></textarea><br/>
				<p id="error_jobDesc" class='errorjform' style="color:red; font-size:12;"></p>
			</div>

		<!--	<div class="terms">
				<input class="termscb" type="checkbox" id="accept">
				<label  class="termslabel" for="terms" style="color:black;">Terms and Conditions</label>
			</div>  -->

		</div>
				
		<div class="middlepanel">
		</div>

		<div class="rightpanel">
			<div class="caption"> Post A Job </div>

			<div class="jobfield" style="margin-top: 20px;">
				<input  class="address" type='text' id='jobAddress' name= 'jobAddress' value='Where is the job at?'/>   <br/>
				<p id="error_jobAddress" class='errorjform' style="color:red; font-size:12;"></p>
			</div>	

			<input id="lat" name="lat" type="hidden"> 
			<input id="lng" name="lng" type="hidden"> 

			<div id="maptwo"></div> 
			<!-- <div id="gmaplegend">You can drag and drop the marker to the correct location</div> -->
			<input class='postjobbtn' type='submit' name='Submit' value='Post this Job' />
		</div>

	</form>
</div>

<!-- Send the data in the database -->

<?php
if (!isset($_SESSION['rand_hash'])){$_SESSION['rand_hash']="";}

//if the post array is a duplicate, nullify the submission
if (isset($_POST) && md5(serialize($_POST)) == $_SESSION['rand_hash']){ 
   unset($_POST);
} 
else { 
//otherwise, if this is new data, update the stored hash
   $_SESSION['rand_hash'] = md5(serialize($_POST));
}

if(isset($_POST['postjobsubmitted']))
{
	$jobAddress= sanitize($_POST['jobAddress']);
	$jobDescription= sanitize($_POST['jobDesc']);
	$jobAmount= sanitize($_POST['jobAmt']);
	$jobType= sanitize($_POST['jobType']);
	$jobDue = sanitize($_POST['jobDue']);
	$jobDuration = sanitize($_POST['jobDuration']);
	$title= sanitize($_POST['title']);
	$lat= sanitize($_POST['lat']);
	$lng= sanitize($_POST['lng']);
	//echo $jobType;
		
	//Write query to add employer id depending on employer email 	
	
	if($fgmembersite->CheckLogin())
		$email = $fgmembersite->UserEmail();
	else if($user)
		$email = $user_info['email'];
	 
	$uidquery= "select userid from user where email='$email'";
	$result = mysql_query($uidquery);
	if (!$result) {
		die("Invalid query: " . mysql_error());
	} 
	$row = mysql_fetch_assoc($result);
	$uid= $row['userid']; 
		
	$query = "insert into jobs (employerID,jobType,jobName,jobAddress, jobDescription, jobAmt,lat,lng,dueDate,jobDuration,jobStatus) values ('$uid','$jobType',
																			'$title',
																			 '$jobAddress'
																			,'$jobDescription', 
																			'$jobAmount','$lat','$lng','$jobDue','$jobDuration','1')";
	$result = mysql_query($query);
	if (!$result) {
	  die("Invalid query: " . mysql_error());
	} 

} 

?> 