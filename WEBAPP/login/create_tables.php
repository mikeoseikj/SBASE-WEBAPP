<?php

//CREATE ALL TABLES EXCEPT admin_slogin_info (14 TABLES)


function create_required_tables()
{
	$config = include('../config.php');
	$conn = mysqli_connect($config->dbhost, $config->dbuser, $config->dbpass);

	if(! $conn)
	{
		print("<script>alert('DATABASE CONNECTION ERROR');document.location.href='index.php'</script>");
		exit;
	}

	$sql = "CREATE DATABASE IF NOT EXISTS SBASE";
	mysqli_query($conn,$sql);
	mysqli_select_db($conn,"SBASE");

//configuration tables
	$sql = "CREATE TABLE IF NOT EXISTS form_info(form VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);
	$sql = "CREATE TABLE IF NOT EXISTS track_info(track VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);
	$sql = "CREATE TABLE IF NOT EXISTS department_info(department VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);
	$sql = "CREATE TABLE IF NOT EXISTS class_info(class VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);
	$sql = "CREATE TABLE IF NOT EXISTS subject_info(subject VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);
	$sql = "CREATE TABLE IF NOT EXISTS exam_info(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,exam VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);


//tutor tables
	$sql = "CREATE TABLE IF NOT EXISTS tutor_login_info(username VARCHAR(14) NOT NULL,password VARCHAR(10) NOT NULL,status INT NOT NULL)";
	mysqli_query($conn,$sql);

	$sql = "CREATE TABLE IF NOT EXISTS tutor_slogin_info(username VARCHAR(14) NOT NULL,password VARCHAR(60) NOT NULL,recoveryhint VARCHAR(60) NOT NULL,status INT NOT NULL)";
	mysqli_query($conn,$sql);

	$sql = "CREATE TABLE IF NOT EXISTS tutor_access_info(tutorname VARCHAR(1024) NOT NULL,username VARCHAR(14) NOT NULL,form VARCHAR(1024) NOT NULL,track VARCHAR(1024) NOT NULL,department VARCHAR(1024) NOT NULL,class VARCHAR(1024) NOT NULL,subject VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);

//student tables 
	$sql = "CREATE TABLE IF NOT EXISTS student_login_info(username VARCHAR(12) NOT NULL,password VARCHAR(8) NOT NULL,status INT NOT NULL)";
	mysqli_query($conn,$sql);

	$sql = "CREATE TABLE IF NOT EXISTS student_slogin_info(username VARCHAR(12) NOT NULL,password VARCHAR(60) NOT NULL,recoveryhint VARCHAR(60) NOT NULL,status INT NOT NULL)";
	mysqli_query($conn,$sql);

	$sql = "CREATE TABLE IF NOT EXISTS student_subject_info(subjectname VARCHAR(1024) NOT NULL,studentname VARCHAR(1024) NOT NULL,username VARCHAR(12) NOT NULL,form VARCHAR(1024) NOT NULL,track VARCHAR(1024) NOT NULL,department VARCHAR(1024) NOT NULL,class VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);


	$sql = "CREATE TABLE IF NOT EXISTS student_results(subjectname VARCHAR(1024) NOT NULL,username VARCHAR(12) NOT NULL,exam VARCHAR(1024) NOT NULL,classmarks FLOAT NOT NULL,exammarks FLOAT NOT NULL,totalmarks FLOAT NOT NULL,form VARCHAR(1024) NOT NULL,track VARCHAR(1024) NOT NULL,department VARCHAR(1024) NOT NULL,class VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);


	$sql = "CREATE TABLE IF NOT EXISTS student_overrall_marks(username VARCHAR(12) NOT NULL,exam VARCHAR(1024) NOT NULL,marks FLOAT NOT NULL,form VARCHAR(1024) NOT NULL,track VARCHAR(1024) NOT NULL,department VARCHAR(1024) NOT NULL,class VARCHAR(1024) NOT NULL)";
	mysqli_query($conn,$sql);
	mysqli_close($conn);
}

?>
