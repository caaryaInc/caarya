<?php
include_once("allfields.php");

if($_GET['action']=='setrate'){
	setRating();
}else if($_GET['action']=='getrate'){
	$cryptemail = $_GET['uid'];
	$checkemail = stringdecrypt($cryptemail);
	echo getRating($checkemail);
}

// function to retrieve
function getRating($email){
	return totalprofrating($email);
}

// function to insert rating
function rate(){
	$text = strip_tags($_GET['rating']);
	$update = "update vote set counter = counter + 1, value = value + ".$_GET['rating']."";

	$result = @mysql_query($update); 
	if(@mysql_affected_rows() == 0){
		$insert = "insert into vote (counter,value) values ('1','".$_GET['rating']."')";
		$result = @mysql_query($insert); 
	}
}
?>
