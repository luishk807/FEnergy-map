<?Php
session_start();
include "../include/function.php";
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
<link rel="icon" type="image/png" href="../images/favicon.ico">
 <style>
html, body {
   height: 100%;
   margin: 0;
   }
</style>
</head>

<body>
<div  style="text-align:center">
  <img src="../images/logot.png" border="0" width="288" height="90" />
</div>
<div style="background:#bdd630; height:30px; text-align:center; font-size:16pt; font-family:'rockw'">
	<div  style="padding-top:2px;">
    	Map System Login
    </div>
</div>
<div style="text-align:center; padding-top:5px">
 <form action="../login.php" method="post" onsubmit="return checkField()" >
     <div id="message2" name="message2">
     &nbsp;
     <?php
     if(isset($_SESSION["loginresult"]))
     {
        echo $_SESSION["loginresult"]."<br/>";
        unset($_SESSION["loginresult"]);
     }
     ?>
     </div>
     <span style="font-family:'agentfb'; color:#000;font-size:16pt;">Username:</span>
     <br/>
     <input type="text" size="30" style='height:20px;font-size:12pt;' id="uname" name="uname"/>
     <br/>
     <br/>
     <span style="font-family:'agentfb'; color:#000;font-size:16pt">Password:</span>
     <br/>
      <input type="password" size="30" style='height:20px;font-size:12pt;'  id="upass" name="upass" />
     <br/><br/><br/>
     <input type="image"  src="../images/login.png" width="133" height="40">
</form>        
</div>
</body>
</html>