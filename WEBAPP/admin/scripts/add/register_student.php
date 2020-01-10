<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)
{
	//generate username a partially random username eg. userkemim.st
	function genUsername()
	{
		$characters="123456789abcdefghijklmnopqrstuvwxyz";
		$username="user";
		for($i=0;$i < 5; $i++) 
			$username.=$characters[rand(0, strlen($characters)-1)];

		$username.=".st";
		return $username;
	}

//generate an 8 character password
	function genPassword() 
	{
		$characters="123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$password="";
		for($i=0;$i < 8; $i++) 
			$password.=$characters[rand(0, strlen($characters)-1)];

		return $password;
	}


	include '../../../login/func.php';
	include '../../../login/connection.php';




	$form=sanitize_sql_input($_POST["form_menu"],20,"/[^a-zA-Z1-9 ]/");
	$track=sanitize_sql_input($_POST["track_menu"],20,"/[^a-zA-Z1-9 ]/");
	$department=sanitize_sql_input($_POST["department_menu"],20,"/[^a-zA-Z1-9 ]/");
	$class=sanitize_sql_input($_POST["class_menu"],20,"/[^a-zA-Z1-9 ]/");
	
	

	if(empty($form) || empty($track) || empty($department) || empty($class))
	{
		print("<script>alert();document.location.href='../../control_panel.php'</script>");
		exit;
	}

	
	$conn=sql_connect();

	if(! $conn)
	{
		print("<script>alert('".mysqli_connect_error()."');document.location.href='../../control_panel.php';</script>");
		exit;
	}

//getting student subjects
	$count=0;
	$subjects=array();


//getting chosen subjects as an array
	foreach($_POST as $key => $value) 
	{
		if(preg_match('@^s@', $key))
		{ 
			$count=count($value);
			$subjects=$value;
		}
	}


//ensuring that no subject field is empty
	for($i=0;$i< $count;$i++)
	{
		$subjects[$i]=preg_replace("/[^a-zA-Z1-9 ]/", "", $subjects[$i]);
		if(empty($subjects[$i]))
			exit;
	}
//ensuring that no student name is empty
	foreach($_POST as $key => $value) 
	{
		if(preg_match('@^p@', $key))
			if(empty($value))
			{
				print("<script>document.location.href='add_student.php'</script>");exit;} 

			}

			$username_buffer=array();

			$sql="";
			$username="";
			foreach($_POST as $key => $value) 
			{
				if(preg_match('@^p@', $key))
				{
					$value=preg_replace("/[^a-zA-Z ]/", "", $value);
//to ensure that generated username doesn't already exist in data
					do
					{
						$username=genUsername();
						$sql="SELECT * FROM student_login_info WHERE username='".$username."'";
						$results=mysqli_query($conn,$sql);

						if(mysqli_num_rows($results) > 0)
						{
							$sql="SELECT * FROM student_slogin_info WHERE username='".$username."'";
							$results=mysqli_query($conn,$sql);
						}


					}while(mysqli_num_rows($results)>0);

					$sql="INSERT INTO student_login_info(username,password,status) VALUES('".$username."','".genPassword()."','1')";
					mysqli_query($conn,$sql);
					array_push($username_buffer,$username);

				}
			}


//checking if student has already registered for subject
			for($i=0;$i<$count;$i++)
			{
				$sql="SELECT * FROM student_subject_info WHERE  username='".$username."' AND subjectname='".$subjects[$i]."'";
				$results=mysqli_query($conn,$sql);
				if(mysqli_num_rows($results) >0 )
				{
					print("<script>alert('student subject registration error');</script>");
					exit;
				}

			}

			$x=-1;
			foreach($_POST as $key => $value) 
			{
				if(preg_match('@^p@', $key))
				{
					$x=$x+1;
					$value=preg_replace("/[^a-zA-Z ]/", "", $value);

					for($i=0;$i< $count;$i++)
					{
						$sql="INSERT INTO student_subject_info(subjectname,studentname,username,form,track,department,class) VALUES('".$subjects[$i]."','".$value."','".$username_buffer[$x]."','".$form."','".$track."','".$department."','".$class."')";
						mysqli_query($conn,$sql);
					}
				}

			}
		}
		else
		{
			header("location: ../../../login/index.php");
		}
		?>
