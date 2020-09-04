<?php


session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"] == true && $_SESSION["user"] == "tutor")
{

//logout users out after 6 hours
	if((time()-$_SESSION["timestamp"]) > 21600)
	{
		print("<script>alert('session timeout');document.location.href='../../login/logout.php'</script>");
		exit;
	}


	$css =
	"
	<head>
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>

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
		font-family: monospace;
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

	.sidenav 
	{
		font-family: monospace;
		border-style:solid;
		border-width:0px;
		border-right-width:1px;
		border-right-color:#c0c9c9;
		height: 100%;
		width: 0;
		position: fixed;
		z-index: 1;
		top: 0;
		left: 0;
		background-color: #202020;
		overflow-x: hidden;
		padding-top:60px;
		transition: 0.5s;
		padding-top: 60px;
	}

	.sidenav a 
	{
		border-style:solid;
		border-width:0px;
		border-bottom-width:1px;
		padding: 8px 8px 8px 18px;
		text-decoration: none;
		font-size: 15px;
		color:white;
		display: block;
		transition: 0.3s;
		border-bottom-color:#909090;
	}

	.sidenav .closebtn 
	{
		border-width:0px;
		position: absolute;
		top: 0;
		right: 25px;
		font-size: 20px;
		margin-left: 50px;
	}


	.sidenav a:hover 
	{
		background-color:#c0c0c0;
	}

	</style>
	<!--javascript for side pane-->
		<script type='text/javascript'>
		function open_nav() 
		{
			document.getElementById('mysidenav').style.width = '250px';
		}

		function close_nav() 
		{
			document.getElementById('mysidenav').style.width = '0';
		}
		function logout()
		{
			var status = confirm('Do you want to logout?');
			if(status == true)
			{
				window.location = '../../login/logout.php'
			}
		}
		</script>
	</head>
	";
	print($css);
	
	include('../../login/connection.php');
	include('../../login/func.php');

	$form = sanitize_sql_input($_GET["form"],"/[^a-zA-Z0-9\-_() ]/");
	$track = sanitize_sql_input($_GET["track"],"/[^a-zA-Z0-9\-_() ]/");
	$department = sanitize_sql_input($_GET["department"],"/[^a-zA-Z0-9\-_() ]/");
	$class = sanitize_sql_input($_GET["class"],"/[^a-zA-Z0-9\-_() ]/");
	$subject = sanitize_sql_input($_GET["subject"],"/[^a-zA-Z0-9\-_() ]/");





	if(empty($form) || empty($track) || empty($department) || empty($class) ||   empty($subject))
	{
		print("<script>alert('An empty field was provided');document.location.href='page.php'</script>");
		exit;
	}

	$username = $_SESSION["username"];
	
	$conn=sql_connect();

	$exam = "";

	if(isset($_GET["exam"]))
	{
		$exam = sanitize_sql_input($_GET["exam"],"/[^a-zA-Z0-9\-_() ]/");
	}
	else
	{
		$sql = "SELECT * FROM exam_info ORDER BY id DESC LIMIT 1";
		$results = mysqli_query($conn,$sql);

		if(mysqli_num_rows($results) > 0)
		{
			$rows = mysqli_fetch_assoc($results);
			$exam = $rows["exam"];
		}

		else
		{
			print("<script>alert('No exam');document.location.href='page.php'</script>");
			exit;
		}
	}


	$sql = "SELECT * FROM student_subject_info WHERE subjectname='".$subject."' AND form='".$form."' AND track='".$track."' AND department='".$department."' AND class='".$class."'";

	$results = mysqli_query($conn,$sql);
	if(mysqli_num_rows($results) > 0)
	{
		$sql = "SELECT * FROM exam_info";
		$x= mysqli_query($conn, $sql);
		$exam_links = "";

		while($y = mysqli_fetch_assoc($x))
			$exam_links .= "<a href='manip.php?form=".$form."&track=".$track."&department=".$department."&class=".$class."&subject=".$subject."&exam=".$y["exam"]."  '><i style='font-size: 16px; color: #88ffff;' class='fas fa-boxes'></i> ".$y["exam"]."</a><br />";



		print(
			"
			<div id='mysidenav' class='sidenav'>
			<a href='javascript:void(0)' class='closebtn' onclick='close_nav()'>&times;</a><br />
			<a style='color: #55ffff' href='page.php'><i class='fa fa-home'></i> Go Mainpage</a><br /><br />
			<a style='color: #55ffff'>EXAMINATION LIST</a><br />
			<a></a>
			".$exam_links."</div>

			<div class='header'>
			<h1 align='center'>RECORDING SHEET</h1>

			<span style='font-size:30px;cursor:pointer' onclick='open_nav()'>&#9776;</span><br /><br />

            <div style='float:right; margin-right: 0px;'>

			<label style='color: #99ffff;'>CLASS PERCENTAGE: </label>
			<input name='class_percentage' value='30' form='sheet' style='width:10%;border: solid 1px #88ffff; margin-right: 5%;' required type='number' step='5' min='0' max='100' />
		
			<label style='color: #99ffff;'>EXAM PERCENTAGE: </label>
			<input name='exam_percentage' value='70' form='sheet' style='width:10%;border: solid 1px #88ffff;' required type='number' step='5' min='0' max='100' />
			</div>

			</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />");

		print("<input name='subject' readonly type='text' form='sheet' style='text-align: center;background-color: #88ffff; color: white; border: none;border-radius: 2px;width: 15%;font-family: monospace;' value='".$subject."'></input><br /><br />
			

			<a style='float: right; background-color: #ddd;color:#101010; font-family:monospace;text-decoration:none; border: solid 1px #88ffff; border-radius: 2px;'
			href='vresults.php?form=".$form."&track=".$track."
			&department=".$department."&class=".$class."&subject=".$subject."&exam=".$exam."' >
			view ranks</a>
");//a GET request is built dynamically and is sent to the vresults php file

		print("<table width='100%'>
			<tr><th>Name</th><th>Username</th><th>Class marks</th><th>Exam marks</th></tr>");
		print("<form id='sheet' action='record.php' method='POST'>");


		while($rows = mysqli_fetch_assoc($results))
		{

//checking if students marks have already been filled; if true fill current input fields with marks 
			$cmark = 0;
			$emark = 0;
			$sql = "SELECT * FROM student_results WHERE subjectname='".$subject."' AND username='".$rows["username"]."' AND exam='".$exam."'";

			$ret = mysqli_query($conn,$sql);
			if(mysqli_num_rows($ret))
			{
				while($lane = mysqli_fetch_assoc($ret))
				{
					$cmark = $lane["classmarks"];
					$emark = $lane["exammarks"];
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
		print("<input style='display:none' form='sheet' value='".$exam."' name='exam'></input>");


	}

	else
	{
		print("<h1 align='center' style='font-family: monospace; margin-top: 10%;color: #88ffff' >NO REGISTERED STUDENTS</h1>");
	}

}


else
{
	header("location: page.php");
}

?>
