hh = ol = tra = fix = aut = nur = oth = false;

function somethinghasbeenchanged(param) { // it must be called at any time something changes.
	var bounds = map.getBounds();
	var min_lat = bounds.getSouthWest().lat(); 
	var max_lat = bounds.getNorthEast().lat(); 
	var min_lng = bounds.getSouthWest().lng(); 
	var max_lng = bounds.getNorthEast().lng(); 
	var searchurl = "search.php?searchtext=" + document.getElementById("searchbar").value
	 + "&hh=" + ((hh)?"1":"0")
	 + "&ol=" + ((ol)?"1":"0")
	 + "&tra=" + ((tra)?"1":"0")
	 + "&fix=" + ((fix)?"1":"0")
	 + "&aut=" + ((aut)?"1":"0")
	 + "&nur=" + ((nur)?"1":"0")
	 + "&oth=" + ((oth)?"1":"0")
	 + "&pay=" + document.getElementById("pay").value
	 + "&distance=" + document.getElementById("distance").value 
	 + "&duein=" + document.getElementById("duein").value
	 + "&min_lat="+min_lat
	 + "&max_lat="+max_lat
	 + "&min_lng="+min_lng
	 + "&max_lng="+max_lng;
	searchLocations(searchurl,param);
}

function swapimage(type,div, image1, image2) {
	var $img = $(div);
	var src = $img.attr("src");
	if(src == image1){
		$(div).attr('src', image2);
		ShowLocations(type,map);
	}
	else{
		$(div).attr('src', image1);
		HideLocations(type,map);
	}
}

$(document).ready(function() {
	$("#searchbar").keypress(function(e) {
		if(e.which == 13) {
		//	hideAllMarkers();
			somethinghasbeenchanged('dynamic');
		}
	});

	$("#searchbar").keyup(function(){
		somethinghasbeenchanged('text');
		return false;
	});

	$("#household").click(function() {
		swapimage("household","#hh","map-icon/household.png","map-icon/household2.png");
		hh =! hh;
		somethinghasbeenchanged('nondynamic')
		return false;
	});

	$("#online").click(function() {
		swapimage("online","#ol","map-icon/online.png","map-icon/online2.png");
		ol =! ol;
		somethinghasbeenchanged('nondynamic')
		return false;
	});

	$("#travel").click(function() {
		swapimage("travel","#tra","map-icon/travel.png","map-icon/travel2.png");
		tra =! tra;
		somethinghasbeenchanged('nondynamic')
		return false;
	});

	$("#fixing").click(function() {
		swapimage("fixing","#fix","map-icon/fixing.png","map-icon/fixing2.png");
		fix =! fix;
		somethinghasbeenchanged('nondynamic')
		return false;
	});

	$("#auto").click(function() {
		swapimage("auto","#aut","map-icon/auto.png","map-icon/auto2.png");
		aut =! aut;
		somethinghasbeenchanged('nondynamic')
		return false;
	});

	$("#nursing").click(function() {
		swapimage("nursing","#nur","map-icon/nursing.png","map-icon/nursing2.png");
		nur =! nur;
		somethinghasbeenchanged('nondynamic')
		return false;
	});

	$("#other").click(function() {
		swapimage("other","#oth","map-icon/others.png","map-icon/others2.png");
		oth =! oth;
		somethinghasbeenchanged('nondynamic')
		return false;
	});
				
	$("#pay").change(function() {
		somethinghasbeenchanged('nondynamic')
		return false;
	});
				
	$("#distance").change(function() {
		somethinghasbeenchanged('nondynamic')
		return false;
	});

	$("#duein").change(function() {
		somethinghasbeenchanged('nondynamic')
		return false;
	});

				
//	$("#searchbar").trigger("keyup");
//	$("#searchbar").keyup();

});