<?php


include('func.php');
include('connection.php');

sleep(3);

$username = sanitize_sql_input($_GET["username"],"/[^a-zA-Z0-9.]/");
$current_hint = sanitize_sql_input($_GET["current_hint"],"/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");
$password1 = sanitize_sql_input($_GET["password1"],"/[^a-zA-Z0-9.\-_()+*#@%$]/");
$password2 = sanitize_sql_input($_GET["password2"],"/[^a-zA-Z0-9.\-_()+*#@%$]/");
$hint1 = sanitize_sql_input($_GET["hint1"],"/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");
$hint2 = sanitize_sql_input($_GET["hint2"],"/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");

if(strlen($username) != 12 || strlen($current_hint) < 8 )
	exit;

if(($password1 !=$password2) || ($hint1 !=$hint2))
	exit;

if(strlen($password1) <8  || strlen($hint1) <8 )
	exit;


$conn = sql_connect();


$sql = "SELECT * FROM student_slogin_info WHERE username='".$username."' AND status='1'";
$results = mysqli_query($conn,$sql);

if(mysqli_num_rows($results) > 0)
{
	while($rows = mysqli_fetch_assoc($results))
	{
		if(password_verify($current_hint,$rows["recoveryhint"]))
		{
			$pass_hash = password_hash($password1, PASSWORD_BCRYPT);
			$hint_hash = password_hash($hint1, PASSWORD_BCRYPT);
			$sql = "UPDATE student_slogin_info SET password='".$pass_hash."' ,recoveryhint='".$hint_hash."' WHERE username='".$username."'";
			mysqli_query($conn,$sql);
			echo("{\"success\": true}");
		}
	}
}
else
{
	echo("{\"success\": false }");
}




?>