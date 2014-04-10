<?php  
// Get parameters from URL

function highlightkeyword($str, $search) {
	$highlightcolor = "red";
    $occurrences = @substr_count(strtolower($str), strtolower($search));
    $newstring = $str;
    $match = array();
    for ($i=0;$i<$occurrences;$i++) {
		echo $match[$i];
        $match[$i] = stripos($str, $search, $i);
        $match[$i] = substr($str, $match[$i], strlen($search));
        $newstring = str_replace($match[$i], '[#]'.$match[$i].'[@]', strip_tags($newstring));
    }
 
    $newstring = str_replace('[#]', '<span style="color: '.$highlightcolor.';">', $newstring);
    $newstring = str_replace('[@]', '</span>', $newstring);
    return $newstring;
}

$min_lat = $_GET["min_lat"];
$max_lat = $_GET["max_lat"];
if($min_lat>$max_lat)
	$min_lat = -$min_lat;

$min_lng = $_GET["min_lng"];
$max_lng = $_GET["max_lng"];
if($min_lng>$max_lng)
	$min_lng = -$min_lng;

$pn = $_GET['hh'] + $_GET['ol'] + $_GET['tra'] + $_GET['fix'] + $_GET['aut'] + $_GET['nur'] + $_GET['oth'];
if($pn != 0 || $_GET['pay'] != "pay")
	$req .= " AND "; 

if($_GET['hh'] == 1) {
	$req .= "( jobType='household'";
	$_GET['hh']=0;
}
else if($_GET['ol'] == 1) {
	$req .= "( jobType='online'";
	$_GET['ol']=0;
}
else if($_GET['tra'] == 1) {
	$req .= "( jobType='travel'";
	$_GET['tra']=0;
}
else if($_GET['fix'] == 1) {
	$req .= "( jobType='fixing'";
	$_GET['fix']=0;
}
else if($_GET['aut'] == 1) {
	$req .= "( jobType='auto'";
	$_GET['aut']=0;
}
else if($_GET['nur'] == 1) {
	$req .= "( jobType='nursing'";
	$_GET['nur']=0;
}
else if($_GET['oth'] == 1) {
	$req .= "( jobType='other'";
	$_GET['oth']=0;
}
if($_GET['ol'] == 1)
	$req .= " OR jobType='online'";
if($_GET['tra'] == 1)
	$req .= " OR jobType='travel'";
if($_GET['fix'] == 1)
	$req .= " OR jobType='fixing'";
if($_GET['aut'] == 1)
	$req .= " OR jobType='auto'";
if($_GET['nur'] == 1)
	$req .= " OR jobType='nursing'";
if($_GET['oth'] == 1)
	$req .= " OR jobType='other'";

if($pn != 0)
	$req .= " ) ";

if($pn != 0 && $_GET['pay'] != "pay")
	$req .= " AND ";
if($_GET['pay'] == "pay1")
	$req .= "jobAmt > 10";
else if($_GET['pay'] == "pay2")
	$req .= "jobAmt > 50";
else if($_GET['pay'] == "pay3")
	$req .= "jobAmt > 100";
	$date1 = strtotime("now");

if($_GET['duein'] == "duein1")
	$date1 = strtotime("+2 days");
else if($_GET['duein'] == "duein2")
	$date1 = strtotime("+3 days");
else if($_GET['duein'] == "duein3")
	$date1 = strtotime("+1 week");
else if($_GET['duein'] == "duein4")
	$date1 = strtotime("+10 weeks");
/*
	else
		$date1 = strtotime("+99999 weeks");
*/

$comp = 360 / ( 2 * 3.14159265359 * 3959);
if($_GET['distance'] == "distance1")
	$comp *= 2;
else if($_GET['distance'] == "distance2")
	$comp *= 5;
else if($_GET['distance'] == "distance3")
	$comp *= 10;
else if($_GET['distance'] == "distance4")
	$comp *= 20;
else if($_GET['distance'] == "distance5")
	$comp *= 50;
else
	$comp *= 99999;

$comp = pow($comp, 2);

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a mySQL server
//$connection=mysql_connect ('localhost', 'caarya5', 'topgear123!');
$connection=mysql_connect (localhost, 'root', 'root');
if (!$connection) {
  die("Not connected : " . mysql_error());
}

// Set the active mySQL database
//$db_selected = mysql_select_db('caarya5_db', $connection);
$db_selected = mysql_select_db('dailychores', $connection);
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}


// Search the rows in the markers table

//echo $req;

$query = "select * from jobs where lat > ".$min_lat." AND lat <".$max_lat." AND lng >".$min_lng." AND lng <".$max_lng.$req;
//$query = sprintf("Select * from jobs");   

//echo $query;


$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}


header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){

	$dlon = floatval($row['lng'])-floatval($_GET['lon']);
	if($dlon > 180)
		$dlon -= 180;
	else if($dlon < -180)
		$dlon += 180;
	$comp2 = pow(floatval($row['lat'])-floatval($_GET['lat']), 2) + pow($dlon, 2);
	$duedate = strtotime($row['dueDate']);
	if ((strlen($_GET['searchtext']) == 0 || stripos($row['jobAddress'], $_GET['searchtext']) !== FALSE || 
		 stripos($row['jobName'], $_GET['searchtext']) !== FALSE )&& $comp2 < $comp) {
		
		$highlightedName= highlightkeyword($row['jobName'], $_GET['searchtext']);
		$highlightedAddress= highlightkeyword($row['jobAddress'], $_GET['searchtext']);
		
		$node = $dom->createElement("marker");
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("jobID", $row['jobID']);
		$newnode->setAttribute("Address", $row['jobAddress']);
		$newnode->setAttribute("Type", $row['jobType']);
		$newnode->setAttribute("Description", $row['jobDescription']);
		$newnode->setAttribute("Name", $row['jobName']);
		$newnode->setAttribute("Amount", $row['jobAmt']);
		$newnode->setAttribute("lat", $row['lat']);
		$newnode->setAttribute("lng", $row['lng']);
		$newnode->setAttribute("duedate", $row['dueDate']);
		$newnode->setAttribute("jobduration", $row['jobDuration']);
		$newnode->setAttribute("status", $row['jobStatus']);
		$newnode->setAttribute("distance", $row['distance']);
		 // For that user ID also retrieve user name and profile picture and save it as XML 
		$uname = $row['employerID'];
		$query2 =  "Select name, email, profileurllocation from user where userid= $uname ";
		$result2=   mysql_query($query2); 
		$row = @mysql_fetch_assoc($result2);
		$newnode->setAttribute("name", $row['name']); 
		$newnode->setAttribute("email", $row['email']); 
		$newnode->setAttribute("url", $row['profileurllocation']);  
		$newnode->setAttribute("email", $row['email']); 
		$newnode->setAttribute("url", $row['profileurllocation']); 
		$newnode->setAttribute("hname", $highlightedName); 
		$newnode->setAttribute("haddress", $highlightedAddress); 
	
	}

} 

echo $dom->saveXML();

?>