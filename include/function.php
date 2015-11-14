<?php
define("MAPS_HOST", "maps.google.com");
define("KEY", "ABQIAAAAUQoOcLjWVW04XTfLi1SbghRHDJMFrGd7U-5vIm6DVyt_Kv6o_BSNRkm6Jc5CUWvgHIeR0Q2uNVQ4Fw");
$showbutton = true;
$host = "http://www.familyenergymarketing.com/map/";
function checkMap2Access($id)
{
	//if($id =='1' || $id =='2' || $id =='3' || $id =='38' || $id =='22')
	if($id =='1' || $id =='2' || $id =='3' || $id =='38' || $id =='22')
		return true;
	else
		return false;
}
function checkNA($str)
{
	if(!empty($str) && $str !="" && $str !='0')
		return $str;
	else
		return "N/A";
}
function getSession()
{
	return "fmap_user";
}
function getSystemTitle()
{
	return "Family Energy Map System";
}
function getGHost()
{
	return "/map/";
}
function getHost()
{
	return "http://www.familyenergymarketing.com/map";
}
function getMapColor()
{
	$colors=array();
	$colors[]='#6495ED';
	$colors[]='#43edc7';
	$colors[]='#25b7d6';
	$colors[]='#d54325';
	$colors[]='#1b9784';
	$colors[]='#971b6c';
	$colors[]='#fcf8cb';
	$colors[]='#af217a';
	$colors[]='#f8cafc';
	$colors[]='#ed446a';
	$colors[]='#22af57';
	$colors[]='#5ee025';
	$colors[]='#25e0a7';
	$colors[]='#c489a5';
	$colors[]='#498910';
	$colors[]='#8ff0f6';
	$colors[]='#606a1c';
	$colors[]='#649143';
	$colors[]='#8a9142';
	$colors[]='#8a9381';
	return $colors;
}
function setSelected($str,$task)
{
	$selec="";
	if(trim(strtolower($str))==trim(strtolower($task)))
		$selec="selected='selected'";
	return $selec;
}
function getLastDay($cdate)
{
	$lday="";
	if(!empty($cdate))
	{
		$cdatex=explode("-",$cdate);
		$month=$cdatex[1];
		$year=$cdatex[0];
		date_default_timezone_set('America/New_York');
		$lday=date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime($month.'/01/'.$year.' 00:00:00'))));
	}
	return $lday;
}
function getFirstDay($cdate)
{
	$fday="";
	if(!empty($cdate))
	{
		$cdatex=explode("-",$cdate);
		$month=$cdatex[1];
		$year=$cdatex[0];
		$fday=$year."-".$month."-01";
	}
	return $fday;
}
function getMapSales($userid,$date_worked)
{
	$stotal=" N/A";
	if(!empty($userid) && !empty($date_worked))
	{
		//$id=getSalesAgent_byid($userid);
		$id=$userid;
		$user=$_SESSION["fmap_user"];
		$uid=base64_encode($user["id"]);
		$date_workedx=base64_encode($date_worked);
		$query="select sum(stotal) as stotal from sales_report where userid='".$id."' and fromdate='".$date_worked."'";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				if(!empty($info["stotal"]) && $info["stotal"]>0)
					$stotal="<a href=\'http://www.familyenergymarketing.com/salesreport/directaccess.php?uid=".$uid."&aid=".base64_encode($id)."&task=map&date_detail=".$date_workedx."\' target=\'_blank\'>".$info["stotal"]."</a>";
			}
		}
	}
	return $stotal;
}
function getMapSales_real($userid,$date_worked)
{
	$stotal="N/A";
	$id=getSalesAgent_byid($userid);
	if(!empty($id) && !empty($date_worked))
	{
		$query="select sum(xgas) as xgas,sum(xelec) as xelec from sales_report_real where userid='".$id."' and ddate='".$date_worked."'";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				if((!empty($info["xgas"]) && $info["xgas"]>0) || (!empty($info["xelec"]) && $info["xelec"]>0))
					$stotal="<a href=\'http://www.familyenergymarketing.com/salesreport/directaccess.php?uid=".base64_encode($userid)."&aid=".base64_encode($userid)."\'>Gas: ".$info["xgas"]."&nbsp; Electrical: ".$info["xelec"]."</a>";
			}
		}
	}
	return $stotal;
}
function getSalesAgent_byid($id)
{
	$idx="";
	$acode="";
	$query="select * from task_users where id='".clean($id)."' limit 1";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if(!empty($info["acode"]))
				$acode=trim($info["acode"]);
		}
	}
	if(!empty($acode))
	{
		$query="select * from sales_agent where acode='".clean($acode)."' limit 1";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
					$idx=$info["id"];
			}
		}
	}
	else
	{
		$query="select * from sales_agent where name='".clean(trim(stripslashes($info["name"])))."' limit 1";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
					$idx=$info["id"];
			}
		}
	}
	return $idx;	
}
function getSalesAgent_acode($acode)
{
	$id="";
	$query="select * from sales_agent where acode='".clean($acode)."' limit 1";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
				$id=$info["id"];
		}
	}
	return $id;
}
function getName($id)
{
	$namea="";
	$query="select * from task_users where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows=mysql_fetch_assoc($result);
			$namea=stripslashes($rows["name"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea="N/A";
	return $namea;
}
function pView($type)
{
	if($type !='1' && $type !='2' && $type !='4')
		return false;
	return true;
}
function getLink()
{
	$user=$_SESSION["fmap_user"];
	if(!detectAgent())
		return "mobile/";
	else
		return "";
}
function detectAgent()
{
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
	return false;
  return true;
}
function showChooseMan($type)
{
	if($type=="5" || $type=="6")
		return true;
	else
		return false;
}
function checkManSel($type,$office)
{
	if(($type=="5" || $type=="6") && empty($office))
	{
		return true;
	}
	else
		return false;
}
function checkReportTo($type)
{
	if($type=="5")
		return true;
	else
		return false;
}
function getIP()
{
	 return $_SERVER['REMOTE_ADDR'];
}
function rgetGEO($address)
{
	$api_key=KEY;
	$addr="";
	// format this string with the appropriate latitude longitude
	$url = "http://maps.google.com/maps/geo?q=".$address."&output=json&sensor=true_or_false&key=".$api_key;
	// make the HTTP request
	$data = @file_get_contents($url);
	// parse the json response
	$jsondata = json_decode($data,true);
	// if we get a placemark array and the status was good, get the addres
	if(is_array($jsondata )&& $jsondata ['Status']['code']==200)
		  $addr = $jsondata ['Placemark'][0]['address'];
	return $addr;
}
function getGEO($address){
	// Initialize delay in geocode speed
	$delay = 0;
	$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;

	// Iterate through the rows, geocoding each address
  $geocode_pending = true;

  while ($geocode_pending) {
    $request_url = $base_url . "&q=" . urlencode($address);
   $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = explode(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];
	  $values = array('lat'=>$lat,'lng'=>$lng);
 	  return $values;

    } else if (strcmp($status, "620") == 0) {
      // sent geocodes too fast
      $delay += 100000;
    } 
	else 
	{
      // failure to geocode
	  $geocode_pending = false;
	 	$values = array('lat'=>"",'lng'=>"");
  		return $values;
    }
    usleep($delay);
  }
}
function clean($str) 
{
	$str = trim($str);
	if(get_magic_quotes_gpc()) 
	{
		$str = stripslashes($str);
	}
	return mysql_real_escape_string($str);
}
function adminstatus($value)
{
	if($value !="1")
	{
		$_SESSION["loginresult"]="Your Account is currently Blocked";
		header("location:index.php");
		exit;
	}
}
function adminReject($value)
{
	if($value !='1' && $value !='2' && $value !='3' && $value !='4' && $value !='5' && $value !='6' && $value !='7' && $value !='8')
	{
		$_SESSION["loginresult"]="Not Enough Priviledge";
		header("location:index.php");
		exit;
	}
}
function adminMain($str)
{
	if($str !="5")
	{
		unset($_SESSION["fmap_user"]);
		unset($_SESSION["fmap"]);
		unset($_SESSION["titlesearch"]);
		unset($_SESSION["prevlink"]);
		unset($_SESSION["searchin"]);
		$_SESSION["loginresult"]="ACCESS DENIED: The Site is Under Maintenance";
		header("location:index.php");
		exit;
	}
}
function adminPrev($str)
{
	if($str =="1" || $str=="5")
		return true;
	return false;
}
function adminPrevb($str)
{
	if($str =="1" || $str=="2" || $str=="4")
		return true;
	return false;
}
function adminlogin()
{
	if(!isset($_SESSION["fmap_user"]))
	{
		//$_SESSION["loginresult"]="Illegal Access";
		unset($_SESSION["fmap_user"]);
		header("location:status.php");
		exit;
	}
	else
	{
		$user=$_SESSION["fmap_user"];
		//adminMain($user["type"]);
		if($user["status"] != "1")
		{
			unset($_SESSION["fmap_user"]);
			header("location:index.php");
			exit;
		}
	}
}
function adminloginx()
{
	$rlink=getLink();
	if(!isset($_SESSION["fmap_user"]))
	{
		//$_SESSION["loginresult"]="Illegal Access";
		unset($_SESSION["fmap_user"]);
		header("location:test2_login.php");
		exit;
	}
	else
	{
		$user=$_SESSION["fmap_user"];
		//adminMain($user["type"]);
		if($user["status"] != "1")
		{
			unset($_SESSION["fmap_user"]);
			header("location:test2_login.php");
			exit;
		}
	}
}
function convertUS($number)
{
	return number_format($number, 2, '.', ',');
}
function fixdate($str)
{
	if(!empty($str))
	{
		$exp = explode("-",$str);
		if(sizeof($exp)>2)
		{
			$y = $exp[0];
			$m = $exp[1];
			$d = $exp[2];
			if($m<10)
				$m = "0".$m;
			if($d<10)
				$d = "0".$d;
			$newdate = $y."-".$m."-".$d;
			return $newdate;
		}
	}
	return "";
}
function getStatus($value)
{
	$query = "select * from task_users_status where id='$value'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			return stripslashes($row["name"]);
		}
		else
			return "N/A";
	}
	else
		return "N/A";
}
function getPriv($value)
{
	$query = "select * from task_admin_type where id='$value'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			return stripslashes($row["name"]);
		}
		else
			return "N/A";
	}
	else
		return "N/A";
}
function getCoords($value)
{
	$coordss = $value;
	if(!empty($coordss))
	{
		$coorda=explode(",",$coordss);
		$cod = array("lat"=>$coorda[1],"lng"=>$coorda[0]);
	}
	else
		$cod=array("lat"=>"","lng"=>"");
	return $cod;
}
function checkMapAccess()
{
	$query = "select * from file_info";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$queryx = "select * from file_entries";
			if($resultx = mysql_query($queryx))
			{
				if(($num_rowsx = mysql_num_rows($resultx))>0)
					return true;
				else
					return false;
			}
			else
				return false;
		}
		else
			return false;
	}
	else
		return false;
}
function fixdate_comps($task,$value)
{
	date_default_timezone_set('UTC');
	$newtime ="";
	$value = strtotime($value);
	$double = false;
	$valuex = explode(" ",$value);
	if(sizeof($valuex)>1)
		$double=true;
	if(!empty($value) && !empty($task))
	{
		if($task=="h")
			$newtime =date("g:i a",$value);
		if($task=="ho")
			$newtime =date("h",$value);
		else if($task=="d")
			$newtime = date( "F j, Y",$value);
		else if($task=="mildate")
			$newtime = date( "Y-m-d",$value);
		else if($task=="weekday")
			$newtime = date('l',$value);
		else if($task=="mildatecomp")
			$newtime = date( "Y-m-d H:m:s",$value);
		else if($task=="m_text")
			$newtime = date( "F",$value);
		else if($task=="invdate_s")
			$newtime = date( "m/d/Y",$value);
		else if($task=="invdate")
		{
			if($double)
				$newtime = date( "d/m/Y g:i:a",$value);
			else
				$newtime = date( "d/m/Y",$value);
		}
		else if($task=="all")
			$newtime = date( "F j, Y  g:i a",$value);
		else
			$newtime="";
	}
	else
		$newtime="";
	return $newtime;
}
?>