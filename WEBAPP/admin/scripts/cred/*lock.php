<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true && $_SESSION["loggedin"] == true)
{
	
	include('../../../login/connection.php');
	include('../../../login/func.php');

	$cmd = $_GET["cmd"];
	$conn = sql_connect();

	if($cmd == "tlock")
	{
		$sql = "UPDATE tutor_login_info SET status=0 WHERE status=1";
		mysqli_query($conn, $sql);
		$sql = "UPDATE tutor_slogin_info SET status=0 WHERE status=1";
		mysqli_query($conn, $sql);
	}
	elseif($cmd == "tunlock")
	{
		$sql = "UPDATE tutor_login_info SET status=1 WHERE status=0";
		mysqli_query($conn, $sql);
		$sql = "UPDATE tutor_slogin_info SET status=1 WHERE status=0";
		mysqli_query($conn, $sql);
	}
	elseif($cmd == "slock")
	{
		$sql = "UPDATE student_login_info SET status=0 WHERE status=1";
		mysqli_query($conn, $sql);
		$sql = "UPDATE student_slogin_info SET status=0 WHERE status=1";
		mysqli_query($conn, $sql);
	}
	elseif($cmd == "sunlock")
	{
		$sql = "UPDATE student_login_info SET status=1 WHERE status=0";
		mysqli_query($conn, $sql);
		$sql = "UPDATE student_slogin_info SET status=1 WHERE status=0";
		mysqli_query($conn, $sql);
	}
	else
	{
		print("<script>alert();</script>");
	}

}
else
{
	header("location: ../../../login/index.php");
}
?>
