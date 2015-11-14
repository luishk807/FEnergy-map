<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$user=$_SESSION["fmap_user"];
if($user["type"] != "1")
{
	header("location:status.php");
	exit;
}
unset($_SESSION["fmap"]);
unset($_SESSION["titlesearch"]);
unset($_SESSION["prevlink"]);
$showmainbutton = true;
$query = "select * from admin where id != '".$user["id"]."' and id !=1 order by date desc";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>50)
		$height ="";
	else
		$height="style='height:500px'";
}
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
<body onload="preload('savebtn_r.png,savebtn.png,cancelbtn.png,cancelbtn_r.png,createbtn_r.png,createbtn.png')">
<div id="main_cont">
    <div id="body_header">
    <?php include "include/topbutton.php"; ?>
    </div>
    <div id="body_middle" <?php echo $height; ?>>
   	  <div id="body_content" style="text-align:left">
      <div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>, Select admin you wish to see.  <a class='adminlink' href='setting.php'>Return To Previous Page?</a></div>
   	    <br/>
              <div style="text-align:center">
	  <?php
                    if(isset($_SESSION["fmapresult"]))
                    {
                        echo $_SESSION["fmapresult"];
                        unset($_SESSION["fmapresult"]);
                    }
                 ?>
      </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="background-color:#014681; color:#FFF">
    <td width="6%">&nbsp;</td>
    <td width="27%" align="center" valign="middle">Username</td>
    <td width="25%" align="center" valign="middle">Name</td>
    <td width="13%" align="center" valign="middle">Status</td>
    <td width="15%" align="center" valign="middle">Priviledge</td>
    <td width="14%" align="center" valign="middle">Created</td>
  </tr>
  <?php
  	$query = "select * from admin where id != '".$user["id"]."' order by date desc";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$count = 1;
			$total = 0;
			while($rows = mysql_fetch_array($result))
			{
				$total = $count %2;
				if($total !=0)
					$style = "style='background-color:#e6f882'";
				else
					$style="";
				echo "<tr class='rowstyle' $style><td height='27' align='center' valign='middle'>$count</td><td height='27' align='center' valign='middle'><a class='adminlink' href='settingm.php?id=".base64_encode($rows["id"])."'>".stripslashes($rows["username"])."</a></td><td height='27' align='center' valign='middle'>".stripslashes($rows["name"])."</td><td height='27' align='center' valign='middle'>".getStatus($rows["status"])."</td><td height='27' align='center' valign='middle'>".$rows["type_priv"]."</td><td height='27' align='center' valign='middle'>".$rows["date"]."</td></tr>";
				$count++;
			}
		}
		else
			echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No Admin Created</td></tr>";
	}
	else
		echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No Admin Created</td></tr>";
  ?>
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