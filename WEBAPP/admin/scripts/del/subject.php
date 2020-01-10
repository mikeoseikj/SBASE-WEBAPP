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
		background-color: rgb(0, 0, 0); 
		background-color: rgba(0, 0, 0, 0.4); 
		width: 100%;
		height: 100%;
		padding: 16px;
	}

	.form-box
	{
		font-family: monospace;
		border-style: solid;
		border-width:1px;
		border-color: #88ffff;
		margin: 10%;
		background-color: #101010;
		width: 35%;
		height: 140px;
		color: #88ffff;
		opacity: 1;
	}
	.form-box input[type=submit]
	{
		background-color: #020202;
		color: #88ffff;
		font-size: 14px;
		padding: 16px 20px;
		border-style:solid;
		cursor: pointer;
		width: 100%;
		margin-bottom:10px;
		opacity: 0.8;
		font-family: monospace;
		border-width:1px;
		border-color: #88ffff;
		border-radius: 3px;
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
		text-align: center;
		font-size: 18px;
		width: 18%;
		background-color:#202020;
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

	<script language='javascript'>
	function closeDialog()
	{
		document.getElementById('appear').style.display='none';
	}

	</script>

	</head>
	<body>
	<div id='appear' class='container'>
	<form action='del.php' id='del'  class='form-box' method='POST'>
	<a class='cancel' onClick='closeDialog()'>&times</a>
	<h3 align='center'>DELETE BY SUBJECT</h3><br />
	<select name='user' form='del'><option>tutor</option><option>student</option>
	</select>
	";
	$page2="<input  value='proceed' type='submit'></input></form></div></body></html>";


	include '../../../login/connection.php';
	$conn=sql_connect();

	$menu="<select name='subject_menu' form='del' required>";

	$sql="SELECT subject FROM subject_info";
	$results=mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{

		while($row=mysqli_fetch_assoc($results))
		{
			$menu=$menu."<option value='".$row["subject"]."'>".$row["subject"]."</option>";
		}
		$menu=$menu."</select>";


	}


	print($page1.$menu.$page2);

}
else
{
	header("location: ../../../login/index.php");
}
?>
