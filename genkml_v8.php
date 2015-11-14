<?php
session_start();
$coords = $_SESSION["coords"];
require_once dirname(__FILE__) . '/include/excel/PHPExcel.php';
include "include/function.php";
$objPHPExcel = new PHPExcel();
$objReader = new PHPExcel_Reader_Excel5();
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load('address3.xls');
//$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);
//second try
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
$array_data = array();
foreach($rowIterator as $row){
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if(1 == $row->getRowIndex ()) continue;//skip first row
    $rowIndex = $row->getRowIndex();
	$array_data[$rowIndex] = array('A'=>'','B'=>'','C'=>'','D'=>'', 'E'=>'','F'=>'');
	foreach ($cellIterator as $cell) {
        if('A' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        } else if('B' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        } else if('C' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('D' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('E' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('F' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }
    }
}
$user = $array_data;
$def=2;
$coords="";
for($i=$def;$i<(sizeof($user)+$def);$i++)
{
	/*$lat = $user[$i]['D'];
	$lng = $user[$i]['E'];
	$coords .= $lng.",".$lat.",100 ";
	*/
	$cord =getGEO($user[$i]['C']);
	$lat = $cord['lat'];
	$lng = $cord['lng'];
	$coords .= $lng.",".$lat.",100 ";
	$cord =getGEO($user[$i]['D']);
	$lat = $cord['lat'];
	$lng = $cord['lng'];
	$coords .= $lng.",".$lat.",100 ";
}
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
$placeNode->setAttribute('id','linestring1');

//Create name, description, and address elements and assign them the values of 
//the name, type, and address columns from the results

$nameNode = $dom->createElement('name','My path');
$placeNode->appendChild($nameNode);
$descNode= $dom->createElement('description', 'This is the path that I took through my favorite restaurants in Seattle');
$placeNode->appendChild($descNode);

//Create a LineString element
$lineNode = $dom->createElement('LineString');
$placeNode->appendChild($lineNode);
$exnode = $dom->createElement('extrude', '1');
$lineNode->appendChild($exnode);
$almodenode =$dom->createElement('altitudeMode','relativeToGround');
$lineNode->appendChild($almodenode);

//Create a coordinates element and give it the value of the lng and lat columns from the results

$coorNode = $dom->createElement('coordinates',$coords);
$lineNode->appendChild($coorNode);
$kmlOutput = $dom->saveXML();

//assign the KML headers. 
header('Content-type: application/vnd.google-earth.kml+xml');
echo $kmlOutput;