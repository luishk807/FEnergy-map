<?php
session_start();
include "include/function.php";
adminlogin();
$showmainbutton=false;
if(isset($_SESSION["prevlink"]))
	$prevlink = $_SESSION["prevlink"];
else
	$prevlink = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
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
        	This System only reads Excel Files.  Please choose the import file and click the button "Upload"<br/><br/>
          <form enctype="multipart/form-data" action="readexcel4.php" method="post" onsubmit="return checkFieldc();">
  <input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
  <table width="100%">
  <tr>
  <td height="68" align="center" valign="middle">Choose the File:<br/>
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
      <input type="image"  src="images/conbtn.png" onmouseover="javascript:this.src='images/conbtn_r.png';" onmouseout="javascript:this.src='images/conbtn.png';">    </td>
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