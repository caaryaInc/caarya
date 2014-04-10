<?php
/**
 * Jcrop image cropping plugin for jQuery
 * Example cropping script
 * @copyright 2008-2009 Kelly Hallman
 * More info: http://deepliquid.com/content/Jcrop_Implementation_Theory.html
 */
 
include_once("allfields.php");

if (isset($_POST['upload']))
{
	//get file attributes
	if($fgmembersite->CheckLogin())
		$email = $fgmembersite->UserEmail();
	
	$location="profilepictures/".$email.".jpg";
	$name=$_FILES['myfile']['name'];
	$tmp_name=$_FILES['myfile']['tmp_name'];
	
	if($name)
	{
		if(!is_dir("./profilepictures/"))
		{
			 $structure = './profilepictures/';

			// To create the nested structure, the $recursive parameter 
			// to mkdir() must be specified.

			if (!mkdir($structure, 0, true)) {
				die('Failed to create folders...');
			}
		
		}
		
	//start upload process here
	if ($_FILES["myfile"]["error"] > 0)
	{
	  echo "Upload Unsuccessful";
	} 

	//check that only jpeg or png etc and not other file formats can be uploaded
	$allowedExts = array("gif", "jpeg", "jpg", "png","JPG");
	$temp = explode(".", $_FILES["myfile"]["name"]);
	$extension = end($temp);
	if (!in_array($extension, $allowedExts)){
		echo "Invalid file type: Please upload a phtoto";
	}
	
	move_uploaded_file($tmp_name,$location);
	$qry= mysql_query("UPDATE user SET profileurllocation='$location' where email= '$email'") ;
	}
?>
	<html>
	<head>
		<link rel="stylesheet" href="style/imgupload.css"  media="screen, projection" type="text/css" />
		<script type="text/javascript" src="scripts/jquery.js"></script> 
		<script type="text/javascript">
			function toggleVisibility(div)
			{
			if (div.css('visibility')=='hidden')
				div.css('visibility', 'visible');
			else 
				div.css('visibility', 'hidden');
			}
			$(document).ready(function(){
				var div= $("#lightbox, #lightbox-panel");
				toggleVisibility(div);
				$("a#close-panel").show();
			})
		</script>
	</head>

	<body>
		<div id="lightbox"> </div><!-- /lightbox -->
		<div id="lightbox-panel">
			<div class='uploadheader'>
				<p class='uploadtitle'>Select profile picture</p>
				<a id="close-panel" href="editprofile.php">X</a>
			</div>
			<div  class='uploadcontainer'>
				<?php include_once "cropper.php"; ?> 
			</div>
		</div> 
   </body>
   </html>
     
<?php 		
}
// If not a POST request, display page below:

?>