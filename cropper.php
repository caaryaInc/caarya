<?php
include_once("allfields.php");

if($fgmembersite->CheckLogin())
	$email = $fgmembersite->UserEmail();

if (isset($_POST['crop']))
{
	$localimage=$email.".jpg";
	echo "Cropping {$localimage} <br />";
	$pathToImages="profilepictures/";

    // load image and get image size
    $img = imagecreatefromjpeg( "{$pathToImages}{$localimage}");
    $width = $_POST['w'];
    $height = $_POST['h'];

    // create a new temporary image
    $tmp_img = imagecreatetruecolor( $width, $height );

    // copy and resize old image into new image
	imagecopyresampled($tmp_img,$img,0,0,$_POST['x'],$_POST['y'],$_POST['w'],$_POST['h'],$_POST['w'],$_POST['h']);
    //imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

    // save thumbnail into a file
    imagejpeg( $tmp_img,  "{$pathToImages}{$localimage}" );

	echo "<script>window.top.location='editprofile.php'</script>"; 
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>

		<script src="scripts/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="style/jquery.Jcrop.css" type="text/css" />

		<script language="Javascript">

			$(function() {
				$('#cropbox').Jcrop({
					boxHeight: 300,
					aspectRatio: 1,
					bgColor:     'white',
					bgOpacity:   .6,
					setSelect:   [ 100, 100, 500, 500 ],
					trackDocument: true,
					onSelect: updateCoords
				});
			});

			function updateCoords(c)
			{
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};

			function checkCoords()
			{
				if (parseInt($('#w').val())) return true;
				alert('Please select a crop region then press submit.');
				return false;
			};

		</script>
	</head>

	<body>

		<!-- This is the image we're attaching Jcrop to -->
		<div class='uploadimgholder'> 
				<p class='uploadlabel'> To crop this image, drag the region below and then click  <b style="color: #666;">"Crop Image"</b>. To use the original click  <b style="color: #666;">"Keep Original"</b>.</p>
				<img id='cropbox' src="<?php $rand = rand(); echo "profilepictures/".$email.".jpg". "?r=" . $rand ?>" style="visibility:hidden;"/>
		</div> 

		<!-- This is the form that our event handler fills -->
		<form id='cropperform' action="cropper.php" method="post" onsubmit="return checkCoords();">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input type="hidden" name="crop" id="crop" />
			<div id='btnholder'>
				<input class='uploadformbtn' type="submit" value="Crop Image" />
				<a class='uploadformbtn' href='editprofile.php'> Keep Original </a>
			</div>
		</form>

	</body>
</html>