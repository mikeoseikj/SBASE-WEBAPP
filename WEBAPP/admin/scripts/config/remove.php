<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)
{
	include '../../../login/connection.php';
	include '../../../login/func.php';
	

	$capture=$_SERVER["QUERY_STRING"];
	$capture=substr($capture,0,-7);

	$field="";
	$value="";
	$tab="";
	if(substr($capture,0,-(strlen($capture)-4))=="FORM")
	{
		$tab="form_info";
		$field="form";
		$value=$capture=substr($capture,4,strlen($capture));
	}
	elseif(substr($capture,0,-(strlen($capture)-5))=="TRACK")
	{
		$tab="track_info";
		$field="track";
		$value=$capture=substr($capture,5,strlen($capture));

	}
	elseif(substr($capture,0,-(strlen($capture)-10))=="DEPARTMENT")
	{
		$tab="department_info";
		$field="department";
		$value=$capture=substr($capture,10,strlen($capture));
	}
	elseif(substr($capture,0,-(strlen($capture)-5))=="CLASS")
	{
		$tab="class_info";
		$field="class";
		$value=$capture=substr($capture,5,strlen($capture));

	}
	elseif(substr($capture,0,-(strlen($capture)-7))=="SUBJECT")
	{
		$tab="subject_info";
		$field="subject";
		$value=$capture=substr($capture,7,strlen($capture));

	}

	elseif(substr($capture,0,-(strlen($capture)-4))=="EXAM")
	{
		$tab="exam_info";
		$field="exam";
		$value=$capture=substr($capture,4,strlen($capture));

	}

	$value=str_replace("+"," ",$value);
	$value = preg_replace("/[^a-zA-Z1-9 ]/", "", $value);
	if(empty($value))
		exit;

	
	$conn=sql_connect();


	$sql="DELETE FROM ".$tab." WHERE ".$field."='".$value."';";

	$results=mysqli_query($conn,$sql);
	if(! $results)
	{
		print("<script>alert('".mysqli_error()."');</script>");
		exit;
	}
	print("<script>document.location.href='dconfig.php';</script>");

}
else
{
	header("location: ../../../login/index.php");
}
?>
