<?php

include 'func.php';
include 'connection.php';

sleep(3);

$username = sanitize_sql_input($_GET["username"],12,"/[^a-zA-Z1-9.]/");
$password = sanitize_sql_input($_GET["password"],25,"/[^a-zA-Z1-9]/");

if(strlen($username) !=12 || strlen($password) <8 )
exit;

$conn=sql_connect();

$sql="SELECT * FROM student_slogin_info WHERE username='".$username."'";

$results=mysqli_query($conn,$sql);

if(mysqli_num_rows($results) > 0)
{
	while($rows=mysqli_fetch_assoc($results))
	{
		if(password_verify($password,$rows["password"]))
		{
			$json_data=array();
			$sql="SELECT exam,marks FROM student_overrall_marks WHERE username='".$username."'";
			$results=mysqli_query($conn,$sql);

			while($rows=mysqli_fetch_assoc($results))
			{
				$rows["marks"]=(float)$rows["marks"];
				array_push($json_data,$rows);
			}


			$json_data="{\"graph\":".json_encode($json_data)."}";

			echo($json_data);
		}
	}
}



?>