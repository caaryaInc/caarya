function displaymsgs(divid,joblogID){
	var eidiv = document.getElementById('edetails');
	var einfo =  document.getElementById('eifor'+joblogID);
	$('.appliedjobdiv').css('background','none');
	$('#job'+divid).css('background','#f7f6f6');
	$('#einfo').fadeIn();
	$(eidiv).append(einfo);
	showdiv('eifor'+joblogID,eidiv);
	$('#profinfo').hide();
	$('#empinfo').hide();
	$('.middlecol').show();
	$('.rightcol').show();
	showmessages(joblogID,"showapplicantmsg");
	$("#hiddenjblgid").val(joblogID);
	setscrollhght();
}

function setscrollhght(){
	scrollhght = $("#showapplicantmsg").attr("scrollHeight");
	$("#scrollhght").val(scrollhght);
}

function showdesc(descid,parentdiv){
	$(descid).fadeIn("slow");
	var hght = $(parentdiv).height();
	hght= hght+ $(descid).height()+10;
//	alert(hght);
	$(parentdiv).height(hght); 
}

function addapplicantmsg()
{
	var id = $("#hiddenjblgid").val();
	var msg = $("#applicantmsgarea").val();
	addmessages(id,msg);
	$("#applicantmsgarea").val('');
}

function checkapplicantscroll(div){
	var cdiv = $('#'+div);
	var prevhght = $("#scrollhght").val();
	var currhght = cdiv.attr("scrollHeight");
	if(prevhght!=currhght){
		var diff = prevhght - cdiv.scrollTop();
		if (diff>315)
			$('#scrollappbottom').show();
		else 
			$("#showapplicantmsg").scrollTop("100000");
		$("#scrollhght").val(currhght);
	}
}

function refreshapplicantmsgs(){
	if($("#hiddenjblgid").val()!=""){
		var id = $("#hiddenjblgid").val();
		if ( $('#showapplicantmsg').children().length > 0 ){	
			var lastmsgid =  $('#showapplicantmsg').children().last().attr('id');
			updatemessages(id,"showapplicantmsg",lastmsgid);
		}
		else
			showmessages(id,"showapplicantmsg");
		checkapplicantscroll('showapplicantmsg');
	}
}

$(document).ready(function() {
	$("#applicantmsgarea").keypress(function(e) {
		if(e.which == 13) {
			addapplicantmsg();
		}
	});

	$('#japplied').hover(function(){
		$('.scrollbar-handle').show();
	});

	$('#japplied').click(function(){
		$('#msginfo').fadeOut();
		$('#msgsexchanged').fadeIn();
	});
	
	if($('#japplied').children().length==0){
		$('#japplied').hide();
		$('#empinfo').hide();
	}else
		$('#noapplied').hide();

//	waitformsg();

//	setInterval(refreshapplicantmsgs,1000); 
});