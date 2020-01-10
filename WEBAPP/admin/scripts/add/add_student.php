<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true &&  $_SESSION["loggedin"]==true)
{
	$page1=
	"
	<html>
	<head>
	<style type='text/css'>
	body
	{
		font-family: monospace;
		color: #88ffff;
		background-color: #000000;
	}

	input[type='text'] 
	{

		color: #88ffff;
		background-color: #101010;
		margin:2px 0
		border-style: solid;
		border-width: 1px;
		border-color: #88ffff;
		height: 30px;
		width: 40%;
		border-radius:3px;
		font-size: 18px;
	}

	h1
	{
		color: #000000;
		background-color: #99eeee;
		border-style: solid;
		border-width: 2px;
		border-color: #88ffff;
		border-radius: 2px;
	}
	fieldset
	{
		border-color: #88ffff;
	}
	input[type='submit']
	{
		background-color: #4CAF50;
		color:white;
		width:13%;
		height:35px;
		border: none;
		border-width: 1px;
		font-size: 14px;
		font-family: serif;
		float: right;

	}

	input[type='button']
	{
		color:#e0e0e0;
		background-color: #f44336;
		border-style: solid;
		border-width: 1px;
		border-radius: 1px;
		border-color: #f44336;
	}

	select
	{
		font-family: monospace;
		text-align: center;
		font-size: 14px;
		width: 18%;
		background-color:#090909;
		color:green;
		border-style: solid;
		border-width: 1px;
		border-color: green;
	}

	select:hover
	{
		background-color: #101010;
	}

	</style>
	</head>
	<body>
	
	<h1  align='center' >UPLOADING OF STUDENTS IN THE SAME CLASS</h1>
	<br />
	<h3 style='color: #ff2342'>Select the various fields for students the in the bar below </h3>
	";

	$page2=
	"

	<br /><br />
	<form action='register_student.php' id='add_student' method='post'>
	<fieldset id='student'>
	<legend>STUDENTS</legend>
	<label for='p1'>name:</label>
	<input maxlength='30' width='40%' type='text' name='p1' id='p1'>
	</fieldset>
	<div>
	<br />
	<br />
	<input  type='submit' value='upload all students'>
	</div>
	</form>

	<script src='add_student.js'></script>

	</body>
	</html>
	";



	include '../../../login/connection.php';
	$conn=sql_connect();

	if(! $conn)
	{
		print("connection failed".mysqli_connect_error());
		exit;
	}

	$all_menu="";

	$menu="";
	$field="";
	for($i=0; $i<5; $i++)
	{

		if($i==0)
			$field="form";
		elseif($i==1)
			$field="track";
		elseif($i==2)
			$field="department";
		elseif($i==3)
			$field="class";
		elseif($i==4)
			$field="subject";


		$menu="<select name='".$field."_menu' form='add_student'>";

		if($field=="subject")
			$menu="<br /><h3 style='color: #ff2342' align='top' >press and hold ctrl to select multiple subjects</h3><div><select   multiple name='".$field."_menu[]' form='add_student' required>";


//building select menus
		$sql="SELECT ".$field." FROM ".$field."_info";
		$results=mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) > 0)
		{

			while($row=mysqli_fetch_assoc($results))
			{
				$menu=$menu."<option value='".$row[$field]."'>".$row[$field]."</option>";
			}
			$menu=$menu."</select>";

			if($field=="subject")
				$menu=$menu."</div><br /><hr style='color: red; width: 100%; border-style:thin;'> </hr>";

			$all_menu=$all_menu.$menu;
		}


}//for loop
print($page1.$all_menu.$page2);
}
else
{
	header("location: ../../../login/index.php");
}

?>
