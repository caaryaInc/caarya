var map;
var markers = [];
var infoBubble;

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
		zoom: 4,
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
	searchLocations();
}

function searchLocations() {
	var address = "351 firwood dr apartment 1A dayton ohio 45419";
	var geocoder = new google.maps.Geocoder();

	geocoder.geocode({address: address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			searchLocationsNear(results[0].geometry.location);
		}
		else {
			alert(address + ' not found');
		}
	});
}

function clearLocations() {
	for (var i = 0; i < markers.length; i++)
		markers[i].setMap(null);
}

function hideLocations()
{
    for (var i = 0; i < markers.length; i++) 
       markers[i].setVisible(false);

}
	
function ShowLocations(type,map) {
	for (var i = 0; i < markerGroups[type].length; i++) 
		markerGroups[type][i].setVisible(true);
}
	
function ShowAllLocations() {
    for (var i = 0; i < markers.length; i++) 
		markers[i].setVisible(true);
} 

function searchLocationsNear(center) {
	clearLocations();
	//var radius = document.getElementById('radiusSelect').value;
	var searchUrl = 'gmapslogin.php?lat=' + center.lat() + '&lng=' + center.lng();
	downloadUrl(searchUrl, function(data) {
		var xml = parseXml(data);
		var markerNodes = xml.documentElement.getElementsByTagName("marker");
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < markerNodes.length; i++) {
			var jobID=markerNodes[i].getAttribute('jobID');
			var name = markerNodes[i].getAttribute('Name');
			var address = markerNodes[i].getAttribute('Address');
			var description = markerNodes[i].getAttribute('Description');
			var amount = markerNodes[i].getAttribute('Amount');
			var jtype = markerNodes[i].getAttribute('Type');
			var email = markerNodes[i].getAttribute('email');
			var url = markerNodes[i].getAttribute('url');
			var distance = parseFloat(markerNodes[i].getAttribute("distance"));
			var latlng = new google.maps.LatLng(
			parseFloat(markerNodes[i].getAttribute("lat")),
			parseFloat(markerNodes[i].getAttribute("lng")));
			createMarker(latlng, name,jobID, address,description, amount,jtype,email,url);
			bounds.extend(latlng);
		}

		map.fitBounds(bounds);

		var mapholder= document.getElementById('mapholder');
		mapholder.className = mapholder.className + " effect2";
	});
}


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

	var marker = new google.maps.Marker({
		map:map,
		position: latlng,
		draggable: false,
		tite: name,
		icon: customIcons[jtype],
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
					"<div class='infoamt right'>$"+amount+"</div>"+	
				"</div>"+
			"</div>"+
		"</div>"+
		"<div class='bottomcolumn'>"+
			"<div class='datetime left'>"+
				"<div class='time '><img src='style/img/timeicon.png' alt='Not Found' height='10px' width='10px'>4hrs</div>"+
				"<div class='date'> Due: Jun 4 </div>"+
			"</div>"+
			"<a class='viewmore right' onclick='openapplydiv()'>View More</a>"+
		"</div>"+
		"<a class='boxclose' onclick=closeinfobox()> </a>"+
	"</div>"


	getCityAndState(address);

	infoBubble = new InfoBubble({
			map: map,
			//position: new google.maps.LatLng(-35, 151),
			shadowStyle: 1,
			padding: '0px',
			backgroundColor: '#fff',
			borderRadius: 5,
			minWidth: 150,
			minHeight:140,
			arrowSize: 14,
			borderWidth: 0,
			borderColor: '#2c2c2c',
			disableAutoPan: false,
			hideCloseButton: true,
			arrowPosition: 15,
			pixelOffset: new google.maps.Size(130, 120),
			arrowStyle: 2
		});

	google.maps.event.addListener(marker, 'click', function() {
		infoBubble.setContent(html);
		infoBubble.open(map, marker);  
		getCityAndState(address);
	});

	markers.push(marker);
	markerGroups[jtype].push(marker);
}

function closeinfobox(){
	infoBubble.close();
	google.maps.event.trigger(infoBubble, 'closeclick');
	closeapplydiv();
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
		gl.getCurrentPosition(displayPosition, displayError);
	}
	else {
		alert("Geolocation services are not supported by your web browser.");
	}
}

function displayError(positionError) {
	//alert("error");
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
		animation: google.maps.Animation.BOUNCE,
	});

	for (var i = 0; i < markers.length; i++) {
		markers[i].setAnimation(google.maps.Animation.DROP);	 
	}	 
}