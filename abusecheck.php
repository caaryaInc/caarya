<?php
$file = fopen ("abuse.txt", "r");
while (!feof ($file)) {
	$variable = fgets ($file);
	$variable = rtrim ($variable, "\r\n");
	if (strstr ($_GET["str"], $variable) != false) {
		echo "abuse";
	}
}
fclose ($file);
?>