<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true)
{
	print("
		<html>
		<head>
		<style type='text/css'>
		body
		{
			font-family: monospace;
			background-image: url('../images/index.jpg');
			background-repeat: no-repeat;
		}
		.finput
		{
			width: 100%;
			color: #000000;
			width: 70%;
			padding: 10px;
			margin:20px;
			border: none;
			background: #303030;
		}
		.container
		{
			top:0;
			left: 0;
			display: block;
			position: relative;
			z-index:1;
			background-color: rgb(0, 0, 0); 
			background-color: rgba(0, 0, 0, 0.4); 
			width: 100%;
			height: 100%;
			padding: 16px;
		}
		label
		{
			padding-left: 20px;
		}
		.form-box
		{
			font-family: monospace;
			border-style: solid;
			border-width:1px;
			border-color: #88ffff;
			margin: 5%;
			margin-left:30%;

			background-color: #101010;
			width: 40%;
			height: 540px;
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

		</script>
		</head>

		<body>
		<div  class='container'>
		<form action='secreg.php' class='form-box' method='POST'>
		<h3 align='center'>CREDENTIAL SECURITY</h3><br />

		<label>New Password</label><br />
		<input  id='password1' name='password1'  placeholder='Enter new password'  class='finput' type='password' pattern='[A-Za-z1-9]{16,32}'  oninput='setPasswordConfirmValidity();' required></input><br />

		<label >**</label><br />
		<input  id='password2' name='password2'  placeholder='Re-enter Password' class='finput' type='password' pattern='[A-Za-z1-9]{16,32}' oninput='setPasswordConfirmValidity();' required></input><br />

		<h4 align='center'>Account Recovery Settings</h4>
		<label >Hint</label><br />
		<input  id='hint1' name='hint1'  placeholder='hint for account recovery' maxlength='50' class='finput' type='text' pattern='[A-Za-z1-9 ]{16,50}' oninput='setHintConfirmValidity();' required></input><br />

		<label >**</label><br />
		<input  id='hint2' name='hint2'  placeholder='Re-enter hint' maxlength='50' class='finput' type='text' pattern='[A-Za-z1-9 ]{16,50}'  oninput='setHintConfirmValidity();' required></input><br />


		<input  name='submit' value='proceed' type='submit'></input>
		</form>
		</div>

		</body>
		</html>
		");
}
else
{
	header("location: ../index.php");
}
