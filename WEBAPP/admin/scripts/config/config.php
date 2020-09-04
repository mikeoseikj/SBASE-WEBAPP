<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"]== true)
{


	if(isset($_POST["submit"]))
	{
		include('../../../login/connection.php');
		include('../../../login/func.php');


		$input="";
		$field="";
		if(isset($_POST["FORM"]))
			{$input = $_POST["FORM"]; $field = "form";}


		elseif(isset($_POST["TRACK"]))
			{$input = $_POST["TRACK"]; $field = "track";}

		elseif(isset($_POST["DEPARTMENT"]))
			{$input = $_POST["DEPARTMENT"]; $field = "department";}

		elseif(isset($_POST["CLASS"]))
			{$input = $_POST["CLASS"]; $field = "class";}

		elseif(isset($_POST["SUBJECT"]))
			{$input = $_POST["SUBJECT"]; $field = "subject";}

		$string = sanitize_sql_input($input,"/[^a-zA-Z0-9\-_() ]/");
		


		if(empty($string))
		{
			print("<script>alert('Field cannot be empty');</script>");
			exit;
		}

		
		$conn=sql_connect();

//ensuring that one field is not added twice
		$sql = "";
		$tab = "";
		if($field == "form")
		{
			$tab = "form_info";
			$sql = "SELECT ".$field." FROM form_info";
		}
		elseif($field == "track")
		{
			$tab = "track_info";
			$sql = "SELECT ".$field." FROM track_info";
		}
		elseif($field == "department")
		{
			$tab = "department_info";
			$sql = "SELECT ".$field." FROM department_info";
		}
		elseif($field == "class")
		{
			$tab = "class_info";
			$sql = "SELECT ".$field." FROM class_info";
		}
		elseif($field == "subject")
		{
			$tab = "subject_info";
			$sql = "SELECT ".$field." FROM subject_info";
		}

		$results = mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) > 0)
		{
			while($row = mysqli_fetch_assoc($results))
			{
				if(strtolower($row[$field]) == strtolower($string))
				{
					print("<script>alert('already exist');document.location.href='../../control_panel.php'</script>");
					exit;
				}
			}
		}

		$sql = "INSERT INTO ".$tab."(".$field.")VALUES ('".$string."')";
		mysqli_query($conn,$sql);

		header("location: ../../control_panel.php");
	}

	else
	{
		print("<script>alert('not allowed');document.location.href='../../control_panel.php'</script>");
	}

}
else
{
	header("location: ../../../login/index.php");
}
?>
