<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
include('include/mapheader.php');
if(!checkMap2Access($user['id']))
{
	$_SESSION['fmapresult']='ERROR: You are not authorized to access the page';
	header("location:home.php");
	exit;
}
$showmainbutton=true;
if(isset($_SESSION["prevlink"]))
	$prevlink = $_SESSION["prevlink"];
else
	$prevlink = "";
date_default_timezone_set('UTC');
$xdate = date("Y-m-d");
if(!detectAgent())
{
	$rlink=getLink();
	header("location:".$rlink."showmap2.php");
	exit;
}
$xadd=$_REQUEST["xadd"];
$startpoint="40.714623,-74.006605";
if(!empty($xadd))
{
	$xstartpoint=getGEO($xadd);
	$startpoint=$xstartpoint['lat'].",".$xstartpoint['lng'];
}
$checkpend=false;
$qpend="select * from map_entry where type='2' and userid='".$user["id"]."'";
if($rpend=mysql_query($qpend))
{
	if(($numpend=mysql_num_rows($rpend))>0)
		$checkpend=true;
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
	if($xshow_area !='na' && sizeof($mapentry)>0)
	{
	//$startpoint="";
	$cid="";
	for($i=0;$i<sizeof($mapentry);$i++)
	{
		$coord=$mapentry[$i]["coords"];
		$mcolor=$mapentry[$i]["color"];
		$mtype=$mapentry[$i]["type"];
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
		colors[".$i."]=new Array();";
		if($mtype=='2')
		{
			echo "colors[".$i."][0]='#FF0000';";
			echo "colors[".$i."][1]='#FF0000';";
		}
		else
		{
			echo "colors[".$i."][0]='".$mcolor."';";
			echo "colors[".$i."][1]='".$mcolor."';";
		}
		echo "\r\n";
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
  if($xshow_area !='na')
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
  }
  function checkArray()
  {
	  var paths=new Array();
	  poly.getPath().forEach(function (vertex, inex) {
       // document.getElementById("info").innerHTML +=inex+">"+vertex.lat()+','+vertex.lng()+"<br/>";
		  paths[inex]=vertex.lat()+" "+vertex.lng();
      });
	  document.getElementById("farray").value=paths;
	  var confirmx = window.confirm("WARNING!!: YOU WILL BE SENT TO THE CONFIRMATION PAGE.  MAP WILL NOT BE SAVED YET UNTIL YOU GIVE THE FINAL CONFIRMATION IN THE NEXT PAGE!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	  if(confirmx==true)
	  {
	 	// return true;
		window.location.href="showmap2pre_edit.php?farray="+paths;
	  }
	  // else
	  //	 return false;
  }
  function refMap()
  {
	  var xadd=document.getElementById("xadd").value;
	  if(xadd.length>0)
		  window.location.href="showmap2.php?xadd="+xadd;
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
	  var xshow_area=document.getElementById("xshow_area").value;
	  xshow_area_chk="&xshow_area="+xshow_area;
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
	  	window.location.href='showmap2.php?xadd='+xadd+""+dateq+""+man_q+""+xshow_area_chk;
  }
  function findmap_show()
  {
	  var xshow_area=document.getElementById("xshow_area").value;
	  xshow_area_chk="xshow_area="+xshow_area;
	  window.location.href='showmap2.php?'+xshow_area_chk;
  }
  function printthis()
  {
	 var xshow_areas=document.getElementById("xshow_area").value;
	 var query="";
	 var xshow_area='no';
	 var pathquery='';
	 if(xshow_areas !='na')
	 {
		 xshow_area='yes';
	     var xadd=document.getElementById("xadd").value;
		 var date1=document.getElementById("date1").value;
		 var date2=document.getElementById("date2").value;
		 var man_sel=document.getElementById("xman").value;
		  var xshow_area=document.getElementById("xshow_area").value;
		  var xshow_area_chk="&xshow_area="+xshow_area;
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
		  query="xadd="+xadd+""+dateq+""+man_q+""+xshow_area_chk;
	 }
	 else
	 	query ="xshow_area="+xshow_area;
	var paths=new Array();
    poly.getPath().forEach(function (vertex, inex) {
		paths[inex]=vertex.lat()+" "+vertex.lng();
    });
	pathquery='&farray='+paths;
	 var newWin = window.open("printviewv2.php?"+query+""+pathquery+"&task=mix","Family_Energy_Map_System_Print_View",'width=880, height=800,resizable');
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
         Hello <u><?php echo $user["username"]; ?></u>, to begin Please click the area within the map to draw the area<br/><br/>
        <?php
		if($checkpend)
		{
			?>
        <div style="background:#F00; color:#FFF; font-size:15pt"><a href='showmap2_list.php?task=<?php echo base64_encode('predefined'); ?>' style="color:#FFF">You Have Drawed Areas That Are Not Official Yet, Do You Wish To Make Changes?</a></div>
        <?php
		}
		?>
         <div id="message2" name="message2" class="white" style="text-align:center">
         <?php
		if(isset($_SESSION["fmapresult"]))
        {
           echo $_SESSION["fmapresult"]."<br/>";
           unset($_SESSION["fmapresult"]);
        }
		?>
         </div>
		<form action="" method="">
   <input type="hidden" id="farray" name="farray" value="" />
   <fieldset>
   		<legend>Change Map: [<span style='font-size:15pt; font-style:italic'>
        <select id="xshow_area" name="xshow_area" onchange="findmap_show()">
        	<option value="na">Choose One</option>
            <option value="1" <?php echo setSelected($xshow_area_sel,'1'); ?>>Show Saved Area</option>
            <option value="2" <?php echo setSelected($xshow_area_sel,'2'); ?>>Show Pre-Defined Area</option>
            <option value="all" <?php echo setSelected($xshow_area_sel,'all'); ?>>Show All Area</option>
        </select>
        <!--<input type="checkbox" id="xshow_area" name="xshow_area" onclick="findmap_show()" <?Php //echo $xshow_area_sel; ?> />-->
        </span>]</legend>
        <div style="padding-left:80px;">
        <div style="float:left;"><input type="text" size="80" id="xadd" name="xadd" value="<?php echo rgetGEO($starpoint);  ?>" /></div>&nbsp;&nbsp;
     	<div style="float:left;"><a href="javascript:refMap()"><img src="images/ssearchbtn.jpg"  border="0" alt="Search" name="smap" /></a></div>
        <div style="clear:both"></div>
       </div>
    </fieldset>
    <?php
	if(!empty($xshow_area) && $xshow_area !='na')
	{
		?>
   <br/>
           <div>
        <fieldset>
   			<legend>Search For Saved Areas</legend>
         <div style="text-align:center">
         	<span style="font-size:16pt;">Saved Address:</span>&nbsp;
         	<input type="text" size="80" id="xadd" name="xadd" value="<?php echo $_REQUEST["xadd"]; ?>" />
        </div>
        <br/>
        <hr/>
        <div style="text-align:center">
            <span style="font-size:16pt;">Choose Managers:</span>
			&nbsp;&nbsp;
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
        <br/>
        <hr/>
        <div style="text-align:center; padding-left:80px;">
        	<div style="float:left;"><span style="font-size:16pt;">Date Range From:</span>&nbsp;</div>
            <input name="date1" id="date1" class="date-pick" readonly="readonly" value="<?Php echo $date1x; ?>" />
            <div style="float:left;">&nbsp;<span style="font-size:16pt;">To:</span>&nbsp;</div>
            <input name="date2" id="date2" class="date-pick" readonly="readonly" value="<?Php echo $date2x; ?>" />
            <div style="clear:both"></div>
        </div>
        <br/>
        <div style="text-align:center">
        	<a href="javascript:findmap()"><img src="images/schangemapbtn.jpg"  border="0" alt="Search" name="smap" /></a>    
        </div>
        </fieldset>
        </div>
     <?Php
	}
	?>
    <br/>
    	<div style="text-align:right">
         <a href="javascript:printthis()" onmouseover="document.print.src='images/printicon_r.jpg'" onmouseout="document.print.src='images/printicon.jpg'"><img src="images/printicon.jpg"  border="0" alt="Print" name="print" /></a>
         </div>
     	 <div id="map" style="width:100%; height:500px;"></div>
  		<div id="info">&nbsp;
        </div>
        <br/>
        <a href="javascript:checkArray()" onmouseover="document.create.src='images/createmapbtn_r.jpg'" onmouseout="document.create.src='images/createmapbtn.jpg'"><img src="images/createmapbtn.jpg"  border="0" alt="Create Map" name="create" /></a>
        &nbsp;&nbsp;&nbsp;
        <a href="javascript:goBack('showmap2.php')" onmouseover="document.reset.src='images/resetmapbtn_r.jpg'" onmouseout="document.reset.src='images/resetmapbtn.jpg'"><img src="images/resetmapbtn.jpg"  border="0" alt="Reset Map" name="reset" /></a>
        &nbsp;&nbsp;&nbsp;
        <a href="javascript:goBack('showmap2_all.php')" onmouseover="document.viewmap.src='images/viewmapbtn_r.jpg'" onmouseout="document.viewmap.src='images/viewmapbtn.jpg'"><img src="images/viewmapbtn.jpg"  border="0" alt="View All Maps" name="viewmap" /></a>
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