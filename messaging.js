function showmessages(id,div){
	$.ajax({url:"messaging.php?jlid="+id+"&action=showmsg", dataType:"html", success: function(msg) { 
			$('#'+div).html(msg); 
		//	$('.scrollbar').scrollbar();
			waitformsg();
		}
	}) 
}

function addmessages(id,msg){
	$.ajax({url:"messaging.php?jlid="+id+"&msg="+msg+"&action=updatemsg", dataType:"html", success: function() { 
			// alert("msg sent");
		}
	}) 
}


function updatemessages(id,div,lastmsgid){
	$.ajax({url:"messaging.php?jlid="+id+"&lastmsgid="+lastmsgid+"&action=shownewmsgs", dataType:"html", success: function(msg) { 
			$('#'+div).append(msg); 
		}
	}) 
} 

//long term polling to check for new msgs 

function waitformsg(){	
	if($("#hiddenjblgid").val()!=""){
	//	alert('fisrt loop');
		var id = $("#hiddenjblgid").val();
			if ( $('#showapplicantmsg').children().length > 0 ){	
		//	alert('second loop');
			var lastmsgid =  $('#showapplicantmsg').children().last().attr('id');
				$.ajax({ url:"messaging.php?jlid="+id+"&lastmsgid="+lastmsgid+"&action=shownewmsgs", dataType:"html", success: function(msg){
					$('#showapplicantmsg').append(msg); 
				//	alert('success');
				},
				dataType: "html", timeout: 30000 }); 
			} else
			alert($('#showapplicantmsg').children().length);
		} 
	//	else
	//	alert('allready there');
} 

