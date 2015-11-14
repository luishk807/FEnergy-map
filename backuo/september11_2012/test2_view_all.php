<?php
session_start();
include 'include/config.php';
include "include/function.php";
ini_set('memory_limit','500M');
date_default_timezone_set('America/New_York');
include('include/mapheader.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script>
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="js/calendarb_js/date.js"></script>
<script type="text/javascript" src="js/calendarb_js/jquery.datePicker.js"></script>
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
		infox[".$i."][1]='<u>".getName($mapentry[$i]["userid"])."</u><br/>Sales Made: ".getMapSales($mapentry[$i]["userid"],$mapentry[$i]["date_worked"])."<br/>Date Work: ".fixdate_comps('invdate_s',$mapentry[$i]["date_worked"]);
		if(adminPrevb($user["type"]))
			echo "<br/><a href=\'test3_view.php?id=".base64_encode($mapentry[$i]["id"])."\'>Edit This Map</a>";
		echo "';";
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
      <?php
   if(sizeof($mapentry)>0)
   {
	   ?>
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
   <?php
   }
   ?>
  }
  //load points
  function loadPoints(points,mark) {
    path.insertAt(path.length, points);
    var marker = new google.maps.Marker({
      position: points,
      map: map,
      draggable: false,
	  icon:'images/miconb.png'
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
	  	window.location.href='test2_view_all.php?xadd='+xadd+""+dateq+""+man_q;
  }
</script>
 </head>
   <body onload="initialize()">
   		<form action="">
        <div style="width:500px">
        <fieldset>
   			<legend>Advance Search</legend>
         <div>
         	Address:&nbsp;
         	<input type="text" size="50" id="xadd" name="xadd" value="<?php echo $_REQUEST["xadd"]; ?>" />
        </div>
        <br/>
        <hr/>
        <div style="width:500px;">
            Choose Managers:<br/>
			<div>
            <select id="xman" name="xman" onchange="findmap()">
            	<option value="all" <?php if(empty($man_sel)) echo "selected='selected'"; ?>>View All</option>
                <?php
					$xq="select * from task_users where type in ('6','1','4')";
					if($rx=mysql_query($xq))
					{
						if(($num_rows=mysql_num_rows($rx))>0)
						{
							while($rows=mysql_fetch_array($rx))
							{
								$iselected=setSelected($rows["id"],$man_sel);
								echo "<option $iselected value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
							}
						}
					}
				?>
            </select>
           </div>
        </div>
        <br/>
        <hr/>
        <div style="width:500px;">
            Choose Date Range:<br/>
        	<div style="float:left;">From:&nbsp;</div>
            <input name="date1" id="date1" class="date-pick" readonly="readonly" value="<?Php echo $date1x; ?>" />
            <div style="float:left;">&nbsp;To:&nbsp;</div>
            <input name="date2" id="date2" class="date-pick" readonly="readonly" value="<?Php echo $date2x; ?>" />
            <div style="clear:both"></div>
        </div>
        <br/>
        <div><input type="button" value="Change Map" onclick="findmap()"/></div>
        </fieldset>
        </div>
     	 	<div id="map" style="width:500px; height:500px; margin:5px;"></div>
        <div>
            <div id="info">
                    <?php
                    if(isset($_SESSION["fmapresult"]))
                    {
                        echo $_SESSION["fmapresult"]."<br/>";
                        unset($_SESSION["fmapresult"]);
                     }
                    ?>
             </div>
             <input type="button" value="Back" onclick="getBack('test3.php')" />
             &nbsp;&nbsp;<input type="button" value="Reset" onclick="getBack('test2_view_all.php')" />
        </div>
        </form>
   </body>
</html>
<?php
include 'include/unconfig.php';
?>