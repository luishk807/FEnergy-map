<?php
session_start();
include 'include/config.php';
include "include/function.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
	/* var points=[
		new google.maps.LatLng(40.71696500739573,-74.0061758465576),
		new google.maps.LatLng(40.71620061126093,-74.00452360580442),
		new google.maps.LatLng(40.71392363459524,-74.00634750793455),
		new google.maps.LatLng(40.71480190624912,-74.00802120635984)
		]*/
	/*points[0]=new Array[
		new google.maps.LatLng(40.71696500739573,-74.0061758465576),
		new google.maps.LatLng(40.71620061126093,-74.00452360580442),
		new google.maps.LatLng(40.71392363459524,-74.00634750793455),
		new google.maps.LatLng(40.71480190624912,-74.00802120635984)
		];
	points[1]=new Array[
		new google.maps.LatLng(40.718225428662784,-74.00540337036131),
		new google.maps.LatLng(40.71739599283665,-74.00366529891966),
		new google.maps.LatLng(40.71910364356756,-74.00209888885496),
		new google.maps.LatLng(40.71986800636897,-74.00403007934568)
		];*/
	//array of coordinates
	points[0]=new Array(4)
	points[0][0]=new google.maps.LatLng(40.71696500739573,-74.0061758465576);
	points[0][1]=new google.maps.LatLng(40.71620061126093,-74.00452360580442);
	points[0][2]=new google.maps.LatLng(40.71392363459524,-74.00634750793455);
	points[0][3]=new google.maps.LatLng(40.71480190624912,-74.00802120635984);
	
	points[1]=new Array(4)
	points[1][0]=new google.maps.LatLng(40.718225428662784,-74.00540337036131);
	points[1][1]=new google.maps.LatLng(40.71739599283665,-74.00366529891966);
	points[1][2]=new google.maps.LatLng(40.71910364356756,-74.00209888885496);
	points[1][3]=new google.maps.LatLng(40.71986800636897,-74.00403007934568);
	
	//array of colors for stroke and fills
	colors[0]=new Array(2);
	colors[0][0]='#000';
	colors[0][1]='#F00';
	colors[1]=new Array(2);
	colors[1][0]='#F00';
	colors[1][1]='#000';
	
	//array of information
	infox[0]=new Array();
	infox[0][0]='5';
	infox[0][1]="<u>Antony</u><br/>Manhattan 2012-08-13<br/><a href='changethis.php?id="+5+"'>Edit This Map</a>";
	infox[0][2]="";
	infox[1]=new Array();
	infox[1][0]='6';
	infox[1][1]="<u>Lee Cohen</u><br/>Manhattan 2012-08-06<br/><a href='changethis.php?id="+6+"'>Edit This Map</a>";
	infox[1][2]="";
    var uluru = new google.maps.LatLng(40.714623,-74.006605);
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 14,
      center: uluru,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
   
   /* poly = new google.maps.Polygon({
	  strokeColor:'#000',
      strokeWeight: 2,
      fillColor:'#F00'
    });
    poly.setMap(map);
    poly.setPaths(new google.maps.MVCArray([path]));
    google.maps.event.addListener(map, 'click', addPoint);
   //load points
   if(points.length>0)
   {
	   for(var x=0;x<points.length;x++)
		   loadPoints(points[x]);
   }*/
   //end of load points
  
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
		 this.setOptions({fillColor: "#00FF00"});
		// infowindow.close();
		// infowindow.setContent(infox[mark][1]);
		// infowindow.open(map, marker);
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
</script>
 </head>
   <body onload="initialize()">
     	 <div id="map" style="width:500px; height:500px; float:left; margin:5px;"></div>
  		<div id="info"></div>
   </body>
</html>
<?php
include 'include/unconfig.php';
?>