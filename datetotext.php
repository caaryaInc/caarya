<?php
function dtt($date){
/*	$datearr = explode('-', $date);
	$year = $datearr[0];
	$month   = $datearr[1];
	$day = $datearr[2];
	echo $month; */
	$dt = strtotime($date);
	$fm =  date("F j, Y", $dt); 
	echo $fm;
}

 dtt('2013-07-09');
?>