<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
if(empty($_SERVER['HTTP_REFERER']))
{
	header("location:status.php");
	exit;
}
$task = $_REQUEST["task"];
if($task=="save")
{
	$user = $_SESSION["fmap_user"];
	$uname = trim($_REQUEST["uname"]);
	if(stripslashes($user["username"]) != $uname)
	{
		$queryx = "select * from task_users where username='".clean($uname)."' and id !='".$user["id"]."'";
		if($resultx = mysql_query($queryx))
		{
			if(($numrowsx = mysql_num_rows($resultx))>0)
			{
				$_SESSION["fmapresult"]="ERROR: Username is taken, please choose another username";
				header('location:setting.php');
				exit;
			}
		}
	}
	$upass = trim($_REQUEST["newpass"]);
	$changepass = $_REQUEST["changepass"];
	$newpass = trim($_REQUEST["newpass"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$reportto = base64_decode($_REQUEST["reportto"]);
	$officeman = base64_decode($_REQUEST["officeman"]);
	$email =trim(strtolower($_REQUEST["uemail"]));
	//$status =$_REQUEST["ustatus"];
	//$type = $_REQUEST["utype"];
	$title = trim(ucwords(strtolower($_REQUEST["utitle"])));
	if($changepass=="yes")
		$query = "update task_users set username='".clean($uname)."',password='".md5(clean($newpass))."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."'  where id='".$user["id"]."'";
	else
		$query = "update task_users set username='".clean($uname)."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."' where id='".$user["id"]."'";
	if($result = mysql_query($query))
		$_SESSION["fmapresult"]="SUCCESS: Changes Saved";
	else
		$_SESSION["fmapresult"]="ERROR: Unable To Save Changes";
	$query = "select * from task_users where id='".$user["id"]."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			$user = array("id"=>$row["id"], "name"=>stripslashes($row["name"]),"username"=>stripslashes($row["username"]),"password"=>stripslashes($row["password"]),"email"=>stripslashes($row["email"]),'title'=>$row["title"],"status"=>$row["status"],"type"=>$row["type"]);
			adminstatus($row["status"]);
			$_SESSION["fmap_user"]=$user;
			header("location:setting.php");
			exit;
		}
		else
		{
			$_SESSION["fmap_status"]=array("bad","Invalid Username And Password","You need to login to access this page<br/><br/>Please Click <a href='index.php'>Here</a> To Login");
			unset($_SESSION["fmap_user"]);
			header("location:status.php");
			exit;
		}
	}
	else
	{
		$_SESSION["loginresult"]="System is unable to check your username and password, please try again later";
		unset($_SESSION["fmap_user"]);
		header("location:index.php");
		exit;
	}
}
else if($task=="create")
{
	$uname = trim($_REQUEST["uname"]);
	$upass = trim($_REQUEST["newpass"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$email =trim(strtolower($_REQUEST["uemail"]));
	$reportto = base64_decode($_REQUEST["reportto"]);
	$officeman = base64_decode($_REQUEST["officeman"]);
	$status =$_REQUEST["ustatus"];
	$type = $_REQUEST["utype"];
	$title = trim(ucwords(strtolower($_REQUEST["utitle"])));
	$query = "select * from task_users where username='".clean($uname)."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$_SESSION["fmapresult"]="ERROR: Username already exist, please another username";
			header("location:create.php");
			exit;
		}
	}
	if($officeman !='na' && !empty($officeman))
		$officemanq="'".$officeman."'";
	else
		$officemanq="NULL";
	if($reportto !='na' && !empty($reportto))
		$reporttoq="'".$reportto."'";
	else
		$reporttoq="NULL";
	$query = "insert ignore into task_users(username,password,name,title,email,status,type,date,office,report_to)values('".clean($uname)."','".md5(clean($upass))."','".clean($name)."','".clean($title)."','".clean($email)."','".$status."','".$type."',NOW(),$officemanq,$reporttoq)";
	if($result = mysql_query($query))
	{
		$_SESSION["fmapresult"]="SUCCESS: User Created";
				$_SESSION["fmapresult"]="SUCCESS: User Created";
		$host = "http://www.familyenerymap.com/";
		if(!empty($email))
		{
			$title = "Family Energy Map System Access Information";
			$message ="Hello $name<br/><br/>";
			$message .="This email is to confirm that your account for the Family Energy Map System is setup and ready to use.<br/><br/>";
			$message .="Please click the link the link below to access the site and use the username and password in this email. You can always change your settings at the setting page locate at the top of the website after you logged in.<br/><br/>";
			$message .="<a href='http://www.familyenergymap.com' target='_blank'>Go To The Map System Now</a><br/><br/>";
			$message .="Username: <b>".$uname."</b><br/>";
			$message .="Password: <b>".$upass."</b><br/><br/>";
			$message .="Attn,<br/><br/>";
			$message .="Family Energy Team<br/>";
			$headers  = 'MIME-Version: 1.0'."\r\n";
			$headers .='Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .="From: FamilyEnergy Map System<no-reply@familyenergymap.com>\r\n"."X-Mailer: PHP/".phpversion();
			if($result = mail($email,$title, $message,$headers))
				$_SESSION["fmapresult"]="SUCCESS: User Created and Email Sent";
		}
	}
	else
		$_SESSION["fmapresult"]="ERROR: Unable To Create User";
	header('location:setting.php');
	exit;	
}
else if($task=="savem")
{
	$userid = base64_decode($_REQUEST["id"]);
	$uname = trim($_REQUEST["uname"]);
	$query = "select * from task_users where id='".$userid."'";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			$checkuser = mysql_fetch_assoc($result);
			if(stripslashes($checkuser["username"]) != $uname)
			{
				$queryx = "select * from task_users where username='".clean($uname)."' and id !='".$userid."'";
				if($resultx = mysql_query($queryx))
				{
					if(($numrowsx = mysql_num_rows($resultx))>0)
					{
						$_SESSION["fmapresult"]="ERROR: Username is taken, please choose another username";
						header('location:settingm.php?id='.base64_encode($userid));
						exit;
					}
				}
			}
		}
	}
	$upass = trim($_REQUEST["newpass"]);
	$changepass = $_REQUEST["changepass"];
	$newpass = trim($_REQUEST["newpass"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$reportto = base64_decode($_REQUEST["reportto"]);
	$officeman = base64_decode($_REQUEST["officeman"]);
	$email =trim(strtolower($_REQUEST["uemail"]));
	$status =$_REQUEST["ustatus"];
	$type = $_REQUEST["utype"];
	$title = trim(ucwords(strtolower($_REQUEST["utitle"])));
	if($type=='6' || $type=='5')
	{
		if($officeman !='na' && !empty($officeman))
			$officemanq = ",office='".$officeman."' ";
		else
			$officemanq=",office=NULL ";
		if($type=='6')
			$reporttoq=",report_to=NULL ";
		else
		{
			if($reportto !='na' && !empty($reportto))
				$reporttoq = ",report_to='".$reportto."' ";
			else
				$reporttoq=",report_to=NULL ";
		}
	}
	else
	{
		$officemanq=",office=NULL ";
		$reporttoq=",report_to=NULL ";
	}
	if($changepass=="yes")
		$query = "update task_users set task_users='".clean($uname)."',password='".md5(clean($newpass))."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."',status='".$status."',type='".$type."' $officemanq $reporttoq where id='".$userid."'";
	else
		$query = "update task_users set username='".clean($uname)."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."',status='".$status."',type='".$type."' $officemanq $reporttoq where id='".$userid."'";
	if($result = mysql_query($query))
		$_SESSION["fmapresult"]="SUCCESS: Changes For Admin $uname Saved";
	else
		$_SESSION["fmapresult"]="ERROR: Unable To Save Changes For Admin $uname";
	header('location:view.php');
	exit;
}
else if($task=="savead")
{
	$id = base64_decode($_REQUEST["id"]);
	$fileid = base64_decode($_REQUEST["fileid"]);
	$aname = trim(ucwords(strtolower($_REQUEST["aname"])));
	$acode = trim(strtoupper($_REQUEST["acode"]));
	$amanager= trim($_REQUEST["amanager"]);
	$changedate = $_REQUEST["changeadates"];
	$newdate = fixdate($_REQUEST["adate"]);
	$aoffice = trim($_REQUEST["aoffice"]);
	$atgas = $_REQUEST["atgas"];
	$atpower=$_REQUEST["atpower"];
	$totalutil = $atgas + $atpower;
	//start address
	$aaddress_s = $_REQUEST["aaddress_s"];
	$acity_s = $_REQUEST["acity_s"];
	$acountry_s = $_REQUEST["acountry_s"];
	$azip_s = $_REQUEST["azip_s"];
	$start_ad = $aaddress_s.", ".$acity_s." ".$acountry_s." ".$azip_s;
	//end address
	$aaddress_e = $_REQUEST["aaddress_e"];
	$acity_e = $_REQUEST["acity_e"];
	$acountry_e = $_REQUEST["acountry_e"];
	$azip_e = $_REQUEST["azip_e"];
	$end_ad = $aaddress_e.", ".$acity_e." ".$acountry_e." ".$azip_e;
	//get coordsinates
	$cord =getGEO($start_ad);
	$lat = $cord['lat'];
	$lng = $cord['lng'];
	if(empty($lat) || empty($lng))
	{
		$_SESSION["fmapresult"]="ERROR: Invalid Starting Address";
		header("location:editad.php?id=".base64_encode($id));
		exit;
	}
	$coords = $lng.",".$lat.",100 ";
	$cord =getGEO($end_ad);
	$lat = $cord['lat'];
	$lng = $cord['lng'];
	if(empty($lat) || empty($lng))
	{
		$_SESSION["fmapresult"]="ERROR: Invalid Ending Address";
		header("location:editad.php?id=".base64_encode($id));
		exit;
	}
	$coords .= $lng.",".$lat.",100 ";
	if($changedate=="yes")
		$query = "update file_entries set fileid='".$fileid."', address1='".clean($start_ad)."', address2='".clean($end_ad)."', coords='".$coords."', totalp='".$atpower."', totalg='".$atgas."', totalgross='".$totalutil."', agent='".clean($aname)."', agent_code='".clean($acode)."', date='".$newdate."', office='".clean($aoffice)."', manager='".clean($amanager)."' where id='".$id."'";
	else
		$query = "update file_entries set  fileid='".$fileid."', address1='".clean($start_ad)."', address2='".clean($end_ad)."', coords='".$coords."', totalp='".$atpower."', totalg='".$atgas."', totalgross='".$totalutil."', agent='".clean($aname)."', agent_code='".clean($acode)."', office='".clean($aoffice)."', manager='".clean($amanager)."' where id='".$id."'";
	if($result = mysql_query($query))
		$_SESSION["mapresult"]="SUCCESS: Changes For Entry  Saved";
	else
		$_SESSION["mapresult"]="ERROR: Unable To Save Changes For Entry";
	unset($_SESSION["fmap"]);
	unset($_SESSION["titlesearch"]);
	unset($_SESSION["prevlink"]);
	header('location:showmap.php');
	exit;
	//header('location:editad.php?id='.base64_encode($id));
	//exit;
}
else if($task=="adentry")
{
	$fileid = base64_decode($_REQUEST["fileid"]);
	$aname = trim(ucwords(strtolower($_REQUEST["aname"])));
	$acode = trim(strtoupper($_REQUEST["acode"]));
	$amanager= trim($_REQUEST["amanager"]);
	$newdate = fixdate($_REQUEST["adate"]);
	$aoffice = trim($_REQUEST["aoffice"]);
	$atgas = $_REQUEST["atgas"];
	$atpower=$_REQUEST["atpower"];
	$totalutil = $atgas + $atpower;
	//start address
	$aaddress_s = $_REQUEST["aaddress_s"];
	$acity_s = $_REQUEST["acity_s"];
	$acountry_s = $_REQUEST["acountry_s"];
	$azip_s = $_REQUEST["azip_s"];
	$start_ad = $aaddress_s.", ".$acity_s." ".$acountry_s." ".$azip_s;
	//end address
	$aaddress_e = $_REQUEST["aaddress_e"];
	$acity_e = $_REQUEST["acity_e"];
	$acountry_e = $_REQUEST["acountry_e"];
	$azip_e = $_REQUEST["azip_e"];
	$end_ad = $aaddress_e.", ".$acity_e." ".$acountry_e." ".$azip_e;
	//get coordsinates
	$cord =getGEO($start_ad);
	$lat = $cord['lat'];
	$lng = $cord['lng'];
	if(empty($lat) || empty($lng))
	{
		$_SESSION["fmapresult"]="ERROR: Invalid Starting Address";
		header("location:editad.php?id=".base64_encode($id));
		exit;
	}
	$coords = $lng.",".$lat.",100 ";
	$cord =getGEO($end_ad);
	$lat = $cord['lat'];
	$lng = $cord['lng'];
	if(empty($lat) || empty($lng))
	{
		$_SESSION["fmapresult"]="ERROR: Invalid Ending Address";
		header("location:editad.php?id=".base64_encode($id));
		exit;
	}
	$coords .= $lng.",".$lat.",100 ";
	$query = "insert into file_entries(fileid,address1,address2,coords,totalp,totalg,totalgross,agent,agent_code,date,office,manager)values('".$fileid."','".clean($start_ad)."','".clean($end_ad)."','".$coords."','".$atpower."','".$atgas."','".$totalutil."','".clean($aname)."','".clean($acode)."','".$newdate."', '".clean($aoffice)."','".clean($amanager)."')";
	if($result = mysql_query($query))
		$_SESSION["mapresult"]="SUCCESS: New Entry Added";
	else
		$_SESSION["mapresult"]="ERROR: Unable To Add Entry";
	unset($_SESSION["fmap"]);
	unset($_SESSION["titlesearch"]);
	unset($_SESSION["prevlink"]);
	header('location:showmap.php');
	exit;
	//header('location:editad.php?id='.base64_encode($id));
	//exit;
}
else if($task=="delete")
{
	if(isset($_SESSION["fmap_user"]))
	{
		$user = $_SESSION["fmap_user"];
		if(!adminPrev($user["type"]))
		{
			header("location:status.php");
			exit;
		}
	}
	else
	{
		header("location:status.php");
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from task_users where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["fmapresult"]="SUCCESS: Admin Deleted";
	else
		$_SESSION["fmapresult"]="ERROR: Unable To Delete Admin, Please try again later";
	header('location:view.php');
	exit;
}
else if($task=="deletefileinfo")
{
	$id = base64_decode($_REQUEST["id"]);
	if(!empty($id))
	{
		$query = "delete from file_entries where fileid='".$id."'";
		if($result = mysql_query($query))
		{
			$_SESSION["fmapresult"]="SUCCESS: Entry Selected";
			unset($_SESSION["fmap"]);
			unset($_SESSION["titlesearch"]);
			unset($_SESSION["prevlink"]);
			$queryx = "delete from file_info where id='".$id."'";
			if($resultx = mysql_query($queryx))
				$_SESSION["mapresult"]="SUCCESS: Entry Delete Entirely";
			else
				$_SESSION["mapresult"]="ERROR: Unable To Delete Entry Completely";
		}
		else
			$_SESSION["mapresult"]="ERROR: Unable To Delete Entry, Please try again later";
	}
	else
		$_SESSION["mapresult"]="ERROR: Unable To Delete Entry";
	header('location:showmap.php');
	exit;
}
else if($task=="deleteen")
{
	if(isset($_SESSION["fmap_user"]))
	{
		$user = $_SESSION["fmap_user"];
		if(!adminPrev($user["type"]))
		{
			header("location:status.php");
			exit;
		}
	}
	else
	{
		header("location:status.php");
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from file_entries where id='$id'";
	if($result = mysql_query($query))
	{
		unset($_SESSION["fmap"]);
		unset($_SESSION["titlesearch"]);
		unset($_SESSION["prevlink"]);
		$_SESSION["mapresult"]="SUCCESS: Entry Deleted";
		header('location:showmap.php');
		exit;
	}
	else
	{
		$_SESSION["mapresult"]="ERROR: Unable To Delete Entry, Please try again later";
		header('location:editad.php?id='.base64_encode($id));
		exit;
	}
}
else if($task=="deletemaster")
{
	if(isset($_SESSION["fmap_user"]))
	{
		$user = $_SESSION["fmap_user"];
		if(!adminPrev($user["type"]))
		{
			header("location:status.php");
			exit;
		}
	}
	else
	{
		header("location:status.php");
		exit;
	}
	$query = "truncate file_entries";
	if($result = mysql_query($query))
	{
		$query = "truncate file_info";
		if($result = mysql_query($query))
			$_SESSION["fmapresult"]="SUCCESS: ALL Entries and File Entries Deleted";
		else
			$_SESSION["fmapresult"]="ERROR: ALL Entries Deleted But File Info Can't Be Deleted";
	}
	else
		$_SESSION["fmapresult"]="ERROR: ALL Entries Can't Be Deleted";
	header("location:home.php");
	exit;
}
else
{
	header('location:status.php');
	exit;
}
include "include/unconfig.php";
?>