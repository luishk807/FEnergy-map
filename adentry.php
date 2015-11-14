<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$user=$_SESSION["fmap_user"];
$showmainbutton = true;
$id = base64_decode($_REQUEST["id"]);
if(empty($id))
{
	$_SESSION["fmapresult"]="Invalid Entry, please choose a File Entry";
	header('location:showmap.php');
	exit;
}
else
{
	$query = "select * from file_info where id='$id'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$userm = mysql_fetch_assoc($result);
			$fid = $userm["id"];
		}
		else
		{
			$_SESSION["fmapresult"]="Invalid Entry, please choose a File Entry";
			header('location:showmap.php');
			exit;
		}
	}
	else
	{
		$_SESSION["fmapresult"]="Invalid Entry, please choose a File Entry";
		header('location:showmap.php');
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<link rel="stylesheet" type="text/css" href="calendar_asset/css/ng_all.css">
<title>Welcome to Family Energy Map System</title>
</head>
<body onload="preload('createbtn_r.png,cancelbtn_r.png')">
<div id="main_cont">
    <div id="body_header">
    <?php include "include/topbutton.php"; ?>
    </div>
    <div id="body_middle_map">
   	  <div id="body_content" style="text-align:left"><div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>, in this page you can change address and setting</div>
   	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" onsubmit="return checkFieldi()">
            <input type="hidden" id="task" name="task" value="adentry"/>
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>"/>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle"><div id="message" name="message" class="white" style="text-align:center">
        &nbsp;
        <?php
                    if(isset($_SESSION["fmapresult"]))
                    {
                        echo $_SESSION["fmapresult"];
                        unset($_SESSION["fmapresult"]);
                    }
                 ?>
      </div> </td>
   	        </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">File Entry:</td>
    	      <td align="left" valign="middle">
              	&nbsp;&nbsp;<select id="fileid" name="fileid">
                	<option value="na">Select Filed Entry</option>
                    <?php
					$queryx = "select * from file_info order by id desc";
					if($rex = mysql_query($queryx))
					{
						if(($numx = mysql_num_rows($rex))>0)
						{
							while($rox = mysql_fetch_array($rex))
							{
								if(!empty($fid))
								{
									if($fid==$rox["id"])
										echo "<option value='".base64_encode($rox["id"])."' selected='selected'>".stripslashes($rox["name"])." - ".$rox["date"]."</option>";
									else
										echo "<option value='".base64_encode($rox["id"])."'>".stripslashes($rox["name"])." - ".$rox["date"]."</option>";
								}
								else
									echo "<option value='".base64_encode($rox["id"])."' >".stripslashes($rox["name"])." - ".$rox["date"]."</option>";
							}
						}
					}
					?>
                </select>
              </td>
  	      </tr>
    	    <tr>
    	      <td width="27%" height="37" align="right" valign="middle">Agent:</td>
    	      <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="aname" name="aname" size="60" value="" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Agent Code:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acode" name="acode" size="60" value="" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Manager:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="amanager" name="amanager" size="60" value="" /></td>
  	      </tr>
    	    <tr>
    	      <td height="73" align="right" valign="middle">Date:</td>
    	      <td align="left" valign="middle">&nbsp;
                   <input type="text" id="adate" name="adate"/>
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
								input: 'adate',	// the input field id
								start_date: 'year - 1',	// the start date (default is today)
								display_date: new Date()	// the display date (default is start_date)
							});
							
						});
					</script>
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Office:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="aoffice" name="aoffice" size="60" value=""/></td>
  	      </tr>
            <tr>
              <td height="37" colspan="2" align="right" valign="middle">&nbsp;</td>
            </tr>
            <tr>
    	      <td height="37" align="right" valign="middle">Total Gas:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="atgas" name="atgas" size="60" value="0"/></td>
  	      </tr>
          <tr>
    	      <td height="37" align="right" valign="middle">Total Power:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="atpower" name="atpower" size="60" value="0"/></td>
  	      </tr>
          <tr>
            <td height="37" colspan="2" align="right" valign="middle">&nbsp;</td>
            </tr>
          <tr>
    	      <td height="37" align="right" valign="middle"><u>Address Start:</u></td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;</td>
  	      </tr>
          <tr>
    	      <td height="37" align="right" valign="middle">Address:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="aaddress_s" name="aaddress_s" size="60" value=""/></td>
  	      </tr>
           <tr>
    	      <td height="37" align="right" valign="middle">City:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acity_s" name="acity_s" size="60" value=""/></td>
  	      </tr>
           <tr>
             <td height="37" align="right" valign="middle">State:</td>
             <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acountry_s" name="acountry_s" size="60" value=""/></td>
           </tr>
            <tr>
             <td height="37" align="right" valign="middle">Zip/Postal Code:</td>
             <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="azip_s" name="azip_s" size="60" value=""/></td>
           </tr>
           <tr>
             <td height="37" colspan="2" align="right" valign="middle">&nbsp;</td>
            </tr>
           <tr>
    	      <td height="37" align="right" valign="middle"><u>Address End:</u></td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;</td>
  	      </tr>
                    <tr>
    	      <td height="37" align="right" valign="middle">Address:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="aaddress_e" name="aaddress_e" size="60" value=""/></td>
  	      </tr>
           <tr>
    	      <td height="37" align="right" valign="middle">City:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acity_e" name="acity_e" size="60" value=""/></td>
  	      </tr>
           <tr>
             <td height="37" align="right" valign="middle">State:</td>
             <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acountry_e" name="acountry_e" size="60" value=""/></td>
           </tr>
            <tr>
             <td height="37" align="right" valign="middle">Zip/Postal Code:</td>
             <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="azip_e" name="azip_e" size="60" value=""/></td>
           </tr>
    	    <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="white" style="text-align:center">
        &nbsp;
      </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
              <a href="showmap.php" onmouseover="document.cancel.src='images/cancelbtn_r.png'" onmouseout="document.cancel.src='images/cancelbtn.png'"><img src="images/cancelbtn.png"  border="0" alt="Cancel and return to View Page" name="cancel" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image"  src="images/createbtn.png" onmouseover="javascript:this.src='images/createbtn_r.png';" onmouseout="javascript:this.src='images/createbtn.png';">
              </td>
  	      </tr>
    	    <tr>
    	      <td colspan="2" align="left" valign="middle">&nbsp;</td>
  	      </tr>
          </form>
        </table>
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