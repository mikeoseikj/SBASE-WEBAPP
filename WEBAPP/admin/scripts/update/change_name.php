<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"] == true)
{
	include('../../../login/connection.php');
	include('../../../login/func.php');

	$realname = sanitize_sql_input($_POST["realname"], "/[^a-zA-Z ]/");
	$username = sanitize_sql_input($_POST["username"], "/[^a-zA-Z0-9.]/");

	if(empty($realname) || empty($username))
	{
		print("<script>alert('An empty field was provided');</script>");
		exit;
	}

	$conn = sql_connect();

	if(strlen($username)  == 12)   //if susername is that of a student
	{
		$sql = "SELECT username FROM student_subject_info WHERE username='".$username."'";
		$results = mysqli_query($conn, $sql);

		if(mysqli_num_rows($results) < 1)
		{
			print("<script>alert('username does not exist');</script>");
			exit;
		}
		$sql = "UPDATE student_subject_info SET studentname='".$realname."' WHERE username='".$username."'";
		mysqli_query($conn, $sql);

	}
	else
	{
		$sql = "SELECT username FROM tutor_access_info WHERE username='".$username."'";
		$results = mysqli_query($conn, $sql);

		if(mysqli_num_rows($results) < 1)
		{
			print("<script>alert('username does not exist');</script>");
			exit;
		}
		$sql = "UPDATE tutor_access_info SET tutorname='".$realname."' WHERE username='".$username."'";
		mysqli_query($conn, $sql);

	}

}

else
{
	header("location: ../../../login/index.php");
}
?>
