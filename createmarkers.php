<?php 
// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

$jsonjobs = $_POST['jobdata'];
$jobs =  json_decode($jsonjobs);

header("Content-type: text/xml");
foreach($jobs as $job)
{
//	echo $job->jobid;
	$node = $dom->createElement("marker");
	$newnode = $parnode->appendChild($node);
	$newnode->setAttribute("jobID", $job->jobid);
	$newnode->setAttribute("Address", $job->jobAddress);
	$newnode->setAttribute("Type", $job->jobType);
	$newnode->setAttribute("Description", $job->jobDescription);
	$newnode->setAttribute("Name", $job->jobName);
	$newnode->setAttribute("Amount", $job->jobAmt);
	$newnode->setAttribute("lat", $job->lat);
	$newnode->setAttribute("lng", $job->lng);
	$newnode->setAttribute("duedate", $job->duedate);
	$newnode->setAttribute("jobduration", $job->jobduration);
	$newnode->setAttribute("status", $job->status);
	$newnode->setAttribute("distance", $job->distance);
	$newnode->setAttribute("name", $job->name); 
	$newnode->setAttribute("email", $job->email); 
	$newnode->setAttribute("url", $job->url);  
}
echo $dom->saveXML();
?>



