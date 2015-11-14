<?php
//adminloginx();
$user=$_SESSION["fmap_user"];
$today=date('Y-m-d');
$mapentry=array();
$coords=array();
$date1_get=$_REQUEST["date1"];
$date2_get=$_REQUEST["date2"];
$man_sel=$_REQUEST["man_sel"];
$xshow_area=$_REQUEST["xshow_area"];
$xshow_area_sel="";
$xshow_areaq="";
if(!empty($xshow_area) && $xshow_area !='na')
{
	$xshow_area_sel=$xshow_area; 
	if($xshow_area !='all')
		$xshow_areaq=" and type='".$xshow_area."'";
}
$man_q=""; //for the map_coords
$man_q_s=""; //for the map_entry
if(!empty($man_sel))
{
	if($man_sel !='all')
	{
		$man_sel=base64_decode($man_sel);
		if(empty($man_sel))
			$man_sel="";
		else
		{
			$man_q_s=" and userid='".$man_sel."' ";
			/*$query="select * from map_entry where userid='".$man_sel."'";
			if($result=mysql_query($query))
			{
				if(($num_rows=mysql_num_rows($result))>0)
				{
					$rows_id="";
					while($rows=mysql_fetch_array($result))
					{
						if(empty($rows_id))
							$rows_id="'".$rows["id"]."'";
						else
							$rows_id=",'".$rows["id"]."'";
					}
					if(!empty($rows_id))
						$man_q=" and entryid in (".$rows_id.") ";
				}
			}*/
		}
	}
	else
		$man_sel="";
}
if(!empty($date1_get) && !empty($date2_get))
{
	$date1x=fixdate_comps('invdate_s',$date1_get);
	$date2x=fixdate_comps('invdate_s',$date2_get);
	$date1=fixdate_comps('mildate',$date1_get);
	$date2=fixdate_comps('mildate',$date2_get);
}
if(empty($date1) || empty($date2))
{
	$date1x=fixdate_comps('invdate_s',getFirstDay($today));
	$date2x=fixdate_comps('invdate_s',getLastDay($today));
	$date1=fixdate_comps('mildate',$date1x);
	$date2=fixdate_comps('mildate',$date2x);
}
/**************COLOR FOR THE LINES************************************************/
//this is the use to color the graph lines, ave number of team leader and manager is 8 but to play safe i used 20
$colorsc=array();//color choosen
$colors=getMapColor();
/**************END OF COLOR FOR THE LINES************************************************/
$xadd=$_REQUEST["xadd"];
$xfollow=true;
if(!empty($xadd))
{
	$xaddx=getGEO($xadd);
	$xlat=$xaddx["lat"];
	$xlng=$xaddx["lng"];
	if(!empty($xlat) && !empty($xlng))
		$xlatlng_q=" or (lat='".$xlat."' or lng='".$xlng."')";
	$mapid=array();
	$donq=false;
	$query="select * from map_coords where date between '".$date1."' and '".$date2."' and (address like '%".$xadd."%' $xlatlng_q )";
	//echo $query;
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
			{
				if(sizeof($mapid) <1)
					$mapid[]=$rows["entryid"];
				else
				{
					$found=false;
					for($i=0;$i<sizeof($mapid);$i++)
					{
						if($mapid[$i]==$rows["entryid"])
						{
							$found=true;
							break;
						}
					}
					if(!$found)
						$mapid[]=$rows["entryid"];
				}
			}
		}
		else
			$donq=true;
	}
	else
		$donq=true;
    if($donq)
	{
		$query="SELECT address, entryid, date, lat, lng, ( 3959 * acos( cos( radians('".$xlat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$xlng."') ) + sin( radians('".$xlat."') ) * sin( radians( lat ) ) ) ) AS distance FROM map_coords HAVING distance < '1' where date between '".$date1."' and '".$date2."' ORDER BY distance LIMIT 0 , 20";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				while($rows=mysql_fetch_array($result))
				{
					if(sizeof($mapid) <1)
						$mapid[]=$rows["entryid"];
					else
					{
						$found=false;
						for($i=0;$i<sizeof($mapid);$i++)
						{
							if($mapid[$i]==$rows["entryid"])
							{
								$found=true;
								break;
							}
						}
						if(!$found)
							$mapid[]=$rows["entryid"];
					}
				}
			}
		}
	}
	if(sizeof($mapid)>0)
	{
		for($o=0;$o<sizeof($mapid);$o++)
		{
			$map_id="";
			$query="select * from map_entry where id='".$mapid[$o]."' $man_q_s $xshow_areaq ";
			if($result=mysql_query($query))
			{
				if(($num_rows=mysql_num_rows($result))>0)
				{
					$rows=mysql_fetch_assoc($result);
					$map_id=$rows["id"];
				}
			}
			if(!empty($map_id))
			{
			$c_color="";
			$coordx=array();
			$qx="select * from map_coords where entryid='".$rows["id"]."' order by id";
			if($rx=mysql_query($qx))
			{
				if(($numx=mysql_num_rows($rx))>0)
				{
					while($rxx=mysql_fetch_array($rx))
						$coordx[]=array('id'=>$rxx["id"],'entryid'=>$rxx["entryid"],'date'=>$rxx['date'],'lat'=>$rxx["lat"],'lng'=>$rxx["lng"],'address'=>stripslashes($rxx["address"]));
				}
			}
			if(sizeof($colorsc)<1)
			{
				$colorsc[]=array('id'=>$rows["userid"],'color'=>$colors[0]);
				$c_color=$colors[0];
			}
			else
			{
				$xdo=true;
				//check if color is already set to this user
				for($i=0;$i<sizeof($colorsc);$i++)
				{
					if($colorsc[$i]["id"]==$rows["userid"])
					{
						$c_color=$colorsc[$i]["color"];
						$xdo=false;
						break;
					}
				}
				//if color is not set yet for this user
				if($xdo)
				{
					for($i=0;$i<sizeof($colors);$i++)
					{
						$c_color=$colors[$i];
						$found=false;
						for($y=0;$y<sizeof($colorsc);$y++)
						{
							if($colorsc[$y]["color"] !=$c_color)
							{
								$found=true;
								break;
							}
						}
						if($found)
						{
							$colorsc[]=array('id'=>$rows["userid"],'color'=>$c_color);
							break;
						}
					}
				}
			}
			$mapentry[]=array('id'=>$rows["id"],'userid'=>$rows["userid"],'date_worked'=>$rows['date_worked'],'date'=>$rows["date"],'coords'=>$coordx,'color'=>$c_color,'type'=>$rows["type"]);
			}
		}
	}
	if(sizeof($mapentry)<1)
	{
		if(!empty($xshow_area) && $xshow_area !='na')
			$_SESSION["fmapresult"]="ERROR: No Map Found Under This Address";
	}
	else
		$xfollow=false;
}
if($xfollow)
{
	$query="select * from map_entry where date_worked between '".$date1."' and '".$date2."' $man_q_s $xshow_areaq order by id";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
			{
				$c_color="";
				$coordx=array();
				$qx="select * from map_coords where entryid='".$rows["id"]."' order by id";
				if($rx=mysql_query($qx))
				{
					if(($numx=mysql_num_rows($rx))>0)
					{
						while($rxx=mysql_fetch_array($rx))
							$coordx[]=array('id'=>$rxx["id"],'entryid'=>$rxx["entryid"],'date'=>$rxx['date'],'lat'=>$rxx["lat"],'lng'=>$rxx["lng"],'address'=>stripslashes($rxx["address"]));
					}
				}
				if(sizeof($colorsc)<1)
				{
					$colorsc[]=array('id'=>$rows["userid"],'color'=>$colors[0]);
					$c_color=$colors[0];
				}
				else
				{
					$xdo=true;
					//check if color is already set to this user
					for($i=0;$i<sizeof($colorsc);$i++)
					{
						if($colorsc[$i]["id"]==$rows["userid"])
						{
							$c_color=$colorsc[$i]["color"];
							$xdo=false;
							break;
						}
					}
					//if color is not set yet for this user
					if($xdo)
					{
						for($i=0;$i<sizeof($colors);$i++)
						{
							$c_color=$colors[$i];
							$found=false;
							for($y=0;$y<sizeof($colorsc);$y++)
							{
								if($colorsc[$y]["color"] !=$c_color)
								{
									$found=true;
									break;
								}
							}
							if($found)
							{
								$colorsc[]=array('id'=>$rows["userid"],'color'=>$c_color);
								break;
							}
						}
					}
				}
				$mapentry[]=array('id'=>$rows["id"],'userid'=>$rows["userid"],'date_worked'=>$rows['date_worked'],'date'=>$rows["date"],'coords'=>$coordx,'color'=>$c_color,'type'=>$rows["type"]);
			}
		}
	}
	if(sizeof($mapentry)<1)
	{
		if(!empty($xshow_area) && $xshow_area !='na')
			$_SESSION["fmapresult"]="ERROR: Empty Entry Detected For ".fixdate_comps('invdate_s',$date1)." and ".fixdate_comps('invdate_s',$date2)."!";
		//header("location:test3.php");
		//exit;
		$startpoint="40.714623,-74.006605";
	}
}
?>