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

	$username=sanitize_sql_input($_POST["tutor_name"],14,"/[^a-zA-Z1-9.]/");

	


	if(empty($form) || empty($track) || empty($department) || empty($class) || empty($subject)|| empty($username))
	{
		print("<script>alert('not allowed');</script>");
		exit;
	}

	$conn=sql_connect();


//checking if tutor username exists
	$sql="SELECT * FROM tutor_access_info WHERE username='".$username."'";
	$results=mysqli_query($conn,$sql);
	if(mysqli_num_rows($results) <1)
	{
		print("<script>alert('username does not exist');</script>");
		exit;
	}
	while($x=mysqli_fetch_assoc($results))
		$name=$x["tutorname"];

//checking if a tutor has already been registered for the subject in the same class
	$sql="SELECT * FROM tutor_access_info WHERE form='".$form."' AND track='".$track."' AND  department='".$department."' AND class='".$class."' AND subject='".$subject."'";

	$results=mysqli_query($conn,$sql);
	if(mysqli_num_rows($results) > 0)
	{
		print("<script>alert('registered to someone else');</script>");
		exit;

	}

//adding tutor
	$sql="INSERT INTO tutor_access_info(tutorname,username,form,track,department,class,subject) VALUES('".$name."','".$username."','".$form."','".$track."','".$department."','".$class."','".$subject."')";
	mysqli_query($conn,$sql);

}

else
{
	header("location: ../../../login/index.php");
}
?>
