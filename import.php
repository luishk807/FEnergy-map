<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$showmainbutton=false;
if(isset($_SESSION["prevlink"]))
	$prevlink = $_SESSION["prevlink"];
else
	$prevlink = "";
date_default_timezone_set('UTC');
$xdate = date("Y-m-d");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="calendar_asset/css/ng_all.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<title>Welcome to Family Energy Map System</title>
</head>
<body onload="preload('homebtn.png,homebtn_r.png,prevbtn_r.png,prevbtn.png,conbtn_r.png,conbtn.png')">
<div id="main_cont">
    <div id="body_header"><?php include "include/topbutton.php"; ?>
    </div>
    <div id="body_middle">
        <div id="body_content">
        	This System only reads Excel Files.  Please choose the import file and click the button "Continue"<br/><br/>
          <form enctype="multipart/form-data" action="readexcel4.php" method="post" onsubmit="return checkFieldc();">
  <input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
  <table width="100%">
   <tr>
  <td height="68" align="center" valign="middle"><span class='importSub'>Step 1.</span> &nbsp;Please Provide An Office Name For This File <span class='importSub'>(do not write a date)</span>:<br/>
    <input type="text" name="filename" id="filename" size="80" /> <br/>
    <br/>
    <img src="images/checkinlineb.jpg" alt="line" border="0" />
    <br/>
	<fieldset style="width:500px;">
    <legend><span class='importSub'>Step 2.</span> &nbsp;Date of File</legend>
    <span style="font-size:14pt; color:#F00"><u>IMPORTANT:</u> System will give a date for this file entry, if you want to change the date you can click the change checkbox.  System will only acknowledge this date entry for search engine.</span><br/><br/>
    Date:&nbsp; <span class="importSub"><?php echo $xdate; ?></span>&nbsp;&nbsp;Change? <input type="checkbox" id="cxdate" name="cxdate" onclick="allowxdate()" /> &nbsp;&nbsp;
    <span id="xdiv_date" style="display:none;padding-top:5px;">
            	<input type="text" id="datex" name="datex" />
                <script type="text/javascript">
						var ng_config = {
							assests_dir: 'calendar_asset/'	// the path to the assets directory
						}
					</script>
					<script type="text/javascript" src="js/calendar_js/ng_all.js"></script>
					<script type="text/javascript" src="js/calendar_js/calendar.js"></script>
					<script type="text/javascript">
					var my_cal;
					ng.ready(function(){
							// creating the calendar
							my_cal = new ng.Calendar({
								input: 'datex',	// the input field id
								start_date: 'year - 1',	// the start date (default is today)
								display_date: new Date()	// the display date (default is start_date)
							});
							
						});
					</script>
            </span>
    <input type="hidden" id="cxdatex" name="cxdatex" value="no" />
    </fieldset>
    </td>
  </tr>
  <tr>
  <td height="68" align="center" valign="middle">
  <br/>
    <img src="images/checkinlineb.jpg" alt="line" border="0" /><br/>
  <span class='importSub'>Step 3.</span> &nbsp;Choose the File:<br/>
    <input type="file" name="file" id="file" size="60" /></td>
  </tr>
  <tr>
    <td align="left" valign="middle">
      <div id="message2" name="message2" class="white" style="text-align:center">
        &nbsp;
        <?php
                    if(isset($_SESSION["fmapresult"]))
                    {
                        echo $_SESSION["fmapresult"];
                        unset($_SESSION["fmapresult"]);
                    }
                 ?>
      </div>    </td>
    </tr>
  <tr>
    <td height="151" align="center" valign="middle">
    <?php
	if(!empty($prevlink))
	{
		?>
      <a href="<?php if(!empty($prevlink)) echo $prevlink; else echo "home.php"; ?>" onmouseover="document.cancel.src='images/prevbtn_r.png'" onmouseout="document.cancel.src='images/prevbtn.png'"><img src="images/prevbtn.png"  border="0" alt="Go To Previous Page" name="cancel" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="home.php" onmouseover="document.home.src='images/homebtn_r.png'" onmouseout="document.home.src='images/homebtn.png'"><img src="images/homebtn.png"  border="0" alt="Go To Home Page" name="home" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php
	}
	else
	{
		?>
   <a href="home.php" onmouseover="document.home.src='images/prevbtn_r.png'" onmouseout="document.home.src='images/prevbtn.png'"><img src="images/prevbtn.png"  border="0" alt="Go To Previous Page" name="home" /></a>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php
	}
	?>
      <span id="contbuton"><input type="image"  src="images/conbtn.png" onmouseover="javascript:this.src='images/conbtn_r.png';" onmouseout="javascript:this.src='images/conbtn.png';"></span>  
      <span id="loadergif" style="display:none">
      	<img src="images/loader.gif" border="0" />
      </span>
      <?Php
	  if(checkMapAccess())
	  {
      ?>
      <a href="showmap.php" onmouseover="document.gmap.src='images/gomapbtn_r.jpg'" onmouseout="document.gmap.src='images/gomapbtn.jpg'"><img src="images/gomapbtn.jpg"  border="0" alt="Go To Map" name="gmap" /></a>
      <?php
	  }
	  ?>
       </td>
    </tr>
  </table>
  </form>
        </div>
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