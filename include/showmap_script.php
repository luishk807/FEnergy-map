<?Php
$showmainbutton = true;
$showdiv=false;
$default_div=true;
$default_found=true;
$agents= array();
$totalg=0;
$totalp=0;
$totalgrand=0;
$getcoords=array();
if(isset($_SESSION["fmap"]))
{
	$fmap = $_SESSION["fmap"];
	if(!empty($fmap))
	{
		$fileid =$fmap["fileid"];
		$taskcheck = $fmap["task"];
		$titlefield = $fmap["title_in"];
		echo $titlefield;
		if($taskcheck=="normal")
		{
			$fileid =$fmap["fileid"];
			$query = "select * from file_entries where fileid='".$fileid."' order by agent";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if(sizeof($agents)>0)
						{
							for($i=0;$i<sizeof($agents);$i++)
							{
								if(trim($agents[$i]["code"])==stripslashes($rows["agent_code"]))
									$found=true;
							}
							if(!$found)
							{
								$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}
						}
						else
						{
							$ag = array("name"=>$rows["agent"],"code"=>$rows["agent_code"]);
							$agents[]=$ag;
						}
						$totalg +=$rows["totalg"];
						$totalp +=$rows["totalp"];
						$totalgrand +=$rows["totalgross"];
					}
				}
				else
					$default_found=false;
			}
			else
				$default_found = false;
			$showdiv=false;
		}
		else if($taskcheck=="agent")
		{
			//no file type set
			$fileid =$fmap["fileid"];
			$query = "select * from file_entries where fileid='".$fileid."' order by agent";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if(sizeof($agents)>0)
						{
							for($i=0;$i<sizeof($agents);$i++)
							{
								if(trim($agents[$i]["code"])==stripslashes($rows["agent_code"]))
									$found=true;
							}
							if(!$found)
							{
								$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}
						}
						else
						{
							$ag = array("name"=>$rows["agent"],"code"=>$rows["agent_code"]);
							$agents[]=$ag;
						}
						$totalg +=$rows["totalg"];
						$totalp +=$rows["totalp"];
						$totalgrand +=$rows["totalgross"];
					}
				}
				else
					$default_found =false;
			}
			else
				$default_found=false;
			if($default_found)
			{
				$agentcode = $fmap["agent"];
				if($agentcode=="all")
					$showdiv=false;
				else
					$showdiv=true;
			}
			else
				$showdiv=false;
		}
		else if($taskcheck=="fileinfo")
		{
			//no file type set
			$query = $fmap["query"];
			$fileid =$fmap["fileid"];
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if(sizeof($agents)>0)
						{
							for($i=0;$i<sizeof($agents);$i++)
							{
								if(trim($agents[$i]["code"])==stripslashes($rows["agent_code"]))
									$found=true;
							}
							if(!$found)
							{
								$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}
						}
						else
						{
							$ag = array("name"=>$rows["agent"],"code"=>$rows["agent_code"]);
							$agents[]=$ag;
						}
						$totalg +=$rows["totalg"];
						$totalp +=$rows["totalp"];
						$totalgrand +=$rows["totalgross"];
					}
				}
				else
					$default_found=false;
			}
			else
				$default_found=false;
			$showdiv=false;
		}
		else if($taskcheck=="searchbar")
		{
			$fileid ="";
			$query = $fmap["query"];
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if($fmap["taski"]=="name")
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==$rows["id"])
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
								$agents[]=$ag;
							}

						}
						else
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==stripslashes($rows["agent_code"]))
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>$rows["agent"],"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}
						}
						$totalg +=$rows["totalg"];
						$totalp +=$rows["totalp"];
						$totalgrand +=$rows["totalgross"];
					}
				}
				else
					$default_found =false;
			}
			else
				$default_found = false;
			$default_div=false;
		}
		else if($taskcheck=="agent_searchbar" || $taskcheck=="agentadd_searchbar" || $taskcheck=="agentdate_searchbar" || $taskcheck=="agentdaterange_searchbar")
		{
			$fileid ="";
			$query = $fmap["query"];
			$agentcode = $fmap["agent"];
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
						$found=false;
						if($fmap["taski"]=="name")
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==$rows["id"])
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>stripslashes($rows["address1"])." To ".stripslashes($rows["address2"]),"code"=>$rows["id"]);
								$agents[]=$ag;
							}
						}
						else if($fmap["taski"]=="address" || $fmap["taski"]=="date" || $fmap["taski"]=="daterange")
						{
							if(sizeof($agents)>0)
							{
								for($i=0;$i<sizeof($agents);$i++)
								{
									if(trim($agents[$i]["code"])==$rows["agent_code"])
										$found=true;
								}
								if(!$found)
								{
									$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
									$agents[]=$ag;
								}
							}
							else
							{
								$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
								$agents[]=$ag;
							}

						}
						else
							$default_found = false;
						if($default_found)
						{
							$totalg +=$rows["totalg"];
							$totalp +=$rows["totalp"];
							$totalgrand +=$rows["totalgross"];
						}
					}
				}
				else
					$default_found = false;
			}
			else
				$default_found = false;
			$default_div=false;
			$showdiv=true;
		}
	}
	else
	{
		//file id is empty
			$query = "select * from file_info order by id desc limit 1";
		if($result = mysql_query($query))
		$row = mysql_fetch_assoc($result);
		$fileid = $row["id"];
		$dataret = array("fileid"=>$fileid,"task"=>'normal','taski'=>'','query'=>'','agent'=>'','title_in'=>'','querys'=>'');
		$_SESSION["fmap"]=$dataret;
		$query = "select * from file_entries where fileid='".$fileid."' order by id";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				while($rows = mysql_fetch_array($result))
				{
					if(empty($getcoords))
							$getcoords = getCoords($rows["coords"]);
					$found=false;
					if(sizeof($agents)>0)
					{
						for($i=0;$i<sizeof($agents);$i++)
						{
							if(trim($agents[$i]["code"])==trim($rows["agent_code"]))
								$found=true;
						}
						if(!$found)
						{
							$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
							$agents[]=$ag;
						}
					}
					else
					{
						$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
						$agents[]=$ag;
					}
					$totalg +=$rows["totalg"];
					$totalp +=$rows["totalp"];
					$totalgrand +=$rows["totalgross"];
				}
			}
			else
				$default_found=false;
		}
		else
			$default_found = false;
		$showdiv=false;
	}
}
else
{
	$query = "select * from file_info order by id desc limit 1";
	if($result = mysql_query($query))
		$row = mysql_fetch_assoc($result);
	$fileid = $row["id"];
	$dataret = array("fileid"=>$fileid,"task"=>'normal','taski'=>'','query'=>'','agent'=>'','title_in'=>'','querys'=>'');
	$_SESSION["fmap"]=$dataret;
	$query = "select * from file_entries where fileid='".$fileid."' order by id";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				if(empty($getcoords))
					$getcoords = getCoords($rows["coords"]);
				$found=false;
				if(sizeof($agents)>0)
				{
					for($i=0;$i<sizeof($agents);$i++)
					{
						if(trim($agents[$i]["code"])==trim($rows["agent_code"]))
							$found=true;
					}
					if(!$found)
					{
						$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
						$agents[]=$ag;
					}
				}
				else
				{
					$ag = array("name"=>stripslashes($rows["agent"]),"code"=>$rows["agent_code"]);
					$agents[]=$ag;
				}
				$totalg +=$rows["totalg"];
				$totalp +=$rows["totalp"];
				$totalgrand +=$rows["totalgross"];
			}
		}
		else
			$default_found = false;
	}
	else
		$default_found = false;
	$showdiv=false;
}
?>