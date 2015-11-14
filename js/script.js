// JavaScript Document
function errorcheckb(task,vars,message)
{
	var color = "#cee838";
	var variable = document.getElementById(vars).value;
	if(task=="text")
	{
		if(variable.length==0 || isNaN(variable)==false)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	if(task=="select")
	{
		if(variable=="0")
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="number")
	{
		if(variable.length==0 || isNaN(variable)==true)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}	
	}
	else if(task=="normal")
	{
		if(variable.length==0)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="email")
	{
		var email = document.getElementById(vars).value;
		if(email.length !=0)
		{
			if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
			{
				document.getElementById(vars).style.background=color;
				document.getElementById("message2").innerHTML=message;
				return false;
			}
			else
			{
				document.getElementById(vars).style.background="";
				document.getElementById("message2").innerHTML="";
			}
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="emailf")
	{
		var email = document.getElementById(vars).value;
		if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="selects")
	{
		if(document.getElementById(vars).selectedIndex==0)
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="checksa")
	{
		if(document.getElementById(vars).checked==false)
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	return true;
}
function errorcheckx(task,vars,message)
{
	var cont='message2m';
	var color = "c3c2c2";
	var variable = document.getElementById(vars).value;
	if(task=="text")
	{
		if(variable.length==0 || isNaN(variable)==false)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	if(task=="select")
	{
		if(variable=="0")
		{
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="number")
	{
		if(variable.length==0 || isNaN(variable)==true)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}	
	}
	else if(task=="normal")
	{
		if(variable.length==0)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="email")
	{
		var email = document.getElementById(vars).value;
		if(email.length !=0)
		{
			if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
			{
				document.getElementById(vars).style.background=color;
				document.getElementById(cont).innerHTML=message;
				return false;
			}
			else
			{
				document.getElementById(vars).style.background="";
				document.getElementById(cont).innerHTML="";
			}
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="emailf")
	{
		var email = document.getElementById(vars).value;
		if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="selects")
	{
		if(document.getElementById(vars).selectedIndex==0)
		{
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="checksa")
	{
		if(document.getElementById(vars).checked==false)
		{
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(cont).innerHTML="";
		}
	}
	return true;
}
function checkField_fp1()
{
	//create reset password page
	if(!errorcheck("email","femail","Please enter a valid email"))
		return false;
	var femail=document.getElementById("femail").value;
	var uname=document.getElementById("uname").value;
	if(femail.length<1 && uname.length<1)
	{
		document.getElementById("femail").style.background="#b5b5b5";
		document.getElementById("uname").style.background="#b5b5b5";
		document.getElementById("message2").innerHTML="You Must Provide A Mean To Find Your Information.<br/>This could be either an email address or your username";
		return false;
	}
	else
	{
		document.getElementById("femail").style.background="";
		document.getElementById("uname").style.background="";
		document.getElementById("message2").innerHTML="";
	}
	return true;
}
function checkField_fp2()
{
	//reset password page
	if(!errorcheck("normal","fpass","Please your new password"))
		return false;
	if(!errorcheck("normal","rfpass","Please re-type your new password"))
		return false;
	var fpass=document.getElementById("fpass").value;
	var rfpass=document.getElementById("rfpass").value;
	if(fpass != rfpass)
	{
		document.getElementById("fpass").style.background="#b5b5b5";
		document.getElementById("rfpass").style.background="#b5b5b5";
		document.getElementById("message2").innerHTML="Both Password Must Match, Please retry";
		return false;
	}
	else
	{
		document.getElementById("fpass").style.background="";
		document.getElementById("rfpass").style.background="";
		document.getElementById("message2").innerHTML="";
	}
	return true;
}
function errorcheck(task,vars,message)
{
	//var color = "#cee838";
	var color = "";
	var variable = document.getElementById(vars).value;
	if(task=="text")
	{
		if(variable.length==0 || isNaN(variable)==false)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	if(task=="select")
	{
		if(variable=="0")
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="number")
	{
		if(variable.length==0 || isNaN(variable)==true)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}	
	}
	else if(task=="normal")
	{
		if(variable.length==0)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="email")
	{
		var email = document.getElementById(vars).value;
		if(email.length !=0)
		{
			if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
			{
				document.getElementById(vars).style.background=color;
				document.getElementById("message2").innerHTML=message;
				return false;
			}
			else
			{
				document.getElementById(vars).style.background="";
				document.getElementById("message2").innerHTML="";
			}
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="emailf")
	{
		var email = document.getElementById(vars).value;
		if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="selects")
	{
		if(document.getElementById(vars).selectedIndex==0)
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="checksa")
	{
		if(document.getElementById(vars).checked==false)
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	return true;
}
function preload(images) {
    if (document.images) {
        var i = 0;
        var imageArray = new Array();
        imageArray = images.split(',');
        var imageObj = new Image();
        for(i=0; i<=imageArray.length-1; i++) {
            //document.write('<img src="' + imageArray[i] + '" />');// Write to page (uncomment to check images)
            imageObj.src=images[i];
        }
    }
}
function clearmailform()
{
	document.getElementById("maddtype").selectedIndex=0;
	document.getElementById("maddapt").value="";
	document.getElementById("maddnum").value="";
	document.getElementById("maddsuf").value="";
	document.getElementById("maddst").value="";
	document.getElementById("maddsttype").selectedIndex=0;			   
	document.getElementById("maddstdir").selectedIndex=0;
	document.getElementById("maddcity").value="";
	document.getElementById("maddzip").value="";
	document.getElementById("madddwell").value="";
	document.getElementById("maddocup").value="";
}
function checkField()
{
	if(!errorcheck("normal","uname","Please enter username"))
		return false;
	if(!errorcheck("normal","upass","Please enter password"))
		return false;
	return true;
}
function checkField_m()
{
	//login page
	if(!errorcheckx("normal","uname","Please enter username"))
		return false;
	if(!errorcheckx("normal","upass","Please enter password"))
		return false;
	return true;
}
function checkFieldc()
{
	var loadcheck = true;
	var checkx = document.getElementById("cxdatex").value;
	if(!errorcheck("normal","filename","Please Provide A Name For This File"))
	{
		loadcheck = false;
		return false;
	}
	if(checkx=="yes")
	{
		if(!errorcheck("normal","datex","Please Choose A Date of This Entry File"))
		{
			loadcheck = false;
			return false;
		}
	}
	if(!errorcheck("normal","file","Please Choose A File To Upload"))
	{
		loadcheck = false;
		return false;
	}
	if(loadcheck==true)
	{
		document.getElementById("contbuton").style.display="none";
		document.getElementById("loadergif").style.display="block";
	}
	return false;
}
function checkFieldd()
{
	var type = document.getElementById("types").value;
	var daterange = document.getElementById("daterange").value;
	document.getElementById("type").value=type;
	if(type=="date")
	{
		if(!errorcheckb("normal","datec","Please choose a date"))
		return false;
	}
	else if(type=="daterange")
	{
		if(!errorcheckb("normal","datea","Please complete the date range selection"))
		return false;
		if(!errorcheckb("normal","dateb","Please complete the date range selection"))
		return false;
	}
	else if(type=="fileentry")
	{
		if(!errorcheckb("selects","fileentry","Please choose a date entry file"))
		return false;
	}
	else if(type=="office")
	{
		var checkoff = document.getElementById("checkoffice").value;
		if(checkoff=="no")
		{
			if(!errorcheckb("normal","offices","Please enter the office you wish to search"))
			return false;
		}
		else
		{
			if(!errorcheckb("selects","offices","Please enter the office you wish to search"))
			return false;
		}
		if(!errorcheckb("normal","dateo","Please enter a date"))
		return false;
	}
	else
	{
		if(!errorcheckb("normal","searchin","Please fill the search box"))
		return false;
	}
	if(daterange=="yes")
	{
		if(!errorcheckb("normal","date1","Please complete the date range selection or select No to cancel the date range"))
		return false;
		if(!errorcheckb("normal","date2","Please complete the date range selection or select No to cancel the date range"))
		return false;
	}
	return true;
}
function checkFielde()
{
	var checkin = document.getElementById("changepass").value;
	if(!errorcheckb("normal","uname","Please Write A Username"))
		return false;
	if(checkin=="yes")
	{
		if(!errorcheckb("normal","newpass","Please write the new password"))
			return false;
		if(!errorcheckb("normal","renewpass","Please re-type the password"))
			return false;
		var newpass = document.getElementById("newpass").value;
		var renewpass = document.getElementById("renewpass").value;
		if(newpass != renewpass)
		{
			document.getElementById("renewpass").style.background="#cee838";
			document.getElementById("message2").innerHTML="Both Password Must Match";
			return false;
		}
		else
		{
			document.getElementById("renewpass").style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else
	{
		document.getElementById("message2").innerHTML="";
		document.getElementById("newpass").style.background="";
		document.getElementById("renewpass").style.background="";
	}
	if(!errorcheckb("normal","realname","Please provide a valid name"))
		return false;
	if(!errorcheckb("emailf","uemail","Please provide a valid email"))
		return false;
	if(!errorcheckb("normal","utitle","Please provide a title"))
		return false;
	if(!warningpop("status","ustatus",""))
		return false;
	if(!warningpop("type","utype",document.getElementById("utype").value))
		return false;
	var checktype = document.getElementById("checktype").value;
	if(checktype=="yes")
	{
		if(!errorcheck("selects","officeman","Please select the office for this manager or team leader"))
		return false;
	}
	var checkreportt = document.getElementById("checkreportt").value;
	if(checkreportt=="yes")
	{
		if(!errorcheck("selects","reportto","Please select the manager that this team leader reports to"))
		return false;
	}
	return true;
}
function checkFieldf()
{
	if(!errorcheckb("normal","uname","Please Write A Username"))
		return false;
	var newpass = document.getElementById("newpass").value;
	var renewpass = document.getElementById("renewpass").value;
	if(newpass != renewpass)
	{
		document.getElementById("renewpass").style.background="#cee838";
		document.getElementById("message2").innerHTML="Both Password Must Match";
		return false;
	}
	else
	{
		document.getElementById("renewpass").style.background="";
		document.getElementById("message2").innerHTML="";
	}
	if(!errorcheckb("normal","realname","Please provide a valid name"))
		return false;
	if(!errorcheckb("emailf","uemail","Please provide a valid email"))
		return false;
	if(!errorcheckb("normal","utitle","Please provide a title"))
		return false;
		var utype = document.getElementById("utype").value;
	if(!warningpop("status","ustatus",""))
		return false;
	if(!warningpop("type","utype",document.getElementById("utype").value))
		return false;
	var checktype = document.getElementById("checktype").value;
	if(checktype=="yes")
	{
		if(!errorcheck("selects","officeman","Please select the office for this manager or team leader"))
		return false;
	}
	var checkreportt = document.getElementById("checkreportt").value;
	if(checkreportt=="yes")
	{
		if(!errorcheck("selects","reportto","Please select the manager that this team leader reports to"))
		return false;
	}
	return true;
}
function showdaterange(value)
{
	if(value=="yes")
	{
		document.getElementById("datedrangediv").style.display="block";
		document.getElementById("daterange").value="yes";
	}
	else
	{
		document.getElementById("message2").innerHTML="";
		document.getElementById("date1").value="";
		document.getElementById("date2").value="";
		document.getElementById("daterange").value="no";
		document.getElementById("datedrangediv").style.display="none";
	}
}
function checkFieldg()
{
	//form to edit the entry
	var changedate = document.getElementById("changeadates").value;
	if(!errorcheckb("normal","aname","Please provide a valid name"))
		return false;
	if(!errorcheckb("normal","acode","Please provide a valid agent code"))
		return false;
	if(!errorcheckb("normal","amanager","Please provide a valid name of manager"))
		return false;
	if(changedate=="yes")
	{
		if(!errorcheckb("normal","adate","Please choose a date"))
			return false;
	}
	if(!errorcheckb("normal","atgas","Please provide total of gas"))
		return false;
	if(!errorcheckb("normal","atpower","Please provide a total of power"))
		return false;
	if(!errorcheckb("normal","aaddress_s","Please provide a valid starting address"))
		return false;
	if(!errorcheckb("normal","acity_s","Please provide a valid starting city"))
		return false;
	if(!errorcheckb("normal","acountry_s","Please provide a valid starting state"))
		return false;
	if(!errorcheckb("normal","aaddress_s","Please provide a valid starting postal/zip code"))
		return false;
	if(!errorcheckb("normal","aaddress_e","Please provide a valid ending address"))
		return false;
	if(!errorcheckb("normal","acity_e","Please provide a valid ending city"))
		return false;
	if(!errorcheckb("normal","acountry_e","Please provide a valid ending state"))
		return false;
	if(!errorcheckb("normal","aaddress_e","Please provide a valid ending postal/zip code"))
		return false;
	return true;
}
function checkFieldh()
{
	//form check for the edit import page
	var loadcheck = true;
	var checkx = document.getElementById("cxdatex").value;
	if(!errorcheck("normal","filename","Please Provide A Name For This File"))
		return false;
	if(checkx=="yes")
	{
		if(!errorcheck("normal","datex","Please Choose A Date of This Entry File"))
		{
			loadcheck = false;
		return false;
		}
	}
	var checkim = document.getElementById("reimport").value;
	if(checkim=="fclean")
	{
		if(!errorcheck("normal","file","Please Choose A File To Upload"))
		{
			loadcheck = false;
			return false;
		}
	}
	else if(checkim=="fover")
	{
		if(!errorcheck("normal","fileov","Please Choose A File To Upload"))
		{
			loadcheck = false;
			return false;
		}
	}
	if(loadcheck==true)
	{
		document.getElementById("contbuton").style.display="none";
		document.getElementById("loadergif").style.display="block";
	}
	return false
}
function checkFieldi()
{
	//form to add new entry
	if(!errorcheckb("selects","fileid","Please select a File Entry"))
		return false;
	if(!errorcheckb("normal","aname","Please provide a valid name"))
		return false;
	if(!errorcheckb("normal","acode","Please provide a valid agent code"))
		return false;
	if(!errorcheckb("normal","amanager","Please provide a valid name of manager"))
		return false;
	if(!errorcheckb("normal","adate","Please choose a date"))
		return false;
	if(!errorcheckb("normal","atgas","Please provide total of gas"))
		return false;
	if(!errorcheckb("normal","atpower","Please provide a total of power"))
		return false;
	if(!errorcheckb("normal","aaddress_s","Please provide a valid starting address"))
		return false;
	if(!errorcheckb("normal","acity_s","Please provide a valid starting city"))
		return false;
	if(!errorcheckb("normal","acountry_s","Please provide a valid starting state"))
		return false;
	if(!errorcheckb("normal","aaddress_s","Please provide a valid starting postal/zip code"))
		return false;
	if(!errorcheckb("normal","aaddress_e","Please provide a valid ending address"))
		return false;
	if(!errorcheckb("normal","acity_e","Please provide a valid ending city"))
		return false;
	if(!errorcheckb("normal","acountry_e","Please provide a valid ending state"))
		return false;
	if(!errorcheckb("normal","aaddress_e","Please provide a valid ending postal/zip code"))
		return false;
	return true;
}
function showtype(value)
{
	document.getElementById("searchin").style.background="";
	document.getElementById("message2").innerHTML="";
	document.getElementById("datedrangediv").style.display="none";
	document.getElementById("typediv_file").selectedIndex=0;
	document.getElementById("daterangeselec").selectedIndex=0;
	document.getElementById("offices").value="";
	document.getElementById("dateo").value="";
	if(value=="date")
	{
		document.getElementById("typediv_date").style.display="block";
		document.getElementById("typediv_daterange").style.display="none";
		document.getElementById("typediv").style.display="none";
		document.getElementById("typediv_file").style.display="none";
		document.getElementById("typediv_office").style.display="none";
		document.getElementById("date").value="";
	}
	else if(value=="daterange")
	{
		document.getElementById("typediv_daterange").style.display="block";
		document.getElementById("daterange").value="";
		document.getElementById("typediv_date").style.display="none";
		document.getElementById("typediv_file").style.display="none";
		document.getElementById("typediv_office").style.display="none";
		document.getElementById("typediv").style.display="none";
	}
	else if(value=="office")
	{
		document.getElementById("typediv_office").style.display="block";
		document.getElementById("typediv_daterange").style.display="none";
		document.getElementById("daterange").value="";
		document.getElementById("typediv_date").style.display="none";
		document.getElementById("typediv_file").style.display="none";
		document.getElementById("typediv").style.display="none";
	}
	else if(value=="fileentry")
	{
		document.getElementById("typediv_file").style.display="block";
		document.getElementById("daterange").value="";
		document.getElementById("typediv_daterange").style.display="none";
		document.getElementById("typediv_date").style.display="none";
		document.getElementById("typediv_office").style.display="none";
		document.getElementById("typediv").style.display="none";
	}
	else
	{
		document.getElementById("typediv").style.display="block";
		document.getElementById("typediv_date").style.display="none";
		document.getElementById("typediv_daterange").style.display="none";
		document.getElementById("typediv_office").style.display="none";
		document.getElementById("typediv_file").style.display="none";
	}
}
function allowpassword()
{
	var checking = document.getElementById("checkchange").checked;
	if(checking==true)
	{
		document.getElementById("allowpassworddiv").style.display="block";
		document.getElementById("changepass").value="yes";
	}
	else
	{
		document.getElementById("changepass").value="no";
		document.getElementById("newpass").value="";
		document.getElementById("renewpass").value="";
		document.getElementById("allowpassworddiv").style.display="none";
	}
}
function changeadatediv()
{
	var checkin = document.getElementById("changeadate").checked;
	if(checkin==true)
	{
		document.getElementById("allowadate").style.display="block";
		document.getElementById("changeadates").value="yes";
	}
	else
	{
		document.getElementById("allowadate").style.display="none";
		document.getElementById("changeadates").value="no";
	}
}
function allowxdate()
{
	var checkin = document.getElementById("cxdate").checked;
	if(checkin==true)
	{
		document.getElementById("xdiv_date").style.display="block";
		document.getElementById("cxdatex").value="yes";
	}
	else
	{
		document.getElementById("xdiv_date").style.display="none";
		document.getElementById("cxdatex").value="no";
		document.getElementById("datex").value="";
	}
}
function deleteadmin(value)
{
	var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS ADMIN, ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	if(confirmx==true)
		window.location.href='save.php?task=delete&id='+value;
}
function deleteentry(value)
{
	var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS ENTRY, ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	if(confirmx==true)
		window.location.href='save.php?task=deleteen&id='+value;
}
function deletemaster()
{
	var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE ALL ENTRIES AND FILE ENTRIES. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	if(confirmx==true)
		window.location.href='save.php?task=deletemaster';
}
function deleteAll(id,task)
{
	if(task=="deletefileinfo")
	{
		var confirmx = window.confirm("WARNING!: YOU ARE ABOUT TO DELETE THIS ENTIRE ENTRY! ARE YOU SURE YOU WANT TO PROCEED?\r\n\r\rClick Okay To Continue or Click Cancel To Cancel");
		if(confirmx==true)
		{
			window.location.href="save.php?task="+task+"&id="+id;
		}
	}
}
function allowreimport()
{
	var checkin = document.getElementById("creimport").value;
	if(checkin=="fclean")
	{
		document.getElementById("reimport_div").style.display="block";
		document.getElementById("reimportov_div").style.display="none";
		document.getElementById("reimport").value="fclean";
		document.getElementById("fileov").value=""
	}
	else if(checkin=="fover")
	{
		document.getElementById("reimport_div").style.display="none";
		document.getElementById("reimportov_div").style.display="block";
		document.getElementById("reimport").value="fover";
		document.getElementById("file").value="";
	}
	else
	{
		document.getElementById("reimport_div").style.display="none";
		document.getElementById("reimportov_div").style.display="none";
		document.getElementById("reimport").value="no";
		document.getElementById("file").value="";
		document.getElementById("fileov").value="";
	}
}
function allowofficeman(value)
{
	if(value=="5")
	{
		document.getElementById("officemandiv").style.display="block";
		document.getElementById("reporttodiv").style.display="block";
		document.getElementById("checktype").value="yes";
		document.getElementById("checkreportt").value="yes";
	}
	else if(value=="6")
	{
		document.getElementById("officemandiv").style.display="block";
		document.getElementById("reporttodiv").style.display="none";
		document.getElementById("checktype").value="yes";
		document.getElementById("checkreportt").value="no";
	}
	else
	{
		document.getElementById("officemandiv").style.display="none";
		document.getElementById("reporttodiv").style.display="none";
		document.getElementById("checktype").value="no";
		document.getElementById("checkreportt").value="no";
	}
}
function warningpop(task,valuex,valuexb)
{
	//show differnet warning pop
	var value= document.getElementById(valuex).value;
	if(task=="status")
	{
		if(value =="2" || value=="3")
		{
			var confirmx = window.confirm("WARNING! You Are About To Block Access For This User!.\r\n\r\nUser Wouldn't be able to access Map System, Task Manager System and Master Recuiter System.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			else
				return true;
		}
		return true;
	}
	else if(task=="type")
	{
		if(value =="1" || value=="2" || value=="4")
		{
			var confirmx = window.confirm("WARNING! You Are About To Grant Administrator Access To This User!. Doing so user will be able to do task that are normally exclusive for a Super Admin, Admin, and Web Designer!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			else
				return true;
		}
		return true;
	}
	else if(task=="type_c")
	{
		if(value =="1" || value=="2" || value=="4")
		{
			var confirmx = window.confirm("WARNING! You Are About To Grant Administrator Access To This User!. Doing so user will be able to do task that are normally exclusive for a Super Admin, Admin, and Web Designer!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			else
				return true;
		}
		return true;
	}
	return true;
}