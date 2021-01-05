 <?php


//remember username is between 

//make page accessible only by link
$file = substr($_SERVER["HTTP_REFERER"],-7);
if($file !== "rec.php")
{
	header("location: ../index.php");
	exit;
}

print("<body bgcolor='#000000'></body>");
include('../func.php');

$username = sanitize_sql_input($_POST["username"], "/[^a-zA-Z0-9.]/");
$password1 = sanitize_sql_input($_POST["password1"], "/[^a-zA-Z0-9.\-_()+*#@%$]/");
$password2 = sanitize_sql_input($_POST["password2"], "/[^a-zA-Z0-9.\-_()+*#@%$]/");


// old hints for account recovery
$ohint1 = sanitize_sql_input($_POST["ohint1"], "/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");
$ohint2 = sanitize_sql_input($_POST["ohint2"], "/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");

// hints for new password
$hint1 = sanitize_sql_input($_POST["hint1"], "/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");
$hint2 = sanitize_sql_input($_POST["hint2"], "/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");

if($password1 !== $password2 || $hint1 !== $hint2 || $ohint1 !== $ohint2)
{
	print("<script>alert('not allowed');document.location.href='../index.php'</script>");
	exit;
}


if(empty($password1) || empty($hint1) || empty($ohint1) || empty($username))
{
	print("<script>alert('An empty field was provided');document.location.href='../index.php'</script>");
	exit;
};

if(strtolower($username) == "admin")
{
	if(strlen($password1) < 16  || strlen($ohint1) < 16  || strlen($hint1) < 16 )
	{
		print("<script>alert('password and hint must be 16 or more characters');document.location.href='../index.php'</script>");
		exit;
	}
}
else
{
	if(strlen($password1) < 8 || strlen($ohint1) < 8 || strlen($hint1) < 8 )
	{
		print("<script>alert('password and hint must 8 or more characters');document.location.href='../index.php'</script>");
		exit;
	}
}

$pass_hash = password_hash($password1, PASSWORD_BCRYPT);
$hint_hash = password_hash(strtolower($hint1), PASSWORD_BCRYPT);


$config = include('../../config.php');
$conn = mysqli_connect($config->dbhost, $config->dbuser, $config->dbpass, "SBASE");

if(! $conn)
{
	print("<script>alert('DATABASE CONNECTION PROBLEM');document.location.href='../index.php'</script>");
	exit;
}

//a simple method to make hint guessing impractical
sleep(3);

$table;
if(strlen($username) == 14)
	{$table = "tutor";}
elseif(strlen($username) == 12)
	{$table = "student";}
elseif(strtolower($username) == "admin")
	{$table = "admin";}
else
{
	print("<script>alert('unknown username');document.location.href='../index.php'</script>");
	exit;
}

if($table == "admin")
{
	$sql = "SELECT * FROM admin_slogin_info";
	$results = mysqli_query($conn, $sql);
	while($rows = mysqli_fetch_assoc($results))
		$hint = $rows["recoveryhint"];

	if(password_verify($ohint1, $hint))
	{
		$sql = "UPDATE ".$table."_slogin_info SET password='".$pass_hash."' ,recoveryhint='".$hint_hash."'"; 
		mysqli_query($conn, $sql);
	}
	else
	{
		print("<script>alert('wrong hint');document.location.href='../index.php'</script>");
		exit;
	}
	header("location: ../index.php");
}

else
{
	$sql = "SELECT * FROM ".$table."_slogin_info WHERE username='".$username."' AND status='1'";
	$results = mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		while($rows = mysqli_fetch_assoc($results))
		{

			if(password_verify($ohint1, $rows["recoveryhint"]))
			{
				$sql = "UPDATE ".$table."_slogin_info SET password='".$pass_hash."' ,recoveryhint='".$hint_hash."' WHERE username='".$username."'" ;
				mysqli_query($conn, $sql);
			}
			else
			{
				print("<script>alert('wrong hint');document.location.href='../index.php'</script>");
				exit;
			}

		}
		header("location: ../index.php");
	}

	else
	{
		print("<script>alert('unknown username');document.location.href='../index.php'</script>");
		exit;
	}
}


?>
