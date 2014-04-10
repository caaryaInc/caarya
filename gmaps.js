var map;
var markers = [];
var infoBubble;
var markerids= [];
var currids = [];
var radius = "100";
var bounds;
var markerclick = false;

var options =
{
	map: map,
	//position: new google.maps.LatLng(-35, 151),
	shadowStyle: 1,
	padding: '0px',
	backgroundColor: '#fff',
	borderRadius: 5,
	minWidth: 150,
	minHeight:100,
	Height: 150,
	arrowSize: 14,
	borderWidth: 0,
	borderColor: '#2c2c2c',
	disableAutoPan: true,
	hideCloseButton: true,
	arrowPosition: 15,
	pixelOffset: new google.maps.Size(130, 120),
	arrowStyle: 2
}

infoBubble = new InfoBubble(options);

/* different icons for different types */
var householdIcon = new google.maps.MarkerImage(
	"map-icon/map-household.png",
	null,
	null,
	null,
	new google.maps.Size(40, 40)
);

var onlineIcon = new google.maps.MarkerImage(
	"map-icon/map-online.png",
	null,
	null,
	null,
	new google.maps.Size(40, 40)
);

var travelIcon = new google.maps.MarkerImage(
	"map-icon/map-travel.png",
	null,
	null,
	null,
	new google.maps.Size(40, 40)
);

var fixingIcon = new google.maps.MarkerImage(
	"map-icon/map-fixing.png",
	null,
	null,
	null,
	new google.maps.Size(40, 40)
);

var autoIcon = new google.maps.MarkerImage(
	"map-icon/map-auto.png",
	null,
	null,
	null,
	new google.maps.Size(40, 40)
);

var nursingIcon = new google.maps.MarkerImage(
	"map-icon/map-nursing.png",
	null,
	null,
	null,
	new google.maps.Size(40, 40)
);

var otherIcon = new google.maps.MarkerImage(
	"map-icon/map-other.png",
	null,
	null,
	null,
	new google.maps.Size(40, 40)
);

var customIcons = [];
customIcons["household"] = householdIcon;
customIcons["online"] = onlineIcon;
customIcons["travel"] = travelIcon;
customIcons["fixing"] = fixingIcon;
customIcons["auto"] = autoIcon;
customIcons["nursing"] = nursingIcon;
customIcons["other"] = otherIcon;
var markerGroups = { "household": [], "online": [], "travel": [],"fixing": [],"auto": [], "nursing": [],"other": []};

function load() {
	map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(40, -100),
		zoom: 8,
		mapTypeId: 'roadmap',
		mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU } ,
		panControl: true,
		panControlOptions: {
		  position: google.maps.ControlPosition.RIGHT_CENTER
		},
		zoomControl: true,
		zoomControlOptions: {
		  style: google.maps.ZoomControlStyle.LARGE,
		  position: google.maps.ControlPosition.RIGHT_CENTER
		} 
	});
	getCurrentPosition(map);
	google.maps.event.addListener(map, 'idle', function() {
		if(!markerclick){
			somethinghasbeenchanged('bounds');      
			markerclick = false;
		}
	});
}

function clearLocations() {
	for (var i = 0; i < markers.length; i++)
		markers[i].setMap(null);
}

function ShowAllLocations() {
    for (var i = 0; i < markers.length; i++) 
		markers[i].setVisible(true);
} 

function ShowLocations(type,map) {
	for (var i = 0; i < markerGroups[type].length; i++) 
		markerGroups[type][i].setVisible(true);
}
	
function HideLocations(type,map){
	for (var i = 0; i < markerGroups[type].length; i++) 
		markerGroups[type][i].setVisible(false);
}

function ShowMarker(id){
     markerids[id].setVisible(true); 
}

function hideAllMarkers()
{
    for (var i = 0; i < markers.length; i++) 
       markers[i].setVisible(false);

}

function searchLocations(searchUrl,param) {
//	alert(param);
//	$('#resultpanel').html('');
	$('.inforesult, .line').hide();
	downloadUrl(searchUrl, function(data) {
//		alert(searchUrl);
		var xml = parseXml(data);
		var markerNodes = xml.documentElement.getElementsByTagName("marker");
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < markerNodes.length; i++) {
			var jobID=markerNodes[i].getAttribute('jobID');
			var name = markerNodes[i].getAttribute('Name');
			var hname = markerNodes[i].getAttribute('hname');
			var address = markerNodes[i].getAttribute('Address');
			var haddress = markerNodes[i].getAttribute('haddress');
			var description = markerNodes[i].getAttribute('Description');
			var amount = markerNodes[i].getAttribute('Amount');
			var jtype = markerNodes[i].getAttribute('Type');
			var email = markerNodes[i].getAttribute('email');
			var url = markerNodes[i].getAttribute('url');
			var distance = parseFloat(markerNodes[i].getAttribute("distance"));
			var latlng = new google.maps.LatLng(
			parseFloat(markerNodes[i].getAttribute("lat")),
			parseFloat(markerNodes[i].getAttribute("lng")));

			if(currids.indexOf(jobID)==-1){
				createMarker(latlng, name,jobID, address,description, amount,jtype,email,url);
				if(param=='text')
					createSearchResult(jobID,hname,haddress,amount,jtype,url);
				else
					createSearchResult(jobID,name,address,amount,jtype,url);
				bounds.extend(latlng); 
			}
			$('#'+jobID).show();
			$('#l'+jobID).show();
		}
		if(param=='dynamic')
			map.fitBounds(bounds);
		var mapholder= document.getElementById('mapholder');
		mapholder.className = mapholder.className + " effect2";
	});
}

function createSearchResult(jobID,name,address,amount,jobType,pic){
	var img;
	if(jobType == 'household')		
		img = 'map-icon/household2.png';
	else if(jobType == 'fixing')	
		img = 'map-icon/fixing2.png';
	else if(jobType == 'auto')	
		img = 'map-icon/auto2.png';
	else if(jobType == 'travel')	
		img = 'map-icon/travel2.png';
	else if(jobType == 'online')	
		img = 'map-icon/online2.png';
	else if(jobType == 'nursing')	
		img = 'map-icon/nursing2.png';
	else if(jobType == 'other')	
		img = 'map-icon/other2.png';

	var alt = "profilepictures/unknown2.png";
	if(pic=="")
		url = alt;
	else  url = pic; 

	 var html =  "<div id='"+jobID+"'class='inforesult' onclick='triggermarkerclick("+jobID+");'>"+
					"<div id='upic'> <img src='"+url+"' alt='Not Found' height='40px' width='40px'> </div>"+
					"<div id='jdetails'>"+
						"<div id='jname'>"+name+"</div>"+
						"<div id='jadd'>"+address+"</div>"+
					"</div>"+
					"<div id='jextras'>"+
						"<div id='jamt'>$"+amount+"</div>"+
						"<div id='imgntime'><img src='"+img+"' alt='Not Found'><div id='time'>4 hours</div></div>"+
					"</div>"+
				"</div><hr id='l"+jobID+"' class='line'>"; 
	$('#resultpanel').append(html);
}

//Function to perform dynamic search 
/*
function dynamicsearch(){
	var bounds = new google.maps.LatLngBounds();
	var idsdiv = $("#jids").html();
	var hashedids= $.parseJSON(idsdiv);
	hideAllMarkers();
	for (var i = 0; i < hashedids.length; i++){
	//	alert(jobids[i]);
		var jobstr =  hashedids[i];
		var len = parseInt(jobstr.charAt(jobstr.length-1));
		var jobid = jobstr.substring(7,7+len);
		ShowMarker(jobid);
//		var position =  markerids[jobid].getPosition();
//		bounds.extend( position );
	}
//    map.fitBounds(bounds);
}*/


// Function to retrieve city and state from Latlng address 
function getCityAndState(address) {
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
      
        result=results[0].address_components;
        var info=[];
        for(var i=0;i<result.length;++i)
        {
            if(result[i].types[0]=="administrative_area_level_1"){info.push(result[i].long_name)}
            if(result[i].types[0]=="locality"){info.unshift(result[i].long_name)}
        }
	    $('#add').html(info[0]+','+info[1]);
		} 
	}); 
}


function createMarker(latlng, name, jobID, address,description, amount,jtype,email,url) {
//	alert('hey');
	var marker = new google.maps.Marker({
		map:map,
		position: latlng,
		draggable: false,
		icon: customIcons[jtype]
	});

 	var src;
	var alt = "profilepictures/unknown2.png";
	if(url=="")
		src=alt;
	else  src = url;

	//Add the entire html out here to display things in the display window
	var html= "<div id='infobox'>"+
		"<div class='topcolumn'>"+
			"<div class='column1 left'><img src='"+src+"' alt='Not Found' height='64px' width='64px'></div>"+
			"<div  class='column2'>"+
				"<div class='infoname'>"+name+"</div>"+ 
				"<div class='addnamt'>"+
					"<div id='add' class='infoadd left'>"+address+"</div>"+ 
					"<div class='infoamt right'><p>$"+amount+"</p></div>"+	
				"</div>"+
			"</div>"+
		"</div>"+
		"<div class='bottomcolumn'>"+
			"<div class='datetime left'>"+
				"<div class='time '><img src='style/img/timeicon.png' alt='Not Found'>4hrs</div>"+
				"<div class='date'> Due: Jun 4 </div>"+
			"</div>"+
			"<a class='viewmore right' onclick='openapplydiv()'>View More</a>"+
		"</div>"+
		"<a class='boxclose' onclick='closeinfobox()'></a>"+
	"</div>"

	var btnstr = "<input type='submit' class='apply right' value='Apply For Job'/>"
	var sessionemail = $('#loggedIn').val();
	if(email==sessionemail){
		btnstr = "<a href='#' class='apply right'>You Posted This Job</a>"
	}

	//Apply Job Content
	var applyjobcontent="<form id='applyjobform' action='homepage.php' method='post' accept-charset='UTF-8'>"+ 
						"<input type='hidden' name='applyjobsubmitted' id='applyjobsubmitted' value='1'/>"+
						"<input type='hidden' name='jobId' id='jobId' value='"+jobID+"'/>"+
						"<div id='appjholder'>"+
								"<div class='picrow'> <div class='jbpropic left propicshadow'><img src='"+src+"' alt='Not Found'></div>"+
								"<div class='jbname'>"+name+"</div> </div>"+
								"<div class='jbadd'><img class = 'addicon' src='style/img/maps_pin.png' alt='Not Found'>"+address+"</div>"+ 
								"<div class='extras'>"+
									"<div class='jbamt left'>$"+amount+"</div>"+	
									"<div class='jbdatetime'>"+
										"<div class='jbtime '><img src='style/img/clock_icon.jpg' alt='Not Found'><p class='timeval'>4hrs</p></div>"+
										"<div class='jbdate'> Due: Jun 4 </div>"+
									"</div>"+
								"</div>"+
								"<div class='borderline'></div>"+
								"<div class='jbdesc'><p class='label'>Description:</p>"+description+"</div>"+ 

								"<div class='msgcontainer'>"+
									"<div class='borderline'></div>"+
									"<div class='sendmsg'><p class='label'>Message:</p><textarea id='msgcnt' name='msgcnt' class='msgarea' rows='4' cols='50'> </textarea></div>"+ 
									btnstr+
								"</div>"+

						 "</div>"+
						"</form>"


	getCityAndState(address);

	google.maps.event.addListener(marker, 'click', function() {
		markerclick = true;
		var jobclass = parseInt(sha1_rsa_key())+parseInt(encodeurl_base64_key())+parseInt(jobID);
		closeapplydiv();
		infoBubble.setContent(html);
		infoBubble.open(map, marker); 
		getCityAndState(address);
		map.panTo(latlng);
		$("#applyforjobs").html(applyjobcontent);
		$(".inforesult").css('background-color','transparent');
		$('.'+jobclass).css('background-color','#eceaea'); 
	});

	markers.push(marker);
	markerGroups[jtype].push(marker);
	markerids[jobID]=marker;
	currids.push(jobID);
}

function triggermarkerclick(div){
//	alert(div);
	google.maps.event.trigger(markerids[div],'click');
}

function closeinfobox(){
	infoBubble.close();
	google.maps.event.trigger(infoBubble, 'closeclick');
	closeapplydiv();
	markerclick = false;
	somethinghasbeenchanged('bounds'); 
//	alert('hey');
}

function openapplydiv(){
	$("#mapholder").animate({width:'80%'},"fast");
	$("#applyjobholder").animate({width:'20%'},"fast");
//	$("#mapholder").addClass("maxwidth");
//	$("#applyjobholder").addClass("minwidth");
}

function closeapplydiv(){
	$("#mapholder").css('width','100%');
	$("#applyjobholder").css('width','0px');
}
	

function downloadUrl(url, callback) {
	var request = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest;

	request.onreadystatechange = function() {
		if (request.readyState == 4) {
			request.onreadystatechange = doNothing;
			callback(request.responseText, request.status);
		}
	};

	request.open('GET', url, true);
	request.send(null);
}

function parseXml(str) {
	if (window.ActiveXObject) {
		var doc = new ActiveXObject('Microsoft.XMLDOM');
		doc.loadXML(str);
		return doc;
	}
	else if (window.DOMParser) {
		return (new DOMParser).parseFromString(str, 'text/xml');
	}
}

function doNothing() {}

// To get current location 

function getCurrentPosition(map) {
	var gl;
	try {
		if (typeof navigator.geolocation =='undefined') {
			gl = google.gears.factory.create('beta.geolocation');
		}
		else {
			gl = navigator.geolocation;
		}
	}
	catch(e) {}

	if (gl) {
	//	displayError();
		gl.getCurrentPosition(displayPosition, displayError, {maximumAge:600000});
	}
	else {
		alert("Geolocation services are not supported by your web browser.");
	}
}

function displayError() {
	var address = "351 firwood dr apartment 1A dayton ohio 45419";
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address: address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			var lat = results[0].geometry.location.lat();
			var lng = results[0].geometry.location.lng();
			//This should be the center of google map
			var latlng = new google.maps.LatLng(lat, lng);
			map.setCenter(latlng);
		}
		else {
			alert(address + ' not found');
		}
	});
}

// Success callback function
function displayPosition(pos) {
	var mylat = pos.coords.latitude;
	var mylong = pos.coords.longitude;

	//This should be the center of google map
	var latlng = new google.maps.LatLng(mylat, mylong);
	map.setCenter(latlng);

	//Add marker
	var marker = new google.maps.Marker({
		position: latlng, 
		map: map, 
		title:"You are here",
		// animation: google.maps.Animation.DROP
		animation: google.maps.Animation.BOUNCE
	});

	// Add circle overlay and bind to marker
/*	var circle = new google.maps.Circle({
	  map: map,
	  radius: 16093,    // 10 miles in metres
	  fillColor: '#78C7C7'
	});

	circle.bindTo('center', marker, 'position'); */

/*(	for (var i = 0; i < markers.length; i++) {
		markers[i].setAnimation(google.maps.Animation.DROP);	 
	}	*/ 
}