<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"]== true)
{

	print("<style type='text/css'>
		body
		{
			background-color: #000000;
			font-family: monospace;
			margin-top: 10%;
		}
		h1
		{
			color:#88ffff;
		}

		input
		{
			background-color: #ff4444;
			color:white;
			width:15%;
			border-style: solid;
			border-width:1px;
			border-color: red;
			border-radius: 2px;
			font-family: monospace;
		}


		.sticker
		{
			background-color: #888888;
			color:white;
			width: 25%;
			border-style: solid;
			border-width:1px;
			border-color: #c0c0c0;
			border-radius: 2px;
		}

		h2
		{
			border-style:solid;
			border-width: 1px;
			border-color:#88ffff;
			color:#88ffff;
			border-radius: 5px;
		}

		h3
		{
			border-style:solid;
			border-width: 1px;
			border-color:#88ffff;
			color:#88ffff;
			border-radius: 5px;
		}

		</style>");

	print("<h1 align='center'><i>DATABASE FIELD VIEW</i></h1>");

	include('../../../login/connection.php');

	$conn = sql_connect();

	$sql = "SELECT form FROM form_info";

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>".mysqli_num_rows($results)." FORM(S) IN DATABASE</h1>");
		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["form"]))
			{
				print("<input class='sticker' align='center' type='submit' value='".$row["form"]."'></input><br />");
			}
		}
	}
	else
	{
		print("<h3>NO FORM INFORMATION IN DATABASE<h3><br />");
	}


	$sql = "SELECT track FROM track_info";

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>".mysqli_num_rows($results)." TRACK(S) IN DATABASE</h2>");
		while($row=mysqli_fetch_assoc($results))
		{
			if(! empty($row["track"]))
			{
				print("<input class='sticker' type='submit' value='".$row["track"]."'></input><br />");
			}
		}
	}
	else
	{
		print("<h3>NO TRACK INFORMATION IN DATABASE<h3><br />");
	}

	$sql = "SELECT department FROM department_info";

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>".mysqli_num_rows($results)." DEPARTMENT(S) IN DATABASE</h2>");
		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["department"]))
			{
				print("<input class='sticker' type='submit' value='".$row["department"]."'></input><br />");
			}
		}
	}

	else
	{
		print("<h3>NO DEPARTMENT INFORMATION IN DATABASE<h3><br />");
	}

//reading all classes from database
	$sql = "SELECT class FROM class_info";

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>".mysqli_num_rows($results)." CLASS(ES) IN DATABASE</h2>");

		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["class"]))
			{
				print("<input class='sticker' type='submit' value='".$row["class"]."'></input><br />");
			}
		}
	}

	else
	{
		print("<h3>NO CLASS INFORMATION IN DATABASE<h3><br />");
	}

//reading all subjects from database
	$sql = "SELECT subject FROM subject_info";

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>".mysqli_num_rows($results)." SUBJECT(S) IN DATABASE</h2>");


		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["subject"]))
			{
				print("<input class='sticker' type='submit' value='".$row["subject"]."'></input><br />");
			}
		}
	}

	else
	{
		print("<h3>NO SUBJECT INFORMATION IN DATABASE<h3><br />");
	}


//reading all exams from database
	$sql = "SELECT exam FROM exam_info";

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>".mysqli_num_rows($results)." EXAM(S) IN DATABASE</h2>");


		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["exam"]))
			{
				print("<input class='sticker' type='submit' value='".$row["exam"]."'></input><br />");
			}
		}
	}

	else
	{
		print("<h3>NO EXAM INFORMATION IN DATABASE<h3><br />");
	}

}
else
{
	header("location: ../../../login/index.php");
}
?>
