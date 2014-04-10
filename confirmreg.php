<?PHP
@require_once("./include/membersite_config.php");


if(isset($_GET['code']))
{
   if($fgmembersite->ConfirmUser())
   {
		if(isset($_GET['email']))
			echo "<script>window.top.location='editprofile.php?ft=1&email=".$_GET['email']."'</script>"; 
		
   }
	else
		echo "wrong confirmation code";
}

?>
