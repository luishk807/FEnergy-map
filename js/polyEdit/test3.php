<?php
session_start();
include '../../include/config.php';
include "../../include/function.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script>
<script type="text/javascript" src="polygonEdit.js"></script>
<script type="text/javascript">
  function initialize()
  {
	  var uluru = new google.maps.LatLng(50.909528, 34.811726);
      var map = new google.maps.Map(
	  	document.getElementById("map"),
		{
			zoom: 14,
			center: uluru,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
	  );
      mapPolygon = new google.maps.Polygon({
		  map:map,
          strokeColor:'#ff0000',
          strokeOpacity:0.6,
          strokeWeight:4,
          path:[new google.maps.LatLng(50.91607609098315,34.80485954492187),new google.maps.LatLng(50.91753710953153,34.80485954492187),new google.maps.LatLng(50.91759122044873,34.815159227539056),new google.maps.LatLng(50.9159678655622,34.815159227539056),new google.maps.LatLng(50.91044803534999,34.81258430688476),new google.maps.LatLng(50.91044803534999,34.81584587304687),new google.maps.LatLng(50.90931151845126,34.81533088891601),new google.maps.LatLng(50.90931151845126,34.811897661376946),new google.maps.LatLng(50.90395327929007,34.8094944020996),new google.maps.LatLng(50.9040074060014,34.80700531213378),new google.maps.LatLng(50.90914915662899,34.809666063476556),new google.maps.LatLng(50.90920327729935,34.8065761586914),new google.maps.LatLng(50.91033979684091,34.80700531213378),new google.maps.LatLng(50.910285677492006,34.81035270898437),new google.maps.LatLng(50.91607609098315,34.81301346032714)]
      });
    mapPolygon.runEdit(true);
    google.maps.event.addListener(mapPolygon, 'click', function() {
      document.getElementById("info").innerHTML = 'path:[';
      mapPolygon.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<mapPolygon.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });
  }
</script>
<script type="text/javascript" src="http://code.google.com/js/prettify.js"></script>
 </head>
   <body onload="initialize()">
     	 <div id="map" style="width:500px; height:500px; float:left; margin:5px;"></div>
  		<div id="info"></div>
   </body>
</html>
<?php
include '../../include/unconfig.php';
?>