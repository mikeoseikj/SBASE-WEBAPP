<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)
{
	include '../../../login/connection.php';
	include '../../../login/func.php';


	$subjects=array();

	$form_old=sanitize_sql_input($_POST["form_old"],20,"/[^a-zA-Z1-9 ]/");
	$track_old=sanitize_sql_input($_POST["track_old"],20,"/[^a-zA-Z1-9 ]/");
	$department_old=sanitize_sql_input($_POST["department_old"],20,"/[^a-zA-Z1-9 ]/");
	$class_old=sanitize_sql_input($_POST["class_old"],20,"/[^a-zA-Z1-9 ]/");


    $form_new=sanitize_sql_input($_POST["form_new"],20,"/[^a-zA-Z1-9 ]/");
	$track_new=sanitize_sql_input($_POST["track_new"],20,"/[^a-zA-Z1-9 ]/");
	$department_new=sanitize_sql_input($_POST["department_new"],20,"/[^a-zA-Z1-9 ]/");
	$class_new=sanitize_sql_input($_POST["class_new"],20,"/[^a-zA-Z1-9 ]/");

    $subjects=$_POST["subject_menu"];

	$username=sanitize_sql_input($_POST["student_name"],12,"/[^a-zA-Z1-9.]/");
	$rule=$_POST["rule"];

	
	if(empty($form_old) || empty($track_old)|| empty($department_old) || empty($class_old) || empty($form_new) || empty($track_new) || empty($department_new) || empty($class_new))
	{
		print("<script>alert('not allowed');</script>");
		exit;
	}


//validating subject names
	$count=count($subjects);
	for($i=0;$i<$count;$i++)
	{
		$subjects[$i] = preg_replace("/[^a-zA-Z1-9 ]/", "", $subjects[$i]);
		$subjects[$i]=substr($subjects[$i],0,20);
		if(empty($subjects[$i]))
			exit;
	}

	
	$conn=sql_connect();


	if(! empty($username))
	{
		$sql="SELECT username FROM student_subject_info;";
		$results=mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) < 1)
		{
			print("<script>alert('no such username');</script>");
			exit;
		}
	}

//merge and ignore handling
	$sql="SELECT * FROM student_subject_info WHERE form='".$form_new."' AND track='".$track_new."' AND department='".$department_new."' AND class='".$class_new."'";
	$results=mysqli_query($conn,$sql);
	if(mysqli_num_rows($results) > 0)
	{
		if($rule != "merge")
		{
			print("<script>alert('ignoring update');</script>");
			exit;
		}
	}


	$sql="SELECT * FROM student_subject_info WHERE  form='".$form_old."' AND track='".$track_old."' AND department='".$department_old."' AND class='".$class_old."'";


	if(! empty($username))
	{
		$sql="SELECT * FROM student_subject_info WHERE username='".$username."' AND form='".$form_old."' AND track='".$track_old."' AND department='".$department_old."' AND class='".$class_old."'";
	}

	$results=mysqli_query($conn,$sql);

	if(mysqli_num_rows($results)>0)
	{
		while($row=mysqli_fetch_assoc($results))
		{
			$username=$row["username"];
			$name=$row["studentname"];
			$sql="DELETE FROM student_subject_info WHERE username='".$username."'";
			mysqli_query($conn,$sql);


			for($i=0;$i< $count;$i++)
			{
				$sql="INSERT INTO student_subject_info(subjectname,studentname,username,form,track,department,class) VALUES('".$subjects[$i]."','".$name."','".$username."','".$form_new."','".$track_new."','".$department_new."','".$class_new."')";
				mysqli_query($conn,$sql);
			}
		}

}//num_rows
else
{
	print("<script>alert('no users found with such information');</script>");
	exit;
}

}
else
{
	header("location: ../../../login/index.php");
}

?>
