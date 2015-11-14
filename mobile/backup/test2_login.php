<?Php
session_start();
include "../include/function.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome To Family Energy Map System</title>
<script type="text/javascript" language="javascript" src="../js/script.js"></script>
<link rel="icon" type="image/png" href="../images/favicon.ico">
 <style>
html, body {
   height: 100%;
   margin: 0;
   }
#content {
   min-height: 100%;
   height: auto !important;
   height: 100%;
   margin: 0 auto -40px;
   }
#footer, #push {
   height: 40px;
   }
  .mobiletext{
	height:50px;
	width:600px;
	font-size:25pt;
}
#message2m{
	font-size:25pt;
	color:#000;
	font-family: 'rockw';
}
</style>
</head>

<body>
<div  style="text-align:center">
  <img src="../images/logobig.png" border="0" />
</div>
<div id="loginheaderb" style="background:#00477f;color:#FFF; height:80px; text-align:center; font-size:25pt; font-family:'rockw'">
	<div id="loginheaderb_in" style="padding-top:20px;">
    	Map System Login
    </div>
</div>
<div id="mlogincont" style="text-align:center; padding-top:80px;">
 <form action="../test2_login_action.php" method="post" onsubmit="return checkField_m()" >
     <div id="message2m" name="message2m">
     &nbsp;
     <?php
     if(isset($_SESSION["loginresult"]))
     {
        echo $_SESSION["loginresult"]."<br/>";
        unset($_SESSION["loginresult"]);
     }
     ?>
     </div>
     <span style="font-family:'agentfb'; color:#000;font-size:25pt;">Username:</span>
     <br/>
     <input type="text" size="100" id="uname" name="uname" class='mobiletext'/>
     <br/>
     <br/><br/>
     <span style="font-family:'agentfb'; color:#000;font-size:25pt">Password:</span>
     <br/>
      <input type="password" size="100" id="upass" name="upass" class='mobiletext' />
     <br/><br/><br/>
     <input type="image"  src="../images/loginbtnm.png" onmouseover="javascript:this.src='../images/loginbtnm.png';" onmouseout="javascript:this.src='../images/loginbtnm.png';">
</form>        
</div>
</body>
</html>