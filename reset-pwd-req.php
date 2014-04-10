<?PHP
@include 'header_login.php'; 

$emailsent = false;

if(isset($_POST['submitted']))
{
	$randcode =  substr(md5(uniqid(rand(), true)), 16, 6);
	$encryptedcode = $fgmembersite->encrypt_decrypt('encrypt', $randcode);
	$encryptedmail = $fgmembersite->encrypt_decrypt('encrypt', $_POST['frgtemail']);

	if($fgmembersite->EmailResetPasswordLink($randcode))
	{
		$url="reset-pwd-link-sent.php?sec=".$encryptedcode."&sv=".$encryptedmail ; 
		echo "<script>window.top.location='".$url."'</script>";
        exit;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
		<title>Caarya- Password Reset</title>
		<link rel="stylesheet" href="style/resetpwd.css"  media="screen, projection" type="text/css" />
		<script src="scripts/jquery-1.7.2.min.js"></script>  
		<script type="text/javascript">
			$(document).ready(function() {
				$('#frgtemail').click(function(){
					$(this).val('');
				});

				$('#frgtemail').blur(function() {
					if($(this).val() == '') {
						$(this).val('Enter your email here');
					}
				});

			});
		</script>
	</head>
	<body>
		<form id='resetreq' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
			<input type='hidden' name='submitted' id='submitted' value='1'/>
			<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
			<div class='resetcontainer'>
				<p class='frgttitle'> Dont Worry! This happens all the time! </p>
				<div class='inputholder'>
					<label for='username' class='frgtlabel' >Enter your email address and we will send you a link to reset your password.</label>
					<input type='text' name='frgtemail' id='frgtemail' class='frgtemail' style="border: 1px solid #a0a0a0;" value='' maxlength="50" /><br/>
				</div>
				<span id='resetreq_email_errorloc' class='error'></span>
				<input  class='frgtsubmit' type='submit' name='Submit' value='Submit' />
			</div>
		</form>
		<div id="footer">
			<p><strong>@ 2012 Copyrights by Caarya. </strong>
		             All rights reserved.</p>
		</div>
	</body>
</html>