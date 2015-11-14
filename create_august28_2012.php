<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$user=$_SESSION["fmap_user"];
unset($_SESSION["fmap"]);
unset($_SESSION["titlesearch"]);
unset($_SESSION["prevlink"]);
$showmainbutton = true;
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
<body onload="preload('cancelbtn.png,cancelbtn_r.png,createbtn_r.png,createbtn.png')">
<div id="main_cont">
    <div id="body_header">
    <?php include "include/topbutton.php"; ?>
    </div>
    <div id="body_middle">
   	  <div id="body_content" style="text-align:left"><div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>, Please fill up the form below to create a new admin</div>
   	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" onsubmit="return checkFieldf()">
            <input type="hidden" id="task" name="task" value="create"/>
             <input type="hidden" id="checktype" name="checktype" value="no" />
            <input type="hidden" id="checkreportt" name="checkreportt" value="no" />
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
    	      <td width="27%" height="37" align="right" valign="middle">Username:</td>
    	      <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="uname" name="uname" size="60" value="" /></td>
  	      </tr>
<tr>
                    <td width="27%" height="36" align="right" valign="middle">New Password: </td>
                    <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="password" id="newpass" name="newpass" size="60" value="" /></td>
                  </tr>
                  <tr>
                    <td height="36" align="right" valign="middle">Re-Type Password:</td>
                    <td align="left" valign="middle">&nbsp;&nbsp;<input type="password" id="renewpass" name="renewpass" size="60" /></td>
                  </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Name:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="realname" name="realname" size="60" value="" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Email:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="uemail" name="uemail" size="60" value="" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Title:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="utitle" name="utitle" size="60" value=""/></td>
  	      </tr>
    	    <tr>
    	      <td height="27" align="right" valign="middle">Status:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;  <select id="ustatus" name="ustatus">
                <?php
					$query = "select * from task_users_status order by id";
					if($result = mysql_query($query))
					{
						if(($num_rows = mysql_num_rows($result))>0)
						{
							while($rows = mysql_fetch_array($result))
							{
								echo "<option value='".$rows["id"]."'>".$rows["name"]."</option>";
							}
						}
					}
				?>
              </select>
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Type:</td>
    	      <td align="left" valign="middle">
              	&nbsp;&nbsp;
                <select id="utype" name="utype" onchange="allowofficeman(this.value)">
                <?php
					$query = "select * from task_admin_type order by id";
					if($result = mysql_query($query))
					{
						if(($num_rows = mysql_num_rows($result))>0)
						{
							while($rows = mysql_fetch_array($result))
							{
								echo "<option value='".$rows["id"]."'>".$rows["name"]."</option>";
							}
						}
					}
				?>
              </select>
              </td>
  	      </tr>
           	    <tr>
    	      <td  colspan="2" align="left" valign="middle">
              <div id="officemandiv" name="officemandiv" style="display:none">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="27%" height="37" align="right" valign="middle">Manager For Office:</td>
    	          <td width="73%" align="left" valign="middle">&nbsp;&nbsp;
                  <select id="officeman" name="officeman">
                  <option value="na">Select Office</option>
                  <?php
						$qo="select * from rec_office order by id";
						if($ro = mysql_query($qo))
						{
							if(($numro = mysql_num_rows($ro))>0)
							{
								while($rowo = mysql_fetch_array($ro))
								{
									echo "<option value='".base64_encode($rowo["id"])."'>".stripslashes($rowo["name"])."</option>";
								}
							}
						}
				  ?>
                  </select>
                  </td>
  	          </tr>
  	        </table>
            	</div>
            </td>
   	        </tr>
            <tr>
    	      <td  colspan="2" align="left" valign="middle">
              <div id="reporttodiv" name="reporttodiv" style="display:none">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="27%" height="37" align="right" valign="middle">Report To:</td>
    	          <td width="73%" align="left" valign="middle">&nbsp;&nbsp;
                  <select id="reportto" name="reportto">
                  <option value="na">Select Manager</option>
                  <?php
						$qo="select * from task_users where type='6' order by id";
						if($ro = mysql_query($qo))
						{
							if(($numro = mysql_num_rows($ro))>0)
							{
								while($rowo = mysql_fetch_array($ro))
								{
									echo "<option value='".base64_encode($rowo["id"])."'>".stripslashes($rowo["name"])."</option>";
								}
							}
						}
				  ?>
                  </select>
                  </td>
  	          </tr>
  	        </table>
            	</div>
            </td>
   	        </tr>
          <tr>
          	<td height="50" colspan="2">&nbsp;</td>
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
      <a href="setting.php" onmouseover="document.cancel.src='images/cancelbtn_r.png'" onmouseout="document.cancel.src='images/cancelbtn.png'"><img src="images/cancelbtn.png"  border="0" alt="Cancel and return to setting page" name="cancel" /></a>
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