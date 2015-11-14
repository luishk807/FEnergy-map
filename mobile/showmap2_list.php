<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
//redirect();
$user=$_SESSION["fmap_user"];
if(!checkMap2Access($user['id']))
{
	$_SESSION['fmapresult']='ERROR: You are not authorized to access the page';
	header("location:home.php");
	exit;
}
$showmainbutton=true;
$qpend="select * from map_entry where type='2' and userid='".$user["id"]."' order by date_worked";
$pendheight="";
if($rpend=mysql_query($qpend))
{
	if(($numpend=mysql_num_rows($rpend)) < 5)
	{
		if($numpend <2)
		{
			$infopend=mysql_fetch_assoc($rpend);
			header("location:showmap2_edit.php?task=".$_REQUEST["task"]."&id=".base64_encode($infopend["id"]));
			exit;
		}
		$pendheight="style='height:500px'";
	}
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
<br/>
<div id="mbody_home">
<form action="../fmapv2_action.php" method="post" onsubmit="return checkArray();">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr  style='background-color:#014681; color:#FFF;'>
        <td width="80%" align="center" valign="middle">Last Area Marked</td>
        <td width="20%" align="center" valign="middle">Worked</td>
      </tr>
          <?php
		  	 if($rpend=mysql_query($qpend))
			 {
				 if(($numpend=mysql_num_rows($rpend))>0)
				 {
					 $count=1;
		 			 $total=0;
					 $pendstyle="";
					 while($rowpend=mysql_fetch_array($rpend))
					 {
					    $lastworkdx="";
						$qpendx="select * from map_coords where entryid='".$rowpend["id"]."' order by date desc limit 1";
						if($rpendx=mysql_query($qpendx))
						{
							if(($numpendx=mysql_num_rows($rpendx))>0)
							{
								$infopend=mysql_fetch_assoc($rpendx);
								$lastworkdx=$infopend["address"];
							}
						}
						echo "<tr><td colspan='2' align='center' valign='middle'><hr/></td></tr>";
						echo "<tr>
						<td width='80%' align='center' valign='middle'><a href='showmap2_edit.php?task=".$_REQUEST["task"]."&id=".base64_encode($rowpend["id"])."' style='color:#000'>".checkNA($lastworkdx)."</a></td>
						<td width='20%' align='center' valign='middle'>".fixdate_comps('invdate_s',$rowpend["date_worked"])."</td>
						</tr>";
						 $count++;
					 }
				 }
				 else
				 	echo "<tr style='font-size:15pt; color:#FFF; background:#F00'><td colspan='2' align='center' valign='middle'>No Pending Area Found</td></tr>";
			 }
			 else
			 	echo "<tr style='font-size:15pt; color:#FFF; background:#F00'><td colspan='2' align='center' valign='middle'>No Pending Area Found</td></tr>";
		  ?>
    </table>
    </form>
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>