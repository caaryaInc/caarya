<?php
require_once("allfields.php");

if(!($fgmembersite->CheckLogin()||$user)&&!isset($_GET['ft']))
	echo "<script>window.top.location='welcomepage.php'</script>";

if($name!="") $username = $name;
else $username="Welcome,";
?>