<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"] == true)
{
	include('../../../login/connection.php');
	include('../../../login/func.php');

	$exam = sanitize_sql_input($_POST["exam_menu"], "/[^a-zA-Z0-9\-_() ]/");

	if(empty($exam))
	{
		print("<script>alert('Exam field is empty');</script>");
		exit;
	}

	
	$conn = sql_connect();
	$sql = "SELECT * FROM student_overrall_marks WHERE exam='".$exam."'";
	$results = mysqli_query($conn, $sql);

	if(mysqli_num_rows($results) > 0)
	{

		while($rows = mysqli_fetch_assoc($results))
		{
			$username = $rows["username"];
			$sql = "DELETE FROM student_login_info WHERE username='".$username."'";
			mysqli_query($conn,$sql);
			$sql = "DELETE FROM student_subject_info WHERE username='".$username."'";
			mysqli_query($conn,$sql);
			$sql = "DELETE FROM student_results WHERE username='".$username."'";
			mysqli_query($conn,$sql);
			$sql = "DELETE FROM student_overrall_marks WHERE username='".$username."'";
			mysqli_query($conn,$sql);
		}
	}

	else
	{
		print("<script>alert('No one has such information');</script>");
		exit;
	}

}
else
{
	header("location: ../../../login/index.php");
}
?>
