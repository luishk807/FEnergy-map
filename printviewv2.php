<?php
session_start();
include "include/config.php";
include "include/function.php";
include('include/mapheader.php');
date_default_timezone_set('UTC');
$task=$_REQUEST["task"];
$xdate=date("Y-m-d");
if($task=='tempo')
{
	$mapentry=array();
	$coords=array();
	$parray=array($_REQUEST["farray"]);
	foreach($parray as $getarray)
	$garray=explode(",",$getarray);
		$today=date('Y-m-d');
	for($i=0;$i<sizeof($garray);$i++)
	{
		$garrayx=explode(" ",$garray[$i]);
		$lat="";
		$lng="";
		if(sizeof($garrayx)>1)
		{
			$lat=$garrayx[0];
			$lng=$garrayx[1];
			$coords[]=array('date'=>$today,'lat'=>$lat,'lng'=>$lng);
		}
	}
	$mapentry[]=array('userid'=>$user["id"],'date_worked'=>$today,'date'=>$today,'coords'=>$coords);
}
else if($task=='mix')
{
    if($xshow_area=='no')
	{
		$mapentry=array();
		$coords=array();
	}
	$parray=array($_REQUEST["farray"]);
	foreach($parray as $getarray)
	$garray=explode(",",$getarray);
		$today=date('Y-m-d');
	if(sizeof($garray)>0)
	{
		for($i=0;$i<sizeof($garray);$i++)
		{
			$garrayx=explode(" ",$garray[$i]);
			$lat="";
			$lng="";
			if(sizeof($garrayx)>1)
			{
				$lat=$garrayx[0];
				$lng=$garrayx[1];
				$coords[]=array('date'=>$today,'lat'=>$lat,'lng'=>$lng);
			}
		}
		$mapentry[]=array('userid'=>$user["id"],'date_worked'=>$today,'date'=>$today,'coords'=>$coords);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to Family Energy Map System</title>
<link rel="icon" type="image/png" href="images/favicon.ico">
<style>
#map{
	width:850px;
	height:900px;
}
</style>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript">
	var script = '<script type="text/javascript" src="http://google-maps-' +
	'utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble';
	if (document.location.search.indexOf('compiled') !== -1) {
		script += '-compiled';
	}
	script += '.js"><' + '/script>';
	document.write(script);
</script>
<script type="text/javascript">
  $(function()
  {
	//$('.date-pick').datePicker({autoFocusNextInput: true});
	Date.format = 'mm/dd/yyyy';
	$('.date-pick').datePicker({startDate:'01/01/1996'});
  });
  var polys=new Array();
  var saved_polys=new Array();
  var points=new Array();
  var colors=new Array();
  var infox=new Array();
  
  var infoBubble2;
  var cinfoBubble;
  
  var poly, polied, map;
  var markers=[];
  var markers_saved=[];
  var path = new google.maps.MVCArray;
  var path_saved = new google.maps.MVCArray;
  function initialize(){
	//array of coordinates
	<?Php
	if(sizeof($mapentry)>0)
	{
	$startpoint="";
	$cid="";
	for($i=0;$i<sizeof($mapentry);$i++)
	{
		$coord=$mapentry[$i]["coords"];
		$mcolor=$mapentry[$i]["color"];
		if(sizeof($coord)>0)
		{
			echo "points[".$i."]=new Array();\r\n";
			for($x=0;$x<sizeof($coord);$x++)
			{
				if(empty($startpoint))
					$startpoint=$coord[$x]["lat"].",".$coord[$x]["lng"];
				echo "points[".$i."][".$x."]=new google.maps.LatLng(".$coord[$x]["lat"].",".$coord[$x]["lng"].");\r\n";
			}
		}
		//array of information
		echo "
		infox[".$i."]=new Array();
		infox[".$i."][0]='".base64_encode($mapentry[$i]["id"])."';
		infox[".$i."][1]='<span style=\'font-size:15pt;\'><u>".getName($mapentry[$i]["userid"])."</u><br/>Sales Made: ".getMapSales($mapentry[$i]["userid"],$mapentry[$i]["date_worked"])."<br/>Date Work: ".fixdate_comps('invdate_s',$mapentry[$i]["date_worked"]);
		echo "</span>';";
		echo "infox[".$i."][2]='';";
		//array of colors for stroke and fills
		echo "
		colors[".$i."]=new Array();
		colors[".$i."][0]='".$mcolor."';
		colors[".$i."][1]='".$mcolor."';\r\n";
	}
	}
	?>
    var uluru = new google.maps.LatLng(<?php echo $startpoint; ?>);
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 14,
      center: uluru,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
   
    poly = new google.maps.Polygon({
	  strokeColor:'#000',
      strokeWeight: 2,
      fillColor: '#F00'
    });
   poly.setMap(map);
   poly.setPaths(new google.maps.MVCArray([path]));
    <?php
   if(sizeof($mapentry)>0)
   {
	   ?>
   if(points.length>0)
   {
	 //alert(points.length);
	 for(var x=0;x<points.length;x++)
	 {
		polied = new google.maps.Polygon({
		 strokeColor:colors[x][0],
		 strokeWeight: 2,
		 fillColor: colors[x][1]
	    });
		polied.setMap(map);
	    saved_polys[x]=polied;
		var xpoint=points[x];
		var xpoly=saved_polys[x];
		for(var y=0;y<xpoint.length; y++)
		{
			if(y==0)
				path_saved  = new google.maps.MVCArray;
			xpoly.setPaths(new google.maps.MVCArray([path_saved]));
			if(y==0)
			{
			//infobubble
			  infoBubble2 = new InfoBubble({
				map: map,
				content: infox[x][1],
				position: new google.maps.LatLng(-35, 151),
				shadowStyle: 1,
				padding: 2,
				backgroundColor: '#FFF',
				borderRadius: 4,
				arrowSize: 10,
				borderWidth: 2,
				maxWidth:500,
				borderColor: '#2c2c2c',
				disableAutoPan: false,
				hideCloseButton: false,
				arrowPosition: 30,
				arrowStyle: 2
			  });
			  infox[x][2]=infoBubble2;
			  //end of infobubble
			}
			saved_polys[x]=xpoly;
			loadPoints(xpoint[y],x);
		}
	 }
   }
   <?php
   }
   ?>
  }
    //load points
  function loadPoints(points,mark) {
    path_saved.insertAt(path_saved.length, points);
    var marker = new google.maps.Marker({
      position: points,
      map: map,
      draggable: false,
	  icon:'../images/miconb.png'
    });
    markers_saved.push(marker);
    marker.setTitle("#" + path_saved.length);
	//poly.runEdit(true);
	var xpoly=saved_polys[mark];
	var xinfo=infox[mark][2];
     google.maps.event.addListener(xpoly,"click",function(){
		if(cinfoBubble !=null)
			cinfoBubble.close();
		cinfoBubble=xinfo;
		xinfo.open(map, marker);
	});
	google.maps.event.addListener(xpoly,"mouseout",function(){
	 this.setOptions({fillColor: colors[mark][1]});
	})
  }
  //end of load points
  function getBack(value)
  {
	  if(value !="")
	 	 window.location.href=value;
  }
  function findmap()
  {
	  var xadd=document.getElementById("xadd").value;
	  var date1=document.getElementById("date1").value;
	  var date2=document.getElementById("date2").value;
	  var man_sel=document.getElementById("xman").value;
	  var dateq="";
	  var man_q="";
	  var dosearch=false;
	  if(date1.length>0 && date2.length>0)
	  {
	  		dateq="&date1="+date1+"&date2="+date2;
			dosearch=true;
	  }
	  if(man_sel.length>0)
	  {
	  		man_q="&man_sel="+man_sel;
			dosearch=true;
	  }
	  if(xadd.length>0 || dosearch==true)
	  	window.location.href='showmap2_all.php?xadd='+xadd+""+dateq+""+man_q;
  }
  function printer()
  {
		window.print();
  }
</script>
</head>

<body onload="initialize(),printer()">
<h1>Family Energy Map Printer View&nbsp;&nbsp;<a href='javascript:printer()'>Print Again?</a></h1>
<div id="map"></div>
</body>
</html>
<?php
include 'include/unconfig.php';
?>