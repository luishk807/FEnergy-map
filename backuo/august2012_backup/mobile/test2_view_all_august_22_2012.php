<?php
session_start();
include '../include/config.php';
include "../include/function.php";
$mapentry=array();
$coords=array();
/**************COLOR FOR THE LINES************************************************/
//this is the use to color the graph lines, ave number of team leader and manager is 8 but to play safe i used 20
$colorsc=array();//color choosen
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
	$query="select * from map_coords where address like '%".$xadd."%' $xlatlng_q ";
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
	if(sizeof($mapid)>0)
	{
		for($o=0;$o<sizeof($mapid);$o++)
		{
			$query="select * from map_entry where id='".$mapid[$o]."'";
			if($result=mysql_query($query))
			{
				if(($num_rows=mysql_num_rows($result))>0)
					$rows=mysql_fetch_assoc($result);
			}
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
			$mapentry[]=array('id'=>$rows["id"],'userid'=>$rows["userid"],'date_worked'=>$rows['date_worked'],'date'=>$rows["date"],'coords'=>$coordx,'color'=>$c_color);
		}
	}
	if(sizeof($mapentry)<1)
		$_SESSION["fmapresult"]="ERROR: No Map Found Under This Address";
	else
		$xfollow=false;
}
if($xfollow)
{
	$query="select * from map_entry order by id";
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
				$mapentry[]=array('id'=>$rows["id"],'userid'=>$rows["userid"],'date_worked'=>$rows['date_worked'],'date'=>$rows["date"],'coords'=>$coordx,'color'=>$c_color);
			}
		}
	}
	if(sizeof($mapentry)<1)
	{
		$_SESSION["fmapresult"]="ERROR: Empty Entry Detected!";
		header("location:test3.php");
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title></title>
<style>
html, body {
   height: 100%;
   margin: 0;
   }
#content {
   min-height: 100%;
   height: auto !important;
   height: 100%;
   margin: 0 auto -40px;
   }
#footer, #push {
   height: 40px;
   }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script>
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
  var map;
  var poly;
  var polys=new Array();
  var markers=[];
  var path = new google.maps.MVCArray;
  var points=new Array();
  var colors=new Array();
  var infox=new Array();
  // Creating an InfoWindow object
  //var infowindow = new google.maps.InfoWindow({
//	  content: 'Hello world'
  //});
  var infoBubble2;
  var cinfoBubble;
  function initialize() {
	//array of coordinates
	<?Php
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
		infox[".$i."][1]='<u>".getName($mapentry[$i]["userid"])."</u><br/>Date Work: ".fixdate_comps('invdate_s',$mapentry[$i]["date_worked"])."<br/><a href=\'test3_view.php?id=".base64_encode($mapentry[$i]["id"])."\'>Edit This Map</a>';
		infox[".$i."][2]='';
		";
		//array of colors for stroke and fills
		echo "
		colors[".$i."]=new Array();
		colors[".$i."][0]='".$mcolor."';
		colors[".$i."][1]='".$mcolor."';\r\n";
	}
	?>
    var uluru = new google.maps.LatLng(<?php echo $startpoint; ?>);
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 14,
      center: uluru,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

   if(points.length>0)
   {
	 //alert(points.length);
	 for(var x=0;x<points.length;x++)
	 {
		poly = new google.maps.Polygon({
		 strokeColor:colors[x][0],
		 strokeWeight: 2,
		 fillColor: colors[x][1]
	    });
		poly.setMap(map);
	    polys[x]=poly;
		var xpoint=points[x];
		var xpoly=polys[x];
		for(var y=0;y<xpoint.length; y++)
		{
			if(y==0)
				path = new google.maps.MVCArray;
			xpoly.setPaths(new google.maps.MVCArray([path]));
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
				maxWidth:300,
				borderColor: '#2c2c2c',
				disableAutoPan: false,
				hideCloseButton: false,
				arrowPosition: 30,
				arrowStyle: 2
			  });
			  infox[x][2]=infoBubble2;
			  //end of infobubble
			}
			polys[x]=xpoly;
			loadPoints(xpoint[y],x);
		}
	 }
   }
  }
  //load points
  function loadPoints(points,mark) {
    path.insertAt(path.length, points);
    var marker = new google.maps.Marker({
      position: points,
      map: map,
      draggable: false,
	  icon:'../images/micon.png'
    });
    markers.push(marker);
    marker.setTitle("#" + path.length);
	//poly.runEdit(true);
	var xpoly=polys[mark];
	var xinfo=infox[mark][2];
	google.maps.event.addListener(xpoly, 'click', function() {
      document.getElementById("info").innerHTML = 'path:[';
      xpoly.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<xpoly.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });
     google.maps.event.addListener(xpoly,"mouseover",function(){
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
	  if(xadd.length>0)
	  	window.location.href='test2_view_all.php?xadd='+xadd;
  }
</script>
 </head>
   <body onload="initialize()">
   <form action="">
   <input type="hidden" id="farray" name="farray" value="" />
   		<div id="content">
        	<div>
         	<input type="text" size="30" id="xadd" name="xadd" value="<?php echo $_REQUEST["xadd"]; ?>" />&nbsp;&nbsp;<input type="button" value="Find Map" onclick="findmap()" />
           </div>
			<div id="map" style="width:100%; height:300px;"></div>
            <div id="push"></div>
        </div>
        <div id="footer" style="text-align:center">
        	<div id="info">
            	<?php
				if(isset($_SESSION["fmapresult"]))
                {
                    echo $_SESSION["fmapresult"]."<br/>";
                    unset($_SESSION["fmapresult"]);
                 }
				?>
            </div>
        	<div><input type="button" value="Back" onclick="getBack('test3.php')" />
             &nbsp;&nbsp;<input type="button" value="Reset" onclick="getBack('test2_view_all.php')" /></div>
        </div>
    </form>
   </body>
</html>
<?php
include '../include/unconfig.php';
?>