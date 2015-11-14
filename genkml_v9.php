<?php
session_start();
include "include/config.php";
include "include/function.php";
//makes the coordinates for map
//if a map session is created and avaliable don't use the following
if(!isset($_SESSION["fmap"]))
{
	$query = "select * from file_info order by date desc limit 1";
	$coords="";
	if($result = mysql_query($query))
	{
		$rowdate = mysql_fetch_assoc($result);
		$filedate = $rowdate["date"];
		if(!empty($rowdate["date"]))
		{
			$query = "select * from file_entries where fileid='".$rowdate["id"]."' order by date desc";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
						$coords .=$rows["coords"]." ";
				}
			}
		}
	}
	$desc="";
}
else
{
	//if this it's set, then use this format
	$datamap =$_SESSION["fmap"];
	$query = $datamap["query"];
	$coords="";
	if($datamap["task"]=="fileinfo" || $datamap["task"]=="agent" || $datamap["task"]=="searchbar")
	{
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				while($rows = mysql_fetch_array($result))
					$coords .=$rows["coords"]." ";
			}
		}
	}
	else if($datamap["task"]=="agent_searchbar" || $datamap["tasK"]=="agentadd_searchbar")
	{ 
		$querys = $datamap["querys"];
		if($result = mysql_query($querys))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				while($rows = mysql_fetch_array($result))
					$coords .=$rows["coords"]." ";
			}
		}
	}
	else
	{
		$query = "select * from file_info order by date desc limit 1";
		$coords="";
		if($result = mysql_query($query))
		{
			$rowdate = mysql_fetch_assoc($result);
			$filedate = $rowdate["date"];
			if(!empty($rowdate["date"]))
			{
				$query = "select * from file_entries where fileid='".$rowdate["id"]."' order by date desc";
				if($result = mysql_query($query))
				{
					if(($num_rows = mysql_num_rows($result))>0)
					{
						while($rows = mysql_fetch_array($result))
							$coords .=$rows["coords"]." ";
					}
				}
			}
		}
	}
	$desc="";
}
//$coords = "-73.7970610,40.6864680,100 -73.8112869,40.7015990,100";
// Start KML file, create parent node
$dom = new DOMDocument('1.0','UTF-8');

//Create the root KML element and append it to the Document
$node = $dom->createElementNS('http://earth.google.com/kml/2.1','kml');
$parNode = $dom->appendChild($node);

//Create a Folder element and append it to the KML element
$fnode = $dom->createElement('Folder');
$folderNode = $parNode->appendChild($fnode);

//Create a Placemark and append it to the document
$node = $dom->createElement('Placemark');
$placeNode = $folderNode->appendChild($node);

//Create an id attribute and assign it the value of id column
$placeNode->setAttribute('id','linestring');

//Create name, description, and address elements and assign them the values of 
//the name, type, and address columns from the results

$nameNode = $dom->createElement('name','My path');
$placeNode->appendChild($nameNode);
$descNode= $dom->createElement('description', $desc);
$placeNode->appendChild($descNode);

//Create a LineString element
$lineNode = $dom->createElement('LineString');
$placeNode->appendChild($lineNode);
$exnode = $dom->createElement('extrude', '1');
$lineNode->appendChild($exnode);
$tesnode = $dom->createElement('tessellate', '1');
$lineNode->appendChild($tesnode);
$almodenode =$dom->createElement('altitudeMode','relativeToGround');
$lineNode->appendChild($almodenode);

//Create a coordinates element and give it the value of the lng and lat columns from the results

$coorNode = $dom->createElement('coordinates',$coords);
$lineNode->appendChild($coorNode);
$kmlOutput = $dom->saveXML();

//assign the KML headers. 
header('Content-type: application/vnd.google-earth.kml+xml');
echo $kmlOutput;
include "include/unconfig.php";
?>