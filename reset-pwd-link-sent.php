<?PHP
@include 'header_login.php'; 

if(isset($_POST['codesubmitted']))
{
	$decryptedcode =  $fgmembersite->encrypt_decrypt('decrypt', $_POST['seccode']);
	if($_POST['inputcc']==$decryptedcode)
		 echo "<script>window.top.location='passchange.php?sv=".$_POST['secemail']."'</script>";
	else 
		echo "<div class='errormsg'> The code you entered is either wrong or has expired. Please click <a href='reset-pwd-req.php'>here </a> to send another code.</div>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
		<title>Caarya- Email has been sent</title>
		<link rel="stylesheet" href="style/pwd-link-sent.css"  media="screen, projection" type="text/css" />
	</head>
	<body>
		<form id='resetreq' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
			<input type='hidden' name='codesubmitted' id='codesubmitted' value='1'/>
			<div class='resetcontainer'>
				<p class='frgttitle'> Check Your Mail! </p>
				<div class='inputholder'>
						<label for='username' class='frgtlabel'> A 6 digit confirmation code has been sent to your email. Please enter the code below.</label>
						<input type='text' name='inputcc' id='inputcc' maxlength="6" />
						<input type='hidden' name='seccode' id='seccode' value='<?php echo $_GET['sec'] ?>'/>
						<input type='hidden' name='secemail' id='secemail' value='<?php echo $_GET['sv'] ?>'/>
				</div>
			</div>
			<div class='ccfooter'>
				<p>If you did not get any Email please click <a href="reset-pwd-req.php">here </a></p>
				<input  class='codesubmit' type='submit' name='Submit' value='Continue to Caarya' />
			</div>
			<div id="footer">
				<p><strong>@ 2012 Copyrights by Caarya. </strong>
						 All rights reserved.</p>
			</div>
		</form>
	</body>
</html>
