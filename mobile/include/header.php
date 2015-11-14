<div  style="text-align:center">
  <img src="../images/logot.png" border="0" width="288" height="90" />
</div>
<div style="background:#bdd630; height:30px; text-align:center; font-size:16pt; font-family:'rockw'">
	<div  style="padding-top:2px;">
    	Map System Login
    </div>
</div>
<div id="header_menu">
<?php
$ux = $_SESSION["fmap_user"];
if($mopen !="close" && pView($ux["type"]))
{
	?>
    <a class='contlinkc' href='showmap2.php'>View Map</a>&nbsp;&nbsp;
<?Php
}
?>
<a class='contlinkc' href='home.php'>Main Menu</a>&nbsp;&nbsp;
<a class='contlinkc' href='../logout.php'>Logout</a>&nbsp;
</div>