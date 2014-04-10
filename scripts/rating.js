//Get Rating
function getRating(id){
	$.ajax({
		type: "GET",
		url: "rating_backend.php",
		data: "uid="+id+"&action=getrate",
		cache: false,
		async: false,
		success: function(result) {
		//	alert(result);
			$("#rate"+id).css({ width:""+result+"%" });
		},
		error: function(result) {
			alert("some error occured, please try again later");
		}
	}); 
}
		
// Set Rating
/*
$('#ratelinks li a').click(function(){
	$.ajax({
		type: "GET",
		url: "update.php",
		data: "rating="+$(this).text()+"&do=rate",
		cache: false,
		async: false,
		success: function(result) {
			// remove #ratelinks element to prevent another rate
			$("#ratelinks").remove();
			// get rating after click
			getRating();
		},
		error: function(result) {
			alert("some error occured, please try again later");
		}
	});
}); 
*/
