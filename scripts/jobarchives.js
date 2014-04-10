function displayapp(joblogID){
	var bottom = document.getElementById('bottomhalf');
	var top = document.getElementById('tophalf');
	var einfo =  document.getElementById('eifor'+joblogID);
	var jinfo =  document.getElementById('appforholder'+joblogID);
	$(top).append(jinfo);
	$(bottom).append(einfo);
	showdiv(einfo,bottom); 
	showdiv(jinfo,top); 
}

function displaycand(jid){
	var bottom = document.getElementById('bottomhalf');
	var top = document.getElementById('tophalf');
	var einfo =  document.getElementById('candidatesfor'+jid);
	var jinfo =  document.getElementById('appjholder'+jid);
	$(top).append(jinfo);
	$(bottom).append(einfo);
	showdiv(einfo,bottom); 
	showdiv(jinfo,top);
}

function showdiv(currentdiv,parentdiv) {
	var divs = parentdiv.children;
	for (var i = 0; i < divs.length; i++) {
	   if (divs[i].id == currentdiv.id) {
			$(divs[i]).css('display','block');
	  }
	  else {
		   divs[i].style.display = 'none';
	  }
	} 
} 

$(document).ready(function() {
	$('.scrollbar-handle').hide();
	$('.scrollbar').hover(function(){
		$('.scrollbar-handle').show();
	},
	function(){
		$('.scrollbar-handle').hide();
	});
});

