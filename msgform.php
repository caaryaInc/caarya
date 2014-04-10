<html>
<head>
		<script src="scripts/jquery.js" type="text/javascript"></script>

	<script type="text/javascript">

	 function CheckMessages()  
	 {  
		var employer = $("#employer").val();
		var employee =  $("#employee").val();
		var searchstr = "employer="+employer+"&&employee="+employee;
		$.ajax({url:"messages.php?employer="+employer+"&&employee="+employee, dataType:"html", success: function(msg) { 
			 $('#check').html(msg); 
		}
		}) 
	}  

	</script>

</head>


	<body>
	<div id="showmsg" style="border: 1px solid black; width: 50%; height: 50%;"><div id="check">Hey</div></div>
	<form id="msgform" action="msgform.php" method="post">
		<input type='hidden' name='msgformsubmitted' id='msgformsubmitted' value='1'/>
		<textarea  id="employer" name="employer" rows="4" cols="50"></textarea><br/>

		<textarea id="employee" name="employee" rows="4" cols="50"></textarea>
		<a onclick="CheckMessages()">Send Msg</a>
		<input type="submit" value="submit"/>
	</form>
	</body>
</html>
	