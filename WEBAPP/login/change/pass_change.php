<?php


print("<body bgcolor='#000000'></body>");
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

    include('../connection.php');
    include('../func.php');


    //to prevent sql injection
	$opassword = sanitize_sql_input($_POST["opassword"],"/[^a-zA-Z0-9.\-_()+*#@%$]/");
	$password1 = sanitize_sql_input($_POST["password1"],"/[^a-zA-Z0-9.\-_()+*#@%$]/");
	$password2 = sanitize_sql_input($_POST["password2"],"/[^a-zA-Z0-9.\-_()+*#@%$]/");
	$username = $_SESSION["username"];

	$hint1 = sanitize_sql_input($_POST["hint1"],"/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");
	$hint2 = sanitize_sql_input($_POST["hint2"],"/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");


	if($password1 !==$password2  || $hint1 !== $hint2)
	{
		print("<script>alert('not allowed');document.location.href='../logout.php'</script>");
		exit;
	}

	if(empty($password1) || empty($hint1))
	{
		print("<script>alert('An empty field was provided');document.location.href='../logout.php'</script>");
		exit;
	}

//dont have to query the database for password because its already stored in the session
	if($opassword !==$_SESSION["password"])
	{
		if(strlen($username) == 14)
		{
			print("<script>alert('current password does not match with input');document.location.href='../../users/tutors/page.php'</script>");
		}
		elseif(strlen($username) == 12)
		{
			print("<script>alert('current password does not match with input');document.location.href='../../users/students/results.php'</script>");
		}
		else
		{
			print("<script>alert('current password does not match with input');</script>");
		}
		exit;
	}

	$conn = sql_connect();

	$pass_hash = password_hash($password1,PASSWORD_BCRYPT);
	$hint_hash = password_hash(strtolower($hint1),PASSWORD_BCRYPT);


	if($username == "admin")
	{
		if(strlen($password1) < 16  || strlen($hint1) < 16)
		{
			print("<script>alert('password or hint length must be 16 or more characters');</script>");
			exit;
		}

		$sql = "UPDATE admin_slogin_info SET password='".$pass_hash."' , recoveryhint='".$hint_hash."'";
		mysqli_query($conn,$sql);

	}
	else
	{

		$table="";
		if(strlen($username) == 12)
			{$table="student";}
		elseif(strlen($username) == 14)
			{$table="tutor";}



		if(strlen($password1) < 8 || strlen($hint1) < 8 )
		{
			if($table == "tutor")
			{
				print("<script>alert('password or hint must be 8 or more characters');document.location.href='../../users/tutors/page.php';</script>");
			}
			else
			{
				print("<script>alert('password or hint must be 8 or more characters');document.location.href='../../users/students/results.php';</script>");
			}
			exit;
		}

		$sql = "UPDATE ".$table."_slogin_info SET password='".$pass_hash."' , recoveryhint='".$hint_hash."' WHERE username='".$username."'";
		mysqli_query($conn,$sql);

		if($table == "tutor")
			{header("location: ../../users/tutors/page.php");}
		else
			{header("location: ../../users/students/results.php");}
	}

}


else
{
	header("location: ../index.php");
}

?>
