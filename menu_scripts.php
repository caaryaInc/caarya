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

<!-- for rating system -->
<script src="scripts/rating.js" type="text/javascript"></script>
<script src="messaging.js" type="text/javascript"></script> 