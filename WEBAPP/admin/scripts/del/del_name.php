<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"] == true)
{
	include('../../../login/connection.php');
	include('../../../login/func.php');

	$username = "";
	$table = array();
	if($_POST["t_username"])
	{
		array_push($table, "tutor_login_info");
		array_push($table, "tutor_access_info");
		array_push($table, "tutor_slogin_info");

		$username = sanitize_sql_input($_POST["t_username"], "/[^a-zA-Z0-9.]/");
	}
	elseif($_POST["s_username"])
	{
		array_push($table, "student_login_info");
		array_push($table, "student_slogin_info");
		array_push($table, "student_subject_info");
		array_push($table, "student_results");
		array_push($table, "student_overrall_marks");
	
		$username = sanitize_sql_input($_POST["s_username"], "/[^a-zA-Z0-9.]/");
	
	}

	if(empty($username))
	{
		print("<script>alert('Username field empty');</script>");
		exit;
	}
	$conn = sql_connect();

	if(strlen($username) == 14)
	{
		$sql = "SELECT * FROM tutor_access_info WHERE username='".$username."'";
		$results = mysqli_query($conn, $sql);
		if(mysqli_num_rows($results) < 1)
		{
			print("<script>alert('username not found');document.location.href='../../control_panel.php'</script>");
			exit;
		}
	}
	else
	{
		$sql = "SELECT * FROM student_subject_info WHERE username='".$username."'";
		$results = mysqli_query($conn, $sql);
		if(mysqli_num_rows($results) < 1)
		{
			print("<script>alert('username not found');document.location.href='../../control_panel.php'</script>");
			exit;
		}
	}


	for($i = 0;$i < count($table); $i++)
	{
		$sql = "DELETE FROM ".$table[$i]." WHERE username='".$username."'";
		mysqli_query($conn, $sql);
	}
	header("location: ../../control_panel.php");
}
else
{
	header("location: ../../../login/index.php");
}
?>
