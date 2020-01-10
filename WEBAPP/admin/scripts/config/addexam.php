<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)
{
	include '../../../login/connection.php';
	include '../../../login/func.php';

	if(isset($_POST["submit"]))
	{
		$exam=sanitize_sql_input($_POST["EXAM"],20,"/[^a-zA-Z1-9 ]/");
	
		if(empty($exam))
		{
			print("<script>alert('not allowed');</script>");
			exit;
		}

		$conn=sql_connect();

		$sql="SELECT exam FROM exam_info";

		$results=mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) > 0)
		{
			while($rows=mysqli_fetch_assoc($results))
			{
				if(strtolower($exam)==strtolower($rows["exam"]))
				{
					print("<script>alert('already exist');document.location.href='../../control_panel.php'</script>");
					exit;
				}
			}
		}

		$sql="INSERT INTO exam_info(exam) VALUES('".$exam."')";
		$results=mysqli_query($conn,$sql);

		if(! $results)
		{
			print("<script>alert('".mysqli_error()."');</script>");
			exit;
		}

		header("location: ../../control_panel.php");
	}

	else
	{
		print("<script>alert('not allowed');document.location.href='control_panel.php'</script>");
	}


}
else
{
	header("location: ../../../login/index.php");
}
?>
