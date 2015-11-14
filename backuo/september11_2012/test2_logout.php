<?php
session_start();
include "include/config.php";
include "include/function.php";
unset($_SESSION["fmap_user"]);
$rlink=getLink();
$_SESSION["loginresult"]="SUCCESS: You are logout";
header("location:".$rlink."test2_login.php");
exit;
include "include/unconfig.php";
?>