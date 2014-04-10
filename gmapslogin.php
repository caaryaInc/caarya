<?php  


// Get parameters from URL
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];

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
$query = sprintf("SELECT jobID, employerID, jobType, jobName, jobAddress, jobDescription, jobAmt, dueDate, jobDuration, lat, lng,  ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM jobs HAVING distance < '100' ORDER BY distance LIMIT 20",
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat)
 );  

//  $query = "SELECT * FROM jobs ";

$result = mysql_query($query);
if (!$result) {
  die("Invalid query: " . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
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
  
} 

echo $dom->saveXML();
?>
