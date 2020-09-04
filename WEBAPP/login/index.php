<?php

//this line is to prevent a bug that occurs when this file opens index.php as an iframe after logging out
//in a different window/tab
print
("
	<script>
	if(window.location !== window.parent.location ) 
	{
		top.window.location.href='index.php'; 
	}
	</script>  
	");
session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"] == true)
{
	if(strlen($_SESSION) == 14)
	{
		header("location: ../users/tutors/page.php");
		exit;
	}
	elseif($_SESSION["username"] == "admin")
	{
		header("location: ../admin/control_panel.php");
		exit;
	}
	elseif(strlen($_SESSION["username"]) == 12)
	{
		header("location: ../users/students/results.php");
		exit;
	}

}


print("
	<html>
	<head>
	<style type='text/css'>
	body
	{
		font-family: monospace;
		background-image: url('images/index.jpg');
		background-repeat: no-repeat;
		background-size: cover;
	}
	.finput
	{
		font-family: monospace;
		color: #000000;
		width: 95%;
		padding: 10px;
		margin: 20px;
		margin-left: 2%;
		border: none;
		background: #303030;
		border-radius: 5px;
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
		padding: 10px;
	}
	label
	{
		float: left;
		margin-left: 20px;
	}
	.form-box
	{
		letter-spacing: 2px;
		font-family: monospace;
		border: solid 1px #88ffff;
		margin: 10%;
		margin-left:30%;
		background-color: #101010;
		width: 30%;
		height: 343px;
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

	}


	input[type=button]
	{
		font-family: monospace;
		color: #ffffff;
		margin-top: 30%;
		margin-left:48%;
		width: 120px;
		height:25px;
		border-radius: 10px;
		border: none;
		background-color: powderblue;
	}

	</style>
	</head>

	<body>
	<div id='appear' class='container'>
	<form id='add_tutor' action='login.php' class='form-box' method='POST'>
	<h3 align='center'>ACCESS ACCOUNT</h3><br />

	<div align='center'>

	<label>Username</label><br />
	<input  name='username'  placeholder='Enter username' class='finput' pattern='[A-Za-z0-9.]{5,14}' maxlength='14' type='text' required /><br />

	<label>Password</label><br />
	<input  name='password'  pattern='[a-zA-Z0-9.\-_()+*#@%$]{5,}'class='finput' type='password' required />

	<br /><br />
	<a href='recover/rec.php' style='float:right;text-decoration:none;color: #88ffff;margin: 3px; cursor:pointer;'>Forgot Password</a><br /><br />

	<input  name='submit' value='login' type='submit'></input>
	</form>
	</div>
	</div>

	</body>
	</html>");

	?>
