<?php
	$con=mysql_connect("localhost", "root", "root");
//	$con=mysql_connect("localhost", "caarya5", "topgear123!");
	if(mysqli_connect_errno($con)) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	mysql_select_db("dailychores");
//	mysql_select_db("caarya5_db");
	$result = mysql_query('SELECT SUM(jobAmt) AS value_sum FROM jobs');
	$row = mysql_fetch_assoc($result);
 	echo $row['value_sum'] . " ";

	$result = mysql_query("SELECT * FROM jobs");
	echo mysql_num_rows($result);

	mysql_close($con);
?>
