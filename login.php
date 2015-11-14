<?php
session_start();
include "include/config.php";
include "include/function.php";
$rlink=getLink();
if(empty($_SERVER['HTTP_REFERER']))
{
	$_SESSION["loginresult"]="Illegal entry detected";
	header("location:".$rlink."index.php");
	exit;
}
$uname = trim($_REQUEST["uname"]);
$upass = trim($_REQUEST["upass"]);
$query = "select * from task_users where (email='".clean($uname)."' or username = '".clean($uname)."') and password ='".md5(clean($upass))."'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		$row = mysql_fetch_assoc($result);
		adminReject($row["type"]);
		$user = array("id"=>$row["id"], "name"=>stripslashes($row["name"]),"username"=>stripslashes($row["username"]),"password"=>stripslashes($row["password"]),"email"=>stripslashes($row["email"]),'title'=>$row["title"],"status"=>$row["status"],"type"=>$row["type"],'acode'=>stripslashes($row["acode"]));
		adminstatus($row["status"]);
		$_SESSION["fmap_user"]=$user;
		header("location:".$rlink."home.php");
		exit;
	}
	else
	{
		$_SESSION["loginresult"]="Invalid Username And Password";
		header("location:".$rlink."index.php");
		exit;
	}
}
else
{
	$_SESSION["loginresult"]="System is unable to check your username and password, please try again later";
	unset($_SESSION["fmap_user"]);
	header("location:".$rlink."index.php");
	exit;
}
include "include/unconfig.php";
?>