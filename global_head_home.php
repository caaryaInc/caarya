<?php $sht = gettime(); ?>	



	<script src="scripts/jquery-1.7.2.min.js"></script> 
	<script src="scripts/searchform.js"></script> 

	<!--[if IE 8]>
	  <style type="text/css">
		.arrow{
		   display:none;
		}
	  </style>
	<![endif]-->

	<!--[if lt IE 8]>
	  <style type="text/css">
		.arrow{
		   display:none;
		}
	  </style>
	<![endif]-->

	<!--[if  gte IE 8]>
	  <style type="text/css">
		.arrow{
		   display:none;
		}
	  </style>
	<![endif]-->

<!-- for home page -->
	<script type="text/javascript">
		function sha1_rsa_key(){
			return <?php echo $fgmembersite->stringhash('sha1_rsa_key'); ?>
		}

		function encodeurl_base64_key(){
			return <?php echo $fgmembersite->stringhash('encodeurl_base64_key'); ?>
		}
	</script>


	<script language="JavaScript" src="http://j.maxmind.com/app/geoip.js"></script>
	<script type="text/javascript" src="scripts/infobubble.js"></script>
	<script src="http://maps.google.com/maps/api/js?v=3&sensor=true&key=AIzaSyCDpWqNy8Tiq4mogdob2lwFFbm5IwkiLgY" type="text/javascript"></script>
	<script src="gmaps.js" type="text/javascript"></script>  

<!-- for google maps -->

	<!-- <script src="http://maps.google.com/maps/api/js?sensor=false"></script> -->
	<script src="scripts/postjob/jquery-1.4.4.min.js"></script> 
	<script src="scripts/postjob/jquery-ui-1.8.7.min.js"></script>		 				
	<script src="scripts/postjob/jquery.ui.addresspicker.js"></script>


<!-- For Tabs -->
	<script>	
	$(document).ready(function(){
		var tabContainers = $('div.tabs > div');
   		$('div.tabs ul.tabNavigation a').click(function () {
			tabContainers.hide().filter(this.hash).show();
			$('div.tabs ul.tabNavigation a').removeClass('selected');
			$(this).addClass('selected');
			return false;
		}).filter(':first').click(); 
			
		<?php if($fgmembersite->checkConfirmCode($email)) {?>
			$("#searchbox").css('z-index',101);
		<?php } ?>
		
		$('#confirmclick').click(function(){
			<?php $fgmembersite->ResendUserConfirmation($email); ?>
		}); 

	}); 
	</script>

<!-- for jobs posted -->

	<script src="messaging.js" type="text/javascript"></script> 
	<script src="scripts/jobsposted.js" type="text/javascript"></script> 

<!-- for applied jobs -->


	<script src="scripts/appliedjobs.js"></script>

<?php $fht = gettime(); ?>	