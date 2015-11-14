<?php
session_start();
include "include/config.php";
include "include/function.php";
$cx=$_REQUEST["cx"];
date_default_timezone_set('America/New_York');
$today=date('Y-m-d');
if(empty($cx))
{
	$_SESSION["loginresult"]="ERROR:Invalid Entry";
	header("location:index.php");
	exit;
}
else
{
	$query="select * from task_users where fpass_code='".clean($cx)."' and fpass_date='".$today."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			$info=mysql_fetch_assoc($result);
		else
		{
			$_SESSION["loginresult"]="ERROR:Password Reset Invalid or Expired";
			header("location:index.php");
			exit;
		}
	}
	else
	{
		$_SESSION["loginresult"]="ERROR: System Failure, Unable To Continue";
		header("location:index.php");
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
<title>Welcome to Family Energy Map System</title>
</head>
<body>
<div id="main_cont">
	<div id="body_header">&nbsp;
    </div>
    <div id="body_middle">
        <div id="body_content" style="height:600px">
        	Hello <b><u><?Php echo $info["username"]; ?></u></b>, to reset your password please type your new password.<br/><br/>
          <div id="message2" name="message2" class="white" style="text-align:center">
          &nbsp;
          <?php
                    if(isset($_SESSION["fmapresult"]))
                    {
                        echo $_SESSION["fmapresult"]."<br/>";
                        unset($_SESSION["fmapresult"]);
                    }
                 ?>
          </div>
				  <div style="text-align:center">
                <form method="post" action="fpass.php" onsubmit="return checkField_fp2()">
                <input type="hidden" id="ctx" name="ctx" value="<?Php echo md5("reset"); ?>" />
                <input type="hidden" id="cx" name="cx" value="<?Php echo $_REQUEST["cx"]; ?>" />
                <h3>New Password</h3>
                <input type="password" name="fpass" id="fpass" size="80" />
               	<br/>
                 <h3>Re-Type Your Password</h3>
                <input type="password" name="rfpass" id="rfpass" size="80" />
                <br/><br/><br/><br/><br/>
                <a href="index.php"><img src="images/cancelbtn.png" border="0" alt="Cancel" /></a>
     			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image"  src="images/conbtn.png" onmouseover="javascript:this.src='images/conbtn_r.png';" onmouseout="javascript:this.src='images/conbtn.png';">
                </form>
                </div>
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