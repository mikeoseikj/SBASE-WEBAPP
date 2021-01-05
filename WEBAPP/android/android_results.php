<?php

include('func.php');
include('connection.php');

sleep(3);

//to mitigate sql injection
$username = sanitize_sql_input($_GET["username"], "/[^a-zA-Z0-9.]/");
$password = sanitize_sql_input($_GET["password"], "/[^a-zA-Z0-9.\-_()+*#@%$]/");
$exam = $_GET["exam"];

if(empty($exam))
	exit;

$conn = sql_connect();
//getting the last added exam the student is registered for; the comparison below using the 'unique id' is to check if it is a new login from the android app

if($exam == "unique_id_di_euqinu")
{
	$sql = "SELECT exam FROM student_overrall_marks WHERE username='".$username."'";
	$results = mysqli_query($conn, $sql); 
	while ($rows = mysqli_fetch_assoc($results))
		$exam = $rows["exam"];
}

$exam = sanitize_sql_input($exam, "/[^a-zA-Z0-9.\-_()+*#@?%$, ]/");
//verifying username and password before obtaining results
$sql = "SELECT * FROM student_slogin_info WHERE username='".$username."' AND status='1'";
$results = mysqli_query($conn, $sql);
if(mysqli_num_rows($results) < 1)    //if not in database
	exit;

while($rows = mysqli_fetch_assoc($results))
{
	if(password_verify($password, $rows["password"]) == false)   //if wrong password
		exit;
}

$json_data = array();

//get student results based on the specified exam
$sql = "SELECT  subjectname,classmarks,exammarks,totalmarks FROM student_results WHERE username='".$username."' AND exam='".$exam."'";
$results = mysqli_query($conn, $sql);
while($rows = mysqli_fetch_assoc($results))
{
	$rows["classmarks"] = (float)$rows["classmarks"];
	$rows["exammarks"] = (float)$rows["exammarks"];
	$rows["totalmarks"] = (float)$rows["totalmarks"];
	array_push($json_data,$rows);
}

$json_data = "\"results\":".json_encode($json_data);

//getting student details
$sql = "SELECT * FROM student_overrall_marks WHERE username='".$username."' AND exam='".$exam."'";
$results = mysqli_query($conn, $sql);
$form;$track;$department;$class;

if(mysqli_num_rows($results) > 0)
	while($rows = mysqli_fetch_assoc($results))
	{
		$form = $rows["form"];
		$track = $rows["track"];
		$department = $rows["department"];
		$class = $rows["class"];
	}


	//getting class position(rank) 
	$sql = "SELECT * FROM student_overrall_marks WHERE exam='".$exam."' AND form='".$form."' AND track='".$track."' AND department='".$department."' AND class='".$class."'  ORDER BY marks DESC";
	$overrall_marks = 0.0;
	$class_position = 1;

	$x = mysqli_query($conn, $sql);
	while($y = mysqli_fetch_assoc($x))
	{
		if($y["username"] == $username)
		{
			$overrall_marks = $y["marks"];
			$form = $y["form"];
			break;
		}
		$class_position++;
	}

	//getting form position(rank) 
	$sql = "SELECT * FROM student_overrall_marks WHERE exam='".$exam."' AND form='".$form."'  ORDER BY marks DESC";
    $form_position = 1;
	
	$x = mysqli_query($conn, $sql);
	while($y = mysqli_fetch_assoc($x))
	{
		if($y["username"] == $username)
			break;

		$form_position++;
	}

	$jn = array("form" => $form,"class_position" =>(int)$class_position,"form_position" => (int)$form_position,"marks" => (float)$overrall_marks);
	$json_data = "{\"default\":[".json_encode($jn)."],".$json_data."}";
	echo($json_data);

	?>
