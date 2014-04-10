<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title> Welcome to Caarya </title>
<link rel="stylesheet" type="text/css" href="style/masterstyle.css"/>  

<!-- for header_login -->
	<link rel="stylesheet" type="text/css" href="style/header_login.css"/> 

<!-- for welcome page -->
	<noscript>
		  Javascript is disabled.   
		<meta HTTP-EQUIV="REFRESH" content="0; url=basic.php"> 
    </noscript>

	<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="style/welcomepage.css"  media="screen, projection" type="text/css" />
	<link rel="stylesheet" href="style/infobox.css"  media="screen, projection" type="text/css" />
	<script type="text/javaScript" src="http://j.maxmind.com/app/geoip.js"></script>
	<script type="text/javascript" src="scripts/infobubble.js"></script>
	<script src="http://maps.google.com/maps/api/js?v=3&sensor=true&key=AIzaSyCDpWqNy8Tiq4mogdob2lwFFbm5IwkiLgY" type="text/javascript"></script>
	<script src="gmapslogin.js" type="text/javascript"></script>
	<script type="text/javascript">
		function loadXMLDoc2() { // statistics:
			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp2=new XMLHttpRequest();
			}
			else { // code for IE6, IE5
				xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp2.open("GET", "stat.php", true);
			xmlhttp2.send();
			xmlhttp2.onreadystatechange=function() {
			if (xmlhttp2.readyState==4 && xmlhttp2.status==200) {
					var myarr = xmlhttp2.responseText.split(" ");
					document.getElementById("tasks").innerHTML = myarr[1];
					document.getElementById("dollars").innerHTML = "$" + myarr[0];
				}
			}
		}
		function repeat(){
			loadXMLDoc2();
			setInterval('loadXMLDoc2()', 3000);
		}
	</script> 

<!-- for login box -->

	<script type='text/javascript' src='http://code.jquery.com/jquery-1.5.2.js'></script>
	<link rel="STYLESHEET" type="text/css" href="style/login_box.css" />   
	<script type='text/javascript' src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.js"></script>
	<style type='text/css'>
		.pleasefillfield {
			background: yellow;
			color:red;
		}
	</style>

<!-- for register box-->

	<link href="style/register_box.css" media="screen, projection" rel="stylesheet" type="text/css">
	<script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
	<link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
	<script src="scripts/pwdwidget.js" type="text/javascript"></script> 
	<script src="scripts/jquery.js" type="text/javascript"> </script> 		
