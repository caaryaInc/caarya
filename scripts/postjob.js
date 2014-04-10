$(function() {
	var addresspicker = $( "#addresspicker" ).addresspicker();
	var addresspickerMap = $( "#jobAddress" ).addresspicker({
		regionBias: "us",
		elements: {
			map:      "#maptwo",
			lat:      "#lat",
			lng:      "#lng",
			locality: '#locality',
			country:  '#country',
			type:    '#type'
		}
	});
	var gmarker = addresspickerMap.addresspicker( "marker");
	gmarker.setVisible(true);
	addresspickerMap.addresspicker( "updatePosition");
});

$(function() {
	$( "#jobDue" ).datepicker({ dateFormat: 'yy-mm-dd', minDate:0 });
});

function dateformat(div){
	var validformat=/^\d{4}\-\d{2}\-\d{2}$/ //Basic check for format validity
	var date=$(div).val();
	if (!validformat.test(date))
		return false;
	else
		return true;
}

function validate(div,type,msg){
	var maindiv = '#'+div;
	var errordiv = '#error_'+div;
	var str= $(maindiv).val();
	if(type=='nonempty'){
		if(!str){
			$(errordiv).html(msg);
			$(errordiv).show();
		}
		else
			$(errordiv).hide();
	}
	else if(type=='date'){
		if(!dateformat(maindiv)){
			$(errordiv).html(msg);
			$(errordiv).show();
		}
		else{
			$(errordiv).hide();
		}
	}
}	

function numbervalidate(div,min,max,msg){
	var maindiv = '#'+div;
	var errordiv = '#error_'+div;
	var str = $(maindiv).val();
	if(str!=""){
		if(isNaN(str)){
			$(errordiv).html('The value entered is not a number');
			$(errordiv).show();
		}
		else if(parseInt(str)<min || parseInt(str)>max){
			$(errordiv).html(msg);
			$(errordiv).show();
		}
		else{
			$(errordiv).hide(); 
		}
	}
}

function wordvalidate(div){
	var maindiv = '#'+div;
	var errordiv = '#error_'+div;
	var str = $(maindiv).val();
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp3 = new XMLHttpRequest ();
	}
	else { // code for IE6, IE5
		xmlhttp3 = new ActiveXObject ("Microsoft.XMLHTTP");
	}
	var tosend = "abusecheck.php?str=" + str;
	xmlhttp3.open ("GET", tosend, true);
	xmlhttp3.send ();
	xmlhttp3.onreadystatechange = function () {
		if (xmlhttp3.readyState == 4 && xmlhttp3.status == 200) {
			if(xmlhttp3.responseText=='abuse'){
				$(errordiv).html("Please dont enter abuse words");
				$(errordiv).show();
			}
			else
				$(errordiv).hide();
		}
	}
}

$(document).ready(function() {

	$("#jobAddress").focus(function() {
		$("#jobAddress").addClass('addfocus');
		if(this.value=='Where is the job at?')
			this.value='';
	});

	$("#jobAddress").blur(function() {
		if(this.value==''){	
			this.value='Where is the job at?';
			$("#jobAddress").removeClass('addfocus');
		}
		else
			$('#error_jobAddress').hide();
	});

	$('#title').blur(function(){
		if(this.value=="")
			validate('title','nonempty','Please enter a title for the job');
		else
			wordvalidate('title');
	});

	$('#jobDue').blur(function(){
		if(this.value!=""){
			validate('jobDue','date','Please enter a valid date');
		}
	});


	$("#jobAmt").blur(function() {
		validate('jobAmt','nonempty','How much are you willing to pay for the job?');
		numbervalidate('jobAmt','5','5000','The pay must be between 5 and 5000');
	});

	$("#jobDuration").blur(function() {
		validate('jobDuration','nonempty','How long will the job take to complete?');
		numbervalidate('jobDuration','0','100000000','');
	});

	$("#jobDesc").blur(function() {
		if(this.value=="")
			validate('jobDesc','nonempty','Please enter a job description!');
		else
			wordvalidate('jobDesc');
	});

	$( "#jobform" ).submit(function() {
		if($('#jobAddress').val()=='Where is the job at?'){
			$('#error_jobAddress').html('Please enter a valid address!');
			$('#error_jobAddress').show();
		}
		else
			$('#error_jobAddress').hide();

		validate('jobDue','date','Please enter a valid date');
		validate('jobAmt','nonempty','How much are you willing to pay for the job?');
		validate('jobDuration','nonempty','How long will the job take to complete?');
		numbervalidate('jobAmt','5','5000','The pay must be between 5 and 5000');
		numbervalidate('jobDuration','0','100000000','');

		if($('#title').val()=="")
			validate('title','nonempty','Please enter a title for the job');
		else
			wordvalidate('title');

		if($('#jobDesc').val()=="")
			validate('jobDesc','nonempty','Please enter a job description!');
		else
			wordvalidate('jobDesc');

		var errors = $('.errorjform:visible').length;
		if(errors!=0)
			return false;
	});

});