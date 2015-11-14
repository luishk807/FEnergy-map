<?php
session_start();
include '../include/config.php';
include "../include/function.php";
$id=base64_decode($_REQUEST["id"]);
//adminlogin();
$user=$_SESSION["fmap_user"];
$mapentry=array();
$coords=array();
if(empty($id))
{
	$_SESSION["fmapresult"]="ERROR: Invalid Entry!";
	header("location:test3.php");
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
	header("location:test3.php");
	exit;
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
	$infox=$mapentry[0];
	echo "
	infox[0][0]='".base64_encode($mapentry[0]["id"])."';
	infox[0][1]='<u>".getName($mapentry[0]["userid"])."</u><br/>Date Work: ".fixdate_comps('invdate_s',$mapentry[0]["date_worked"])."';
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
	google.maps.event.addListener(xpoly, 'click', function() {
      document.getElementById("info").innerHTML = 'path:[';
      xpoly.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<xpoly.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });
	
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
	    google.maps.event.addListener(marker, 'dragend', function() {
      for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
      path.setAt(i, marker.getPosition());
      }
    );
	google.maps.event.addListener(xpoly,"mouseout",function(){
	 this.setOptions({fillColor: colors[mark][1]});
	})
  }
	//end of load points
  function getBack()
  {
	  window.location.href="test3.php";
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
  function resetmap(value)
  {
	  window.location.href="test3_view.php?id="+value;
  }
</script>
 </head>
   <body onload="initialize()">
   <form action="../test3_action.php" method="post" onsubmit="return checkArray();">
   <input type="hidden" id="farray" name="farray" value="" />
   <input type="hidden" id="task" name="task" value="save" />
   <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
   		<div id="content">
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
        	<div><input type="submit" value="Save Map" />&nbsp;&nbsp;<input type="button" value="Reset Map" onclick="resetmap('<?php echo $_REQUEST["id"]; ?>')" />&nbsp;&nbsp;<input type="button" value="Back" onclick="getBack()" /></div>
        </div>
    </form>
   </body>
</html>
<?php
include '../include/unconfig.php';
?>