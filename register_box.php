<script type="text/javascript">
	function toggleVisibility(div){
		var infovisible= $(div).is(":visible")
		if(infovisible)
			$(div).css("visibility", "hidden");
		else
			$(div).css("visibility", "visible");
	}

	$(document).ready(function(){
		var registerbtn=false;

		<?php if(isset($_POST['registersubmitted'])) { ?>
		registerbtn=true;
		<?php } ?>

		if(registerbtn) {
			$('div#popup').fadeIn("slow");
			$(".cancel").click(function() {
				$("#popup").fadeOut("slow");
			});
			//$(".signin").click(function(e));
			$("#regcontainer #regarea").hide();
			$('ul#info').css("visibility", "hidden");
		} 

		$('#signin').submit(function(){
			if($("#pass:nth-child(2)").val() != $("#confpassword").val()) 
				return false;
		})

		$('#regopen').click(function () {
			$('ul#info').css("visibility", "hidden");
			$('#regcontainer #regarea').slideToggle(function(){
				if($(this).is(":hidden")) {
					$('ul#info').css("visibility", "visible");
				}
			});
		}); 
			
		$("#confpassword").blur(function() {
			if($("#pass:nth-child(2)").val() != $("#confpassword").val()) {
				$("#notmatcherror").css("display", "inline");
				$("#pass:nth-child(2)").css("border", "1px solid red");
				$("#confpassword").css("border", "1px solid red");
			}
			else {
				$("#notmatcherror").css("display", "none");
				$("#pass:nth-child(2)").css("border", "1px solid #DDD");
				$("#confpassword").css("border", "1px solid #DDD");
			}
		});

		
		$("fieldset#signin_menu").mouseup(function(e) {
			return false;
		});

		$(document).mouseup(function(e) {
			if(e.target.id=="regopen"){
				$('ul#info').css("visibility", "visible");
				return false;
			} 
			if($(e.target).parent("a.signin").length==0) {
				if( !$('#signin input:focus').length ) {
					$("#regarea").slideUp(function() {
						if($(this).is(":hidden")) {
							$('ul#info').css("visibility", "visible");
						}
					});
				}
			}  
		});

		$("input#email").blur(function() {
			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else { // code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			var right=$("img#right");
			var wrong=$("img#wrong");
			if($(this).val().indexOf(".")!=-1 && $(this).val().indexOf("@")!=-1) {
				xmlhttp.open("GET", "alaki.php?email=" + $(this).val(), true);
				xmlhttp.send();
			}
			else if($("input#email").val()!="") {
				right.css("display", "none");
				wrong.css("display", "inline");
			}

			var ea=$(this);
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					if(xmlhttp.responseText!="0") {
					//	ea.css("color", "red");
						$("#alreadyexist").css("display", "inline");
						right.css("display", "none");
						wrong.css("display", "inline");
					}
					else {
					//	ea.css("color", "black");
						$("#alreadyexist").css("display", "none");
						wrong.css("display", "none");
						right.css("display", "inline");
					}
				}
			}
		}); 

	});
</script>

<div id="regcontainer">
  	<!-- HIDDEN / POP-UP DIV -->
		<div id="popup">
			<span class="cancel"><a title="dismiss this notification">x</a></span>
			<?php  if($fgmembersite->RegisterUser()) {
				echo "A confirmation mail has been sent to you.";
			?>
			<?php } else { ?>
			<h3>Please Rectify the following errors</h3>
			<p>
				<?php
				$msgs= $fgmembersite->GetErrorMessage();
				if(!empty($msgs))
					echo $msgs;
				?>
			</p>

			<?php } ?>
	</div>

	<div id="regarea">
    	<fieldset id="signin_menu">
			<form method="post" id="signin" action="welcomepage.php">
				<input type='hidden' name='registersubmitted' id='registersubmitted' value='1'/>
				<input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' style="display: none;" />

				<div> <span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?> </span> </div>

				<p>
					<label for="email">Email Address:</label><br />
					<input id="email" name="email" value="" tabindex="5" type="text">
					<!--<div id="check">
						<img id="right" src="style/img/right.png" width="26px" height="26px" style="display: none;">
						<img id="wrong" src="style/img/wrong.png" width="26px" height="26px" style="display: none;">
					</div> -->
					<span id='signin_email_errorloc' class='error'></span>
					<div id='alreadyexist' style="display: none; color: red;"> The email you entered already exists.
					<p style="color: blue;"><a href="http://www.caarya.com/caarya/reset-pwd-req.php">Forgot Password?</a></p>
					</div>
				</p>

				<p>
					<label for="password">Password</label>
					<input type="password" id="pass" name="password" value="" tabindex="6">
					<span id='signin_password_errorloc' class='error'></span>
				</p>

				<p>
					<label for='confpassword' >Confirm Password:</label> <br/>
					<input type='password' name='confpassword' id='confpassword' maxlength="50" tabindex="7" />
					<span id='signin_confpassword_errorloc' class='error'></span>
					<span id='notmatcherror' style="display: none; color: red;">The passwords you entered do not match.</span>
				</p>

			<!--	<p>
				<input type="checkbox" id="accept">
					<label for="terms" style="color:black;">I admit whatever you say is true and you can do whatever you want to me</label>
				</p> -->

				<p class="remember">
					<input type="submit" id="signin_submit" value="Register" tabindex="8">
				</p>
			</form>
		</fieldset>
	</div>
	<p id="regopen"><a class="signin"><span>sign up</span></a></p>
</div>
<!-- //clientsDropDown -->

<!-- client-side Form Validations: -->
<script type='text/javascript'>
	// <![CDATA[
	var frmvalidator  = new Validator("signin");
	frmvalidator.EnableOnPageErrorDisplay();
	frmvalidator.EnableMsgsTogether();
	frmvalidator.addValidation("email","req","Please provide your Email");
	frmvalidator.addValidation("email","email","Please Enter a Valid Email");
	frmvalidator.addValidation("password","req","Please provide the password");
	frmvalidator.addValidation("confpassword","req","Please confirm your  password");
	// ]]>
</script>