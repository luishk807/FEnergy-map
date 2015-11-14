<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
$userm = $_SESSION["fmap_user"];
$id = base64_decode($_REQUEST["id"]);
$query = "select * from task_users where id='".clean($id)."'";
if($result = mysql_query($query))
{
	if(($num_rows= mysql_num_rows($result))>0)
		$user = mysql_fetch_assoc($result);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<title>Welcome To Family Energy Map System</title>
<script type="text/javascript" language="javascript" src="../js/script.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="../js/swfobject_modified.js" type="text/javascript"></script>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../css/styleie.css" />
<![endif]-->
 <link rel="stylesheet" type="text/css" href="../css/stylem.css" />
</head>

<body>
<?php
include "include/header.php";
?>
<div id="mbody">
<!--start-->
<?php
	$crud_ghost=getGHost();
	$crud_host=getHost();
	$crud_session=getSession();
	$crud_systemtitle=getSystemTitle();
	include "../../crud_lib/mobile/settingm.php";
?>
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>