<?php

include 'func.php';
include 'connection.php';

sleep(3);

//to mitigate sql injection
$username = sanitize_sql_input($_GET["username"],12,"/[^a-zA-Z1-9.]/");
$password = sanitize_sql_input($_GET["password"],25,"/[^a-zA-Z1-9]/");


$conn=sql_connect();


//check for first time logins and if account is active
$sql="SELECT * FROM student_login_info WHERE username='".$username."' AND password='".$password."' AND status='1'";
$results=mysqli_query($conn,$sql);

if(mysqli_num_rows($results)  > 0)
{
	echo("{
		\"first_login\":true,
		\"success\":false
	}");
	exit;
}

//if not first time login verify credential
else
{
//if not in database
	$sql="SELECT * FROM student_slogin_info WHERE username='".$username."' AND status='1'";
	$results=mysqli_query($conn,$sql);
	if(mysqli_num_rows($results) < 1)
	{
		echo("{
			\"first_login\":false,
			\"success\":false
		}");
		exit;
	}

//wrong password
	while($rows=mysqli_fetch_assoc($results))
		if(password_verify($password,$rows["password"])==false)
		{
			echo("{
				\"first_login\":false,
				\"success\":false
			}");

			exit;
		}

//if the login credentials are correct and the account is active
		else
		{
//get name of user
			$sql="SELECT * FROM student_subject_info WHERE username='".$username."' LIMIT 1";
			$results=mysqli_query($conn,$sql);
			while($rows=mysqli_fetch_assoc($results))
				$name=$rows["studentname"];

			$count=0;

//get exam names
			$sql="SELECT * FROM student_overrall_marks WHERE username='".$username."'";
			$exams="[";
			$results=mysqli_query($conn,$sql);
			$count=mysqli_num_rows($results);

			if(mysqli_num_rows($results) > 0)
			{
				while($rows=mysqli_fetch_assoc($results))
				{
$exams=$exams."\"".$rows["exam"]."\",";     //manually making a json array value eg. ["car","motor","bike"]
}
}
//replace the last character ',' with ']'
$exams[-1]=']';

//XML for users after login
echo("{
	\"first_login\":false,
	\"success\":true,
	\"student_name\":\"".$name."\",
	\"exams\":".$exams."
}");


}

}



?>
