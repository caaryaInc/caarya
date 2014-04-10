<?php
if(isset($_POST['loginsubmitted'])) {
	if($fgmembersite->Login()) {
		echo "<script>window.top.location='homepage.php'</script>";
	}
}
?>

<script type='text/javascript'>//<![CDATA[ 
	$(window).load(function() {
		var loggedin=false;
		<?php if(isset($_POST['loginsubmitted'])) { ?>
		loggedin=true;
		<?php } ?>
		if(loggedin) {
			$('div#pop-up').fadeIn("slow");
			$(".dismiss").click(function() {
				$("#pop-up").fadeOut("slow");
			});
		}

		$('#password-clear').show();
		$('#password').hide();
		$('#password-clear').keypress(function() {
			$('#password-clear').hide();
			$('#password').show();
			$('#password').focus();
		});

		$('#password').blur(function() {
			if($('#password').val() == '') {
				$('#password-clear').show();
				$('#password-clear').css('color','#777');
				$('#password-clear').css('font-size','13px');
				$('#password-clear').val("Password");
				$('#password').hide();
			}
		});

		$('#password').focus(function() {
			$(this).css('color', 'black');
			$('#password-clear').val("PasswordChanged");
		})

		var doClear=function (field) { if (field.value == field.defaultValue) { field.value = "";$(field).removeClass('pleasefillfield') } };
		var doDefault=function (field){
			if (field.value == "") {
				field.value = field.defaultValue;
				$(field).css('color','#777');
				$(field).css('font-size', '13px');
			}
		};

		$('form#login input[type="text"]').focus(function() {
			doClear(this);
			$(this).css('color', 'black');
			$(this).css('font-size', '12px');
		})

		$('form#login input[type="text"]').blur(function() {
			doDefault(this);
		})

		$('form#login input[type="text"]').each(function() {
			this.defaultValue= $(this).attr('name');
			if($(this).attr('name')=='emaillogin') {
				this.defaultValue="Email";
				if(loggedin)
					this.defaultValue="<?php echo $fgmembersite->SafeDisplay('emaillogin') ?> ";
				else
					this.defaultValue="Email"; 
			}
		})

		$('form#login').submit(function() {
			var errors=0;
			var form=this;
			$('form#login input[type="text"]').each(function(){
				if(this.value==""||this.value==this.defaultValue&&!loggedin) {
					errors+=1;
				//	alert(this.name);
					doDefault(this);
				}
			})

			if (!errors) {
			//	alert('Ok');
				form.submit()   
			}
			else {
				//alert("errors: "+errors);
				$('form#login input[type="text"]').each(function() {
					if(this.value==""||this.value==this.defaultValue) {
						$(this).addClass('pleasefillfield');
						$(this).css('color','red');
					}
				})
			}
			return false;
		})

	});//]]>  

</script>


<!-- This is the form to enter login details -->
<form id='login' class="right" action='welcomepage.php' method='post' accept-charset='UTF-8'>
	<input type='hidden' name='loginsubmitted' id='loginsubmitted' value='1'/>
	<div id="errmsg" style="DISPLAY: none"> </div>

	<!-- HIDDEN / POP-UP DIV -->
	<div id="pop-up">
	<span class="dismiss"><a title="dismiss this notification">x</a></span>
		<h3>Please Rectify the following errors</h3>
		<p>
			<?php $msgs= $fgmembersite->GetErrorMessage();
				if(!empty($msgs))
			echo $msgs;
			?>
		</p>
		</div>
		<div  id="emailcont" class='container'> 
			<ul>
				<li> <input  type='text' name='emaillogin' id='emaillogin'  maxlength="50" tabindex="1" /></li>
				<li style="margin-top: 3px;">
					<p class="login-remember">
						<a>
							<input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="3"/>
							Remember me
						</a>
					</p>
				</li>
			</ul>
		</div>
		<div id="passcont" class='container'>
			<ul>
				<li> 
					<input style="display: none;" id="password-clear" type="text" name="Password" value="Password" autocomplete="off" tabindex="2" />
					<input style="height: 24px; width:145px;" type='password' name='password' id='password'  maxlength="50" tabindex="2" />
				</li>
				<li style="margin-top: 5px;"> <a href='reset-pwd-req.php'>Forgot Password?</a></li>
			</ul>
		</div>
		<div style="display:inline-block; vertical-align:top;">
			<input  type='submit' name='Submit' class="custombutton" value='login' tabindex="4"/> 
		</div>
</form>