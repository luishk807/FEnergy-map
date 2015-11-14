<?php
include 'include/config.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" language="javascript">
 var directionDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;
  //var haight = new google.maps.LatLng(37.7699298, -122.4469157);
  //var oceanBeach = new google.maps.LatLng(37.7683909618184, -122.51089453697205);
 	var haight = <?php echo "new google.maps.LatLng(40.6868670, -73.7956420); "?>
  	var oceanBeach = new google.maps.LatLng(40.6927273, -73.7956420);
 	var haight2 = new google.maps.LatLng(40.6932482, -73.8079166);
  	var oceanBeach2 = new google.maps.LatLng(40.7102720, -73.8138290);
	var routes = [{origin:new google.maps.LatLng(40.6868670, -73.7956420),destination:new google.maps.LatLng(40.6927273, -73.7956420)},{origin:new google.maps.LatLng(40.6932482, -73.8079166),destination:new google.maps.LatLng(40.7102720, -73.8138290)}];
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
      center: new google.maps.LatLng(40.6868670, -73.7956420)
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    directionsDisplay.setMap(map);
	for(var i=0;i<routes.length;i++)
	{
		calcRoute(i);
	}
  }

  function calcRoute(i) {
	var selectedMode = document.getElementById("mode").value;
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
</script>
</head>

<body onload="initialize()">
<b>Mode of Travel: </b>

<select id="mode" onchange="calcRoute();">
  <option value="DRIVING">Driving</option>
  <option value="WALKING" selected="selected">Walking</option>
  <option value="BICYCLING">Bicycling</option>
</select>
</div>
<div id="map_canvas" style="top:30px;"></div>
</body>
</html>
<?php
include 'include/unconfig.php';
?>