<?php


session_start();
if(isset($_POST["submit"]) && isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"]==true && $_SESSION["user"]=="tutor")
{

//logout users out after 1 hour
	if((time()-$_SESSION["timestamp"]) > 3600)
	{
		print("<script>alert('session timeout');document.location.href='../../login/logout.php'</script>");
		exit;
	}


	$css=
	"
	<head>
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>

	<style type='text/css'>
	body
	{
		background-color: #303030
	}
	th
	{
		font-size:16px;
		background-color: #88ffff;
	}

	table, th, td
	{
		font-family: monospace;
		border-style: solid;
		border-width: 1px;
		border-color: #99ffff;
		padding: 5px;
	}
	input[type=text]
	{
		background-color: #303030;
		color: #999999;
		border: none;
		width: 100%;
	}
	input[type=number]
	{
		background-color: #202020;
		color: #ff2233;
		border: none;
		width: 100%;
	}

	input[type=number]:focus
	{
		background-color: #ddd;
	}

	input[type=submit]
	{
		color: #77ffff;
		border-style: solid;
		border-width: 1px;
		border-color: #88ffff;
		background-color: #101010;
		border-radius: 4px;
		float:right;
	}
	.header
	{
		font-family:monospace;
		position:fixed;
		color:white;
		top:0px;
		left:0px;
		width:100%;
		padding:15px;
		background-color:#353535;
		border-style: solid;
		border-width:0px;
		border-bottom-width: 1px;
		border-bottom-color: #88ffff;
	}

	</style>
	</head>
	";
	print($css);
	
	include '../../login/connection.php';
	include '../../login/func.php';

	$form = sanitize_sql_input($_POST["form"],20,"/[^a-zA-Z1-9 ]/");
	$track = sanitize_sql_input($_POST["track"],20,"/[^a-zA-Z1-9 ]/");
	$department = sanitize_sql_input($_POST["department"],20,"/[^a-zA-Z1-9 ]/");
	$class = sanitize_sql_input($_POST["class"],20,"/[^a-zA-Z1-9 ]/");
	$subject = sanitize_sql_input($_POST["subject"],20,"/[^a-zA-Z1-9 ]/");



	if(empty($form) || empty($track) || empty($department) || empty($class) ||   empty($subject))
	{
		print("<script>alert('not allowed');document.location.href='../../login/index.php'</script>");
		exit;
	}

	$username=$_SESSION["username"];
	$username = sanitize_sql_input($username,14,"/[^a-zA-Z1-9.]/");


	
	$conn=sql_connect();

	if(! $conn)
	{
		header("location: ../../login/index.php");
		exit;
	}

	$exam="";
	$sql="SELECT * FROM exam_info ORDER BY id DESC LIMIT 1";
	$results=mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		while($rows=mysqli_fetch_assoc($results))
		{
			$exam=$rows["exam"];
		}
	}

	else
	{
		print("<script>alert('No exam');document.location.href='page.php'</script>");
		exit;
	}


	$sql="SELECT * FROM student_subject_info WHERE subjectname='".$subject."' AND form='".$form."' AND track='".$track."' AND department='".$department."' AND class='".$class."'";

	$results=mysqli_query($conn,$sql);
	if(mysqli_num_rows($results) > 0)
	{
		print(
			"
			<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>

			<div class='header'>
			<h1 align='center'>RECORDING SHEET</h1>
			<a style='color:#ffffff;font-size:25px;cursor:pointer' href='../../login/logout.php'><i class='fa fa-sign-out'></i></a>

			<a style='color:#ffffff;font-size:25px;cursor:pointer' href='page.php'><i class='fa fa-home'></i></a><br /><br />

			<label style='margin-right:5%;float: right;color: #99ffff;'>total marks percentage</label><br />
			<input name='percentage' value='100' form='sheet' style='margin-right: 5%;float: right;width:10%;border: solid 1px #88ffff;' required type='number' step='5' min='0' max='100'></input>
			</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />");

		print("<input name='subject' readonly type='text' form='sheet' style='background-color: #88ffff; color: #202020; border: none;border-radius: 2px;width: 9%;font-family: monospace;' value='".$subject."'></input><br /><br />
			

			<a style='float: right; background-color: #ddd;color:#101010; font-family:monospace;text-decoration:none; border: solid 1px #88ffff; border-radius: 2px;'
			href='vresults.php?form=".$form."&track=".$track."
			&department=".$department."&class=".$class."&subject=".$subject."&exam=".$exam."' >
			view ranks</a>
");//a GET request is built dynamically and is sent to the vresults php file

		print("<table width='100%'>
			<tr><th>Name</th><th>Username</th><th>Class marks</th><th>Exam marks</th></tr>");
		print("<form id='sheet' action='record.php' method='POST'>");


		while($rows=mysqli_fetch_assoc($results))
		{

//checking if students marks have already been filled; if true fill current input fields with marks 
			$cmark=0;
			$emark=0;
			$sql="SELECT * FROM student_results WHERE subjectname='".$subject."' AND username='".$rows["username"]."' AND exam='".$exam."'";

			$ret=mysqli_query($conn,$sql);
			if(mysqli_num_rows($ret))
			{
				while($lane=mysqli_fetch_assoc($ret))
				{
					$cmark=$lane["classmarks"];
					$emark=$lane["exammarks"];
				}
			}

			print
			("
				<tr><td><input type='text'  readonly value='".$rows["studentname"]."'></input></td>
				<td><input name='usernames[]' required type='text' readonly value='".$rows["username"]."'></input></td>
				<td><input name='classmarks[]' required value='".$cmark."' min='0' step='0.01' type='number'></input></td>
				<td><input name='exammarks[]' required value='".$emark."' min='0' step='0.01' type='number'></input></td></tr><br />

				");

		}

		print("</table><br /><input type='submit' name='submit' value='RECORD'></input></form>");

//to help submit form,track etc
		print("<input style='display:none' form='sheet' value='".$form."' name='form'></input>");
		print("<input style='display:none' form='sheet' value='".$track."' name='track'></input>");
		print("<input style='display:none' form='sheet' value='".$department."' name='department'></input>");
		print("<input style='display:none' form='sheet' value='".$class."' name='class'></input>");

	}

	else
	{
		print("<h1 align='center' style='color: #88ffff' >NO REGISTERED STUDENTS</h1>");
	}

}


else
{
	header("location: page.php");
}

?>
