<?php
session_start();
include 'include/config.php';
include "include/function.php";
@$prevlink = $_SERVER['PHP_SELF'];
if(empty($prevlink))
	$prevlink="index.php";
$checki = $_SESSION["fmap"];
$_SESSION["prevlink"]=$prevlink;
adminlogin();
$showmainbutton = true;
$showdiv=false;
$default_div=true;
$default_found=true;
$agents= array();
$totalg=0;
$totalp=0;
$totalgrand=0;
$getcoords=array();
if(isset($_SESSION["fmap"]))
{
	$fmap = $_SESSION["fmap"];
	if(!empty($fmap))
	{
		$fileid =$fmap["fileid"];
		$taskcheck = $fmap["task"];
		$titlefield = $fmap["title_in"];
		echo $titlefield;
		if($taskcheck=="normal")
		{
			$fileid =$fmap["fileid"];
			$query = "select * from file_entries where fileid='".$fileid."' order by agent";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if(sizeof($agents)>0)
						{
							for($i=0;$i<sizeof($agents);$i++)
							{
								if(trim($agents[$i]["code"])==stripslashes($rows["agent_code"]))
									$found=true;
							}
							if(!$found)
							{
								$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}
						}
						else
						{
							$ag = array("name"=>$rows["agent"],"code"=>$rows["agent_code"]);
							$agents[]=$ag;
						}
						$totalg +=$rows["totalg"];
						$totalp +=$rows["totalp"];
						$totalgrand +=$rows["totalgross"];
					}
				}
				else
					$default_found=false;
			}
			else
				$default_found = false;
			$showdiv=false;
		}
		else if($taskcheck=="agent")
		{
			//no file type set
			$fileid =$fmap["fileid"];
			$query = "select * from file_entries where fileid='".$fileid."' order by agent";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if(sizeof($agents)>0)
						{
							for($i=0;$i<sizeof($agents);$i++)
							{
								if(trim($agents[$i]["code"])==stripslashes($rows["agent_code"]))
									$found=true;
							}
							if(!$found)
							{
								$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}
						}
						else
						{
							$ag = array("name"=>$rows["agent"],"code"=>$rows["agent_code"]);
							$agents[]=$ag;
						}
						$totalg +=$rows["totalg"];
						$totalp +=$rows["totalp"];
						$totalgrand +=$rows["totalgross"];
					}
				}
				else
					$default_found =false;
			}
			else
				$default_found=false;
			if($default_found)
			{
				$agentcode = $fmap["agent"];
				if($agentcode=="all")
					$showdiv=false;
				else
					$showdiv=true;
			}
			else
				$showdiv=false;
		}
		else if($taskcheck=="fileinfo")
		{
			//no file type set
			$query = $fmap["query"];
			$fileid =$fmap["fileid"];
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if(sizeof($agents)>0)
						{
							for($i=0;$i<sizeof($agents);$i++)
							{
								if(trim($agents[$i]["code"])==stripslashes($rows["agent_code"]))
									$found=true;
							}
							if(!$found)
							{
								$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}
						}
						else
						{
							$ag = array("name"=>$rows["agent"],"code"=>$rows["agent_code"]);
							$agents[]=$ag;
						}
						$totalg +=$rows["totalg"];
						$totalp +=$rows["totalp"];
						$totalgrand +=$rows["totalgross"];
					}
				}
				else
					$default_found=false;
			}
			else
				$default_found=false;
			$showdiv=false;
		}
		else if($taskcheck=="searchbar")
		{
			$fileid ="";
			$query = $fmap["query"];
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if($fmap["taski"]=="name")
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==$rows["id"])
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
								$agents[]=$ag;
							}

						}
						else
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==stripslashes($rows["agent_code"]))
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>$rows["agent"],"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}
						}
						$totalg +=$rows["totalg"];
						$totalp +=$rows["totalp"];
						$totalgrand +=$rows["totalgross"];
					}
				}
				else
					$default_found =false;
			}
			else
				$default_found = false;
			$default_div=false;
		}
		else if($taskcheck=="office")
		{
			$fileid ="";
			$query = $fmap["query"];
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if($fmap["taski"]=="name")
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==$rows["id"])
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
								$agents[]=$ag;
							}
						}
						else
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==stripslashes($rows["agent_code"]))
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>$rows["agent"],"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}
						}
						$totalg +=$rows["totalg"];
						$totalp +=$rows["totalp"];
						$totalgrand +=$rows["totalgross"];
					}
				}
				else
					$default_found =false;
			}
			else
				$default_found = false;
			$default_div=false;
		}
		else if($taskcheck=="agent_searchbar" || $taskcheck=="agentadd_searchbar" || $taskcheck=="agentdate_searchbar" || $taskcheck=="agentdaterange_searchbar" || $taskcheck=="agentoffice_searchbar")
		{
			$fileid ="";
			$query = $fmap["query"];
			$agentcode = $fmap["agent"];
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if($fmap["taski"]=="name")
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==$rows["id"])
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
								$agents[]=$ag;
							}
						}
						else if($fmap["taski"]=="address" || $fmap["taski"]=="date" || $fmap["taski"]=="daterange" || $fmap["taski"]=="office")
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==$rows["agent_code"])
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}

						}
						else
							$default_found = false;
						if($default_found)
						{
							$totalg +=$rows["totalg"];
							$totalp +=$rows["totalp"];
							$totalgrand +=$rows["totalgross"];
						}
					}
				}
				else
					$default_found = false;
			}
			else
				$default_found = false;
			$default_div=false;
			$showdiv=true;
		}
	}
	else
	{
		//file id is empty
		$query = "select * from file_info order by id desc limit 1";
		if($result = mysql_query($query))
		$row = mysql_fetch_assoc($result);
		$fileid = $row["id"];
		$dataret = array("fileid"=>$fileid,"task"=>'normal','taski'=>'','query'=>'','agent'=>'','title_in'=>'','querys'=>'','datef'=>'','searchin_vet'=>'');
		$_SESSION["fmap"]=$dataret;
		$query = "select * from file_entries where fileid='".$fileid."' order by id";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				while($rows = mysql_fetch_array($result))
				{
					if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
					$found=false;
					if(sizeof($agents)>0)
					{
						for($i=0;$i<sizeof($agents);$i++)
						{
							if(trim($agents[$i]["code"])==trim($rows["agent_code"]))
								$found=true;
						}
						if(!$found)
						{
							$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
							$agents[]=$ag;
						}
					}
					else
					{
						$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
						$agents[]=$ag;
					}
					$totalg +=$rows["totalg"];
					$totalp +=$rows["totalp"];
					$totalgrand +=$rows["totalgross"];
				}
			}
			else
				$default_found=false;
		}
		else
			$default_found = false;
		$showdiv=false;
	}
}
else
{
	$query = "select * from file_info order by id desc limit 1";
	if($result = mysql_query($query))
		$row = mysql_fetch_assoc($result);
	$fileid = $row["id"];
	$dataret = array("fileid"=>$fileid,"task"=>'normal','taski'=>'','query'=>'','agent'=>'','title_in'=>'','querys'=>'','datef'=>'','searchin_vet'=>'');
	$_SESSION["fmap"]=$dataret;
	$query = "select * from file_entries where fileid='".$fileid."' order by id";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				if(empty($getcoords))
					$getcoords = getCoords($rows["coords"]);
				$found=false;
				if(sizeof($agents)>0)
				{
					for($i=0;$i<sizeof($agents);$i++)
					{
						if(trim($agents[$i]["code"])==trim($rows["agent_code"]))
							$found=true;
					}
					if(!$found)
					{
						$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
						$agents[]=$ag;
					}
				}
				else
				{
					$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
					$agents[]=$ag;
				}
				$totalg +=$rows["totalg"];
				$totalp +=$rows["totalp"];
				$totalgrand +=$rows["totalgross"];
			}
		}
		else
			$default_found = false;
	}
	else
		$default_found = false;
	$showdiv=false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to Family Energy Map System</title>
<link rel="icon" type="image/png" href="images/favicon.ico">
<style>
#map_canvas{
	width:850px;
	height:400px;
}
</style>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" language="javascript">
 var directionDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;
    <?php
	$count=0;
	$startheight="";
	$startx=array();
	$endx=array();
	if(!isset($_SESSION["fmap"]))
	{
		$query = "select * from file_info order by date desc limit 1";
		$coords="";
		if($result = mysql_query($query))
		{
			$rowdate = mysql_fetch_assoc($result);
			if(!empty($rowdate["date"]))
			{
				$query = "select * from file_entries where fileid='".$rowdate["id"]."' order by date desc";
				if($result = mysql_query($query))
				{
					if(($num_rows = mysql_num_rows($result))>0)
					{
						while($rows = mysql_fetch_array($result))
						{
							$alcoords = explode(",100",$rows["coords"]);
							$start = explode(",",trim($alcoords[0]));
							$newstart = $start[1].",".$start[0];
							$end = explode(",",trim($alcoords[1]));
							$newsend= $end[1].",".$end[0];
							$startx[]=$newstart;
							$endx[]= $newsend;
							$count++;
						}
					}
				}
			}
		}
	}
	else
	{
		//if this it's set, then use this format
		$datamap =$_SESSION["fmap"];
		$query = $datamap["query"];
		$coords="";
		if($datamap["task"]=="fileinfo" || $datamap["task"]=="agent" || $datamap["task"]=="searchbar" || $datamap["task"]=="office")
		{
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						$alcoords = explode(",100",$rows["coords"]);
						$start = explode(",",trim($alcoords[0]));
						$newstart = $start[1].",".$start[0];
						$end = explode(",",trim($alcoords[1]));
						$newsend= $end[1].",".$end[0];
						$startx[]=$newstart;
						$endx[]= $newsend;
						$count++;
					}
				}
			}
		}
		else if($datamap["task"]=="agent_searchbar" || $datamap["task"]=="agentadd_searchbar" || $datamap["task"]=="agentdate_searchbar" || $datamap["task"]=="agentdaterange_searchbar" || $datamap["task"]=="agentoffice_searchbar")
		{ 
			$querys = $datamap["querys"];
			if($result = mysql_query($querys))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						$alcoords = explode(",100",$rows["coords"]);
						$start = explode(",",trim($alcoords[0]));
						$newstart = $start[1].",".$start[0];
						$end = explode(",",trim($alcoords[1]));
						$newsend= $end[1].",".$end[0];
						$startx[]=$newstart;
						$endx[]= $newsend;
						$count++;
					}
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
							{
								$alcoords = explode(",100",$rows["coords"]);
								$start = explode(",",trim($alcoords[0]));
								$newstart = $start[1].",".$start[0];
								$end = explode(",",trim($alcoords[1]));
								$newsend= $end[1].",".$end[0];
								$startx[]=$newstart;
								$endx[]= $newsend;
								$count++;
							}
						}
					}
				}
			}
		}
	}
	?>
	var routes = [
	<?php
	for($x=0;$x<$count;$x++)
	{
		if($x>0)
	    	echo ",{origin: new google.maps.LatLng(".$startx[$x].")".",destination: new google.maps.LatLng(".$endx[$x].")}";
		else
		{
			$startheight=$startx[$x];
			echo "{origin: new google.maps.LatLng(".$startx[$x].")".",destination: new google.maps.LatLng(".$endx[$x].")}";
		}
	}
	?>
	];
	var rendererOptions = {
    preserveViewport: true,         
    suppressMarkers:true,
    routeIndex:i
    };
  function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    var myOptions = {
      zoom: 14,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
	  draggable:true,
      //center: haight
	  center: new google.maps.LatLng(<?php echo $startheight; ?>)
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    directionsDisplay.setMap(map);
	for(var i=0;i<routes.length;i++)
	{
		calcRoute(i);
	}
  }
  function calcRoute(i) {
	//var selectedMode = document.getElementById("mode").value;
	var selectedMode = "WALKING";
	var request = {
		origin: routes[i].origin,
		destination: routes[i].destination,
			// Note that Javascript allows us to access the constant
			// using square brackets and a string value as its
			// "property."
		travelMode: google.maps.DirectionsTravelMode[selectedMode]
	};
	var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
	directionsDisplay.setMap(map);
	directionsService.route(request, function(response, status) {
		 if (status == google.maps.DirectionsStatus.OK) {
		directionsDisplay.setDirections(response);
		 }
	});
  }
  function printer()
  {
  	window.print();
  }
</script>
</head>

<body onload="initialize(),printer()" onunload="GUnload()">
<h1>Family Energy Map Printer View <b><?php echo $_SESSION["titlesearch"]; ?></b>&nbsp;&nbsp;<a href='javascript:printer()'>Print Again?</a></h1>
<div id="map_canvas"></div>
          <?php
		  if($default_found)
		  {
			  if($default_div)
			  {
				  ?>
			  <div id="filesummary">
				<fieldset>
				  <legend>Summary From Entry:
						<?php
						$query = "select * from file_info order by date desc limit 30";
						if($result = mysql_query($query))
						{
							if(($num_rows = mysql_num_rows($result))>0)
							{
								while($rows = mysql_fetch_array($result))
								{
									if($fileid==$rows["id"])
										echo $rows["date"];
								}
							}
						}
						?>
				</legend>
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="44" colspan="2" align="center" valign="middle"><span style="font-size:20pt; font-weight:bold">Total Gross: <?php echo $totalgrand; ?></span><br/><hr /></td>
					</tr>
				  <tr>
					<td height="44" colspan="2" align="center" valign="middle"><span style="font-size:20pt; font-weight:bold">Number of Agents: <?php echo sizeof($agents); ?></span><br/><hr /></td>
				  </tr>
				  <tr>
					<td width="50%" align="left" valign="middle">Total Gas: <?php echo $totalg; ?></td>
					<td width="50%" align="left" valign="middle">Total Power: <?php echo $totalp;  ?></td>
				  </tr>
				</table>
				</fieldset>
			  </div>
			  <br/>
			  <!--fields from file info normal-->
			  <?php
				 if($showdiv)
				 {
					  //to show details of agents
					  ?>
				  <div id="filesind">
					<fieldset><!--show agent descriptive-->
					  <legend>Performance of: 
							<?php
							if(sizeof($agents)>0)
							{
								for($i=0; $i<sizeof($agents);$i++)
								{
									if(!empty($agentcode))
									{
										if($agents[$i]["code"]==$agentcode)
											echo $agents[$i]["name"]." - ".$agents[$i]["code"];
									}
									else
									{
										if($i==0)
											echo $agents[$i]["name"]." - ".$agents[$i]["code"];
									}
								}
							}
							?>
					  </legend>
					  <?php
					  if(sizeof($agents)>0)
					  {
						  if(!empty($agentcode))
							$query = "select * from file_entries where agent_code = '".$agentcode."'";
						  else
							$query = "select * from file_entries where agent_code = '".$agents[0]["code"]."'";
						 if($result = mysql_query($query))
						 {
							 if(($num_rows = mysql_num_rows($result))>0)
								$userdata = mysql_fetch_assoc($result);
						 }
					  }
					  ?>
					 <table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="50%" height="38" align="left" valign="middle">Rep Code: <? echo @$userdata["agent_code"]; ?></td>
						<td width="50%" align="left" valign="middle">Manager: <?php echo @$userdata["manager"];   ?></td>
					  </tr>
					  <tr>
						<td height="34" colspan="2" align="left" valign="middle"><hr /></td>
					   </tr>
					  <tr>
						<td height="34" align="left" valign="middle">Total Gas: <?php echo $userdata["totalg"]; ?></td>
						<td align="left" valign="middle">Total Power: <?Php echo $userdata["totalp"]; ?></td>
					  </tr>
					  <tr>
						<td height="51" colspan="2" align="left" valign="middle">Total Gross Sales: <?php echo $userdata["totalgross"]; ?></td>
					   </tr>
					  <tr>
						<td height="60" colspan="2" align="left" valign="middle"><hr />Start Address: <b> <?php echo @$userdata["address1"];  ?></b></td>
					   </tr>
					  <tr>
						<td height="45" colspan="2" align="left" valign="middle">End Address: <b><?php echo @$userdata["address2"]; ?></b></td>
					  </tr>
					  <tr>
						<td colspan="2" align="left" valign="middle">&nbsp;</td>
					  </tr>
					 </table>
					</fieldset>
				  </div>
				  <?php
				  }
				 ?>
			  <!--end fields from file info normal-->
			  <!-- fields from the search bar-->
			  <?php
			  }
			  else
			  {
			  ?>
				 <div id="filesummary">
					  <fieldset>
						<legend>Summary For: <?php echo $_SESSION["titlesearch"]; ?></legend>
						 <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td height="44" colspan="2" align="center" valign="middle"><span style="font-size:20pt; font-weight:bold">Total Gross: <?php echo $totalgrand; ?></span><br/><hr /></td>
							</tr>
						  <?php
						  if($fmap["taski"] != "name")
						  {
							  ?>
						  <tr>
							<td height="44" colspan="2" align="center" valign="middle"><span style="font-size:20pt; font-weight:bold">Number of Agents: <?php echo sizeof($agents); ?></span><br/><hr /></td>
						  </tr>
						  <?php
						  }
						  ?>
						  <tr>
							<td width="50%" align="left" valign="middle">Total Gas: <?php echo $totalg; ?></td>
							<td width="50%" align="left" valign="middle">Total Power: <?php echo $totalp;  ?></td>
						  </tr>
						</table>
					  </fieldset>
				 </div>
				  <br/>
				<?Php
				 if($showdiv)
				 {
				 ?>
					<?php
					  if($taskcheck=="agent_searchbar")
					  {
						  ?>
						  <fieldset><!--show agent descriptive-->
					 <legend>Performance of: 
						<?php
							if(sizeof($agents)>0)
							{
								for($i=0; $i<sizeof($agents);$i++)
								{
									if(!empty($agentcode))
									{
										if($agents[$i]["code"]==$agentcode)
											echo $agents[$i]["name"];
									}
									else
									{
										if($i==0)
											echo $agents[$i]["name"];
									}
								}
							}
						?>
					 </legend>
				  <?php
					   if(sizeof($agents)>0)
					   {
						 $query =$fmap["querys"];
						 if($result = mysql_query($query))
						 {
							if(($num_rows = mysql_num_rows($result))>0)
								$userdata = mysql_fetch_assoc($result);
						 }
						}
						  ?>
						 <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="50%" height="38" align="left" valign="middle">Rep Code: <? echo @$userdata["agent_code"]; ?></td>
							<td width="50%" align="left" valign="middle">Current Manager: <?php echo @$userdata["manager"];   ?></td>
						  </tr>
						  <tr>
							<td height="34" colspan="2" align="left" valign="middle"><hr /></td>
						   </tr>
						  <tr>
							<td height="34" align="left" valign="middle">Total Gas: <?php echo $userdata["totalg"]; ?></td>
							<td align="left" valign="middle">Total Power: <?Php echo $userdata["totalp"]; ?></td>
						  </tr>
						  <tr>
							<td height="51" colspan="2" align="left" valign="middle">Total Gross Sales: <?php echo $userdata["totalgross"]; ?></td>
						   </tr>
						  <tr>
							<td colspan="2" align="left" valign="middle">&nbsp;</td>
						  </tr>
						 </table>
					</fieldset>
						<?php
					  }
					  else if($taskcheck=="agentadd_searchbar")
					  {
					?>
						  <fieldset><!--show agent descriptive-->
					 <legend>Performance of: 
						<?php
							if(sizeof($agents)>0)
							{
								for($i=0; $i<sizeof($agents);$i++)
								{
									if(!empty($agentcode))
									{
										if($agents[$i]["code"]==$agentcode)
											echo $agents[$i]["name"];
									}
									else
									{
										if($i==0)
											echo $agents[$i]["name"];
									}
								}
							}
						?>
					 </legend>
				  <?php
					   if(sizeof($agents)>0)
					   {
						 $query =$fmap["querys"];
						 if($result = mysql_query($query))
						 {
							if(($num_rows = mysql_num_rows($result))>0)
							{
								$totalg=0;
								$totalp=0;
								$totalgross=0;
								while($userdata = mysql_fetch_array($result))
								{
									$agent_code = $userdata["agent_code"];
									$manager = $userdata["manager"];
									$totalg += $userdata["totalg"];
									$totalp += $userdata["totalp"];
									$totalgross += $userdata["totalgross"];
								}
							}
						 }
						}
						  ?>
						 <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="50%" height="38" align="left" valign="middle">Rep Code: <? echo $agent_code; ?></td>
							<td width="50%" align="left" valign="middle">Current Manager: <?php echo $manager;   ?></td>
						  </tr>
						  <tr>
							<td height="34" colspan="2" align="left" valign="middle"><hr /></td>
						   </tr>
						  <tr>
							<td height="34" align="left" valign="middle">Total Gas: <?php echo $totalg; ?></td>
							<td align="left" valign="middle">Total Power: <?Php echo $totalp; ?></td>
						  </tr>
						  <tr>
							<td height="51" colspan="2" align="left" valign="middle">Total Gross Sales: <?php echo $totalgross; ?></td>
						   </tr>
						  <tr>
							<td colspan="2" align="left" valign="middle">&nbsp;</td>
						  </tr>
						 </table>
					</fieldset>
						<?php
					  }
					  else if($taskcheck=="agentdate_searchbar")
					  {
						  ?>
						  <fieldset><!--show agent descriptive-->
					 <legend>Performance of: 
						<?php
							if(sizeof($agents)>0)
							{
								for($i=0; $i<sizeof($agents);$i++)
								{
									if(!empty($agentcode))
									{
										if(trim($agents[$i]["code"])==trim($agentcode))
											echo $agents[$i]["name"];
									}
									else
									{
										if($i==0)
											echo $agents[$i]["name"];
									}
								}
							}
						?>
					 </legend>
				  <?php
					   if(sizeof($agents)>0)
					   {
						 $query =$fmap["querys"];
						 if($result = mysql_query($query))
						 {
							if(($num_rows = mysql_num_rows($result))>0)
							{
								$totalg=0;
								$totalp=0;
								$totalgross=0;
								while($userdata = mysql_fetch_array($result))
								{
									$agent_code = $userdata["agent_code"];
									$manager = $userdata["manager"];
									$totalg += $userdata["totalg"];
									$totalp += $userdata["totalp"];
									$totalgross += $userdata["totalgross"];
								}
							}
						 }
						}
						  ?>
						 <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="50%" height="38" align="left" valign="middle">Rep Code: <? echo $agent_code; ?></td>
							<td width="50%" align="left" valign="middle">Current Manager: <?php echo $manager;   ?></td>
						  </tr>
						  <tr>
							<td height="34" colspan="2" align="left" valign="middle"><hr /></td>
						   </tr>
						  <tr>
							<td height="34" align="left" valign="middle">Total Gas: <?php echo $totalg; ?></td>
							<td align="left" valign="middle">Total Power: <?Php echo $totalp; ?></td>
						  </tr>
						  <tr>
							<td height="51" colspan="2" align="left" valign="middle">Total Gross Sales: <?php echo $totalgross; ?></td>
						   </tr>
						  <tr>
							<td colspan="2" align="left" valign="middle">&nbsp;</td>
						  </tr>
						 </table>
					</fieldset>
						  <?php
					  }
					  else if($taskcheck=="agentdaterange_searchbar")
					  {
						  ?>
						  <fieldset><!--show agent descriptive-->
					 <legend>Performance of: 
						<?php
							if(sizeof($agents)>0)
							{
								for($i=0; $i<sizeof($agents);$i++)
								{
									if(!empty($agentcode))
									{
										if(trim($agents[$i]["code"])==trim($agentcode))
											echo $agents[$i]["name"];
									}
									else
									{
										if($i==0)
											echo $agents[$i]["name"];
									}
								}
							}
						?>
					 </legend>
				  <?php
					   if(sizeof($agents)>0)
					   {
						 $query =$fmap["querys"];
						 if($result = mysql_query($query))
						 {
							if(($num_rows = mysql_num_rows($result))>0)
							{
								$totalg=0;
								$totalp=0;
								$totalgross=0;
								while($userdata = mysql_fetch_array($result))
								{
									$agent_code = $userdata["agent_code"];
									$manager = $userdata["manager"];
									$totalg += $userdata["totalg"];
									$totalp += $userdata["totalp"];
									$totalgross += $userdata["totalgross"];
								}
							}
						 }
						}
						  ?>
						 <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="50%" height="38" align="left" valign="middle">Rep Code: <? echo $agent_code; ?></td>
							<td width="50%" align="left" valign="middle">Current Manager: <?php echo $manager;   ?></td>
						  </tr>
						  <tr>
							<td height="34" colspan="2" align="left" valign="middle"><hr /></td>
						   </tr>
						  <tr>
							<td height="34" align="left" valign="middle">Total Gas: <?php echo $totalg; ?></td>
							<td align="left" valign="middle">Total Power: <?Php echo $totalp; ?></td>
						  </tr>
						  <tr>

							<td height="51" colspan="2" align="left" valign="middle">Total Gross Sales: <?php echo $totalgross; ?></td>
						   </tr>
						  <tr>
							<td colspan="2" align="left" valign="middle">&nbsp;</td>
						  </tr>
						 </table>
					</fieldset>
						  <?php
					  }
					  else if($taskcheck=="agentoffice_searchbar")
					  {
						  ?>
                              <fieldset><!--show agent descriptive-->
                         <legend>Performance of: 
                            <?php
                                if(sizeof($agents)>0)
                                {
                                    for($i=0; $i<sizeof($agents);$i++)
                                    {
                                        if(!empty($agentcode))
                                        {
                                            if(trim($agents[$i]["code"])==trim($agentcode))
                                                echo $agents[$i]["name"];
                                        }
                                        else
                                        {
                                            if($i==0)
                                                echo $agents[$i]["name"];
                                        }
                                    }
                                }
                            ?>
                         </legend>
                      <?php
                           if(sizeof($agents)>0)
                           {
                             $query =$fmap["querys"];
                             if($result = mysql_query($query))
                             {
                                if(($num_rows = mysql_num_rows($result))>0)
                                {
                                    $totalg=0;
                                    $totalp=0;
                                    $totalgross=0;
                                    while($userdata = mysql_fetch_array($result))
                                    {
                                        $agent_code = $userdata["agent_code"];
                                        $manager = $userdata["manager"];
                                        $totalg += $userdata["totalg"];
                                        $totalp += $userdata["totalp"];
                                        $totalgross += $userdata["totalgross"];
                                    }
                                }
                             }
                            }
                              ?>
                             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="50%" height="38" align="left" valign="middle">Rep Code: <? echo $agent_code; ?></td>
                                <td width="50%" align="left" valign="middle">Current Manager: <?php echo $manager;   ?></td>
                              </tr>
                              <tr>
                                <td height="34" colspan="2" align="left" valign="middle"><hr /></td>
                               </tr>
                              <tr>
                                <td height="34" align="left" valign="middle">Total Gas: <?php echo $totalg; ?></td>
                                <td align="left" valign="middle">Total Power: <?Php echo $totalp; ?></td>
                              </tr>
                              <tr>
                                <td height="51" colspan="2" align="left" valign="middle">Total Gross Sales: <?php echo $totalgross; ?></td>
                               </tr>
                              <tr>
                                <td colspan="2" align="left" valign="middle">&nbsp;</td>
                              </tr>
                             </table>
                        </fieldset>                        
                          <?php
					  }
					  else
					  {
						  ?>
							<fieldset><!--show agent descriptive-->
					 <legend>Performance of: 
						<?php
							if(sizeof($agents)>0)
							{
								for($i=0; $i<sizeof($agents);$i++)
								{
									if(!empty($agentcode))
									{
										if($agents[$i]["code"]==$agentcode)
											echo $agents[$i]["name"]." - ".$agents[$i]["code"];
									}
									else
									{
										if($i==0)
											echo $agents[$i]["name"]." - ".$agents[$i]["code"];
									}
								}
							}
						?>
					 </legend>
				  <?php
					   if(sizeof($agents)>0)
					   {
						 if(!empty($agentcode))
							$query = "select * from file_entries where agent_code = '".$agentcode."'";
						 else
							$query = "select * from file_entries where agent_code = '".$agents[0]["code"]."'";
						 if($result = mysql_query($query))
						 {
							if(($num_rows = mysql_num_rows($result))>0)
								$userdata = mysql_fetch_assoc($result);
						 }
						}
						  ?>
						 <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="50%" height="38" align="left" valign="middle">Rep Code: <? echo @$userdata["agent_code"]; ?></td>
							<td width="50%" align="left" valign="middle">Current Manager: <?php echo @$userdata["manager"];   ?></td>
						  </tr>
						  <tr>
							<td height="34" colspan="2" align="left" valign="middle"><hr /></td>
						   </tr>
						  <tr>
							<td height="34" align="left" valign="middle">Total Gas: <?php echo $userdata["totalg"]; ?></td>
							<td align="left" valign="middle">Total Power: <?Php echo $userdata["totalp"]; ?></td>
						  </tr>
						  <tr>
							<td height="51" colspan="2" align="left" valign="middle">Total Gross Sales: <?php echo $userdata["totalgross"]; ?></td>
						   </tr>
						  <tr>
							<td colspan="2" align="left" valign="middle">&nbsp;</td>
						  </tr>
						 </table>
					</fieldset>
					  <?Php
					  }
					  ?>
				<?php
				 }
			  }
		  }
		  else
		  {
		  ?>
          <!--end of fields from serach bar-->
          <fieldset>
          	<legend>Summary For: <?php echo $_SESSION["titlesearch"]; ?></legend>
            	<span style="font-size:20pt; font-style:italic; color:#900">Ops! No Information Was Found Under this <?php echo ucwords(strtolower($fmap["taski"])); ?>,</span><br/> <b>Please Try A Different Search Information</b>
          </fieldset>
          <?php
		  }
		  ?>
</body>
</html>
<?php
include 'include/unconfig.php';
?>