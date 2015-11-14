<?Php
session_start();
include 'include/config.php';
include "include/function.php";
date_default_timezone_set('UTC');
/* This page receives the inputs from the showmap form and fix the session for the FMAP session to recreate the map */
$task=$_REQUEST["task"];
$taski='';
$fixtitle="";
$search="";
if(!empty($task))
{
	// it's a from the normal input search fields from the showmap page
	if($task=="fileinfo")
	{
		$fileid = $_REQUEST["id"];
		//query for the map in genkml_v9.php
		$query = "select * from file_entries where fileid='$fileid' order by id";
	}
	else if($task=="agent")
	{
		$fileid = $_REQUEST["id"];
		$agent = $_REQUEST["name"];
		//query for the map in genkml_v9.php
		if($agent=="all")
			$query = "select * from file_entries where fileid='$fileid' order by id";
		else
			$query = "select * from file_entries where agent_code='$agent' and fileid='$fileid' order by id";
	}
	else if($task=="agent_searchbar")
	{
		$idx = $_REQUEST["id"];
		if($idx !="all")
		{
			$agent=$idx;
			//query for the map in genkml_v9.php
			$querys = "select * from file_entries where id=$idx";
		}
		else
		{
			$task="searchbar";
		}
	}
	else if($task=="agentadd_searchbar")
	{
		$idx = $_REQUEST["id"];
		if($idx !="all")
		{
			$agent=$idx;
			//query for the map in genkml_v9.php
			//$querys = "select * from file_entries where agent_code='$idx'";
			$fmap = $_SESSION["fmap"];
			if(!empty($fmap["datef"]))
				$datef=$fmap["datef"];
			else
				$datef="";
			$searchx = $_SESSION["searchin"];
			$querys = "select * from file_entries where (address1 like '%".$searchx."%' or address2 like '%".$searchx."%') and agent_code='$idx' $datef order by id";
		}
		else
			$task="searchbar";
	}
	else if($task=="agentdate_searchbar")
	{
		$idx = $_REQUEST["id"];
		if($idx !="all")
		{
			$agent=$idx;
			//query for the map in genkml_v9.php
			//$querys = "select * from file_entries where agent_code='$idx'";
			$fmap = $_SESSION["fmap"];
			if(!empty($fmap["datef"]))
				$datef=$fmap["datef"];
			else
				$datef="";
			$searchx = $_SESSION["searchin"];
			$querys = "select * from file_entries where date='".$searchx."' and agent_code='$idx' $datef order by id";
		}
		else
			$task="searchbar";
	}
	else if($task=="agentoffice_searchbar")
	{
		$idx = $_REQUEST["id"];
		if($idx !="all")
		{
			$agent=$idx;
			$fmap = $_SESSION["fmap"];
			$searchx = $_SESSION["searchin"];
			$querys = "select * from file_entries where ".$searchx." and agent_code='$idx' order by id";
		}
		else
			$task="searchbar";
	}
	else if($task=="agentdaterange_searchbar")
	{
		$idx = $_REQUEST["id"];
		if($idx !="all")
		{
			$agent=$idx;
			//query for the map in genkml_v9.php
			//$querys = "select * from file_entries where agent_code='$idx'";
			$fmap = $_SESSION["fmap"];
			if(!empty($fmap["datef"]))
				$datef=$fmap["datef"];
			else
				$datef="";
			$searcho = $_SESSION["searchin"];
			$searchx=explode(",",$searcho);
			$querys = "select * from file_entries where date >= '".$searchx[0]."' and date <= '".$searchx[1]."' and agent_code='".$idx."' order by id";
		}
		else
			$task="searchbar";
	}
	else
	{
		$daterange = $_REQUEST["daterange"];
		if($daterange=="yes")
		{
			$date1 = fixdate($_REQUEST["date1"]);
			$date2 = fixdate($_REQUEST["date2"]);
			$datef = " and (date >= '$date1' and date <= '$date2') ";
			$fixdatetitle = "<span style='font-size:15pt; font-style:italic'>(from: $date1 - to: $date2)</span>";
		}
		else
		{
			$datef="";
			$fixdatetitle="";
		}
		$type = $_REQUEST["type"]; //check the types
		if($type=="name")
		{
			$search = trim($_REQUEST["searchin"]);
			$taski='name';
			//query for the map in genkml_v9.php
			$query = "select * from file_entries where agent like '%".$search."%' $datef order by id";
			$fixtitle=$search." ".$fixdatetitle;
		}
		else if($type=="date")
		{
			$datec = fixdate($_REQUEST["datec"]);
			$search=$datec;
			$taski='date';
			//query for the map in genkml_v9.php
			$query = "select * from file_entries where date ='$datec' order by id";
			$fixtitle=$datec;
		}
		else if($type=="daterange")
		{
			$date1 = fixdate($_REQUEST["datea"]);
			$date2 = fixdate($_REQUEST["dateb"]);
			$search = $date1.",".$date2;
			$taski='daterange';
			//query for the map in genkml_v9.php
			$query = "select * from file_entries where date >= '$date1' and date <= '$date2' order by id";
			$fixtitle="Dated From $date1 to $date2";
		}
		else if($type=="fileentry")
		{
			$fileid = $_REQUEST["fileentry"];
			$search = $fileid;
			//query for the map in genkml_v9.php
			$query = "select * from file_entries where fileid='$fileid' order by id";
			//reset the task to show the default view in showmap
			$task="fileinfo";
		}
		else if($type=="office")
		{
			$fileid = "office='".$_REQUEST["offices"]."' and date='".fixdate($_REQUEST["dateo"])."'";
			$search = $fileid;
			//query for the map in genkml_v9.php
			$query = "select * from file_entries where $fileid order by id";
			//reset the task to show the default view in showmap
			$fixtitle="[Office:] ".$_REQUEST["offices"]." [on:] ".fixdate($_REQUEST["dateo"]);
			$taski='office';
			$task="office";
		}
		else
		{
			// it's a address type
			$search = trim($_REQUEST["searchin"]);
			$taski='address';
			//query for the map in genkml_v9.php
			$query = "select * from file_entries where (address1 like '%".$search."%' or address2 like '%".$search."%') $datef order by id"; 
			$fixtitle=$search." ".$fixdatetitle;
		}
	}
	//fix the session fmap;
	if(isset($_SESSION["fmap"]))
	{
		$dataret= $_SESSION["fmap"];
		unset($_SESSION["fmap"]);
		$dataret["task"]=$task;
		if(!empty($query))
			$dataret["query"]=$query;
		if(!empty($querys))
			$dataret["querys"]=$querys;
	}
	else
	{
		$dataret = array("fileid"=>'',"task"=>$task,'taski'=>'','query'=>$query,'querys'=>'','agent'=>'','title_in'=>'','datef'=>'','searchin_vet'=>'');
	}
	if(!empty($datef))
		$dateref["datef"]=$datef;
	if(!empty($search))
		$_SESSION["searchin"]=$search;
	if(!empty($fileid)) //fieldid should be filled with the id from fileinfo up there.
		$dataret["fileid"]=$fileid;
	if(!empty($taski)) //taski is for the search bar option in the shopmap page
		$dataret["taski"]=$taski;
	if($task=="agent") //to make sure that the current task purpose if to view an agent then we use this
		$dataret["agent"]=$_REQUEST["name"];
	if($task=="agent_searchbar" || $task=="agentadd_searchbar" || $task=="agentdate_searchbar" || $task=="agentdaterange_searchbar") //to make sure that the current task purpose if to view an agent then we use this
		$dataret["agent"]=$agent;
	if(!empty($fixtitle))
	{
		$_SESSION["titlesearch"]=$fixtitle;
	}
	$_SESSION["fmap"]=$dataret; //rewrite the session
}
header('location:showmap.php');
exit;
include 'include/unconfig.php';
?>