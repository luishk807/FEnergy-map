<?php
session_start();
include 'include/config.php';
include "include/function.php";
adminloginx();
include('include/mapheader.php');
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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
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
	if($xshow_area=='yes' && sizeof($mapentry)>0)
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
   
    poly = new google.maps.Polygon({
	  strokeColor:'#000',
      strokeWeight: 2,
      fillColor: '#F00'
    });
   poly.setMap(map);
   poly.setPaths(new google.maps.MVCArray([path]));
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
				maxWidth:300,
				borderColor: '#2c2c2c',
				disableAutoPan: true,
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
   google.maps.event.addListener(map, 'click', addPoint);
  }
  <?php
  if($xshow_area=='yes')
  {
	  ?>
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
	/*google.maps.event.addListener(xpoly, 'click', function() {
      document.getElementById("info").innerHTML = 'path_saved:[';
      xpoly.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<xpoly.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });*/
	
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
  <?php
  }
  ?>
  //end of load points
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
	/*google.maps.event.addListener(poly, 'click', function() {
      document.getElementById("info").innerHTML = 'path:[';
      poly.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<poly.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });*/
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
  function refMap()
  {
	  var xadd=document.getElementById("xadd").value;
	  if(xadd.length>0)
		  window.location.href="test3.php?xadd="+xadd;
	  else
		  alert("You Must Provide An Address To Search");
  }
  function goBack(value)
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
	  var xshow_area=document.getElementById("xshow_area").checked;
	  if(xshow_area==true)
	  	xshow_area_chk="&xshow_area=yes";
	  else
	  	xshow_area_chk="&xshow_area=no";
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
	  	window.location.href='test3.php?xadd='+xadd+""+dateq+""+man_q+""+xshow_area_chk;
  }
  function findmap_show()
  {
	  var xshow_area=document.getElementById("xshow_area").checked;
	  if(xshow_area==true)
	  	xshow_area_chk="xshow_area=yes";
	  else
	  	xshow_area_chk="xshow_area=no";
	  window.location.href='test3.php?'+xshow_area_chk;
  }
</script>
 </head>
   <body onload="initialize()">
   <div style="width:500px;">
   <form action="test3_action.php" method="post" onsubmit="return checkArray();">
   <input type="hidden" id="farray" name="farray" value="" />
   <input type="hidden" id="task" name="task" value="create" />
   <fieldset>
   		<legend>Change Map:</legend>
        <input type="text" size="50" id="xadd" name="xadd" value="<?php echo rgetGEO($starpoint);  ?>" />&nbsp;&nbsp;<input type="button" value="Search" onclick="refMap()" />
    </fieldset>
   <br/>
   <br/>
   <fieldset>
   		<legend>Show Area Saved?:</legend>
        Yes?&nbsp;<input type="checkbox" id="xshow_area" name="xshow_area" onclick="findmap_show()" <?Php echo $xshow_area_sel; ?> />
    </fieldset>
    <?php
	if($xshow_area=='yes')
	{
		?>
   <br/>
   <br/>
           <div style="width:500px">
        <fieldset>
   			<legend>Search For Saved Areas</legend>
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
     <?Php
	}
	?>
    <br/>
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
        <input type="submit" value="Create Map" />&nbsp;&nbsp;&nbsp;<input type="button" value="Reset Map" onclick="goBack('test3.php')" />&nbsp;&nbsp;&nbsp;<input type="button" value="Logout" onclick="goBack('test2_logout.php')" />&nbsp;&nbsp;&nbsp;<input type="button" value="View All Maps" onclick="goBack('test2_view_all.php')" />
    </form>
    </div>
   </body>
</html>
<?php
include 'include/unconfig.php';
?>