<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"] == true)
{

	
	print("
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
		width: 40%;
		margin: 10%;
		height: 290px;
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

	function toContinue()
	{
		var status = confirm('Proceed to change name of user?');
		return status;
	}
	</script>


	</head>
	<body>
	<div class='container' id='appear'>
	<form id='update_name' action='change_name.php' class='form-box' onsubmit='return toContinue()' method='POST'>
	<a class='cancel' onClick='closeDialog()'>&times</a>

	<h3 align='center'>CHANGE NAME OF USER</h3>
	<input  name='username'  pattern='[a-zA-z0-9.]{12,14}' maxlength='14' placeholder='Enter username' class='finput' type='text' required></input>

	<input  name='realname'  pattern='[a-zA-z ]{1,}' placeholder='Name (eg: John Doe)' class='finput' type='text' required></input>
	<input  value='continue' type='submit'></input>
	</form>
	</div>
	</body>
	</html>
	");

}
else
{
	header("location: ../../../login/index.php");
}
?>
