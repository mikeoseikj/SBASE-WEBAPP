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
			color: #000000;
			width: 90%;
			height: 30px;
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
			z-index:1; 
			background-color: rgba(0, 0, 0, 0.4); 
			width: 100%;
			height: 100%;
			position: fixed;
		}
		label
		{
			font-size: 10px;
			padding-left: 20px;
		}
		.form-box
		{
			font-family: monospace;
			border: solid 1px #88ffff;
			margin: 3%;
			margin-left:30%;
			background-color: #101010;
			width: 30%;
			height: 475px;
			color: #88ffff;
			opacity: 1;
		}
		.form-box input[type=submit]
		{
			background-color: #020202;
			color: #88ffff;
			font-size: 12px;
			padding: 9px 9px;
			cursor: pointer;
			width: 100%;
			margin-bottom:10px;
			opacity: 0.8;
			font-family: monospace;
			border: solid 1px #88ffff;

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
		}

		</script>
		</head>

		<body>
		<div  class='container'>
		<form action='secreg.php' class='form-box' method='POST'>
		<h4 align='center'>CREDENTIAL SECURITY</h4><br />

		<label>New Password</label><br />
		<input  id='password1' name='password1'  placeholder='Enter new password'  class='finput' type='password' pattern='[a-zA-Z0-9.\-_()+*#@%$]{16,}'  oninput='setPasswordConfirmValidity();' required></input><br />

		<label >**</label><br />
		<input  id='password2' name='password2'  placeholder='Re-enter Password' class='finput' type='password' pattern='[a-zA-Z0-9.\-_()+*#@%$]{16,}' oninput='setPasswordConfirmValidity();' required></input><br />

		<h5 align='center'>Account Recovery Settings</h5>
		<label >Hint</label><br />
		<input  id='hint1' name='hint1'  placeholder='hint for account recovery' maxlength='50' class='finput' type='text' pattern='[a-zA-Z0-9.\-_()+*#@?%$, ]{16,}' oninput='setHintConfirmValidity();' required></input><br />

		<label >**</label><br />
		<input  id='hint2' name='hint2'  placeholder='Re-enter hint' maxlength='50' class='finput' type='text' pattern='[a-zA-Z0-9.\-_()+*#@?%$, ]{16,}'  oninput='setHintConfirmValidity();' required></input><br />


		<input  name='submit' value='PROCEED' type='submit'></input>
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
