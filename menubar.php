<header id="menucontainer" role="banner">
	<div class="defaultContentWidth clearfix">
	
		<a id="logo" class="left" href="homepage.php"> </a>
			
		<ul id="profileoptions" class="dropdown">
			<li><a href="jobarchives.php"> My Jobs </a></li>
			<li><a href="editprofile.php"> Profile  </a></li>
			<li style="border-bottom:none;"><a href="logout.php"> Logout </a></li>
		</ul> 

		<ul id="notylist" class="dropdown">
			<a href="#"><strong>Notifications</strong></a>  
			<ul id="notifications">whatever</ul>
		</ul> 

		<div id="menuoptions" class="right">
			<ul class="menu">
				<li id="profile" style="cursor:pointer;">
					<div class="username left"><?php echo $username; ?> </div>
					<div class="propic right"> 
						<img src="<?php $alt = "profilepictures/unknown2.png";
														if($pic=="")
														echo $alt;
														else  echo $pic;
													?>" style='width: 34px; height: 34px; margin-top: 5px;' alt='No pic available' />
						<div class="arrow-down"></div>
					</div>
				 </li>
				<li id="msgnnot">
					 <div class= "borderleft left"></div>
					 <div id="notyicon" class="notification">
						<div id="notiholder">
						</div>
					 </div>
					 <div class="messages"> 
						<div id="msgholder">
						</div>
					</div>
					 <div class="borderright right"></div>
				</li>
				<li id="pjob"> <a id="show-panel" href="postjobnew.php" class="postjob right"> post job </a>  </li>
			</ul>
		</div>

	</div>
</header>