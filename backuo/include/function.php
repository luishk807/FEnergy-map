<?php
define("MAPS_HOST", "maps.google.com");
define("KEY", "ABQIAAAAUQoOcLjWVW04XTfLi1SbghRHDJMFrGd7U-5vIm6DVyt_Kv6o_BSNRkm6Jc5CUWvgHIeR0Q2uNVQ4Fw");
 $showbutton = true;
function getIP()
{
	 return $_SERVER['REMOTE_ADDR'];
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
?>