<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$user=$_SESSION["fmap_user"];
if(!checkMap2Access($user['id']))
{
	$_SESSION['fmapresult']='ERROR: You are not authorized to access the page';
	header("location:home.php");
	exit;
}
$mapentry=array();
$coords=array();
$showmainbutton=true;
if(isset($_SESSION["prevlink"]))
	$prevlink = $_SESSION["prevlink"];
else
	$prevlink = "";
date_default_timezone_set('UTC');
$task=base64_decode($_REQUEST["task"]);
$id=base64_decode($_REQUEST["id"]);
if(empty($id))
{
	$_SESSION["fmapresult"]="ERROR: Invalid Entry!";
	header("location:showmap2.php");
	exit;
}
else
{
	$query="select * from map_entry where id='".$id."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			$mapentry[]=array('id'=>$info["id"],'userid'=>$info["userid"],'date_worked'=>$info['date_worked'],'date'=>$info["date"]);
		}
	}
	$query="select * from map_coords where entryid='".$id."' order by id";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
				$coords[]=array('id'=>$rows["id"],'entryid'=>$rows["entryid"],'date'=>$rows['date'],'lat'=>$rows["lat"],'lng'=>$rows["lng"],'address'=>stripslashes($rows["address"]));
		}
	}
}
if(sizeof($coords) <1 || sizeof($mapentry)<1)
{
	$_SESSION["fmapresult"]="ERROR: Empty Entry Detected!";
	header("location:showmap2.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
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
  var map;
  var poly;
  var polys=new Array();
  var markers=[];
  var path = new google.maps.MVCArray;
  var points=new Array();
  var colors=new Array();
  var infox=new Array();
  var infoBubble2;
  var cinfoBubble;
  function initialize() {
	//array of coordinates
	points[0]=new Array()
	<?php
	$startpoint="";
	for($i=0;$i<sizeof($coords);$i++)
	{
		if(empty($startpoint))
			$startpoint=$coords[$i]["lat"].",".$coords[$i]["lng"];
		echo "points[0][".$i."]=new google.maps.LatLng(".$coords[$i]["lat"].",".$coords[$i]["lng"].");"; 
	}
	?>
	//array of colors for stroke and fills
	colors[0]=new Array(2);
	colors[0][0]='#000';
	colors[0][1]='#F00';
	
	//array of information
	infox[0]=new Array();
	<?php
	echo "
	infox[0][0]='".base64_encode($mapentry[0]["id"])."';
	infox[0][1]='<span style=\'font-size:15pt;\'><u>".getName($mapentry[0]["userid"])."</u><br/>Sales Made: ".getMapSales($mapentry[0]["userid"],$mapentry[0]["date_worked"])."<br/>Date Work: ".fixdate_comps('invdate_s',$mapentry[0]["date_worked"])."</span>';
	infox[0][2]='';
	";
	?>
    var uluru = new google.maps.LatLng(<?php echo $startpoint; ?>);
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 14,
      center: uluru,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
   //end of load points
   google.maps.event.addListener(map, 'click', addPoint);
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
			polys[x]=xpoly;
			loadPoints(xpoint[y],x);
		}
	 }
   }
  }
    function addPoint(event) {
    path.insertAt(path.length, event.latLng);
    var marker = new google.maps.Marker({
      position: event.latLng,
      map: map,
      draggable: true
    });
    markers.push(marker);
    marker.setTitle("#" + path.length);

    google.maps.event.addListener(marker, 'click', function() {
      marker.setMap(null);
      for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
      markers.splice(i, 1);
      path.removeAt(i);
      }
    );
    google.maps.event.addListener(marker, 'dragend', function() {
      for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
      path.setAt(i, marker.getPosition());
      }
    );
  }
  //load points
  function loadPoints(points,mark) {
    path.insertAt(path.length, points);
    var marker = new google.maps.Marker({
      position: points,
      map: map,
      draggable: true,
    });
    markers.push(marker);
    marker.setTitle("#" + path.length);
	//poly.runEdit(true);
	var xpoly=polys[mark];
	var xinfo=infox[mark][2];
	/*google.maps.event.addListener(xpoly, 'click', function() {
      document.getElementById("info").innerHTML = 'path:[';
      xpoly.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<xpoly.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });*/
	google.maps.event.addListener(marker, 'dragend', function() {
      for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
      path.setAt(i, marker.getPosition());
      }
    );
	google.maps.event.addListener(marker, 'click', function() {
      marker.setMap(null);
      for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
      markers.splice(i, 1);
      path.removeAt(i);
      }
    );
     google.maps.event.addListener(xpoly,"mouseover",function(){
		 this.setOptions({fillColor: "#00FF00"});
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
	  if(value =="reset")
	  {
		 var idx=document.getElementById("id").value;
	  	 window.location.href="showmap2_edit.php?id="+idx;
	  }
	  else if(value !='')
	 	 window.location.href=value;
  }
  function checkArray()
  {
	  var paths=new Array();
	  poly.getPath().forEach(function (vertex, inex) {
        //document.getElementById("info").innerHTML +=inex+">"+vertex.lat()+','+vertex.lng()+"<br/>";
		  paths[inex]=vertex.lat()+" "+vertex.lng();
      });
	  document.getElementById("farray").value=paths;
	  var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO UPDATE THIS MAP. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	  if(confirmx==true)
	 	 return true;
	   else
		 return false;
  }
  function deletemap(value)
  {
	  var confirmx=window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS MAP. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	  if(confirmx==true)
	 	 window.location.href="fmapv2_action.php?task=delete&id="+value;
  }
  function printthis(value)
  {
	  	var paths=new Array();
	   	poly.getPath().forEach(function (vertex, inex) {
		  paths[inex]=vertex.lat()+" "+vertex.lng();
      	});
		var newWin = window.open("printviewv2.php?farray="+paths+"&task="+value,"Family_Energy_Map_System_Print_View",'width=880, height=800,resizable');
		if (newWin && newWin.top) {
			newWin.focus();
		} else {
			alert("Please enable your pop up blocker");
		}
   }
</script>
<title>Welcome to Family Energy Map System</title>
</head>
<body onload="initialize()">
<div id="main_cont">
    <div id="body_header"><?php include "include/topbutton.php"; ?>
    </div>
    <div id="body_middle">
        <div id="body_content">
         Hello <u><?php echo $user["username"]; ?></u>, to edit the map drag the markers to the desire place and click 'update map' button<br/><br/>
         <div id="message2" name="message2" class="white" style="text-align:center">
         <?php
		if(isset($_SESSION["fmapresult"]))
        {
           echo $_SESSION["fmapresult"]."<br/>";
           unset($_SESSION["fmapresult"]);
         }
		?>
         </div>
		<form action="fmapv2_action.php" method="post" onsubmit="return checkArray();">
   		<input type="hidden" id="farray" name="farray" value="" />
        <?php
		if($task=='predefined')
		{
			?>
        <input type="hidden" id="task" name="task" value="predefined" />
        <?php
		}
		else
		{
		?>
        <input type="hidden" id="task" name="task" value="save" />
        <?php
		}
		?>
   		<input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
           <div>
        </div>
    <br/>
         <div style="text-align:right">
         <a href="javascript:printthis('tempo')" onmouseover="document.print.src='images/printicon_r.jpg'" onmouseout="document.print.src='images/printicon.jpg'"><img src="images/printicon.jpg"  border="0" alt="Print" name="print" /></a>
         </div>
     	 <div id="map" style="width:100%; height:500px;"></div>
  		<div id="info">&nbsp;
        </div>
        <br/>
        <?php
		if(adminPrevb($user["type"]))
		{
			if($task=='predefined')
			{
				?>
		<input type="image"  src="images/setworkedbtn.jpg" onmouseover="javascript:this.src='images/setworkedbtn_r.jpg';" onmouseout="javascript:this.src='images/setworkedbtn.jpg';">
       		<?
			}
			else
			{
			?>
        <input type="image"  src="images/updatemap2btn.jpg" onmouseover="javascript:this.src='images/updatemap2btn_r.jpg';" onmouseout="javascript:this.src='images/updatemap2btn.jpg';">
        	<?php
			}
			?>&nbsp;&nbsp;
        <a href="javascript:deletemap('<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delmap.src='images/deletemap2btn_r.jpg'" onmouseout="document.delmap.src='images/deletemap2btn.jpg'"><img src="images/deletemap2btn.jpg"  border="0" alt="Delete Map" name="delmap" /></a>&nbsp;&nbsp;
        <?php
		}
		?>
        <a href="javascript:getBack('showmap2.php')" onmouseover="document.previous.src='images/prevbtn_r.png'" onmouseout="document.previous.src='images/prevbtn.png'"><img src="images/prevbtn.png"  border="0" alt="Return" name="previous" /></a>
    </form>
        </div>
        <div style="height:150px;"></div>
    </div>
    <div id="body_footer">
    </div>
    <div class="clearfooter"></div>
</div>
<?Php
include "include/footer.php";
?>
</body>
</html>
<?php
include "include/unconfig.php";
?>