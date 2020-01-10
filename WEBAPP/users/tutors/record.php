<?php

session_start();


if(isset($_POST["submit"]) && isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"]==true && $_SESSION["user"]=="tutor")
{

//logout users out after 1 hour
	if((time()-$_SESSION["timestamp"]) > 3600)
	{
		print("<script>alert('session timeout');document.location.href='../../login/logout.php'</script>");
		exit;
	}

    include '../../login/connection.php';
    include '../../login/func.php';
	
	$form = sanitize_sql_input($_POST["form"],20,"/[^a-zA-Z1-9 ]/");
	$track = sanitize_sql_input($_POST["track"],20,"/[^a-zA-Z1-9 ]/");
	$department= sanitize_sql_input($_POST["department"],20,"/[^a-zA-Z1-9 ]/");
	$class= sanitize_sql_input($_POST["class"],20,"/[^a-zA-Z1-9 ]/");




	if(empty($form) || empty($track) || empty($department) || empty($class))
	{
		print("<script>alert('not allowed');document.location.href='page.php'</script>");
		exit;
	}


	$percentage=$_POST["percentage"];
	$usernames=$_POST["usernames"];
	$classmarks=$_POST["classmarks"];
	$exammarks=$_POST["exammarks"];
	$subject=$_POST["subject"];

	if(count($usernames) < 1 || count($classmarks) < 1 || count($exammarks) < 1)
	{
		header("location: page.php");
		exit;
	}
	if((count($usernames) !==count($classmarks)) && (count($usernames)!== count($exammarks)))
	{
		header("location: page.php");
		exit;
	}


//mitigate sql injection
	$subject=str_replace("+"," ",$subject);
	$subject = preg_replace("/[^a-zA-Z1-9 ]/", "", $subject);
	$subject=substr($subject,0,20);

	if(empty($subject))
	{
		print("<script>alert('not allowed');document.location.href='../../login/index.php'</script>");
		exit;
	}

//referring to student usernames
	for($i=0;$i< count($usernames);$i++)
	{
		$usernames[$i] = preg_replace("/[^a-zA-Z1-9.]/", "", $usernames[$i]);
		$usernames[$i]=substr($usernames[$i],0,12);
		if(empty($usernames[$i]))
		{
			print("<script>alert('not allowed');document.location.href='../../login/index.php'</script>");
			exit;
		}
	}

	for($i=0;$i< count($classmarks);$i++)
	{
		if(is_numeric($classmarks[$i])==false)
		{
			print("<script>alert('not allowed');document.location.href='../../login/index.php'</script>");
			exit;
		}
	}

	for($i=0;$i< count($exammarks);$i++)
	{
		if(is_numeric($exammarks[$i])==false)
		{
			print("<script>alert('not allowed');document.location.href='../../login/index.php'</script>");
			exit;
		}
	}



	
	$conn=sql_connect();

	if(! $conn)
	{
		print("<script>alert('connection error')</script>");
		header("location: ../../login/index.php");
		exit;
	}


	$exam="";
	$sql="SELECT * FROM exam_info ORDER BY id DESC LIMIT 1";
	$results=mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		while($rows=mysqli_fetch_assoc($results))
		{
			$exam=$rows["exam"];
		}
	}

	else
	{
		print("<script>alert('No exam');document.location.href='page.php'</script>");
		exit;
	}

	for($i=0;$i< count($usernames);$i++)
	{
		$sql="SELECT * FROM student_results WHERE subjectname='".$subject."' AND username='".$usernames[$i]."' AND exam='".$exam."'";

		$results=mysqli_query($conn,$sql);

//update the marks if they already exist
		if(mysqli_num_rows($results) > 0)
		{
			$cscore=$classmarks[$i];
			$escore=$exammarks[$i];
			$tscore=((($classmarks[$i]+$exammarks[$i])/$percentage) * 100);

			$sql="UPDATE student_results SET classmarks='".$cscore."' , exammarks='".$escore."' , totalmarks='".$tscore."' WHERE subjectname='".$subject."' AND username='".$usernames[$i]."' AND exam='".$exam."'";

			$results=mysqli_query($conn,$sql);
			if(! $results)
			{
				print("<script>alert('marks update error');document.location.href='page.php'</script>");
				exit;
			}


//calculate overrall marks (update)
			$sql="SELECT * FROM student_overrall_marks WHERE username='".$usernames[$i]."' AND exam='".$exam."'";
			$results=mysqli_query($conn,$sql);

			if(mysqli_num_rows($results) > 0)
			{
				$marks=0;
				$sql="SELECT * FROM student_results WHERE username='".$usernames[$i]."' AND exam='".$exam."'";
				$results=mysqli_query($conn,$sql);

				while($rows=mysqli_fetch_assoc($results))
					$marks+=$rows["totalmarks"];

				$sql="UPDATE student_overrall_marks SET marks='".$marks."' WHERE  username='".$usernames[$i]."' AND exam='".$exam."'";
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
				$marks=0;
				$sql="SELECT * FROM student_results WHERE username='".$usernames[$i]."' AND exam='".$exam."'";

				$results=mysqli_query($conn,$sql);

				while($rows=mysqli_fetch_assoc($results))
					$marks+=$rows["totalmarks"];


				$sql="INSERT INTO student_overrall_marks (username,exam,marks,form,track,department,class) VALUES('".$usernames[$i]."','".$exam."','".$marks."','".$form."','".$track."','".$department."','".$class."')";
				$results=mysqli_query($conn,$sql);
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

			$cscore=$classmarks[$i];
			$escore=$exammarks[$i];
			$tscore=((($classmarks[$i]+$exammarks[$i])/$percentage) * 100);

			$sql="INSERT INTO student_results(subjectname,username,exam,classmarks,exammarks,totalmarks,form,track,department,class) VALUES('".$subject."','".$usernames[$i]."','".$exam."','".$cscore."','".$escore."','".$tscore."','".$form."','".$track."','".$department."','".$class."')";
			$results=mysqli_query($conn,$sql);

			if(! $results)
			{
				print("<script>alert('marks insertion error');document.location.href='page.php'</script>");
				exit;
			}

//calculate overrall marks (update)
			$sql="SELECT * FROM student_overrall_marks WHERE username='".$usernames[$i]."' AND exam='".$exam."'";
			$results=mysqli_query($conn,$sql);

			if(mysqli_num_rows($results) > 0)
			{
				$marks=0;
				$sql="SELECT * FROM student_results WHERE username='".$usernames[$i]."' AND exam='".$exam."'";
				$results=mysqli_query($conn,$sql);

				while($rows=mysqli_fetch_assoc($results))
					$marks+=$rows["totalmarks"];

				$sql="UPDATE student_overrall_marks SET marks='".$marks."' WHERE  username='".$usernames[$i]."' AND exam='".$exam."'";
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
				$marks=0;
				$sql="SELECT * FROM student_results WHERE username='".$usernames[$i]."' AND exam='".$exam."'";

				$results=mysqli_query($conn,$sql);

				while($rows=mysqli_fetch_assoc($results))
					$marks+=$rows["totalmarks"];


				$sql="INSERT INTO student_overrall_marks (username,exam,marks,form,track,department,class) VALUES('".$usernames[$i]."','".$exam."','".$marks."','".$form."','".$track."','".$department."','".$class."')";
				$results=mysqli_query($conn,$sql);
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
