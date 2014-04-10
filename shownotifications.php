<?php  
 require_once("getnotifications.php");  
 $notification = new Notification();  
 $notification->for_user = $uid;  
 $notifications = $notification->getAllNotifications();  
 if ($notifications) { 
   echo $notification->newcount . "|";  
   $unseen_ids = array();  
   while ($object = mysql_fetch_object($notifications)) {  
     if ($object->seen == 0) $unseen_ids[] = $object->notificationID;  
     switch($object->notificationType) {  
       case "applicant":  
		//Handle for case applicant 
		$joblogquery = "SELECT * from joblog where joblogID = {$object->referingField}"; 
		$result = mysql_query($joblogquery);
		if (!$result) {
		die("Invalid query: " . mysql_error());         
		} 
		$row = mysql_fetch_assoc($result);            
		$jobid= $row['jobID'];                         
		$employeeid= $row['prospectiveEmployeeID'];   
		$jobquery = "SELECT * from jobs where jobID='$jobid'"; 
		$employeequery = "SELECT name,profileurllocation from user where userid='$employeeid'"; 

		$jobqueryresult = mysql_query($jobquery);
		if (!$jobqueryresult) {
		die("Invalid query: " . mysql_error());         
		} 
		$jobrow = mysql_fetch_assoc($jobqueryresult);            
//		$jobid= $jobrow['jobID']; 

		$employeequeryresult = mysql_query($employeequery);
		if (!$employeequeryresult) {
		die("Invalid query: " . mysql_error());         
		} 
		$employeerow = mysql_fetch_assoc($employeequeryresult);            
		$employeename= $employeerow['name'];                           
		$employeepic= $employeerow['profileurllocation'];
         ?>  
         <li id="notification_<?=$object->id;?>">  
			<div  class='employeepic left'> <img src="<?php $alt = "profilepictures/unknown2.png";
															if($pic=="")
															echo $alt;
															else  echo $employeepic;
														?>" style='width: 34px; height: 34px; margin-top: 5px;' alt='No pic available' /></div> 
			<div class='employeename'> <?php echo $employeename;?> applied for your job</div>
         </li>  
         <?php  
         break;  
  						//add cases for other notifications  
     }  
   }  
   echo "|".json_encode($unseen_ids);  
 }  
	else
		 echo "Showing notifications"; 
 ?>  