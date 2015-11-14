<?php
session_start();
include 'include/config.php';
include "include/function.php";
$user=$_SESSION["fmap_user"];
if(!detectAgent())
{
	$rlink=getLink();
	header("location:".$rlink."test3.php");
	exit;
}
$xadd=$_REQUEST["xadd"];
$startpoint="40.714623,-74.006605";
if(!empty($xadd))
{
	$xstartpoint=getGEO($xadd);
	$startpoint=$xstartpoint['lat'].",".$xstartpoint['lng'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script>
<script type="text/javascript" src="js/polyEdit/polygonEdit.js"></script>
<script type="text/javascript">
  var poly, map;
  var markers=[];
  var path = new google.maps.MVCArray;
  function initialize(){
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
   google.maps.event.addListener(map, 'click', addPoint);
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
	poly.runEdit(true);
	google.maps.event.addListener(poly, 'click', function() {
      document.getElementById("info").innerHTML = 'path:[';
      poly.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<poly.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });
  }
  function checkArray()
  {
	  var paths=new Array();
	  poly.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML +=inex+">"+vertex.lat()+','+vertex.lng()+"<br/>";
		  paths[inex]=vertex.lat()+" "+vertex.lng();
      });
	  document.getElementById("farray").value=paths;
	  var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO CREATE THIS MAP. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	  if(confirmx==true)
	 	 return true;
	   else
		 return false;
  }
  function resetmap()
  {
	  window.location.href="test3.php";
  }
  function refMap()
  {
	  var xadd=document.getElementById("xadd").value;
	  if(xadd.length>0)
		  window.location.href="test3.php?xadd="+xadd;
	  else
		  alert("You Must Provide An Address To Search");
  }
  function viewall()
  {
	   window.location.href="test2_view_all.php";
  }
</script>
 </head>
   <body onload="initialize()">
   <div style="width:500px;">
   <form action="test3_action.php" method="post" onsubmit="return checkArray();">
   <input type="hidden" id="farray" name="farray" value="" />
   <input type="hidden" id="task" name="task" value="create" />
   <input type="text" size="50" id="xadd" name="xadd" value="<?php echo rgetGEO($starpoint);  ?>" />&nbsp;&nbsp;<input type="button" value="Search" onclick="refMap()" />
     	 <div id="map" style="width:500px; height:500px; margin:5px;"></div>
  		<div id="info">
        <?php
		if(isset($_SESSION["fmapresult"]))
        {
           echo $_SESSION["fmapresult"]."<br/>";
           unset($_SESSION["fmapresult"]);
         }
		?>
        </div>
        <br/>
        <input type="submit" value="Create Map" />&nbsp;&nbsp;&nbsp;<input type="button" value="Reset Map" onclick="resetmap()" />&nbsp;&nbsp;&nbsp;<input type="button" value="View All Maps" onclick="viewall()" />
    </form>
    </div>
   </body>
</html>
<?php
include 'include/unconfig.php';
?>