<?php
session_start();
include '../include/config.php';
include "../include/function.php";
include('../include/mapheader.php');
adminloginx();
$user=$_SESSION["fmap_user"];
$xadd=$_REQUEST["xadd"];
$startpoint="40.714623,-74.006605";
if(!empty($xadd))
{
	$xstartpoint=getGEO($xadd);
	$startpoint=$xstartpoint['lat'].",".$xstartpoint['lng'];
}
$xshow_area=$_REQUEST["xshow_area"];
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
<link rel="stylesheet" href="../js/sw/spinningwheel.css" type="text/css" media="all" />
<script type="text/javascript" src="../js/sw/spinningwheel-min.js?v=1.4"></script>
<script type="text/javascript" language="javascript" src="../js/jquery_lib.js"></script>
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
 $(document).ready(function()
		{
			$(".slidingDiv").hide();
			$(".show_this").show();
			$('.show_this').click(function()
			{
				$(".slidingDiv").slideToggle();
			}
	);
  });
  var poly, polied, map;
  var saved_polys=new Array();
  var markers=[];
  var markers_saved=[];
  var path = new google.maps.MVCArray;
  var path_saved = new google.maps.MVCArray;
  
  var points=new Array();
  var colors=new Array();
  var infox=new Array();
  
  var infoBubble2;
  var cinfoBubble;
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
   <?php
  if($xshow_area=='yes')
  {
	  ?>
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
   <?Php
  }
  ?>
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
	  icon:'images/miconb.png'
    });
    markers_saved.push(marker);
    marker.setTitle("#" + path_saved.length);
	//poly.runEdit(true);
	var xpoly=saved_polys[mark];
	var xinfo=infox[mark][2];
	google.maps.event.addListener(xpoly, 'click', function() {
      document.getElementById("info").innerHTML = 'path_saved:[';
      xpoly.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<xpoly.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });
	
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
	  var xshow_area_chk="&xshow_area=yes";
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
  function findmap_show(value)
  {
	  var xshow_area_chk="";
	  if(value=='yes')
	  	xshow_area_chk="xshow_area=yes";
	  else
	  	xshow_area_chk="xshow_area=no";
	  window.location.href='test3.php?'+xshow_area_chk;
  }
</script>
 </head>
   <body onload="initialize()">
   <form action="../test3_action.php" method="post" onsubmit="return checkArray();">
   <input type="hidden" id="farray" name="farray" value="" />
   <input type="hidden" id="task" name="task" value="create" />
   <input type="hidden" id="xarea_show" name="xarea_show" value="" />
   		<div id="content">
        	<?Php
			if($xshow_area=='yes')
			{
				?>
             <div class="show_this" style="font-size:12pt;color:#666; text-align:center; height:25px; padding-top:4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[+] Show Advance Search</div>
      		<span class="slidingDiv" style="display:none">
                <input type="button" value='Hide Area Saved' onclick="findmap_show('no')"/>
                <br/>
                <hr/>   
                Address:&nbsp;<input type="text" size="20" id="xadd" name="xadd" value="<?php echo $_REQUEST["xadd"]; ?>" />&nbsp;&nbsp;<!--<input type="button" value="Find Map" onclick="findmap()" />-->
               <br/>
                <hr/>
               Choose Managers:<br/>
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
                <br/>
                <hr/>
            Date:<br/>
            From:&nbsp;<select id="fromm" name="fromm">
            <option value='na'>Month</option>
            	<?php
				for($x=1;$x<13;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					$is_selected=setSelected($xb,$fromm);
					echo "<option value='$xb' $is_selected>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="fromd" name="fromd">
            <option value='na'>Day</option>
            	<?php
				for($x=1;$x<32;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					$is_selected=setSelected($xb,$fromd);
					echo "<option value='$xb' $is_selected>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="fromy" name="fromy">
            	<?php
				$county=$fromy;
				while($county != $y_search)
				{
					$is_selected=setSelected($county,$fromy);
					echo "<option value='$county' $is_selected>$county</option>";
					$county--;
				}
				?>
            </select>
            <!--To-->
            <br/>To:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="tom" name="tom">
            <option value='na'>Month</option>
            	<?php
				for($x=1;$x<13;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					$is_selected=setSelected($xb,$tom);
					echo "<option value='$xb' $is_selected>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="tod" name="tod">
            <option value='na'>Day</option>
            	<?php
				for($x=1;$x<32;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					$is_selected=setSelected($xb,$tod);
					echo "<option value='$xb' $is_selected>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="toy" name="toy">
            	<?php
				$county=$toy;
				while($county != $y_search)
				{
					$is_selected=setSelected($county,$toy);
					echo "<option value='$county' $is_selected>$county</option>";
					$county--;
				}
				?>
            </select>
            <br/><br/>
               <input type="button" value="Search Map" onclick="findmap()" />
            </span>
            <?php
			}
			else
			{
			?>
        	Address:&nbsp;<input type="text" size="10" id="xadd" name="xadd" value="<?php echo rgetGEO($starpoint);  ?>" />&nbsp;&nbsp;<input type="button" value="Search" onclick="refMap()"/>&nbsp;&nbsp;<input type="button" value='Show Saved' onclick="findmap_show('yes')"/>
            <?php
			}
			?>
             <!--&nbsp;&nbsp;<input type="button" value="Logout" onclick="goBack('../test2_logout.php')" />-->
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
        	<div><input type="submit" value="Create Map" />
            &nbsp;&nbsp; <select id="mapmenu" name="mapmenu" onchange="goBack(this.value)">
                <option value="">Choose</option>
            	<option value="test3.php">Reset Map</option>
                <option value="test2_view_all.php">View All Map</option>
                <option value="../test2_logout.php">Logout</option>
             </select>
            <!--&nbsp;&nbsp;&nbsp;<input type="button" value="Reset Map" onclick="goBack('test3.php')" />&nbsp;&nbsp;&nbsp;<input type="button" value="View All Maps" onclick="goBack('test2_view_all.php')" />--></div>
        </div>
    </form>
   </body>
</html>
<?php
include '../include/unconfig.php';
?>