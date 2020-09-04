<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"]==true)
{
	//generate username a partially random username eg. userkemim.st
	function generate_random_student_username()
	{
		$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
		$username = "user";
		for($i=0; $i < 5; $i++) 
			$username .= $characters[rand(0, strlen($characters)-1)];

		$username .= ".st";
		return $username;
	}

//generate an 8 character password
	function generate_random_student_password() 
	{
		$characters = "023456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$password = "";
		for($i=0;$i < 8; $i++) 
			$password .= $characters[rand(0, strlen($characters)-1)];

		return $password;
	}


	include('../../../login/func.php');
	include('../../../login/connection.php');




	$form = sanitize_sql_input($_POST["form_menu"],"/[^a-zA-Z0-9\-_() ]/");
	$track = sanitize_sql_input($_POST["track_menu"],"/[^a-zA-Z0-9\-_() ]/");
	$department = sanitize_sql_input($_POST["department_menu"],"/[^a-zA-Z0-9\-_() ]/");
	$class = sanitize_sql_input($_POST["class_menu"],"/[^a-zA-Z0-9\-_() ]/");
	$subjects = $_POST["subject_menu"];
	$name_list = $_POST["name_list"];

	if(empty($form) || empty($track) || empty($department) || empty($class))
	{
		print("<script>alert('An empty field was provided');document.location.href='../../control_panel.php'</script>");
		exit;
	}

	
	$conn = sql_connect();

//ensuring that no subject field is empty
	for($i = 0;$i < count($subjects); $i++)
	{
		$subjects[$i] = sanitize_sql_input($subjects[$i], "/[^a-zA-Z0-9\-_() ]/");
		if(empty($subjects[$i]))
			exit;
	}

	$username_buffer = array();

//ensuring that no student name is empty
	for($i = 0; $i < count($name_list); $i++) 
	{
		$name_list[$i] = sanitize_sql_input($name_list[$i], "/[^a-zA-Z ]/");
		if(empty($name_list[$i]))
		{
			print("<script>alert('An empty student name  was provided');document.location.href='add_student.php'</script>");
			exit;
		}
	}
	
	
	for($i  = 0; $i < count($name_list); $i++)
	{
		do    //to ensure that generated username doesn't already exist in data
		{
			$username = generate_random_student_username();
			$sql = "SELECT * FROM student_login_info WHERE username='".$username."'";
			$results = mysqli_query($conn,$sql);

			if(mysqli_num_rows($results) < 1)
			{
				$sql = "SELECT * FROM student_slogin_info WHERE username='".$username."'";
				$results = mysqli_query($conn,$sql);
			}

		}while(mysqli_num_rows($results) > 0);

		$sql = "INSERT INTO student_login_info(username,password,status) VALUES('".$username."','".generate_random_student_password()."','1')";
		mysqli_query($conn, $sql);
		array_push($username_buffer, $username);

	}




	for($i = 0; $i < count($username_buffer); $i++)
	{
		for($j=0; $j < count($subjects); $j++)
		{
			$sql = "INSERT INTO student_subject_info(subjectname,studentname,username,form,track,department,class) VALUES('".$subjects[$j]."','".$name_list[$i]."','".$username_buffer[$i]."','".$form."','".$track."','".$department."','".$class."')";
			mysqli_query($conn,$sql);
		}

	}
}
else
{
	header("location: ../../../login/index.php");
}
?>
