<?php
session_start();
include "include/config.php";
include "include/function.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<title>Welcome to Family Energy Map System</title>
</head>
<body>
<div id="main_cont">
	<div id="body_header">&nbsp;
    </div>
    <div id="body_middle">
        <div id="body_content" style="height:600px">
        	Hello; to begin, please provide the email address used to create<br/> your Family Energy System Account<br/><br/>
          <div id="message2" name="message2" class="white" style="text-align:center">
          &nbsp;
          <?php
                    if(isset($_SESSION["fmapresult"]))
                    {
                        echo $_SESSION["fmapresult"]."<br/>";
                        unset($_SESSION["fmapresult"]);
                    }
                 ?>
          </div>
				<div style="text-align:center">
                <form method="post" action="fpass.php" onsubmit="return checkField_fp1()">
                <input type="hidden" id="ctx" name="ctx" value="<?Php echo md5("create"); ?>" />
                <h3>Email Address</h3>
                <input type="text" name="femail" id="femail" size="80" />
                <br/><br/>Or
                 <h3>Username</h3>
                <input type="text" name="uname" id="uname" size="80" />
                <br/><br/><br/><br/><br/>
                <a href="index.php"><img src="images/cancelbtn.png" border="0" alt="Cancel" /></a>
     			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image"  src="images/conbtn.png" onmouseover="javascript:this.src='images/conbtn_r.png';" onmouseout="javascript:this.src='images/conbtn.png';">
                </form>
                </div>
        </div>
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