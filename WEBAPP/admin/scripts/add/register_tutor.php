<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)

{
//generate username a partially random username eg. userkemim4t.tr
	function generate_random_tutor_username()
	{
		$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
		$username = "user";
		for($i=0; $i < 7; $i++) 
			$username .= $characters[rand(0, strlen($characters)-1)];

		$username .= ".tr";
		return $username;
	}

//generate an 10 character password
	function generate_random_tutor_password() 
	{
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$password = "";
		for($i=0; $i < 10; $i++) 
			$password .= $characters[rand(0, strlen($characters)-1)];

		return $password;
	}

    include('../../../login/connection.php');
    include('../../../login/func.php');

	$form = sanitize_sql_input($_POST["form_menu"],"/[^a-zA-Z0-9\-_() ]/");
	$track = sanitize_sql_input($_POST["track_menu"],"/[^a-zA-Z0-9\-_() ]/");
	$department = sanitize_sql_input($_POST["department_menu"],"/[^a-zA-Z0-9\-_() ]/");
	$subject = sanitize_sql_input($_POST["subject_menu"],"/[^a-zA-Z0-9\-_() ]/");
	$class = sanitize_sql_input($_POST["class_menu"],"/[^a-zA-Z0-9\-_() ]/");

	$name = sanitize_sql_input($_POST["tutor_name"],"/[^a-zA-Z ]/");


	if(empty($form) || empty($track) || empty($department) || empty($class) || empty($subject)|| empty($name))
	{
		print("<script>alert('An empty field was provided');</script>");
		exit;
	}

	$conn = sql_connect();


//checking if a tutor has already been registered for the subject in the same class
	$sql = "SELECT * FROM tutor_access_info WHERE form='".$form."' AND track='".$track."' AND  department='".$department."' AND class='".$class."' AND subject='".$subject."'";

	$results = mysqli_query($conn, $sql);
	if(mysqli_num_rows($results) > 0)
	{
		print("<script>alert('tutor with same access level already exist!');</script>");
		exit;
	}

//to ensure that generated username doesn't already exist in database
	do
	{
		$username = generate_random_tutor_username();
		$sql = "SELECT * FROM tutor_login_info WHERE username='".$username."'";
		$results = mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) < 1)
		{
			$sql = "SELECT * FROM tutor_slogin_info WHERE username='".$username."'";
			$results = mysqli_query($conn,$sql);
		}

	}while(mysqli_num_rows($results) > 0);

	$sql = "INSERT INTO tutor_login_info(username,password,status) VALUES('".$username."','".generate_random_tutor_password()."','1')";
	mysqli_query($conn, $sql);




	$sql = "INSERT INTO tutor_access_info(tutorname,username,form,track,department,class,subject) VALUES('".$name."','".$username."','".$form."','".$track."','".$department."','".$class."','".$subject."')";
	mysqli_query($conn,$sql);
}
else
{
	header("location: ../../../login/index.php");
}

?>
