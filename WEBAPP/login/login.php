<?php


session_start();
print("<body bgcolor='#000000'></body>");
$username = "";
$password = "";

if(isset($_POST["submit"]))
{
	$username = $_POST["username"];
	$password = $_POST["password"];
}

else
{
	header("location: index.php");
	exit;
}


include('func.php');
$config = include('../config.php');


$conn = mysqli_connect($config->dbhost, $config->dbuser, $config->$dbpass);
if(! $conn)
{
	print("<script>alert('DATABASE CONNECTION PROBLEM');document.location.href='index.php'</script>");
	exit;
}



//to mitigate sql injection
$username = sanitize_sql_input($username,"/[^a-zA-Z0-9.]/");
$password = sanitize_sql_input($password,"/[^a-zA-Z0-9.\-_()+*#@%$]/");

if(empty($username) || empty($password))
{
	print("<script>alert('An empty field was provided');document.location.href='index.php'</script>");
	exit;
};

//delay for 3 seconds (this is a simple way to make password bruteforce impractical)
sleep(3);

//checking if it is a tutor login
if(strlen($username) == 14)
{
	mysqli_select_db($conn,"SBASE");
//checking first time login 
	$sql="SELECT * FROM tutor_login_info WHERE username='".$username."' AND password='".$password."' AND status='1'";

	$results = mysqli_query($conn,$sql);
	if(mysqli_num_rows($results) > 0)
	{

		$_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
		$_SESSION["first"] = true;

		header("location: sec.php");
		exit;
	}

//if not first time login, check if user has already created an account
	else
	{
		$sql="SELECT * FROM tutor_slogin_info WHERE username='".$username."'";

		$results=mysqli_query($conn,$sql);
		if(mysqli_num_rows($results) > 0)
		{
			while($hash = mysqli_fetch_assoc($results))
			{
				if(password_verify($password,$hash["password"]) && $hash["status"] == 1)
				{
					$_SESSION["username"] = $username;
					$_SESSION["password"] = $password;
					$_SESSION["loggedin"] = true;
					$_SESSION["user"] = "tutor";
					$_SESSION["timestamp"] = time();
					header("location: ../users/tutors/page.php");
					exit;
				}
				else
				{
					header("location: index.php");
					exit;
				}
			}
		}

		else
		{
			header("location: index.php");
			exit;
		}
	}

}//tutor check



//checking if it is a student login
if(strlen($username) == 12)
{

	mysqli_select_db($conn,"SBASE");

//checking first time login 
	$sql="SELECT * FROM student_login_info WHERE username='".$username."' AND password='".$password."' AND status='1'";

	$results=mysqli_query($conn,$sql);
	if(mysqli_num_rows($results) >0)
	{

		$_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
		$_SESSION["first"] = true;

		header("location: sec.php");
		exit;
	}

//if not first time login, check if user has already created an account
	else
	{
		$sql="SELECT * FROM  student_slogin_info WHERE username='".$username."'";

		$results = mysqli_query($conn,$sql);
		if(mysqli_num_rows($results) > 0)
		{
			while($hash = mysqli_fetch_assoc($results))
			{
				if(password_verify($password,$hash["password"]) && $hash["status"]==1)
				{
					$_SESSION["username"] = $username;
					$_SESSION["password"] = $password;
					$_SESSION["loggedin"] = true;
					$_SESSION["user"] = "student";
					$_SESSION["timestamp"] = time();
					header("location: ../users/students/results.php");
					exit;
				}
				else
				{
					header("location: index.php");
					exit;
				}
			}
		}

		else
		{
			header("location: index.php");
			exit;
		}
	}

}//student check



//checking if admin
elseif (strtolower($username) == "admin")
{

	include('create_tables.php');
	create_required_tables();
	mysqli_select_db($conn,"SBASE");

	$sql = "CREATE TABLE IF NOT EXISTS admin_slogin_info(password VARCHAR(60) NOT NULL,recoveryhint VARCHAR(60) NOT NULL)";
	mysqli_query($conn,$sql);

	$sql = "SELECT * FROM admin_slogin_info";
	$results = mysqli_query($conn,$sql);

//checking if admin is already registered
	if(mysqli_num_rows($results) > 0)
	{
		while($hash=mysqli_fetch_assoc($results))
		{
			if(password_verify($password,$hash["password"]))
			{
				$_SESSION["username"] = "admin";
				$_SESSION["password"] = $password;
				$_SESSION["superuser"] = true;
				$_SESSION["loggedin"] = true;
				header("location: ../admin/control_panel.php");
				exit;
			}
			else
			{
				header("location: index.php");
				exit;
			}
		}
	}
	else
	{
		if($password == $config->admin_firsttime_password)
		{
			$_SESSION["username"] = "admin";
			$_SESSION["password"] = $config->admin_firsttime_password;
			$_SESSION["superuser"] = true;
			$_SESSION["first"] = true;

			header("location: admin/sec.php");
			exit;
		}
		else
		{
			header("location: index.php");
			exit;
		}
	}

}

else
{
	header("location: index.php");
	exit;
}
?>
