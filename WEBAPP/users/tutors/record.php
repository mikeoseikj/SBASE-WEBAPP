<?php

session_start();


if(isset($_POST["submit"]) && isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"] == true && $_SESSION["user"] == "tutor")
{

    include('../../login/connection.php');
    include('../../login/func.php');
	
	$form = sanitize_sql_input($_POST["form"],"/[^a-zA-Z0-9\-_() ]/");
	$track = sanitize_sql_input($_POST["track"],"/[^a-zA-Z0-9\-_() ]/");
	$department = sanitize_sql_input($_POST["department"],"/[^a-zA-Z0-9\-_() ]/");
	$class = sanitize_sql_input($_POST["class"],"/[^a-zA-Z0-9\-_() ]/");
	$subject = sanitize_sql_input($_POST["subject"],"/[^a-zA-Z0-9\-_() ]/");
	$exam = sanitize_sql_input($_POST["exam"],"/[^a-zA-Z0-9\-_() ]/");




	if(empty($form) || empty($track) || empty($department) || empty($class) || empty($subject) || empty($exam))
	{
		print("<script>alert('An empty field was provided');document.location.href='page.php'</script>");
		exit;
	}


	$class_percentage = $_POST["class_percentage"];
	$exam_percentage = $_POST["exam_percentage"];
	$usernames = $_POST["usernames"];
	$classmarks = $_POST["classmarks"];
	$exammarks = $_POST["exammarks"];
	
	if(count($usernames) < 1 || count($classmarks) < 1 || count($exammarks) < 1)
	{
		header("location: page.php");
		exit;
	}
	if((count($usernames) !== count($classmarks)) && (count($usernames) !== count($exammarks)))
	{
		header("location: page.php");
		exit;
	}


//referring to student usernames
	for($i = 0; $i < count($usernames); $i++)
	{
		$usernames[$i] = sanitize_sql_input($usernames[$i], "/[^a-zA-Z0-9.]/");
		if(empty($usernames[$i]))
		{
			print("<script>alert('not allowed');document.location.href='page.php'</script>");
			exit;
		}
	}

	for($i=0;$i< count($classmarks); $i++)
	{
		if(is_numeric($classmarks[$i]) == false)
		{
			print("<script>alert('not allowed');document.location.href='page.php'</script>");
			exit;
		}
	}

	for($i = 0; $i < count($exammarks); $i++)
	{
		if(is_numeric($exammarks[$i]) == false)
		{
			print("<script>alert('not allowed');document.location.href='page.php'</script>");
			exit;
		}
	}

	if((is_numeric($class_percentage) == false) ||  (is_numeric($exam_percentage) == false))
	{
		print("<script>alert('not allowed');document.location.href='page.php'</script>");
		exit;
	}

	
	$conn = sql_connect();

	for($i = 0;$i < count($usernames); $i++)
	{
		$sql = "SELECT * FROM student_results WHERE subjectname='".$subject."' AND username='".$usernames[$i]."' AND exam='".$exam."'";

		$results = mysqli_query($conn,$sql);

//update the marks if they already exist
		if(mysqli_num_rows($results) > 0)
		{
			$cscore = ($classmarks[$i] * ((float)$class_percentage / 100));
			$escore = ($exammarks[$i] * ((float)$exam_percentage / 100));
			$tscore = $cscore + $escore;


			$sql = "UPDATE student_results SET classmarks='".$cscore."' , exammarks='".$escore."' , totalmarks='".$tscore."' WHERE subjectname='".$subject."' AND username='".$usernames[$i]."' AND exam='".$exam."'";

			$results = mysqli_query($conn,$sql);
			if(! $results)
			{
				print("<script>alert('marks update error');document.location.href='page.php'</script>");
				exit;
			}


//calculate overrall marks (update)
			$sql = "SELECT * FROM student_overrall_marks WHERE username='".$usernames[$i]."' AND exam='".$exam."'";
			$results = mysqli_query($conn,$sql);

			if(mysqli_num_rows($results) > 0)
			{
				$marks = 0;
				$sql = "SELECT * FROM student_results WHERE username='".$usernames[$i]."' AND exam='".$exam."'";
				$results = mysqli_query($conn,$sql);

				while($rows = mysqli_fetch_assoc($results))
					$marks += $rows["totalmarks"];

				$sql = "UPDATE student_overrall_marks SET marks='".$marks."' WHERE  username='".$usernames[$i]."' AND exam='".$exam."'";
				$results=mysqli_query($conn,$sql);
				if(! $results)
				{
					print("<script>alert('overrall marks update error');document.location.href='page.php'</script>");
					exit;
				}
			}
			else
			{
//calculate overrall marks (insert)
				$marks = 0;
				$sql = "SELECT * FROM student_results WHERE username='".$usernames[$i]."' AND exam='".$exam."'";

				$results = mysqli_query($conn,$sql);

				while($rows = mysqli_fetch_assoc($results))
					$marks += $rows["totalmarks"];


				$sql = "INSERT INTO student_overrall_marks (username,exam,marks,form,track,department,class) VALUES('".$usernames[$i]."','".$exam."','".$marks."','".$form."','".$track."','".$department."','".$class."')";
				$results = mysqli_query($conn,$sql);
				if(! $results)
				{
					print("<script>alert('overrall marks insertion error');document.location.href='page.php'</script>");
					exit;
				}
			}

		}

//insert they dont exist
		else
		{

			$cscore = ($classmarks[$i] * ((float)$class_percentage / 100));
			$escore = ($exammarks[$i] * ((float)$exam_percentage / 100));
			$tscore = $cscore + $escore;

			$sql = "INSERT INTO student_results(subjectname,username,exam,classmarks,exammarks,totalmarks,form,track,department,class) VALUES('".$subject."','".$usernames[$i]."','".$exam."','".$cscore."','".$escore."','".$tscore."','".$form."','".$track."','".$department."','".$class."')";
			$results = mysqli_query($conn,$sql);

			if(! $results)
			{
				print("<script>alert('marks insertion error');document.location.href='page.php'</script>");
				exit;
			}

//calculate overrall marks (update)
			$sql = "SELECT * FROM student_overrall_marks WHERE username='".$usernames[$i]."' AND exam='".$exam."'";
			$results = mysqli_query($conn,$sql);

			if(mysqli_num_rows($results) > 0)
			{
				$marks = 0;
				$sql = "SELECT * FROM student_results WHERE username='".$usernames[$i]."' AND exam='".$exam."'";
				$results = mysqli_query($conn,$sql);

				while($rows = mysqli_fetch_assoc($results))
					$marks += $rows["totalmarks"];

				$sql = "UPDATE student_overrall_marks SET marks='".$marks."' WHERE  username='".$usernames[$i]."' AND exam='".$exam."'";
				$results = mysqli_query($conn,$sql);
				if(! $results)
				{
					print("<script>alert('overrall marks update error');document.location.href='page.php'</script>");
					exit;
				}
			}
			else
			{
//calculate overrall marks (insert)
				$marks = 0;
				$sql = "SELECT * FROM student_results WHERE username='".$usernames[$i]."' AND exam='".$exam."'";

				$results = mysqli_query($conn,$sql);

				while($rows = mysqli_fetch_assoc($results))
					$marks += $rows["totalmarks"];


				$sql = "INSERT INTO student_overrall_marks (username,exam,marks,form,track,department,class) VALUES('".$usernames[$i]."','".$exam."','".$marks."','".$form."','".$track."','".$department."','".$class."')";
				$results = mysqli_query($conn,$sql);
				if(! $results)
				{
					print("<script>alert('overrall marks insertion error');document.location.href='page.php'</script>");
					exit;
				}
			}

		}

}//for loop

header("location: page.php");
}
else
{
	header("location: page.php");
}
?>
