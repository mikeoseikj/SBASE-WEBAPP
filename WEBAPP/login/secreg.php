<?php


print("<body bgcolor='#000000'></body>");
session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && isset($_SESSION["first"])==true )
{
	include('func.php');

//to mitigate sql injection
	$password1 = sanitize_sql_input($_POST["password1"],"/[^a-zA-Z0-9.\-_()+*#@%$]/");
	$password2 = sanitize_sql_input($_POST["password2"],"/[^a-zA-Z0-9.\-_()+*#@%$]/");

	$hint1 = sanitize_sql_input($_POST["hint1"],"/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");
	$hint2 = sanitize_sql_input($_POST["hint2"],"/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");

	if($password1 !==$password2 || $hint1 !==$hint2)
	{
		print("<script>alert('not allowed');document.location.href='index.php'</script>");
		exit;
	}

	if(empty($password1)  || empty($hint1))
	{
		print("<script>alert('An empty field provided');document.location.href='index.php'</script>");
		exit;
	};
	if(strlen($password1) < 8 || strlen($hint1) < 8 )
	{
		print("<script>alert('both password and hint length must be 8 or more characters');document.location.href='index.php'</script>");
		exit;
	}


	$pass_hash = password_hash($password1,PASSWORD_BCRYPT);
	$hint_hash = password_hash(strtolower($hint1),PASSWORD_BCRYPT);

	include('connection.php');
	$conn = sql_connect();

	if(strlen($_SESSION["username"]) == 14)
		$table="tutor";
	elseif(strlen($_SESSION["username"]) == 12)
		$table="student";

	$sql = "INSERT INTO ".$table."_slogin_info(username,password,recoveryhint,status) VALUES('".$_SESSION["username"]."','".$pass_hash."','".$hint_hash."','1')";

	$results = mysqli_query($conn,$sql);

	if(! $results)
	{
		print("<script>alert('registration error');document.location.href='sec.php'</script>");
		exit;
	}

	$sql = "DELETE FROM ".$table."_login_info WHERE username='".$_SESSION["username"]."'";
	mysqli_query($conn,$sql);

	header("location: logout.php");
}
else
{
	header("location: index.php");
}
?>
