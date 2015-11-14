<?Php
session_start();
include 'include/config.php';
include "include/function.php";
adminlogin();
if(empty($_SERVER['HTTP_REFERER']))
{
	$_SESSION["fmapresult"]="ERROR:Invalid Access";
	header("location:home.php");
	exit;
}
require_once dirname(__FILE__) . '/include/excel/PHPExcel.php';
date_default_timezone_set('UTC');
$fileid=base64_decode($_REQUEST["fileid"]);
$reimport = $_REQUEST["reimport"];
$cxdatex = $_REQUEST["cxdatex"];
$filename = trim($_REQUEST["filename"]);
if($cxdatex=="yes")
{
	$datex = $_REQUEST["datex"];
	$xdate = fixdate($datex);
}
else
{
	$fdate = base64_decode($_REQUEST["fdate"]);
	if(!empty($fdate))
		$xdate =$fdate;
	else
		$xdate = date("Y-m-d");
}
if(empty($fileid))
{
	$_SESSION["mapresult"]="ERROR: Invalid Entry";
	header("location:showmap.php");
	exit;
}
if($reimport =="fclean")
{
	$ext = explode(".",$_FILES['file']['name']);
	//if import is required
	if($ext[1] != "xlsx")
	{
		$_SESSION["fmapresult"]="Invalid File Type";
		header("location:editimport.php?id=".base64_encode($fileid));
		exit;
	}
	else
	{
		//delete all previ entries
		$query = "delete from file_entries where fileid='".$fileid."'";
		@mysql_query($query);
	//read 2003 format
	//$objPHPExcel = new PHPExcel();
	//$objReader = new PHPExcel_Reader_Excel5();
	//$objReader->setReadDataOnly(true);
	//end of read 2003 format
	//read 2007 format
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objReader->setReadDataOnly(true);
	//end of read 2007 forma
	if(empty($filename))
	{
		$_SESSION["fmapresult"]="Provide A Name For File";
		header("location:editimport.php?id=".base64_encode($fileid));
		exit;
	}
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
	$entrysaved=0;
	$query = "update file_info set name='".clean($filename)."',date='$xdate' where id='".$fileid."'";
	if($result = mysql_query($query))
	{
		$entryid = $fileid;
		$totalentries = sizeof($user)-$def;
		for($i=$def;$i<(sizeof($user)+$def);$i++)
		{
			$skip =false;
			$mysqldate ="";
			$coords="";
			$saddress="";
			$eaddress="";
			if(!empty($user[$i]['E']) && !empty($user[$i]['F']) && !empty($user[$i]['G']) && !empty($user[$i]['H']))
			{
				$saddress=$user[$i]['E'].", ".$user[$i]['F']." ".$user[$i]['G']." ".$user[$i]['H'];
				$cord =getGEO($saddress);
				$lat = $cord['lat'];
				$lng = $cord['lng'];
				$coords .= $lng.",".$lat.",100 ";
			}
			if(!empty($user[$i]['I']) && !empty($user[$i]['J']) && !empty($user[$i]['K']) && !empty($user[$i]['L']))
			{
				$eaddress=$user[$i]['I'].", ".$user[$i]['J']." ".$user[$i]['K']." ".$user[$i]['L'];
				$cord =getGEO($eaddress);
				$lat = $cord['lat'];
				$lng = $cord['lng'];
				$coords .= $lng.",".$lat.",100 ";
			}
			//$mysqldate = date('Y-m-d', $user[$i]["G"]);
			//$phpdate = strtotime($mysqldate);
			@$phpdate = PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["A"], "YYYY-M-D");
			$found=false;
			if($user[$i]["N"]=="Column14" || $user[$i]["N"]=="" || empty($user[$i]["N"]))
				$totalg="0";
			else
				$totalg=$user[$i]["N"];
			if($user[$i]["O"]=="Column15" || $user[$i]["O"]=="" || empty($user[$i]["O"]))
				$totalp="0";
			else
				$totalp=$user[$i]["O"];
			if($user[$i]["M"]=="Column13" || $user[$i]["M"]=="" || empty($user[$i]["M"]))
				$totalgross="0";
			else
				$totalgross=$user[$i]["M"];
			if(!empty($saddress) && !empty($eaddress) && !empty($entryid) && !empty($coords) && !empty($user[$i]["B"]) && !empty($user[$i]["C"]) && !empty($phpdate) && !empty($user[$i]["D"]))
			{
				if($saddress =="Column5, Column6 Column7 Column8'" ||  $eaddress=="Column9, Column10 Column11 Column12" || $coords==",,100 ,,100"  || $user[$i]["B"]=="Column2" || $user[$i]["C"]=="COLUMN3" || $user[$i]["A"]=="Column1" || $user[$i]["D"]=="Column4")
					$skip = true;
				if(!$skip)
				{
					//if the entry is valid
					if(!empty($user[$i]["P"]))
						$query = "insert ignore into file_entries(fileid,address1,address2,coords,totalg,totalp,totalgross,agent,agent_code,date,manager,office)values('".$entryid."','".clean(ucwords(strtolower($saddress)))."','".clean(ucwords(strtolower($eaddress)))."','".$coords."','".$totalg."','".$totalp."','".$totalgross."','".ucwords(strtolower(clean($user[$i]["B"])))."','".strtoupper(clean($user[$i]["C"]))."','".$phpdate."','".clean(ucwords(strtolower($user[$i]["D"])))."','".clean($user[$i]["P"])."')";
					else
						$query = "insert ignore into file_entries(fileid,address1,address2,coords,totalg,totalp,totalgross,agent,agent_code,date,manager)values('".$entryid."','".clean(ucwords(strtolower($saddress)))."','".clean(ucwords(strtolower($eaddress)))."','".$coords."','".$totalg."','".$totalp."','".$totalgross."','".ucwords(strtolower(clean($user[$i]["B"])))."','".strtoupper(clean($user[$i]["C"]))."','".$phpdate."','".clean(ucwords(strtolower($user[$i]["D"])))."')";
					@mysql_query($query);
					$entrysaved++;
				}
			}
		}
		//echo "<br/>Total Entries: <u>$totalentries</u> Entryies Saved: <u>$entrysaved</u><br/>";
		if($entrysaved==0)
		{
			$queryx = "delete from file_info where id='".$entryid."'";
			@mysql_query($queryx);
			$_SESSION["mapresult"]="ERROR: Corrupted File, ALL Entries Deleted";
			header("location:showmap.php");
			exit;
		}
		else
		{
			if($totalentries != $entrysaved)
				$_SESSION["mapresult"]= "$entrysaved out of $totalentries Entries Saved. Invalid rows found and ignored";
			else
				$_SESSION["mapresult"]= "All Entries Saved";
		}
	}
	unset($_SESSION["fmap"]);
	unset($_SESSION["titlesearch"]);
	unset($_SESSION["prevlink"]);
	header("location:showmap.php");
	exit;
	}
}
else if($reimport =="fover")
{
	$ext = explode(".",$_FILES['fileov']['name']);
	//if import is required
	if($ext[1] != "xlsx")
	{
		$_SESSION["fmapresult"]="Invalid File Type";
		header("location:editimport.php?id=".base64_encode($fileid));
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
	//end of read 2007 forma
	if(empty($filename))
	{
		$_SESSION["fmapresult"]="Provide A Name For File";
		header("location:editimport.php?id=".base64_encode($fileid));
		exit;
	}
	$objPHPExcel = $objReader->load($_FILES['fileov']['tmp_name']);
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
	$entrysaved=0;
	$query = "update file_info set name='".clean($filename)."',date='$xdate' where id='".$fileid."'";
	if($result = mysql_query($query))
	{
		$entryid = $fileid;
		$totalentries = sizeof($user)-$def;
		for($i=$def;$i<(sizeof($user)+$def);$i++)
		{
			$skip =false;
			$mysqldate ="";
			$coords="";
			$saddress="";
			$eaddress="";
			if(!empty($user[$i]['E']) && !empty($user[$i]['F']) && !empty($user[$i]['G']) && !empty($user[$i]['H']))
			{
				$saddress=$user[$i]['E'].", ".$user[$i]['F']." ".$user[$i]['G']." ".$user[$i]['H'];
				$cord =getGEO($saddress);
				$lat = $cord['lat'];
				$lng = $cord['lng'];
				$coords .= $lng.",".$lat.",100 ";
			}
			if(!empty($user[$i]['I']) && !empty($user[$i]['J']) && !empty($user[$i]['K']) && !empty($user[$i]['L']))
			{
				$eaddress=$user[$i]['I'].", ".$user[$i]['J']." ".$user[$i]['K']." ".$user[$i]['L'];
				$cord =getGEO($eaddress);
				$lat = $cord['lat'];
				$lng = $cord['lng'];
				$coords .= $lng.",".$lat.",100 ";
			}
			//$mysqldate = date('Y-m-d', $user[$i]["G"]);
			//$phpdate = strtotime($mysqldate);
			@$phpdate = PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["A"], "YYYY-M-D");
			$found=false;
			if($user[$i]["N"]=="Column14" || $user[$i]["N"]=="" || empty($user[$i]["N"]))
				$totalg="0";
			else
				$totalg=$user[$i]["N"];
			if($user[$i]["O"]=="Column15" || $user[$i]["O"]=="" || empty($user[$i]["O"]))
				$totalp="0";
			else
				$totalp=$user[$i]["O"];
			if($user[$i]["M"]=="Column13" || $user[$i]["M"]=="" || empty($user[$i]["M"]))
				$totalgross="0";
			else
				$totalgross=$user[$i]["M"];
			if(!empty($saddress) && !empty($eaddress) && !empty($entryid) && !empty($coords) && !empty($user[$i]["B"]) && !empty($user[$i]["C"]) && !empty($phpdate) && !empty($user[$i]["D"]))
			{
				if($saddress =="Column5, Column6 Column7 Column8'" ||  $eaddress=="Column9, Column10 Column11 Column12" || $coords==",,100 ,,100"  || $user[$i]["B"]=="Column2" || $user[$i]["C"]=="COLUMN3" || $user[$i]["A"]=="Column1" || $user[$i]["D"]=="Column4")
					$skip = true;
				if(!$skip)
				{
					//if the entry is valid
					if(!empty($user[$i]["P"]))
						$query = "insert ignore into file_entries(fileid,address1,address2,coords,totalg,totalp,totalgross,agent,agent_code,date,manager,office)values('".$entryid."','".clean(ucwords(strtolower($saddress)))."','".clean(ucwords(strtolower($eaddress)))."','".$coords."','".$totalg."','".$totalp."','".$totalgross."','".ucwords(strtolower(clean($user[$i]["B"])))."','".strtoupper(clean($user[$i]["C"]))."','".$phpdate."','".clean(ucwords(strtolower($user[$i]["D"])))."','".clean($user[$i]["P"])."')";
					else
						$query = "insert ignore into file_entries(fileid,address1,address2,coords,totalg,totalp,totalgross,agent,agent_code,date,manager)values('".$entryid."','".clean(ucwords(strtolower($saddress)))."','".clean(ucwords(strtolower($eaddress)))."','".$coords."','".$totalg."','".$totalp."','".$totalgross."','".ucwords(strtolower(clean($user[$i]["B"])))."','".strtoupper(clean($user[$i]["C"]))."','".$phpdate."','".clean(ucwords(strtolower($user[$i]["D"])))."')";
					@mysql_query($query);
					$entrysaved++;
				}
			}
		}
		//echo "<br/>Total Entries: <u>$totalentries</u> Entryies Saved: <u>$entrysaved</u><br/>";
		if($entrysaved==0)
		{
			$_SESSION["mapresult"]="ERROR: Corrupted File, No New Entries Added";
			header("location:showmap.php");
			exit;
		}
		else
		{
			if($totalentries != $entrysaved)
				$_SESSION["mapresult"]= "$entrysaved out of $totalentries Entries Saved. Invalid rows found and ignored";
			else
				$_SESSION["mapresult"]= "All Entries Saved";
		}
	}
	unset($_SESSION["fmap"]);
	unset($_SESSION["titlesearch"]);
	unset($_SESSION["prevlink"]);
	header("location:showmap.php");
	exit;
	}
}
else
{
	//if no import is required
	if(empty($filename))
	{
		$_SESSION["fmapresult"]="Provide A Name For File";
		header("location:editimport.php?id=".base64_encode($fileid));
		exit;
	}
	$query = "update file_info set name='".clean($filename)."', date='".$xdate."' where id='".$fileid."'";
	if($result = mysql_query($query))
		$_SESSION["mapresult"]="SUCCESS: Changes Saved";
	else
		$_SESSION["mapresult"]="ERROR: Changes not Saved";
	unset($_SESSION["fmap"]);
	unset($_SESSION["titlesearch"]);
	unset($_SESSION["prevlink"]);
	header("location:showmap.php");
	exit;
}
include 'include/unconfig.php';
?>