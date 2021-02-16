<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"] == true)
{
	include('../../../login/connection.php');
	include('../../../login/func.php');
	

	$capture = urldecode($_SERVER["QUERY_STRING"]);
	$capture = substr($capture, 0, -7);

	$field = "";
	$value = "";
	$tab = "";
	if(substr($capture, 0 ,-(strlen($capture)-4)) == "FORM")
	{
		$tab = "form_info";
		$field = "form";
		$value = substr($capture, 4, strlen($capture));
	}
	elseif(substr($capture, 0, -(strlen($capture)-5)) == "TRACK")
	{
		$tab = "track_info";
		$field = "track";
		$value = substr($capture, 5, strlen($capture));

	}
	elseif(substr($capture, 0, -(strlen($capture)-10)) == "DEPARTMENT")
	{
		$tab = "department_info";
		$field = "department";
		$value = substr($capture, 10, strlen($capture));
	}
	elseif(substr($capture, 0 , -(strlen($capture)-5)) == "CLASS")
	{
		$tab = "class_info";
		$field = "class";
		$value = substr($capture ,5 , strlen($capture));

	}
	elseif(substr($capture, 0, -(strlen($capture)-7)) == "SUBJECT")
	{
		$tab = "subject_info";
		$field = "subject";
		$value =substr($capture, 7, strlen($capture));

	}

	elseif(substr($capture, 0, -(strlen($capture)-4)) == "EXAM")
	{
		$tab = "exam_info";
		$field = "exam";
		$value = substr($capture, 4, strlen($capture));

	}
	$value = sanitize_sql_input($value, "/[^a-zA-Z0-9\-_() ]/");
	if(empty($value))
	{
		print("<script>alert('An empty field was provided');</script>");
		exit;
	}

	
	$conn = sql_connect();

	$sql = "DELETE FROM ".$tab." WHERE ".$field."='".$value."';";
    mysqli_query($conn, $sql);
	print("<script>document.location.href='dconfig.php';</script>");

}
else
{
	header("location: ../../../login/index.php");
}
?>
