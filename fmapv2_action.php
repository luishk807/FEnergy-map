<?php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date('Y-m-d');
$date_work=$today;
$farray=$_REQUEST["farray"];
adminlogin();
$user=$_SESSION["fmap_user"];
$ip=getIP();
$parray=array($farray);
$rlink=getLink();
$task=$_REQUEST["task"];
if($task=="delete")
{
	$entryid=base64_decode($_REQUEST["id"]);
	if(!empty($entryid))
	{
		$query="delete from map_coords where entryid='".$entryid."'";
		if($result=mysql_query($query))
		{
			$qx="delete from map_entry where id='".$entryid."'";
			if($rx=mysql_query($qx))
				$_SESSION["fmapresult"]="SUCCESS: Map Deleted!";
			else
				$_SESSION["fmapresult"]="SUCCESS: Map Deleted Can't Be Completed!";
		}
		else
			$_SESSION["fmapresult"]="ERROR: Map can't be deleted!";
	}
	else
		$_SESSION["fmapresult"]="ERROR: Invalid Action!";
	header("location:".$rlink."showmap2.php");
	exit;
}
else
{
	foreach($parray as $getarray)
		$garray=explode(",",$getarray);
	if(sizeof($garray)>0)
	{
		if($task=="create" || $task=='tempo')
		{
			if($task=='create')
				$type=1;
			else if($task=='tempo')
				$type=2;
			$query="insert into map_entry(userid,date_worked,date,ip,type)values('".$user["id"]."','".$date_work."','".$today."','".$ip."','".$type."')";
			if($result=mysql_query($query))
			{
				$idx=mysql_insert_id();
				$count=0;
				for($i=0;$i<sizeof($garray);$i++)
				{
					$garrayx=explode(" ",$garray[$i]);
					$lat="";
					$lng="";
					if(sizeof($garrayx)>1)
					{
						$lat=$garrayx[0];
						$lng=$garrayx[1];
						$address=rgetGEO($lat.",".$lng);
					}
					if(!empty($lat) && !empty($lng) && !empty($address))
					{
						$qx="insert into map_coords(entryid,lat,lng,date,address)values('".$idx."','".$lat."','".$lng."','".$date_work."','".clean($address)."')";
						if($rx=mysql_query($qx))
							$count++;
					}
				}
				if($count==sizeof($garray))
				{
					$_SESSION["fmapresult"]="SUCCESS: Area Saved!";
					header("location:".$rlink."showmap2_edit.php?id=".base64_encode($idx));
					exit;
				}
				else
				{
					$_SESSION["fmapresult"]="ERROR: One or more address can't be saved";
					$qx="delete from map_coords where entryid='".$idx."'";
					if($rx=mysql_query($qx))
					{
						$qxx="delete from map_entry where id='".$idx."'";
						if($rxx=mysql_query($qxx))
							$_SESSION["fmapresult"]="ERROR: One or more address can't be saved, entry deleted!";
					}
				}
			}
		}
		else if($task=="save")
		{
			$entryid=base64_decode($_REQUEST["id"]);
			if(empty($entryid))
			{
				$_SESSION["fmapresult"]="ERROR: Invalid Entry!";
				header("location:".$rlink."showmap2.php");
				exit;
			}
			$query="select * from map_entry where id='".$entryid."'";
			if($result=mysql_query($query))
			{
				if(($num_rows=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$qx="delete from map_coords where entryid='".$entryid."'";
					if(!$rx=mysql_query($qx))
					{
						$_SESSION["fmapresult"]="ERROR: Unable To Proceed";
						header("location:".$rlink."showmap2.php");
						exit;
					}
					$count=0;
					for($i=0;$i<sizeof($garray);$i++)
					{
						$garrayx=explode(" ",$garray[$i]);
						$lat="";
						$lng="";
						if(sizeof($garrayx)>1)
						{
							$lat=$garrayx[0];
							$lng=$garrayx[1];
							$address=rgetGEO($lat.",".$lng);
						}
						if(!empty($lat) && !empty($lng) && !empty($address))
						{
							$qx="insert into map_coords(entryid,lat,lng,date,address)values('".$entryid."','".$lat."','".$lng."','".$date_work."','".clean($address)."')";
							if($rx=mysql_query($qx))
								$count++;
						}
					}
					if($count==sizeof($garray))
					{
						$_SESSION["fmapresult"]="SUCCESS: Area Saved!";
						header("location:".$rlink."showmap2_edit.php?id=".base64_encode($entryid));
						exit;
					}
					else
					{
						$_SESSION["fmapresult"]="ERROR: One or more address can't be saved";
						$qx="delete from map_coords where entryid='".$entryid."'";
						if($rx=mysql_query($qx))
						{
							$qxx="delete from map_entry where id='".$entryid."'";
							if($rxx=mysql_query($qxx))
								$_SESSION["fmapresult"]="ERROR: One or more address can't be saved, entry deleted!";
						}
					}
				}
			}
			else
				$_SESSION["fmapresult"]="ERROR: Invalid Entry!";
		}
		else if($task=="predefined")
		{
			$entryid=base64_decode($_REQUEST["id"]);
			if(empty($entryid))
			{
				$_SESSION["fmapresult"]="ERROR: Invalid Entry!";
				header("location:".$rlink."showmap2.php");
				exit;
			}
			$query="select * from map_entry where id='".$entryid."'";
			if($result=mysql_query($query))
			{
				if(($num_rows=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$qx="delete from map_coords where entryid='".$entryid."'";
					if(!$rx=mysql_query($qx))
					{
						$_SESSION["fmapresult"]="ERROR: Unable To Proceed";
						header("location:".$rlink."showmap2.php");
						exit;
					}
					$count=0;
					for($i=0;$i<sizeof($garray);$i++)
					{
						$garrayx=explode(" ",$garray[$i]);
						$lat="";
						$lng="";
						if(sizeof($garrayx)>1)
						{
							$lat=$garrayx[0];
							$lng=$garrayx[1];
							$address=rgetGEO($lat.",".$lng);
						}
						if(!empty($lat) && !empty($lng) && !empty($address))
						{
							$qx="insert into map_coords(entryid,lat,lng,date,address)values('".$entryid."','".$lat."','".$lng."','".$date_work."','".clean($address)."')";
							if($rx=mysql_query($qx))
								$count++;
						}
					}
					if($count==sizeof($garray))
					{
						$qx="update map_entry set type='1' where id='".$entryid."'";
						if(!$rx=mysql_query($qx))
						{
							$_SESSION["fmapresult"]="ERROR: Unable To Proceed";
							header("location:".$rlink."showmap2.php");
							exit;
						}
						$_SESSION["fmapresult"]="SUCCESS: Area Saved as Worked!";
						header("location:".$rlink."showmap2.php?id=".base64_encode($entryid));
						exit;
					}
					else
					{
						$_SESSION["fmapresult"]="ERROR: One or more address can't be saved";
						$qx="delete from map_coords where entryid='".$entryid."'";
						if($rx=mysql_query($qx))
						{
							$qxx="delete from map_entry where id='".$entryid."'";
							if($rxx=mysql_query($qxx))
								$_SESSION["fmapresult"]="ERROR: One or more address can't be saved, entry deleted!";
						}
					}
				}
			}
			else
				$_SESSION["fmapresult"]="ERROR: Invalid Entry!";
		}
		else
			$_SESSION["fmapresult"]="ERROR: Invalid Entry!";
	}
	else
		$_SESSION["fmapresult"]="ERROR: No Addresese Found";
}
header("location:".$rlink."showmap2.php");
exit;
include "include/unconfig.php";
?>