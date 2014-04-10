<?php 
if ($user){
$name=$user_info['name'];
$email=$user_info['email'];
$currloc=$user_info['current_location'];
$uname=$user_info['username'];

	$connection=mysql_connect ('localhost', 'caarya5', 'topgear123!');   
//	$connection=mysql_connect (localhost, 'root', 'root');  
	if (!$connection) {
		die("Not connected : " . mysql_error());
	}

	$db_selected = mysql_select_db('caarya5_db', $connection);  // donot change. caarya5_db is name of database. uncomment while uploading into server
//	$db_selected = mysql_select_db('dailychores', $connection);  //comment out while uploading into server . Use only for local 
	if (!$db_selected) {
		die ("Can\'t use db : " . mysql_error());
	}

     
	 $insertquery="INSERT INTO user(name,email,username) values('$name','$email','$uname')";
	 $checkemailexistsquery= "Select email from user where email='$email' ";
	 $result = mysql_query($checkemailexistsquery)
			  or die('Error querying database.');
	
	if (!$result) {
		die("Invalid query: " . mysql_error());
		} 

	if(mysql_num_rows($result)<= 0)
		{
				//If 0 rows exist with email id insert details into database
		$result = mysql_query($insertquery)
			  or die('Error querying database.');
			if (!$result) {
				die("Invalid query: " . mysql_error());
				} 
			echo "<script>window.top.location='editprofile.php'</script>";
		}  
		
	 else
		{
		//write query and check if confirm code is y 
		// Here query needs to be written to check for confirm code through email or username 
		$checkconfirmcodequery = "Select confirmcode, email from user where email= '$email'  ";
			  $result = mysql_query($checkconfirmcodequery)
			  or die('Error querying database.');
			if (!$result) {
				die("Invalid query: " . mysql_error());
			} 
			$row = mysql_fetch_assoc($result);
			if($row['confirmcode']=='y')
			{
				//echo "Confirm code is y ";
				echo "<script>window.top.location='homepage.php'</script>";
			}
			else
			{
				//echo "Confirm code is not y.";
				echo "<script>window.top.location='editprofile.php'</script>";
			} 
			
			
		} 
	mysql_close($connection);
	
	}
?>