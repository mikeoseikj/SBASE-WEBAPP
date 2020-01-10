<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)
{
	include '../../../login/connection.php';
	include '../../../login/func.php';

	$form=sanitize_sql_input($_POST["form_menu"],20,"/[^a-zA-Z1-9 ]/");
	$track=sanitize_sql_input($_POST["track_menu"],20,"/[^a-zA-Z1-9 ]/");
	$department=sanitize_sql_input($_POST["department_menu"],20,"/[^a-zA-Z1-9 ]/");
	$class=sanitize_sql_input($_POST["class_menu"],20,"/[^a-zA-Z1-9 ]/");
	$subject=sanitize_sql_input($_POST["subject_menu"],20,"/[^a-zA-Z1-9 ]/");

	$username = sanitize_sql_input($_POST["username"],14,"/[^a-zA-Z1-9.]/");
	$status=$_POST["status"];

	
	

	if(empty($form) || empty($track) || empty($department) || empty($class) || empty($subject) ||   empty($status) )
	{
		print("<script>alert('not allowed');</script>");
		exit;
	}

	
	$conn=sql_connect();


	if($status=="active")
		$state=1;
	else if($status=="inactive")
		$state=0;
	else
	{
		print("<script>alert('not allowed');</script>");
		exit;
	}


	if(empty($username))
	{

		$sql="SELECT * FROM student_subject_info  WHERE form='".$form."' AND track='".$track."' AND  department='".$department."' AND class='".$class."' AND subject='".$subject."'";

		$results=mysqli_query($conn,$sql);
		if(mysqli_num_rows($results) < 1)
		{
			print("<script>alert('no students with such info');</script>");
			exit;
		}

		while($rows=mysqli_fetch_assoc($results))
		{
			$sql="UPDATE student_login_info SET status='".$state."' WHERE username='".$rows["username"]."'";
			mysqli_query($conn,$sql);
			$sql="UPDATE student_slogin_info SET status='".$state."' WHERE username='".$rows["username"]."'";
			mysqli_query($conn,$sql);
		}

	}

	else
	{
		$sql="SELECT * FROM student_subject_info WHERE username='".$username."'";
		$results=mysqli_query($conn,$sql);
		if(mysqli_num_rows($results) < 1 )
		{
			print("<script>alert('username does not exist');</script>");
			exit;
		}


		$sql="UPDATE student_login_info SET status='".$state."' WHERE username='".$username."'";
		$results=mysqli_query($conn,$sql);
		$sql="UPDATE student_slogin_info SET status='".$state."' WHERE username='".$username."'";
		$results=mysqli_query($conn,$sql);

	}


}
else
{
	header("location: ../../../login/index.php");
}
?>
