<?php

//make page only accessible by link
$file = substr($_SERVER["HTTP_REFERER"], -9);
if($file !== "index.php")
{
	header("location: ../index.php");
	exit;
}

print("
	<html>
	<head>

	<style type='text/css'>
	body
	{
		font-family: monospace;
		background-image: url('../images/index.jpg');
		background-size: cover;
		background-repeat: no-repeat;
	}
	.finput
	{
		font-family: monospace;
		color: #000000;
		width: 95%;
		height: 35px;
		padding: 10px;
		margin: 5px;
		border: none;
		background: #303030;
		border-radius: 3px;
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
	}
	label
	{
		float: left;
		font-size: 12px;
		margin-left: 10px;
	}
	.form-box
	{
		letter-spacing: 1px;
		font-family: monospace;
		border: solid 1px #88ffff;
		margin: 3%;
		margin-left:30%;
		background-color: #101010;
		width: 30%;
		height: 565px;
		color: #88ffff;
	}
	.form-box input[type=submit]
	{
		background-color: #020202;
		color: #88ffff;
		font-size: 14px;
		padding: 10px 12px;
		border: solid 1px #88ffff;
		cursor: pointer;
		width: 100%;
		opacity: 0.5;
		font-family: monospace;

	}
	@media screen and (max-width: 846px) {.form-box{height: 580px;}}
	@media screen and (max-width: 586px) {.form-box{height: 595px;}}

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

	function setOldHintConfirmValidity(str) 
	{
		const hint1 = document.getElementById('ohint1');
		const hint2 = document.getElementById('ohint2');
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
	<form action='recover.php' class='form-box' method='POST'>

	<h3 align='center'>ACCOUNT RECOVERY</h3>

	<div align='center'>
	<label >Username</label><br />
	<input name='username'  placeholder='username' pattern='[a-zA-Z0-9.]{5,14}' maxlength='14' class='finput' type='text' required /><br /><br />

	<label>Hint</label><br />
	<input  id='ohint1' name='ohint1'  placeholder='hint for account recovery'  class='finput' type='text' pattern='[a-zA-Z0-9.\-_()+*#@?%$, ]{8,}' oninput='setOldHintConfirmValidity();' required /><br /><br />

	<label >**</label><br />
	<input  id='ohint2' name='ohint2'  placeholder='Re-enter hint' class='finput' type='text' pattern='[a-zA-Z0-9.\-_()+*#@?%$, ]{8,}'  oninput='setOldHintConfirmValidity();' required /><br />

	<h4 align='center'>Reset Account Settings</h4>

	<label style='margin-left: 14px;'>New Password &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; **</label><br />
	<input style='width: 42%; margin-left:1%;' id='password1' name='password1'  placeholder='Enter new password' size='10' class='finput' type='password' pattern='[a-zA-Z0-9.\-_()+*#@%$]{8,}'  oninput='setPasswordConfirmValidity();' required />

	<input style='width: 42%;  margin-left:1%;' id='password2' name='password2'  placeholder='Re-enter Password' size='10' class='finput' type='password' pattern='[a-zA-Z0-9.\-_()+*#@%$]{8,}' oninput='setPasswordConfirmValidity();' required /><br /><br />

	<label >Hint</label><br />
	<input  id='hint1' name='hint1'  placeholder='hint for new password'  class='finput' type='text' pattern='[a-zA-Z0-9.\-_()+*#@?%$, ]{8,}' oninput='setHintConfirmValidity();' required /><br /><br />

	<label >**</label><br />
	<input  id='hint2' name='hint2'  placeholder='Re-enter hint' class='finput' type='text' pattern='[a-zA-Z0-9.\-_()+*#@?%$, ]{8,}'  oninput='setHintConfirmValidity();' required /><br /><br />


	<input  name='submit' value='reset' type='submit'></input>
	</form>
	</div>
	</div>

	</body>
	</html>
	");

