<?Php
session_start();
include "include/config.php";
include "include/function.php";
unset($_SESSION["fmap_user"]);
unset($_SESSION["fmap"]);
unset($_SESSION["titlesearch"]);
unset($_SESSION["prevlink"]);
unset($_SESSION["searchin"]);
$_SESSION["loginresult"]="You Are Logout Successfully";
header('location:'.$rlink.'index.php');
exit;
include "include/unconfig.php";
?>