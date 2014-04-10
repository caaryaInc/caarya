<?php 
require_once("allfields.php");

function getuserpic($uid){
	$userquery= "select * from user where userid='$uid'";   
	$result = mysql_query($userquery);
	if (!$result) {
	die("Invalid query: " . mysql_error());         
	} 
		
	$row = mysql_fetch_assoc($result);            
	$pic= $row['profileurllocation'];
	return $pic;
}

function getEmployeePic($jlid){
	$query = "select * from joblog where joblogID='$jlid'";
    $result = mysql_query($query);
		if (!$result) 
			die("Invalid query: " . mysql_error());
		while ($row = @mysql_fetch_assoc($result)){ 
			$peid= $row['prospectiveEmployeeID'];
			$pic = getuserpic($peid);
		}
	return $pic;
}

function getEmployerPic($jlid){
	$query = "select * from joblog where joblogID='$jlid'";
    $result = mysql_query($query);
		if (!$result) 
			die("Invalid query: " . mysql_error());
		while ($row = @mysql_fetch_assoc($result)){ 
			$jid= $row['jobID'];
			$jbquery = "select employerID from jobs where jobID='$jid'";
			$jbresult = mysql_query($jbquery);
			if (!$jbresult) 
				die("Invalid query: " . mysql_error());
			while ($jbrow = @mysql_fetch_assoc($jbresult)){ 
				$empid= $jbrow['employerID'];
				$pic = getuserpic($empid);
			}
		}
	return $pic;
}

function employer($id,$uid){
	$query = "select * from joblog where joblogID='$id'";
    $result = mysql_query($query);
		if (!$result) 
			die("Invalid query: " . mysql_error());
		 while ($row = @mysql_fetch_assoc($result)){ 
			$peid= $row['prospectiveEmployeeID'];
			if($uid==$peid)
				return false;		//user is employee
			else
				return true;		//user is employer 
		} 
}


function parseAndDisplayMsg($del1,$del2,$str,$employeepic,$employerpic){
		$msgstr =  explode( $del1, $str );
		foreach($msgstr as $msg){
			$parsedmsg = explode($del2, $msg);
			$count = $parsedmsg[0];
			$sender = $parsedmsg[1];
			$msgcontent = $parsedmsg[2];
			$seen = $parsed[3];
			$alt = 'profilepictures/unknown2.png';
			if($sender=='a'){
				if($employerpic=="")
					$pic = $alt;
				else
					$pic = $employerpic;
				echo "<div id=".$count." class='employer msg'>".
						"<img class='empic' src='".$pic."'alt='No pic available' />"."<p>".$msgcontent."</p>".
					"</div>";
			}
			else if($sender=='b'){
				if($employeepic=="")
					$pic = $alt;
				else
					$pic = $employeepic;
				echo "<div id=".$count." class='employee msg'>".
						"<img class='empic' src='".$pic."'alt='No pic available' />"."<p>".$msgcontent."</p>".
					"</div>";
			}
		}
}

function getMsgCount($jlid){
	$selectmsg = "select msgcount from messages where joblogID='$jlid'";
	$result = mysql_query($selectmsg); 
	while ($row = @mysql_fetch_assoc($result)){ 
		$count = $row[msgcount];
		return $count;
	}
}

function formMessageString($msg,$id,$uid){
	$count = getMsgCount($id)+1;
	if(employer($id,$uid))
		$message="~#".$count."^a^".$msg."^1";
	else 
		$message="~#".$count."^b^".$msg."^1";
	return $message;
}


$jlid= $_GET['jlid'];
$msg = $_GET['msg'];
$lastmsgid = $_GET['lastmsgid'];
$action = $_GET['action'];


$message = formMessageString($msg,$jlid,$uid);
$employeepic = getEmployeePic($jlid);
$employerpic = getEmployerPic($jlid);
//echo $message;

 switch($action) {  
   
	case "showmsg":  
		$selectmsg = "select * from messages where joblogID='$jlid'";
		$result = mysql_query($selectmsg); 
		while ($row = @mysql_fetch_assoc($result)){ 
			$message = $row['msgstr'];
			parseAndDisplayMsg("~#","^",$message,$employeepic,$employerpic);
		}
		break;  
 
	case "updatemsg":  
		$checkmsgquery= "Select * from messages where joblogID='$jlid' ";
		$result = mysql_query($checkmsgquery);
			if (!$result) 
				die("Invalid query: " . mysql_error());
			if(mysql_num_rows($result)<=0)
			{
				$addmsg = "INSERT INTO messages(joblogID,msgstr,msgcount) VALUES ('$jlid','$message','1')";  
				mysql_query($addmsg);
			}
		else{
			$updatemsg = "update messages set msgstr=concat(msgstr,'$message'), msgcount = msgcount+1 where joblogID='$jlid'";
			 mysql_query($updatemsg); 	
		}
		break;  

		case "shownewmsgs":
			$count = getMsgCount($jlid);
			if($lastmsgid < $count){
				$substrquery = "select substring_index(msgstr, '~#".($lastmsgid+1)."', -1) as substr from messages where joblogID = '$jlid'";	
				$result = mysql_query($substrquery); 
				while ($row = @mysql_fetch_assoc($result)){ 
					$message = $row['substr'];
					$msgstr =  "~#".($lastmsgid+1).$message;
					parseAndDisplayMsg("~#","^",$msgstr,$employeepic,$employerpic);
				}
			}
			break;

 }  
?>