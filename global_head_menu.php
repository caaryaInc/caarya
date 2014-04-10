<!-- style sheets -->

<!-- for menu bar -->

<link rel="stylesheet" type="text/css" href="style/masterstyle.css"/> 
<link rel="stylesheet" type="text/css" href="style/menubar.css"/> 
<link rel="stylesheet" href="style/postjob/addresspicker.css"> 
<link rel="stylesheet" href="style/postjob/themes/jquery.ui.all.css">
<link rel="stylesheet" href="style/postjob.css"> 
<link rel="stylesheet" href="style/msgarchives.css"> 
<link href="style/rating.css" rel="stylesheet" type="text/css" media="screen" />

<!-- for home page -->

<link rel="stylesheet" href="style/searchbox.css" media="screen, projection" type="text/css" />
<link rel="stylesheet" href="style/homepage.css"  media="screen, projection" type="text/css" /> 
<link rel="stylesheet" href="style/infobox.css"  media="screen, projection" type="text/css" /> 
<link rel="stylesheet" href="style/applyjobholder.css"  media="screen, projection" type="text/css" /> 
<link href="style/scrollbar.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="style/jobposted.css" media="screen" />
<link rel="stylesheet" type="text/css" href="style/messaging.css" media="screen" /> 
<link rel="stylesheet" type="text/css" href="style/appliedjobs.css" media="screen" /> 


<!-- scripts -->

<script src="scripts/jquery-1.7.2.min.js"></script> 

<script src="notify.js"></script> 

<script type="text/javascript">
	$(document).ready(function() {

		$(document).hover(function(){
			 $('.dropdown').hide();
		})

		$('#profile').hover(function(e){
			e.stopPropagation();
			$('#notylist').hide();
			$('#profileoptions').slideDown(150);
		});
		
		$('#notyicon').hover(function(e){
			e.stopPropagation();
			$('#profileoptions').hide();
			$('#notylist').slideDown(150);
		});
										
	});  
</script> 
	
<script type="text/javascript">
	function toggleVisibility(div)
	{
		if (div.css('visibility')=='hidden')
			div.css('visibility', 'visible');
		else 
			div.css('visibility', 'hidden');
	}

	$(document).ready(function(){
		$("#show-panel").click(function(){
			var div= $("#lightbox, #lightbox-panel");
			toggleVisibility(div);
			$('body').addClass('croppedbody');
			$('#container').removeClass('fullcontainer');
			$('#container').addClass('croppedcontainer');
			$("a#close-panel").show();
		});
				
		$("a#close-panel").click(function(){
			$("#lightbox, #lightbox-panel").css('visibility', 'hidden');
			$('#container').addClass('fullcontainer');
			$('#container').removeClass('croppedcontainer');
	 	});
	});
</script>


<!-- for post job -->


<!-- Scripts included in homepage for compatibility -->
<script src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script src="scripts/postjob/jquery-1.4.4.min.js"></script> 
<script src="scripts/postjob/jquery-ui-1.8.7.min.js"></script>		 				
<script src="scripts/postjob/jquery.ui.addresspicker.js"></script>

<!-- for date picker -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="scripts/postjob.js"></script>

<!-- for rating system -->
<script src="scripts/rating.js" type="text/javascript"></script>
