function showapplicants(divid){
	$('#appinfo').hide();
	$('.colmiddle').show();
	var applicantdiv = document.getElementById('applicants');
	var applicantlist =  document.getElementById('candidatesfor'+divid);
	$(applicantlist).css('position','relative');
	$(applicantdiv).append(applicantlist);
	showdiv('candidatesfor'+divid,applicantdiv);
//	alert(firstcand);
	$('.jobdiv').css('background','none');
	$('#jobdiv'+divid).css('background','#f7f6f6');
	if ( $(applicantlist).children().length == 0 ){
		$('#applicants').hide();
		$('#profinfo').hide();
		$('.colmiddle').css('border-right','1px solid #fff');
		$('#noapplicants').show();
		return;
	}
	else{
		$('.colmiddle').css('border-right','1px solid #d8d8d8');
		$('#noapplicants').hide();
	}
	var firstcand =  $(applicantlist).children().first();
	$(firstcand).click();
	$('#profinfo').show();
	//	alert(divid);
	$('.jdesc').hide();
	$('.jobdiv').css('height','78px');
//	showdesc('#jobdesc'+divid,'#'+divid);
	$('#aheader').show();
	$('#applicants').show();
}

function showdiv(currentdiv,parentdiv) {
	var divs = parentdiv.children;
	for (var i = 0; i < divs.length; i++) {
	   if (divs[i].id == currentdiv) {
		    $(divs[i]).fadeIn();
			$(divs[i]).css('display','block');
	  }
	  else {
		   divs[i].style.display = 'none';
	  }
	}
} 

function showdesc(descid,parentdiv){
	$(descid).fadeIn("slow");
	var hght = $(parentdiv).height();
	hght= hght+ $(descid).height()+10;
//	alert(hght);
	$(parentdiv).height(hght); 
}

function showinfodiv(divid,joblogID)
{
	$('.canddiv').css('background','none');
	$('#'+'candidate'+divid).css('background','#f7f6f6');
//	alert(joblogID);
	$("#hiddenid").val(joblogID);
	showmessages(joblogID,"showmsg");
}

function addmsg(){	
	var id = $("#hiddenid").val();
	var msg = $("#msgarea").val();
	addmessages(id,msg);
	$("#msgarea").val('');
}

function checkscroll(div){
	var cdiv = $('#'+div);
	var prevhght = $("#postedscrollhght").val();
	var currhght = cdiv.attr("scrollHeight");
	if(prevhght!=currhght){
		var diff = prevhght - cdiv.scrollTop();
		if (diff>315)
			alert("Go to bottom to see msg");
		else 
			$("#showmsg").scrollTop("100000");
		$("#postedscrollhght").val(currhght);
	}
}


function refreshmsgs(){
	if($("#hiddenid").val()!=""){
		var id = $("#hiddenid").val();
		showmessages(id,"showmsg");
		if ( $('#showmsg').children().length > 0 ){	
			var lastmsgid =  $('#showmsg').children().last().attr('id');
			updatemessages(id,"showmsg",lastmsgid);
		}
		else
			showmessages(id,"showmsg");
		checkscroll('showmsg');
	}
}

$(document).ready(function() {
	$("#msgarea").keypress(function(e) {
		if(e.which == 13) {
			addmsg();
		}
	});

	$('.scrollbar-handle').hide();
	$('.candidatesfor').hide();
	$('#jposted').hover(function(){
		$('.scrollbar-handle').show();
	});
	$('#profinfo').hide();
	
	if($('#jposted').children().length==0){
		$('#jposted').hide();
		$('#appinfo').hide();
	}else
		$('#noposted').hide();
		
	setInterval(refreshmsgs,1000); 
});