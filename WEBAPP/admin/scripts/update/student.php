<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"] == true)
{

	$page1 =
	"
	<html>
	<head>
	<style type='text/css'>
	body
	{
		background-color: #000000;
		font-family: monospace;
	}
	.finput
	{
		font-family: monospace;
		color: #101010;
		font-size: 18px;
		width: 90%;
		padding: 15px;
		margin:20px;
		border: none;
		background: #303030;
	}
	.container
	{
		top:0;
		left: 0;
		display: block;
		position: fixed;
		z-index:1; 
		background-color: rgba(0, 0, 0, 0.4); 
		width: 100%;
		height: 100%;
		padding: 16px;
	}

	.form-box
	{
		font-family: monospace;
		border: solid 1px #88ffff;
		margin: 2%;
		background-color: #101010;
		width: 90%;
		height: 410px;
		color: #888;
		opacity: 1;
	}
	.form-box input[type=submit]
	{
		font-family: monospace;
		background-color: #020202;
		color: #88ffff;
		font-size: 14px;
		padding: 16px 20px;
		border: none;
		cursor: pointer;
		width: 100%;
		margin-bottom:10px;
		opacity: 0.8;
		border-radius: 5px;
	}
	.cancel
	{
		font-size: 22px;
		float:right;
		background-color: #101010;
		color: #88ffff;
		border:none;
		text-decoration: none;
		padding-right: 5px;
	}

	select
	{
		font-family: monospace;
		text-align: center;
		font-size: 18px;
		width: 18%;
		background-color: #404040;
		color: #ddffff;
		border: solid 1px #ddffff;
	}

	select:hover
	{
		background-color: #101010;
	}

	</style>

	<script language='javascript'>
	function closeDialog()
	{
		document.getElementById('appear').style.display='none';
	}
	</script>


	</head>
	<body>";

//form contains <select> to allow admin to register tutors with various fields
	$body = "<div class='container' id='appear'>
	<form id='update_student' action='update_student.php' class='form-box' method='POST'>
	<a class='cancel' onClick='closeDialog()'>&times</a>
	<h3 align='center'>UPDATE EXISTING STUDENTS INFO</h3><br />";

	$page2 =
	"<input  name='username'  placeholder='Optional username - if want it to apply only that user' pattern='[a-zA-Z0-9.]{12}' class='finput' type='text'></input>
	<input  value='CONTINUE' type='submit'></input>
	</form>
	</div>
	</body>
	</html>
	";


	include('../../../login/connection.php');
	$conn = sql_connect();


	$menu1 = "<label style='color: #55ffff';> [CURRENT DATA ]: </label>";
	$menu2 = "<label style='color: #55ffff';> [UPDATE DATA]: </label>";

	$field = "";
	for($i=0; $i<5; $i++)
	{

		if($i == 0)
			$field = "form";
		elseif($i == 1)
			$field = "track";
		elseif($i == 2)
			$field = "department";
		elseif($i == 3)
			$field = "class";
		elseif($i == 4)
			$field = "subject";




		if($field == "subject")
		{
			$menu2 .= "<select  name='".$field."_menu[]' form='update_student' multiple required>";
		}
		else
		{
			$menu1 .= "<select name='".$field."_old' form='update_student' required>";
			$menu2 .= "<select name='".$field."_new' form='update_student' required>";
		}
//building select menus
		$sql = "SELECT ".$field." FROM ".$field."_info";
		$results = mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) > 0)
		{

			while($row = mysqli_fetch_assoc($results))
			{
				if($field == "subject")
				{
					$menu2 = $menu2."<option value='".$row[$field]."'>".$row[$field]."</option>";
				}
				else
				{
					$menu1 = $menu1."<option value='".$row[$field]."'>".$row[$field]."</option>";
					$menu2 = $menu2."<option value='".$row[$field]."'>".$row[$field]."</option>";
				}
			}
			if($field != "subject")
				$menu1 = $menu1."</select>";
			$menu2 = $menu2."</select>";

		}

}//for loop


$menu1 .= "<br /><br /><br />";
$menu2 .= "<h4 style='color: white; float: right;'>press and hold CTRL to select multiple subjects</h4>";

print($page1.$body.$menu1.$menu2.$page2);

}
else
{
	header("location: ../../../login/index.php");
}
?>
