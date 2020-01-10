<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)
{
	include '../../../login/connection.php';
	include '../../../login/func.php';

	$value="";
	$field="";
	$table=array();
	if($_POST["form_menu"])
	{
		$field="form";
		$value = sanitize_sql_input($_POST["form_menu"],20,"/[^a-zA-Z1-9 ]/");
		

	}
	elseif($_POST["track_menu"])
	{
		$field="track";
		$value = sanitize_sql_input($_POST["track_menu"],20,"/[^a-zA-Z1-9 ]/");
	}
	elseif($_POST["department_menu"])
	{
		$field="department";
		$value = sanitize_sql_input($_POST["department_menu"],20,"/[^a-zA-Z1-9 ]/");
	}
	elseif($_POST["class_menu"])
	{
		$field="class";
		$value = sanitize_sql_input($_POST["class_menu"],20,"/[^a-zA-Z1-9 ]/");
	}
	elseif($_POST["subject_menu"])
	{
		$field="subject";
		$value = sanitize_sql_input($_POST["subject_menu"],20,"/[^a-zA-Z1-9 ]/");
	}

	$x=$_POST["user"];

	if(empty($value) || empty($x))
	{
		print("<script>alert('not allowed');</script>");
		exit;
	}

	$conn=sql_connect();

	if($x=="tutor")
	{
		$sql="SELECT username FROM tutor_access_info WHERE ".$field."='".$value."'";
		$results=mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) > 0)
		{
			while($row=mysqli_fetch_assoc($results))
			{
				$username=$row["username"];
				$sql="DELETE FROM tutor_access_info WHERE username='".$username."'";
				mysqli_query($conn,$sql);
				$sql="DELETE FROM tutor_login_info WHERE username='".$username."'";
				mysqli_query($conn,$sql);

				$sql="DELETE FROM tutor_slogin_info WHERE username='".$username."'";
				mysqli_query($conn,$sql);


			}
		}
		else
		{
			print("<script>alert('tutor deletion error');</script>");
			exit;
		}


}//check tutor

elseif($x=="student")
{
	if($field=="subject")
		$field.="name";

	$sql="SELECT username FROM student_subject_info WHERE ".$field."='".$value."'";
	$results=mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		while($row=mysqli_fetch_assoc($results))
		{
			$username=$row["username"];
			$sql="DELETE FROM student_subject_info WHERE username='".$username."'";
			mysqli_query($conn,$sql);
			$sql="DELETE FROM student_login_info WHERE username='".$username."'";
			mysqli_query($conn,$sql);
			$sql="DELETE FROM student_slogin_info WHERE username='".$username."'";
			mysqli_query($conn,$sql);
			$sql="DELETE FROM student_results WHERE username='".$username."'";
			mysqli_query($conn,$sql);
			$sql="DELETE FROM student_overrall_marks WHERE username='".$username."'";
			mysqli_query($conn,$sql);

		}
	}

	else
	{
		print("<script>alert('tutor deletion error');</script>");
		exit;
	}


}//check student


}
else
{
	header("location: ../../../login/index.php");
}

?>
