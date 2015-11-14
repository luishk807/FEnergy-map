<?php
session_start();
include "include/function.php";
if(!isset($_SESSION["fmap"]) && !isset($_SESSION["fmap_user"]))
{
    $showbutton = false;
}
else
{
	adminlogin();
	$user=$_SESSION["fmap_user"];
	$showmainbutton = true;
}
if(isset($_SESSION["fmap_status"]))
{
	$status=$_SESSION["fmap_status"];
}
else
	$status=array("bad","Ops, Error Occured","Invalid Entry Detected, You need to login to access this page<br/><br/>Please Click <a href='index.php'>Here</a> To Login");
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
<body onload="preload('login_r.png,login.png')">
<div id="main_cont">
    <div id="body_header"><?php include "include/topbutton.php"; ?>
    </div>
    <div id="body_middle">
    	   <div id="body_content">
           	<div id="statusholder">
                    <div id="iconstatus">
                        <img src="images/check<?php echo $status[0]; ?>.jpg" border="0" alt="symbol status" />
                    </div>
                    <div id="bodystatus"> <span class='statustitle'><?php echo $status[1]; ?></span><br/><br/>
                   <?php echo $status[2]; ?>
                    </div>
                    <div class="cleardiv"></div>
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