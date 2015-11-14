<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
//redirect();
$user=$_SESSION["fmap_user"];
$mopen="close";
$checkpend=false;
$qpend="select * from map_entry where type='2' and userid='".$user["id"]."'";
if($rpend=mysql_query($qpend))
{
	if(($numpend=mysql_num_rows($rpend))>0)
		$checkpend=true;
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
<div id="mbody_home">
     <div id="message2" name="message2">
     &nbsp;
     <?php
     if(isset($_SESSION["fmapresult"]))
     {
        echo $_SESSION["fmapresult"]."<br/>";
        unset($_SESSION["fmapresult"]);
     }
     ?>
     </div>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='showmap2.php'>View Map</a>
         </div>
    </div>
    <?php
		if($checkpend)
		{
	?>
    <br/><br/>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='showmap2_list.php?task=<?php echo base64_encode('predefined'); ?>'>Temporal Map</a>
         </div>
    </div>
    <?php
		}
	?>
    <br/><br/>
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>