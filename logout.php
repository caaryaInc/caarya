<?PHP

require_once("./include/fbaccess.php");
require_once("./include/membersite_config.php");
 
if($user)
{
session_destroy();
$user = null;
echo "<script>window.top.location='".$logoutUrl."'</script>";
}

else if($fgmembersite->CheckLogin())
{
$fgmembersite->LogOut();
echo "<script>window.top.location='welcomepage.php'</script>";
exit;
}
?>
