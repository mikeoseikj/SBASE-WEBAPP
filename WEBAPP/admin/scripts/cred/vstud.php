<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)
{
	print("
		<style type='text/css'>
		body
		{
			background-color: #000000;
			font-family: monospace;
		}
		th
		{
			font-size:16px;
			background-color: #88ffff;
		}

		table, th, td
		{
			font-family: monospace;
			border-style: solid;
			border-width: 1px;
			border-color: #99ffff;
			border-collapse: collapse;
			padding: 5px;
		}
		button
		{
			width: 100%;
			border: none;
			font-family: monospace;
		}
		h1
		{
			color:#88ffff;
			text-align: center;
			font-family:monospace;
		}

		</style>");


    include '../../../login/connection.php';
    include '../../../login/func.php';

	$form=sanitize_sql_input($_POST["form_menu"],20,"/[^a-zA-Z1-9 ]/");
	$track=sanitize_sql_input($_POST["track_menu"],20,"/[^a-zA-Z1-9 ]/");
	$department=sanitize_sql_input($_POST["department_menu"],20,"/[^a-zA-Z1-9 ]/");
	$class=sanitize_sql_input($_POST["class_menu"],20,"/[^a-zA-Z1-9 ]/");


	if(empty($form) || empty($track) || empty($department) || empty($class))
	{
		print("<script>alert('not allowed');</script>");
		exit;
	}


	
	$conn=sql_connect();

	$sql="SELECT DISTINCT username,studentname FROM student_subject_info WHERE form='".$form."' AND track='".$track."' AND department='".$department."' AND class='".$class."'";
	$results=mysqli_query($conn,$sql);


	if(mysqli_num_rows($results) > 0)
	{
		print("<h1 style='font-family:monospace; color: #88ffff' align='center'>CREDENTIALS FOR NEWLY ADDED STUDENTS</h1>");
		print("<table width='100%'><tr><th>Name</th><th>Username</th><th>Password</th><th>State</th></tr>");

		while($rows=mysqli_fetch_assoc($results))
		{
			$sql="SELECT * FROM student_login_info WHERE username='".$rows["username"]."'";
			$ret=mysqli_query($conn,$sql);
			if(mysqli_num_rows($ret) < 1)
				continue;

			$state="inactive";
			while($lane=mysqli_fetch_assoc($ret))
			{
				$password=$lane["password"];
				if($lane["status"]==1)
					$state="active";
			}

			print
			(
				"<tr><td><button>".$rows["studentname"]."</button></td>".
				"<td><button>".$rows["username"]."</button></td>".
				"<td><button>".$password."</button></td>".
				"<td><button>".$state."</button></td></tr>"

			);
		}
		print("</table>");
	}

	else
	{
		print("<h1>NO NEWLY ADDED STUDENT</h1>");
	}

}

else
{
	exit;
	header("location: ../../../login/index.php");
}
?>
