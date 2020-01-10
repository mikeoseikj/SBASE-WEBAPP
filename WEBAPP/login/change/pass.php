<?php


session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"]==true)
{

//logout tutors after 1 hour
	if((time()-$_SESSION["timestamp"]) > 3600 && strlen($_SESSION["username"])==14)
	{
		print("<script>alert('session timeout');document.location.href='../logout.php'</script>");
		exit;
	}

//logout students after 5 minutes
	if((time()-$_SESSION["timestamp"]) > 300 && strlen($_SESSION["username"])==12)
	{
		print("<script>alert('session timeout');document.location.href='../logout.php'</script>");
		exit;
	}


	print(
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
			position: absolute;
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
			margin-left: 30%; 
			background-color: #101010;
			width: 35%;
			height: 705px;
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
			width: 50%;
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
		label
		{
			margin-left: 10px;
		}

		</style>

		<script language='javascript'>

		function setPasswordConfirmValidity(str) 
		{
			const password1 = document.getElementById('password1');
			const password2 = document.getElementById('password2');

			if(password1.value === password2.value) 
			{
				password2.setCustomValidity('');
			} 
			else 
			{
				password2.setCustomValidity('Passwords must match');
			}

			console.log('password2 customError ', document.getElementById('password2').validity.customError);
			console.log('password2 validationMessage ', document.getElementById('password2').validationMessage);
		}


		function setHintConfirmValidity(str) 
		{
			const hint1 = document.getElementById('hint1');
			const hint2 = document.getElementById('hint2');
			if(hint1.value === hint2.value) 
			{
				hint2.setCustomValidity('');
			} 
			else 
			{
				hint2.setCustomValidity('Hints should not match!');
			}


			console.log('hint2 customError ', document.getElementById('hint2').validity.customError);
			console.log('hint2 validationMessage ', document.getElementById('hint2').validationMessage);

		}

		function closeDialog()
		{
			document.getElementById('appear').style.display='none';

		}
		</script>


		</head>
		<body>
		<div id='appear' class='container'>
		<form action='pass_change.php' id='change'  class='form-box' method='POST'>
		<a class='cancel' onClick='closeDialog()'>&times</a>
		<h3 align='center'>Change Password</h3><br />

		<label>current password</label><br />
		<input name='opassword' placeholder='Enter current password' class='finput' pattern='[A-Za-z1-9]{8,32}' type='password'></input>

		<label>New Password</label><br />
		<input  id='password1' name='password1'  placeholder='Enter new password' size='10' class='finput' type='password' pattern='[A-Za-z1-9]{8,32}'  oninput='setPasswordConfirmValidity();' required></input><br />

		<label >**</label><br />
		<input  id='password2' name='password2'  placeholder='Re-enter Password' size='10' class='finput' type='password' pattern='[A-Za-z1-9]{8,32}' oninput='setPasswordConfirmValidity();' required></input><br />

		<h4 align='center'>Account Recovery Settings</h4>
		<label >New Password Hint</label><br />
		<input  id='hint1' name='hint1'  placeholder='hint for account recovery' maxlength='50' class='finput' type='text' pattern='[A-Za-z1-9 ]{8,50}' oninput='setHintConfirmValidity();' required></input><br />

		<label >**</label><br />
		<input  id='hint2' name='hint2'  placeholder='Re-enter hint' maxlength='50' class='finput' type='text' pattern='[A-Za-z1-9 ]{8,50}'  oninput='setHintConfirmValidity();' required></input><br />

		<input  value='Change' type='submit'></input></form></div></body></html>

		");

}

else
{
	header("location: ../index.php");
}
?>
