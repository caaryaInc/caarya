<?php
	//$con=mysqli_connect("localhost", "root", "root", "dailychores");
	$con=mysqli_connect("localhost", "caarya5", "topgear123!", "caarya5_db");
	if(mysqli_connect_errno($con)) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$result = mysqli_query($con,"SELECT * FROM user WHERE email='$_GET[email]'");
	mysqli_close($con);
	$x=0;
	while(!$x && $row = mysqli_fetch_array($result)) {
		$x++;
	}
	echo $x;
?>