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
	$_SESSION["fmapresult"]="Invalid Entry, please choose a Entry";
	header('location:showmap.php');
	exit;
}
else
{
	$query = "select * from file_entries where id='$id'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$address_s="";
			$city_s="";
			$country_s="";
			$zip_s="";
			$address_e="";
			$city_e="";
			$country_e="";
			$zip_e="";
			$userm = mysql_fetch_assoc($result);
			if(!empty($userm["address1"]))
			{
				$ada=explode(",",stripslashes($userm["address1"]));
				if(sizeof($ada)>0)
				{
					$address_s=$ada[0];
					$adb=explode(" ",$ada[1]);
					if(sizeof($adb)>2)
					{
						$city_s=$adb[1];
						$country_s=$adb[2];
						$zip_s=$adb[3];
					}
				}
				$ada=explode(",",stripslashes($userm["address2"]));
				if(sizeof($ada)>0)
				{
					$address_e=$ada[0];
					$adb=explode(" ",$ada[1]);
					if(sizeof($adb)>2)
					{
						$city_e=$adb[1];
						$country_e=$adb[2];
						$zip_e=$adb[3];
					}
				}
			}
		}
		else
		{
			$_SESSION["fmapresult"]="Invalid Entry, please choose a Entry";
			header('location:showmap.php');
			exit;
		}
	}
	else
	{
		$_SESSION["fmapresult"]="Invalid Entry, please choose a Entry";
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
<body onload="preload('savebtn_r.png,savebtn.png,createbtn_r.png,createbtn.png,viewadminbtn_r.png,viewadminbtn.png')">
<div id="main_cont">
    <div id="body_header">
    <?php include "include/topbutton.php"; ?>
    </div>
    <div id="body_middle_map">
   	  <div id="body_content" style="text-align:left"><div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>, in this page you can change address and setting</div>
   	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" onsubmit="return checkFieldg()">
            <input type="hidden" id="task" name="task" value="savead"/>
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
    	      <td width="27%" height="37" align="right" valign="middle">Agent:</td>
    	      <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="aname" name="aname" size="60" value="<?php echo stripslashes($userm["agent"]); ?>" readonly="readonly"/></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Agent Code:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acode" name="acode" size="60" value="<?Php echo $userm["agent_code"]; ?>" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Manager:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="amanager" name="amanager" size="60" value="<?Php echo stripslashes($userm["manager"]); ?>" /></td>
  	      </tr>
    	    <tr>
    	      <td height="73" align="right" valign="middle">Date:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp; <b><?php echo $userm["date"]; ?></b>&nbsp;<input type="checkbox" id="changeadate" name="changeadate" onclick="changeadatediv()" />&nbsp;<span class='changedate_style'>Change Date?</span>
                <input type="hidden" id="changeadates" name="changeadates" value="no" />
                <div id="allowadate" name="allowadate" style="display:none;">
                  &nbsp;&nbsp; <input type="text" id="adate" name="adate"/>
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
                  </div>
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Office:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="aoffice" name="aoffice" size="60" value="<?Php echo stripslashes($userm["office"]); ?>"/></td>
  	      </tr>
            <tr>
              <td height="37" colspan="2" align="right" valign="middle">&nbsp;</td>
            </tr>
            <tr>
    	      <td height="37" align="right" valign="middle">Total Gas:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="atgas" name="atgas" size="60" value="<?Php echo $userm["totalg"]; ?>"/></td>
  	      </tr>
          <tr>
    	      <td height="37" align="right" valign="middle">Total Power:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="atpower" name="atpower" size="60" value="<?Php echo $userm["totalp"]; ?>"/></td>
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
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="aaddress_s" name="aaddress_s" size="60" value="<?Php echo trim($address_s); ?>"/></td>
  	      </tr>
           <tr>
    	      <td height="37" align="right" valign="middle">City:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acity_s" name="acity_s" size="60" value="<?Php echo trim($city_s); ?>"/></td>
  	      </tr>
           <tr>
             <td height="37" align="right" valign="middle">Country:</td>
             <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acountry_s" name="acountry_s" size="60" value="<?Php echo trim($country_s); ?>"/></td>
           </tr>
            <tr>
             <td height="37" align="right" valign="middle">Zip/Postal Code:</td>
             <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="azip_s" name="azip_s" size="60" value="<?Php echo trim($zip_s); ?>"/></td>
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
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="aaddress_e" name="aaddress_e" size="60" value="<?Php echo trim($address_e); ?>"/></td>
  	      </tr>
           <tr>
    	      <td height="37" align="right" valign="middle">City:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acity_e" name="acity_e" size="60" value="<?Php echo trim($city_e); ?>"/></td>
  	      </tr>
           <tr>
             <td height="37" align="right" valign="middle">Country:</td>
             <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="acountry_e" name="acountry_e" size="60" value="<?Php echo trim($country_e); ?>"/></td>
           </tr>
            <tr>
             <td height="37" align="right" valign="middle">Zip/Postal Code:</td>
             <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="azip_e" name="azip_e" size="60" value="<?Php echo trim($zip_e); ?>"/></td>
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
      <?Php
	  if($user["type"] == "1")
	  {
		?>
      &nbsp;&nbsp;&nbsp;
      <a href="Javascript:deleteentry('<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='images/delete_r.png'" onmouseout="document.delete.src='images/delete.png'"><img src="images/delete.png"  border="0" alt="Delete This Entry" name="delete" /></a>
      <?php
	  }
	  ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image"  src="images/savebtn.png" onmouseover="javascript:this.src='images/savebtn_r.png';" onmouseout="javascript:this.src='images/savebtn.png';">
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