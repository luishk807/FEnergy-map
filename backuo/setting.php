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
<body onload="preload('savebtn_r.png,savebtn.png,createbtn_r.png,createbtn.png,viewadminbtn_r.png,viewadminbtn.png')">
<div id="main_cont">
    <div id="body_header">
    <?php include "include/topbutton.php"; ?>
    </div>
    <div id="body_middle" style="height:500px">
   	  <div id="body_content" style="text-align:left"><div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>, in this page you can change your settings</div>
   	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" onsubmit="return checkFielde()">
          	<input type="hidden" id="changepass" name="changepass" value="no"/>
            <input type="hidden" id="task" name="task" value="save"/>
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
    	      <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="uname" name="uname" size="60" value="<?php echo $user["username"]; ?>" readonly="readonly" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Change Password?:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="checkbox" id="checkchange" name="checkchange" onclick="allowpassword()" /></td>
  	      </tr>
    	    <tr>
    	      <td  colspan="2" align="right" valign="middle">
              <div id="allowpassworddiv" name="allowpassworddiv" style="display:none">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="27%" height="36" align="right" valign="middle">New Password: </td>
                    <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="password" id="newpass" name="newpass" size="60" /></td>
                  </tr>
                  <tr>
                    <td height="36" align="right" valign="middle">Re-Type Password:</td>
                    <td align="left" valign="middle">&nbsp;&nbsp;<input type="password" id="renewpass" name="renewpass" size="60" /></td>
                  </tr>
                </table>

              </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Name:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="realname" name="realname" size="60" value="<?Php echo $user["name"]; ?>" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Email:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="uemail" name="uemail" size="60" value="<?Php echo $user["email"]; ?>" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Title:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="utitle" name="utitle" size="60" value="<?Php echo $user["title"]; ?>"/></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Status:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;  <select id="ustatus" name="ustatus" disabled="disabled">
                <?php
					$query = "select * from admin_status order by id";
					if($result = mysql_query($query))
					{
						if(($num_rows = mysql_num_rows($result))>0)
						{
							while($rows = mysql_fetch_array($result))
							{
								if(isset($user["status"]))
								{
									if($user["status"]==$rows["id"])
										echo "<option value='".$rows["id"]."' selected='selected'>".$rows["name"]."</option>";
									else
										echo "<option value='".$rows["id"]."'>".$rows["name"]."</option>";
								}
								else
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
                <select id="utype" name="utype" disabled="disabled">
                <?php
					$query = "select * from type_admin order by id";
					if($result = mysql_query($query))
					{
						if(($num_rows = mysql_num_rows($result))>0)
						{
							while($rows = mysql_fetch_array($result))
							{
								if(isset($user["type"]))
								{
									if($user["type"]==$rows["id"])
										echo "<option value='".$rows["id"]."' selected='selected'>".$rows["name"]."</option>";
									else
										echo "<option value='".$rows["id"]."'>".$rows["name"]."</option>";
								}
								else
									echo "<option value='".$rows["id"]."'>".$rows["name"]."</option>";
							}
						}
					}
				?>
              </select>
              </td>
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
              <?php
			  if($user["type"]=="1")
			  {
				  ?>
              <a href="create.php" onmouseover="document.create.src='images/createbtn_r.png'" onmouseout="document.create.src='images/createbtn.png'"><img src="images/createbtn.png"  border="0" alt="Create A New Admin" name="create" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="view.php" onmouseover="document.view.src='images/viewadminbtn_r.png'" onmouseout="document.view.src='images/viewadminbtn.png'"><img src="images/viewadminbtn.png"  border="0" alt="View All Admin Admin" name="view" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      		<?php
			  }
			  ?>
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