<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
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
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script type="text/javascript">

</script>
<title>Welcome to Family Energy Map System</title>
</head>
<body onload="initialize()">
<div id="main_cont">
    <div id="body_header"><?php include "include/topbutton.php"; ?>
    </div>
    <div id="body_middle">
        <div id="body_content">
         Hello <u><?php echo $user["username"]; ?></u>, to choose the predefined area please click in the desired list<br/><br/>
         <div id="message2" name="message2" class="white" style="text-align:center">
         <?php
		if(isset($_SESSION["fmapresult"]))
        {
           echo $_SESSION["fmapresult"]."<br/>";
           unset($_SESSION["fmapresult"]);
         }
		?>
         </div>
		<form action="" method="post">
     	<div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr class='cont_t_header'>
        <td width="6%" align="center" valign="middle">&nbsp;</td>
        <td width="74%" align="center" valign="middle">Last Area Marked</td>
        <td width="20%" align="center" valign="middle">Date Worked</td>
      </tr>
      <tr>
        <td colspan="3" align="center" valign="middle">
        <div <?php echo $pendheight; ?>>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
						 $total = $count %2;
						 if($total !=0)
							$pendstyle="class='rowstyle'";
						 else
							$pendstyle="class='rowstyleb'";
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
						echo "<tr ".$pendstyle.">
						<td width='6%' align='center' valign='middle'>".$count."</td>
						<td width='74%' align='center' valign='middle'><a href='showmap2_edit.php?task=".$_REQUEST["task"]."&id=".base64_encode($rowpend["id"])."' style='color:#000'>".checkNA($lastworkdx)."</a></td>
						<td width='20%' align='center' valign='middle'>".fixdate_comps('invdate_s',$rowpend["date_worked"])."</td>
						</tr>";
						 $count++;
					 }
				 }
				 else
				 	echo "<tr style='font-size:15pt; color:#FFF; background:#F00'><td colspan='3' align='center' valign='middle'>No Pending Area Found</td></tr>";
			 }
			 else
			 	echo "<tr style='font-size:15pt; color:#FFF; background:#F00'><td colspan='3' align='center' valign='middle'>No Pending Area Found</td></tr>";
		  ?>
          </table>
        </div>
        </td>
        </tr>
    </table>
        </div>
        <br/>
        <a href="javascript:getBack('showmap2.php')" onmouseover="document.previous.src='images/prevbtn_r.png'" onmouseout="document.previous.src='images/prevbtn.png'"><img src="images/prevbtn.png"  border="0" alt="Return" name="previous" /></a>
    </form>
        </div>
        <div style="height:150px;"></div>
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