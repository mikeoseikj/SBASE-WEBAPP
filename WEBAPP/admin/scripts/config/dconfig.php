<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"] ==true)
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
			background-color: #233422;
			color:white;
			width: 25%;
			border-style: solid;
			border-width:1px;
			border-color: #222322;
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

	print("<marquee><h1 align='center'><i>DATABASE FIELD DELETION</i></h1></marquee>");

	include('../../../login/connection.php');
	$conn = sql_connect();

	$sql = "SELECT form FROM form_info";

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>FORMS IN DATABASE</h1>");
		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["form"]))
			{
				print("<input class='sticker' type='submit' value='".$row["form"]."'></input>");
				print("<form action='remove.php' method='get'>
					<input type='submit' value='remove' name='FORM".$row["form"]."'></input></form>");
			}
		}
	}


	$sql = "SELECT track FROM track_info";

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>TRACKS IN DATABASE</h2>");
		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["track"]))
			{
				print("<input class='sticker' type='submit' value='".$row["track"]."'></input>");
				print("<form action='remove.php' method='get'>
					<input type='submit' value='remove' name='TRACK".$row["track"]."'></input></form>");
			}
		}
	}
  
	$sql = "SELECT department FROM department_info";

	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>DEPARTMENTS IN DATABASE</h2>");
		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["department"]))
			{
				print("<input class='sticker' type='submit' value='".$row["department"]."'></input>");
				print("<form action='remove.php' method='get'>
					<input type='submit' value='remove' name='DEPARTMENT".$row["department"]."'></input></form>");

			}
		}
	}


	// reading all classes from database
	$sql = "SELECT class FROM class_info";
	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>CLASS IN DATABASE</h2>");

		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["class"]))
			{
				print("<input class='sticker' type='submit' value='".$row["class"]."'></input>");
				print("<form action='remove.php' method='get'>
					<input type='submit' value='remove' name='CLASS".$row["class"]."'></input></form>");

			}
		}
	}


	// reading all subjects from database
	$sql = "SELECT subject FROM subject_info";
	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>SUBJECT IN DATABASE</h2>");


		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["subject"]))
			{
				print("<input class='sticker' type='submit' value='".$row["subject"]."'></input>");
				print("<form action='remove.php' method='get'>
					<input type='submit' value='remove' name='SUBJECT".$row["subject"]."'></input></form>");

			}
		}
	}


	//	reading all exams from database
	$sql = "SELECT exam FROM exam_info";
	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h2 align='center'>EXAMS IN DATABASE</h2>");


		while($row = mysqli_fetch_assoc($results))
		{
			if(! empty($row["exam"]))
			{
				print("<input class='sticker' type='submit' value='".$row["exam"]."'></input>");
				print("<form action='remove.php' method='get'>
					<input type='submit' value='remove' name='EXAM".$row["exam"]."'></input></form>");

			}
		}
	}

}

else
{
	header("location: ../../../login/index.php");
}
?>
