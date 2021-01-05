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
		color: #202020;
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
		margin: 10%;
		background-color: #101010;
		width: 50%;
		margin: 10%;
		height: 140px;
		color: #888;
		opacity: 1;
	}
	.form-box input[type=submit]
	{
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
		font-family: monospace;
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

	$body = "<div class='container' id='appear'>
	<form id='vstudents' action='vstud.php' class='form-box' method='POST'>
	<a class='cancel' onClick='closeDialog()'>&times</a>
	<h3 align='center'>SELECT DETAILS</h3><br />";

	$page2 = "<br /><input  value='PROCEED' type='submit'></input>
	</form>
	</div>
	</body>
	</html>
	";


	include('../../../login/connection.php');
	$conn = sql_connect();

	$all_menu = "";

	$menu = "";
	$field = "";
	for($i=0; $i<4; $i++)
	{

		if($i == 0)
			$field = "form";
		elseif($i == 1)
			$field = "track";
		elseif($i == 2)
			$field = "department";
		elseif($i == 3)
			$field = "class";


		$menu = "<select name='".$field."_menu' form='vstudents' required>";

		// building select menus
		$sql = "SELECT ".$field." FROM ".$field."_info";
		$results = mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) > 0)
		{
			while($row = mysqli_fetch_assoc($results))
			{
				$menu = $menu."<option value='".$row[$field]."'>".$row[$field]."</option>";
			}
			$menu = $menu."</select>";
			$all_menu = $all_menu.$menu;
		}


	}//for loop
	print($page1.$body.$all_menu.$page2);

}
else
{
	header("location: ../../../login/index.php");
}
?>
