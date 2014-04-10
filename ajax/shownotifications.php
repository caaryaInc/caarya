<?php  
 include("getnotifications.php");  
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
		$employeequery = "SELECT * from user where useid='$employeeid'"; 

		$jobqueryresult = mysql_query($jobquery);
		if (!$jobqueryresult) {
		die("Invalid query: " . mysql_error());         
		} 
		$jobrow = mysql_fetch_assoc($jobqueryresultresult);            
//		$jobid= $jobrow['jobID']; 

		$employeequeryresult = mysql_query($employeequery);
		if (!$employeequeryresult) {
		die("Invalid query: " . mysql_error());         
		} 
		$employeerow = mysql_fetch_assoc($employeequeryresultresult);            
//		$jobid= $employeerow['jobID'];
         ?>  
         <li id="notification_<?=$object->id;?>">  
			<?php echo $jobid+$employeeID; ?>
           
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