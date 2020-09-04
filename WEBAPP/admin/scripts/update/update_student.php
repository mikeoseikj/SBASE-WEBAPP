<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"] == true)
{
	include('../../../login/connection.php');
	include('../../../login/func.php');


	$form_old = sanitize_sql_input($_POST["form_old"],"/[^a-zA-Z0-9\-_() ]/");
	$track_old = sanitize_sql_input($_POST["track_old"],"/[^a-zA-Z0-9\-_() ]/");
	$department_old = sanitize_sql_input($_POST["department_old"],"/[^a-zA-Z0-9\-_() ]/");
	$class_old = sanitize_sql_input($_POST["class_old"],"/[^a-zA-Z0-9\-_() ]/");


    $form_new = sanitize_sql_input($_POST["form_new"],"/[^a-zA-Z0-9\-_() ]/");
	$track_new = sanitize_sql_input($_POST["track_new"],"/[^a-zA-Z0-9\-_() ]/");
	$department_new = sanitize_sql_input($_POST["department_new"],"/[^a-zA-Z0-9\-_() ]/");
	$class_new = sanitize_sql_input($_POST["class_new"],"/[^a-zA-Z0-9\-_() ]/");

    $subjects = $_POST["subject_menu"];

	$username = sanitize_sql_input($_POST["username"],"/[^a-zA-Z0-9.]/");

	
	if(empty($form_old) || empty($track_old)|| empty($department_old) || empty($class_old) || empty($form_new) || empty($track_new) || empty($department_new) || empty($class_new))
	{
		print("<script>alert('An empty field was provided');</script>");
		exit;
	}


//validating subject names
	$count = count($subjects);
	for($i = 0; $i < $count; $i++)
	{
		$subjects[$i] = sanitize_sql_input($subjects[$i], "/[^a-zA-Z0-9\-_() ]/");
		if(empty($subjects[$i]))
			exit;
	}

	
	$conn = sql_connect();


	if(! empty($username))
	{
		$sql = "SELECT username FROM student_subject_info;";
		$results = mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) < 1)
		{
			print("<script>alert('no such username');</script>");
			exit;
		}
	}

	if(isset($_POST["confirmation_status"]))
	{

		$val = intval($_POST["confirmation_status"]);
        if($val)
        	goto jmp_here;
        else
        	exit;
	}

   // if a group/single student exists with the same new information you provided
	$sql = "SELECT * FROM student_subject_info WHERE form='".$form_new."' AND track='".$track_new."' AND department='".$department_new."' AND class='".$class_new."'";
	$results = mysqli_query($conn,$sql);
	if(mysqli_num_rows($results) > 0)
	{

		$post_data = "form_old=".$form_old."&track_old=".$track_old."&department_old=".$department_old."&class_old=".$class_old."&";

		$post_data .= "form_new=".$form_new."&track_new=".$track_new."&department_new=".$department_new."&class_new=".$class_new."&username=".$username;

		for($i = 0; $i < count($subjects); $i++)
			$post_data .= "&subject_menu[]=".$subjects[$i];

		print("<script>

		    var status = confirm('The new data you provided is already associated to other user(s). Do you want to continue with update? ')? 1 : 0;

		    var xhttp;
		    if(window.XMLHttpRequest) 
    			xhttp = new XMLHttpRequest();
 			else
    			xhttp = new ActiveXObject('Microsoft.XMLHTTP');
	
			xhttp.open('POST', 'update_student.php', true);
			xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhttp.send('".$post_data."'+'&confirmation_status='+status); 
			xhttp.onreadystatechange = processRequest;


			function processRequest(e)
			{
				if(xhttp.readyState == 4)
					document.write(xhttp.responseText);

			}
			</script>");

		exit;
	}

 
	jmp_here:	
	$sql = "SELECT * FROM student_subject_info WHERE  form='".$form_old."' AND track='".$track_old."' AND department='".$department_old."' AND class='".$class_old."'";


	if(! empty($username))
	{
		$sql = "SELECT * FROM student_subject_info WHERE username='".$username."' AND form='".$form_old."' AND track='".$track_old."' AND department='".$department_old."' AND class='".$class_old."'";
	}

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		while($row = mysqli_fetch_assoc($results))
		{
			$username = $row["username"];
			$name = $row["studentname"];
			$sql = "DELETE FROM student_subject_info WHERE username='".$username."'";
			mysqli_query($conn,$sql);


			for($i=0; $i < $count; $i++)
			{
				$sql = "INSERT INTO student_subject_info(subjectname,studentname,username,form,track,department,class) VALUES('".$subjects[$i]."','".$name."','".$username."','".$form_new."','".$track_new."','".$department_new."','".$class_new."')";
				mysqli_query($conn,$sql);
			}
		}

	}//num_rows
	else
	{
		print("<script>alert('No users found with such information');</script>");
		exit;
	}

}
else
{
	header("location: ../../../login/index.php");
}

?>
