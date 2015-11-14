<?php
session_start();
include "include/function.php";
adminlogin();
$user=$_SESSION["fmap_user"];
unset($_SESSION["fmap"]);
unset($_SESSION["titlesearch"]);
unset($_SESSION["prevlink"]);
$showmainbutton = false;
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
        	Hello <b><u><?php echo $user["username"]; ?></u></b>, To Begin, Please Choose One Of The Followings:<br/><br/>
        	<div id="home_icons">
            	<a href='import.php'><img src="images/nfilebtn.jpg" border="0" /></a>
                <br/><br/>
                <a href='showmap.php'><img src="images/mapbtn.jpg" border="0"  /></a>
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