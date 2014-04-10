<div id="searchbox" class="effect7">
	<form id="searchform" onsubmit="return false;">
		<input type='text' name='searchbar' id='searchbar'/>
		<!--	<a id="searchbutton"  onclick="createmarkersonmap()">search</a> -->

		<div id="dropdownoptions">
		<!--	<div id="mpicon"> <img id="pin" src="./style/img/maps_pin.png" alt="Not Found"> </div>-->

			<div class="selectstyle ddboxes">
				<select id="pay">
					<option value="pay">min pay</option>
					<option value="pay1">$10</option>
					<option value="pay2">$50</option>
					<option value="pay3">$100</option>
				</select>
				<div id="pay_arrow" class="arrow"></div>
			</div>

			<div class="selectstyle ddboxes">
				<select id="distance">
					<option value="distance">distance</option>
					<option value="distance1">2 miles</option>
					<option value="distance2">5 miles</option>
					<option value="distance3">10 miles</option>
					<option value="distance4">20 miles</option>
					<option value="distance5">20+ miles</option>
				</select>
				<div id="distance_arrow" class="arrow"></div>
			</div>

			<div class="selectstyle ddboxes">
				<select id="duein">
					<option value="duein">due in</option>
					<option value="duein1">2 days</option>
					<option value="duein2">3 days</option>
					<option value="duein3">1 week</option>
					<option value="duein4">1+ week</option>
				</select>
				<div id="duein_arrow" class="arrow"></div>
			</div>
		</div>

		<ul id="btnpanel">
			<li class="panelbutton">
				<div id="household" class="typeimage">
					<img id="hh" src="map-icon/household.png" alt="Not Found" style="height: 40%; width: 40%;">
					<p class="typefont"> House</p>
				</div>
			</li>
			<li class="panelbutton">
				<div id="online" class="typeimage">
					<img id="ol" src="map-icon/online.png" alt="Not Found" style="height: 40%; width: 40%;">
					<p class="typefont"> Online </p>
				</div>
			</li>
			<li class="panelbutton">
				<div id="travel" class="typeimage">
					<img id="tra" src="map-icon/travel.png" alt="Not Found" style="height: 40%; width: 40%;">
					<p class="typefont">Travel </p>
				</div>
			</li>
			<li class="panelbutton">
				<div id="fixing" class="typeimage">
					<img id="fix" src="map-icon/fixing.png" alt="Not Found" style="height: 40%; width: 40%;">
					<p class="typefont"> Fixing </p>
				</div>
			</li>
			<li class="panelbutton">
				<div id="auto" class="typeimage">
					<img id="aut" src="map-icon/auto.png" alt="Not Found" style="height: 40%; width: 40%;">
					<p class="typefont"> Auto </p>
				</div>
			</li>
			<li class="panelbutton">
				<div id="nursing" class="typeimage">
					<img id="nur" src="map-icon/nursing.png" alt="Not Found" style="height: 40%; width: 40%;">
					<p class="typefont"> Nursing </p>
				</div>
			</li>
			<li class="panelbutton">
				<div id="other" class="typeimage">
					<img id="oth" src="map-icon/others.png" alt="Not Found" style="height: 40%; width: 40%;">
					<p class="typefont"> Other </p>
				</div>
			</li>
		</ul>
	</form>

	<div id="resultpanel">	
	</div>
</div>