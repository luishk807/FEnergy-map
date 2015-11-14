<?Php
session_start();
include 'include/config.php';
include "include/function.php";
require_once dirname(__FILE__) . '/include/excel/PHPExcel.php';
$ext = explode(".",$_FILES['file']['name']);
if($ext[1] != "xlsx")
{
	$_SESSION["fmapresult"]="Invalid File Type";
	header("location:import.php");
	exit;
}
else
{
//read 2003 format
//$objPHPExcel = new PHPExcel();
//$objReader = new PHPExcel_Reader_Excel5();
//$objReader->setReadDataOnly(true);
//end of read 2003 format
//read 2007 format
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
//end of read 2007 format
date_default_timezone_set('UTC');
$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);
//second try
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
$array_data = array();
foreach($rowIterator as $row){
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if(1 == $row->getRowIndex ()) continue;//skip first row
    $rowIndex = $row->getRowIndex();
	$array_data[$rowIndex] = array('A'=>'','B'=>'','C'=>'','D'=>'','E'=>'','F'=>'','G'=>'','H'=>'','I'=>'','J'=>'','K'=>'','L'=>'','N'=>'','O'=>'','P'=>'');
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
        }else if('G' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('H' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('I' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('J' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('K' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('L' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('M' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('N' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('O' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('P' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }
		
    }
}
$user = $array_data;
$def=2;
$query = "insert ignore into file_info(date)values(NOW())";
if($result = mysql_query($query))
{
	$entryid = mysql_insert_id();
	$datamap = array("fileid"=>$entryid,"task"=>"normal",'taski'=>'','query'=>'','agent'=>'','title_in'=>'','querys'=>'','datef'=>'','searchin_vet'=>'');
	unset($_SESSION["fmap"]);
	$_SESSION["fmap"]=$datamap;
	for($i=$def;$i<(sizeof($user)+$def);$i++)
	{
		$mysqldate ="";
		$coords="";
		$saddress="";
		$eaddress="";
		$saddress=$user[$i]['E'].", ".$user[$i]['F']." ".$user[$i]['G']." ".$user[$i]['H'];
		$cord =getGEO($saddress);
		$lat = $cord['lat'];
		$lng = $cord['lng'];
		$coords .= $lng.",".$lat.",100 ";
		$eaddress=$user[$i]['I'].", ".$user[$i]['J']." ".$user[$i]['K']." ".$user[$i]['L'];
		$cord =getGEO($eaddress);
		$lat = $cord['lat'];
		$lng = $cord['lng'];
		$coords .= $lng.",".$lat.",100 ";
		//$mysqldate = date('Y-m-d', $user[$i]["G"]);
		//$phpdate = strtotime($mysqldate);
		@$phpdate = PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["A"], "YYYY-M-D");
		if(!empty($user[$i]["P"]))
			$query = "insert ignore into file_entries(fileid,address1,address2,coords,totalg,totalp,totalgross,agent,agent_code,date,manager,office)values('".$entryid."','".clean(ucwords(strtolower($saddress)))."','".clean(ucwords(strtolower($eaddress)))."','".$coords."','".$user[$i]["N"]."','".$user[$i]["O"]."','".$user[$i]["M"]."','".ucwords(strtolower(clean($user[$i]["B"])))."','".strtoupper(clean($user[$i]["C"]))."','".$phpdate."','".clean(ucwords(strtolower($user[$i]["D"])))."','".clean($user[$i]["P"])."')";
		else
			$query = "insert ignore into file_entries(fileid,address1,address2,coords,totalg,totalp,totalgross,agent,agent_code,date,manager)values('".$entryid."','".clean(ucwords(strtolower($saddress)))."','".clean(ucwords(strtolower($eaddress)))."','".$coords."','".$user[$i]["N"]."','".$user[$i]["O"]."','".$user[$i]["M"]."','".ucwords(strtolower(clean($user[$i]["B"])))."','".strtoupper(clean($user[$i]["C"]))."','".$phpdate."','".clean(ucwords(strtolower($user[$i]["D"])))."')";
		mysql_query($query);
	}
}

header('location:showmap.php');
exit;
}
include 'include/unconfig.php';
?>