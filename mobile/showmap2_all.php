<?php
session_start();
include '../include/config.php';
include "../include/function.php";
include '../include/mapheader.php';
if(!checkMap2Access($user['id']))
{
	$_SESSION['fmapresult']='ERROR: You are not authorized to access the page';
	header("location:home.php");
	exit;
}
//fix date 1 for dropdown
$fromdate=explode("-",$date1);
$fromm=$fromdate[1];
$fromd=$fromdate[2];
$fromy=$fromdate[0];
//fix date 2 for dropdown
$todate=explode("-",$date2);
$tom=$todate[1];
$tod=$todate[2];
$toy=$todate[0];
$y_searchx=date('Y');
$y_search=$y_searchx-10;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script>
<script type="text/javascript" language="javascript" src="../js/jquery_lib.js"></script>
<link rel="stylesheet" href="../js/sw/spinningwheel.css" type="text/css" media="all" />
<script type="text/javascript" src="../js/sw/spinningwheel-min.js?v=1.4"></script>
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
		infox[".$i."][1]='<u>".getName($mapentry[$i]["userid"])."</u><br/>Sales Made: ".getMapSales($mapentry[$i]["userid"],$mapentry[$i]["date_worked"])."<br/>Date Work: ".fixdate_comps('invdate_s',$mapentry[$i]["date_worked"]);
		if(adminPrevb($user["type"]))
			echo "<br/><a href=\'showmap2_edit.php?id=".base64_encode($mapentry[$i]["id"])."\'>Edit This Map</a>";
		echo "';";
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
   <?Php
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
	  icon:'../images/miconb.png'
    });
    markers.push(marker);
    marker.setTitle("#" + path.length);
	//poly.runEdit(true);
	var xpoly=polys[mark];
	var xinfo=infox[mark][2];
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
	  var fromm=document.getElementById("fromm").value;
	  var fromd=document.getElementById("fromd").value;
	  var fromy=document.getElementById("fromy").value;
	  var date1="";
	  var date2="";
	  if(fromm !="" && fromd !="" && fromy !="")
	  	date1=fromy+"-"+fromm+"-"+fromd;
	  var tom=document.getElementById("tom").value;
	  var tod=document.getElementById("tod").value;
	  var toy=document.getElementById("toy").value;
	  if(tom !="" && tod !="" && toy !="")
	  	date2=toy+"-"+tom+"-"+tod;
	 // var date2=document.getElementById("date2").value;
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
	  	window.location.href='showmap2_all.php?xadd='+xadd+""+dateq+""+man_q;
  }

</script>
 </head>
   <body onload="initialize()">
   <form action="">
   <input type="hidden" id="farray" name="farray" value="" />
   		<div id="content">
        	<div class="show_this" style="font-size:12pt;color:#666; text-align:center; height:25px; padding-top:4px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[+] Show Advance Search</div>
      		<span class="slidingDiv" style="display:none">

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
        	<div><input type="button" value="Back" onclick="getBack('showmap2.php')" />
             &nbsp;&nbsp;<input type="button" value="Reset" onclick="getBack('showmap2_all.php')" /></div>
        </div>
        </div>
    </form>
   </body>
</html>
<?php
include '../include/unconfig.php';
?>