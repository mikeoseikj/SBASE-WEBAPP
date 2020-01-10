<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)
{

	print("
		<style type='text/css'>
		body
		{
			background-color: #000000;
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
		print("<table width='100%'><tr><th>Name</th><th>Username</th><th>State</th></tr>");
		while($rows=mysqli_fetch_assoc($results))
		{
			print("<tr><td><button>".$rows["studentname"]."</button></td>");
			print("<td><button>".$rows["username"]."</button></td>");

			$sql="SELECT * FROM student_slogin_info WHERE username='".$rows["username"]."'";
			$ret=mysqli_query($conn,$sql);

			if(mysqli_num_rows($ret) < 1)
				print("<td><button style='background-color:#0000ff;'>unaccessed</button></td></tr><br />");

			if(mysqli_num_rows($ret) > 0)
			{
				while($lane=mysqli_fetch_assoc($ret))
				{
					if($lane["status"]==0)
					{
						print("<td><button style='background-color:#ff0000;'>inactive</button></td></tr><br />");
					}
					else
					{
						print("<td><button style='background-color:#00ff00;'>active</button></td></tr><br />");
					}
				}
			}

		}
		print("</table>");
	}
	else
	{
		print("<h1 align='center' style='color: #88ffff'>NO STUDENTS</h1>");
	}

}

else
{
	header("location: ../../../login/index.php");
}
?>
