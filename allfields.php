<?php
	require_once("./include/fbaccess.php");
	require_once("./include/membersite_config.php");

//	$connection=mysql_connect ('localhost', 'caarya5', 'topgear123!');   
	$connection=mysql_connect (localhost, 'root', 'root');  
	if (!$connection) {
		die("Not connected : " . mysql_error());
	}

//	$db_selected = mysql_select_db('caarya5_db', $connection);  
	$db_selected = mysql_select_db('dailychores', $connection);  
	if (!$db_selected) {
		die ("Can\'t use db : " . mysql_error());
	}

	function gettime(){
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		return $time;
	}

	function sanitize($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	function getrandhash($digits){
		return rand(pow(10, $digits-1), pow(10, $digits)-1);
	}

	function update($field, $value,$email){
		$updatequery= "UPDATE user SET $field='$value' WHERE email='$email'";
		$result = mysql_query($updatequery);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 
	}

	function getuserid($email){
		$query = "select userid from user where email='$email'";
		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 
		$row = mysql_fetch_assoc($result);            
		$uid= $row['userid'];  
		return $uid;
	}

	function getuseremail($id){
		$query = "select email from user where userid='$id'";
		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 
		$row = mysql_fetch_assoc($result);            
		$email= $row['email'];  
		return $email;
	}
	
	function noofjobsposted($email){
		$id = getuserid($email);
		$query = "select COUNT(*) as nojp from jobs where employerid='$id'";
		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 
		$row = mysql_fetch_assoc($result); 
		return $row['nojp'];
	}

	function noofjobsapplied($email){
		$id = getuserid($email);
		$query = "select COUNT(*) as noja from joblog where prospectiveEmployeeID='$id'";
		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 
		$row = mysql_fetch_assoc($result); 
		return $row['noja'];
	}

	function noofjobscompleted($email){
		$id = getuserid($email);
		$query = "select COUNT(*) as nojc from jobs where employeeID='$id'";
		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 
		$row = mysql_fetch_assoc($result); 
		return $row['nojc'];
	}

	function profvalue($email){
		$noja = noofjobsapplied($email);
		$nojp = noofjobsposted($email);
		$nojc = noofjobscompleted($email);
		if($noja!=0)
			$percentcompleted = ($nojc/$noja)*100;
		else
			$percentcompleted = 0;
		$value = $nojp + $percentcompleted;
		return $value;
	}

	function profilecomplete($email){
		$complete_count = 0;
		$query = "select username, address, profileurllocation, jobpreference ,gender, dob from user where email='$email'";
		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 
		$row = mysql_fetch_assoc($result); 
		if($row['username']!=NULL || $row['username']!="") $complete_count++;
		if($row['address']!=NULL || $row['address']!="") $complete_count++;
		if($row['profileurllocation']!=NULL || $row['profileurllocation']!="") $complete_count++;
		if($row['jobpreference']!=NULL || $row['jobpreference']!="") $complete_count++;
		if($row['gender']!=NULL || $row['gender']!="") $complete_count++;
		if($row['dob']!=NULL || $row['dob']!="") $complete_count++;
		$percentcount = (@round($complete_count/6,2))*100;
		return $percentcount;
	}

	function profrating($email){
		$query = "select rate_counter, rate_val from user where email='$email'";
		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 
		$row = mysql_fetch_assoc($result); 
		$rateval = $row['rate_val'];
		$ratecount = $row['rate_counter'];
		if($ratecount==0)
			$rating = 0;
		else
			$rating = $rateval/$ratecount;
		return $rating*20;
	}

	function totalprofrating($email){
		$noja = noofjobsapplied($email);
		$nojc = noofjobscompleted($email);
		if($noja!=0)
			$percentcompleted = ($nojc/$noja)*100;
		else
			$percentcompleted = 0;
		$percentrating = profrating($email);
		$percentprofcomplete = profilecomplete($email);
		$totalrating = $percentcompleted+$percentrating+$percentprofcomplete;
		$rate = $totalrating/3;
		return $rate;
	}

	function stringencrypt($email){
		$hash1 = md5('sha1_rsa_key');
		$hash2 = md5('encodeurl_base64_key');
		$id = getuserid($email);
		$len = strlen($id);
		$str = $hash1.$id.$hash2.$len;
		return $str;
	}	

	function stringdecrypt($number){
		$len = strlen($number);
		$elen = $number[$len-1];
		$id = substr($number, 32 , $elen);
		$email = getuseremail($id);
		return $email;
	}


	// To check if user has logged in through fb or locall 
	if($fgmembersite->CheckLogin())          
		$email = $fgmembersite->UserEmail();  
	else if($user)
		$email = $user_info['email'];  

	if(isset($_GET['ft'])){
		/* Start session */
		if(!isset($_SESSION)){ session_start(); }
		$decodedemail =  $fgmembersite->encrypt_decrypt('decrypt', $_GET['email']);
		$_SESSION[$fgmembersite->GetLoginSessionVar()] = $decodedemail;
		$_SESSION['email_of_user'] = $decodedemail;
	}

	if(isset($_GET['sv'])){
		/* Start session */
		if(!isset($_SESSION)){ session_start(); }
		$decodedemail =  $fgmembersite->encrypt_decrypt('decrypt', $_GET['sv']);
		$_SESSION[$fgmembersite->GetLoginSessionVar()] = $decodedemail;
		$_SESSION['email_of_user'] = $decodedemail;
	}

	if(isset($_POST['accountsubmitted'])) {
		$newemail = $_POST['email'];
		$newname = $_POST['currname'];
		$newuname =  $_POST['uname'];
		$newpass = $_POST['pass'];
		$newpwdmd5 = md5($newpass);
		update('name',$newname,$email);
		update('username',$newuname,$email);
		update('password',$newpassmd5,$email);
		if($newemail!=$email)
			update('temp_email',$newemail,$email);
		echo "<script>window.top.location='editprofile.php'</script>";
	}

	if(isset($_POST['personalsubmitted'])) {
		$newaddress = $_POST['address'];
		$newphnno =  $_POST['phnno'];
		$newdob  =  $_POST['dob'];
		$newgender = $_POST['gender'];
		$newjobpref = $_POST['jobpref'];
		$fgmembersite->update('address',$newaddress,$email);
		$fgmembersite->update('phnno',$newphnno,$email);
		$fgmembersite->update('gender',$newdob,$email);
		$fgmembersite->update('dob',$newgender,$email);
		$fgmembersite->update('jobpreference',$newjobpref,$email);
		echo "<script>window.top.location='editprofile.php'</script>";
	}
	
	$userquery= "select * from user where email='$email'";   
	$result = mysql_query($userquery);
	if (!$result) {
	die("Invalid query: " . mysql_error());         
	} 
		
	$row = mysql_fetch_assoc($result);            
	$uid= $row['userid'];                         
	$name= $row['name'];                           
	$uname= $row['username']; 
	$address= $row['address']; 
	$jpref= $row['jobpreference'];
	$pic= $row['profileurllocation'];
	$phnno = $row['phnno'];
	$dob = $row['dob'];
	$gender = $row['gender'];
	$tempmail = $row['temp_email'];
	
	$nojp = noofjobsposted($email);
	$noja = noofjobsapplied($email);
	$nojc = noofjobscompleted($email);
	$value = profvalue($email);
	$count = profilecomplete($email);
?>		 