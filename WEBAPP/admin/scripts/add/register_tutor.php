<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)

{
//generate username a partially random username eg. userkemim4t.tr
	function genUsername()
	{
		$characters="123456789abcdefghijklmnopqrstuvwxyz";
		$username="user";
		for($i=0;$i < 7; $i++) 
			$username.=$characters[rand(0, strlen($characters)-1)];

		$username.=".tr";
		return $username;
	}

//generate an 10 character password
	function genPassword() 
	{
		$characters="123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$password="";
		for($i=0;$i < 10; $i++) 
			$password.=$characters[rand(0, strlen($characters)-1)];

		return $password;
	}

include '../../../login/connection.php';
include '../../../login/func.php';

	$form=sanitize_sql_input($_POST["form_menu"],20,"/[^a-zA-Z1-9 ]/");
	$track=sanitize_sql_input($_POST["track_menu"],20,"/[^a-zA-Z1-9 ]/");
	$department=sanitize_sql_input($_POST["department_menu"],20,"/[^a-zA-Z1-9 ]/");
	$subject=sanitize_sql_input($_POST["subject_menu"],20,"/[^a-zA-Z1-9 ]/");
	$class=sanitize_sql_input($_POST["class_menu"],20,"/[^a-zA-Z1-9 ]/");

	$name=sanitize_sql_input($_POST["tutor_name"],50,"/[^a-zA-Z1-9 ]/");


	if(empty($form) || empty($track) || empty($department) || empty($class) || empty($subject)|| empty($name))
	{
		print("<script>alert('not allowed');</script>");
		exit;
	}

	$conn=sql_connect();

	$sql="";
	$username="";

//checking if a tutor has already been registered for the subject in the same class
	$sql="SELECT * FROM tutor_access_info WHERE form='".$form."' AND track='".$track."' AND  department='".$department."' AND class='".$class."' AND subject='".$subject."'";

	$results=mysqli_query($conn,$sql);
	if(mysqli_num_rows($results)>0)
	{
		print("<script>alert('tutor with same access level already exist!');</script>");
		exit;
	}

//to ensure that generated username doesn't already exist in database
	do
	{
		$username=genUsername();
		$sql="SELECT * FROM tutor_login_info WHERE username='".$username."'";
		$results=mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) > 0)
		{
			$sql="SELECT * FROM tutor_slogin_info WHERE username='".$username."'";
			$results=mysqli_query($conn,$sql);
		}

	}while(mysqli_num_rows($results)>0);

	$sql="INSERT INTO tutor_login_info(username,password,status) VALUES('".$username."','".genPassword()."','1')";
	mysqli_query($conn,$sql);




	$sql="INSERT INTO tutor_access_info(tutorname,username,form,track,department,class,subject) VALUES('".$name."','".$username."','".$form."','".$track."','".$department."','".$class."','".$subject."')";
	mysqli_query($conn,$sql);
}
else
{
	header("location: ../../../login/index.php");
}

?>
