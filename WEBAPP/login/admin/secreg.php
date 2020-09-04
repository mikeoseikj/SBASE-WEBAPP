<?php

print("<body bgcolor='#000000'></body>");
session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"]==true && $_SESSION["first"]==true )
{
	include('../connection.php');
	include('../func.php');

//to mitigate sql injection
	$password1 = sanitize_sql_input($_POST["password1"],"/[^a-zA-Z0-9.\-_()+*#@%$]/");
	$password2 = sanitize_sql_input($_POST["password2"],"/[^a-zA-Z0-9.\-_()+*#@%$]/");

	$hint1 = sanitize_sql_input($_POST["hint1"],"/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");
	$hint2 = sanitize_sql_input($_POST["hint2"],"/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");


	if($password1 !==$password2 || $hint1 !==$hint2)
	{
		print("<script>alert('not allowed');document.location.href='../index.php'</script>");
		exit;
	}


	if(empty($password1) || empty($hint1))
	{
		print("<script>alert('An empty was provided');document.location.href='../index.php'</script>");
		exit;
	};


	if(strlen($password1) < 16 || strlen($hint1) < 16 )
	{
		print("<script>alert('password and hint length must be 16 or more characters');document.location.href='index.php'</script>");
		exit;
	}



	$pass_hash = password_hash($password1,PASSWORD_BCRYPT);
	$hint_hash = password_hash(strtolower($hint1),PASSWORD_BCRYPT);
	
	$conn = sql_connect();

	$sql="INSERT INTO admin_slogin_info(password,recoveryhint) VALUES('".$pass_hash."','".$hint_hash."')";

	$results=mysqli_query($conn,$sql);

	if(! $results)
	{
		print("<script>alert('registration error');document.location.href='sec.php'</script>");
		exit;
	}


	header("location: ../logout.php");
}
else
{
	header("location: ../index.php");
}
?>
