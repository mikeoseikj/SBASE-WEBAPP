<?php


session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"] == true)
{

//logout tutors after 1 hour
	if((time()-$_SESSION["timestamp"]) > 3600 && strlen($_SESSION["username"]) == 14)
	{
		print("<script>alert('session timeout');document.location.href='../logout.php'</script>");
		exit;
	}

//logout students after 5 minutes
	if((time()-$_SESSION["timestamp"]) > 300 && strlen($_SESSION["username"]) == 12)
	{
		print("<script>alert('session timeout');document.location.href='../logout.php'</script>");
		exit;
	}


	print(
		"
		<head>
		<style type='text/css'>
		body
		{
			background-image: url('../images/index.jpg');
			background-repeat: no-repeat;
			background-size: cover;
			font-family: monospace;
		}
		.finput
		{
			color: #202020;
			font-size: 18px;
			width: 90%;
			padding: 15px;
			margin:10px;
			border: none;
			height: 20px;
			background: #303030;
		}
		.container
		{
			top:0;
			left: 0;
			display: block;
			position: fixed; 
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
			margin-left: 30%; 
			background-color: #101010;
			width: 26%;
			height: 445px;
			color: #88ffff;
			opacity: 1;
		}
		.form-box input[type=submit]
		{
			background-color: #020202;
			color: #88ffff;
			font-size: 12px;
			cursor: pointer;
			width: 100%;
			height: 30px;
			opacity: 0.8;
			font-family: monospace;
			border: solid 1px #88ffff;
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
			border: solid 1px green;
		}

		select:hover
		{
			background-color: #101010;
		}
		label
		{
			margin-left: 15px;
			float:left;
			font-size: 10px;
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

		<br /><br />
		<div align='center'>

		<label>current password</label><br />
		<input name='opassword' placeholder='Enter current password' class='finput' pattern='[a-zA-Z0-9.\-_()+*#@%$]{8,}' type='password'></input>

		<label>New Password</label><br />
		<input  id='password1' name='password1'  placeholder='Enter new password' size='10' class='finput' type='password' pattern='[a-zA-Z0-9.\-_()+*#@%$]{8,}'  oninput='setPasswordConfirmValidity();' required></input><br />

		<label >**</label><br />
		<input  id='password2' name='password2'  placeholder='Re-enter Password' size='10' class='finput' type='password' pattern='[a-zA-Z0-9.\-_()+*#@%$]{8,}' oninput='setPasswordConfirmValidity();' required></input><br />

		<h5 align='center'>Account Recovery Settings</h5>
		<label >New Password Hint</label><br />
		<input  id='hint1' name='hint1'  placeholder='hint for account recovery' maxlength='50' class='finput' type='text' pattern='[a-zA-Z0-9.\-_()+*#@?%$, ]{8,}' oninput='setHintConfirmValidity();' required></input><br />

		<label >**</label><br />
		<input  id='hint2' name='hint2'  placeholder='Re-enter hint' maxlength='50' class='finput' type='text' pattern='[a-zA-Z0-9.\-_()+*#@?%$, ]{8,}'  oninput='setHintConfirmValidity();' required></input><br /><br />

		<input  value='CHANGE PASSWORD' type='submit'></input></form></div></body></html>

		</div>

		");

}

else
{
	header("location: ../index.php");
}
?>
