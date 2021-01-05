<?php

print("<body bgcolor='#101010'></body>");
session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"] == true && $_SESSION["user"] == "student")
{

	//logout users out after 15 minutes
	if((time()-$_SESSION["timestamp"]) > 900)
	{
		print("<script>alert('session timeout');document.location.href='../../login/logout.php'</script>");
		exit;
	}

	include('../../login/connection.php');
	include('../../login/func.php');


	$conn = sql_connect();
	$NO_EXAM_BANNER = "";

	$table = "";
	$name = "";
	$totalmarks = 0;
	$username = $_SESSION["username"];

	$exam = sanitize_sql_input($_GET["exam"], "/[^a-zA-Z0-9\-_() ]/");
	if(empty($exam))	// immediately after login
	{
		$exam = "";
		$sql = "SELECT exam FROM student_overrall_marks WHERE username='".$username."'";
		$results = mysqli_query($conn, $sql);

		//getting latest exam for the student ( inthe ones that has been filled by the tutors)
		while($rows = mysqli_fetch_assoc($results))
			$exam = $rows["exam"];

		if(empty($exam))   //if no results for the student has been uploaded
		{
			//getting student name 
			$sql = "SELECT studentname FROM student_subject_info WHERE username='".$username."' LIMIT 1";
			$x = mysqli_query($conn, $sql);

			$y = mysqli_fetch_assoc($x);
			$name = $y["studentname"];

			$NO_EXAM_BANNER = "<h1 align='center' style='color: #88ffff; font-family: monospace; margin-top: 10%;'>YOU HAVE NO EXAM RECORDED</h1>";
			goto go_label;
			
		}

		header("location: results.php?exam=".$exam);	//revisit with the latest exam
		exit;
	}

	//get exam names that student is registered for
	$sql = "SELECT exam FROM student_overrall_marks WHERE username='".$username."'";
	$exams = array();
	$results = mysqli_query($conn, $sql);

	while($rows = mysqli_fetch_assoc($results))
		array_push($exams,$rows["exam"]);


	//getting student details eg: form, track etc...
	$sql = "SELECT * FROM student_overrall_marks WHERE username='".$username."' AND exam='".$exam."'";
	$results = mysqli_query($conn, $sql);
	    
	$rows = mysqli_fetch_assoc($results);
	$form = $rows["form"];
	$track = $rows["track"];
	$department = $rows["department"];
	$class = $rows["class"];


	//getting class position(rank) 
	$sql = "SELECT * FROM student_overrall_marks WHERE exam='".$exam."' AND form='".$form."' AND track='".$track."' AND department='".$department."' AND class='".$class."'  ORDER BY marks DESC";
	$totalmarks = 0;
	$pos = 1;
	$x = mysqli_query($conn, $sql);
	while($y = mysqli_fetch_assoc($x))
	{
		if($y["username"] == $username)
		{
			$totalmarks = $y["marks"];
				break;
		}
		$pos++;
	}

	//getting form position(rank) 
	$sql = "SELECT * FROM student_overrall_marks WHERE exam='".$exam."' AND form='".$form."' ORDER BY marks DESC";
	$form_pos = 1;
	$x = mysqli_query($conn,$sql);
	while($y = mysqli_fetch_assoc($x))
	{
		if($y["username"] == $username)
			break;

		$form_pos++;
	}

	//getting student name 
	$sql = "SELECT studentname FROM student_subject_info WHERE username='".$username."' LIMIT 1";
	$x = mysqli_query($conn,$sql);

	$y = mysqli_fetch_assoc($x);
	$name = $y["studentname"];

	$sql = "SELECT * FROM student_results WHERE username='".$username."' AND exam='".$exam."'";
	$results = mysqli_query($conn,$sql);
	$table = "";
	if(mysqli_num_rows($results) > 0)
	{
		$table .= "<label style='float:left;'>CLASS RANK:</label> <button style='float:left; background-color: #ff0000;width: 7%;'> ".$pos."</button>
				<label style='float:left;'> FORM RANK:</label> <button style='float:left; background-color: #ff0000;width: 7%;'> ".$form_pos."</button>
				<label style='float:left;'>FORM:</label> <button style='float:left; background-color: #44ffff;width: 7%;'> ".$form."</button><br /><br /><br /><br />";


		$table .= "<table width='90%'><tr><th>Subject</th><th>Class score</th><th> Exam score</th><th>Totalmarks</th></tr>";
				while($rows = mysqli_fetch_assoc($results))
					$table.="<tr><td><button>".$rows["subjectname"]."</button></td>"
				."<td><button>".$rows["classmarks"]."</button></td>"
				."<td><button>".$rows["exammarks"]."</button></td>"
				."<td><button>".$rows["totalmarks"]."</button></td></tr>";

			$table .= "</table>";
	}

	$menu = "";
	for($i = 0; $i < count($exams); $i++)
	{
		$menu .= "<a href='results.php?exam=".$exams[$i]."'>".$exams[$i]."</a>";
	}


	go_label:

	print("
			<!DOCTYPE html>
			<html>
			<head>

			<meta name='viewport' content='width=device-width, initial-scale=1'>
			<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>


			<!--setting background image-->
			<style type='text/css'>
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

			button
			{
				font-family: monospace;
				color:#ffffff;
				background-color: #101010;
    			width: 100%;
				border: 0px;
				font-family: monospace;
			}
			label
			{
				color: #22ffff;
				font-size: 16px;
			}

			body
			{
				font-family: monospace;
				background-color: #000000;
			}
			.header
			{
				font-family:monospace;
				position:fixed;
				color:#55ffff;
				top:0px;
				left:0px;
				width:100%;
				padding:15px;
				font-size:20px;
				background-color:#202020;
				border-style: solid;
				border-width: 0px;
				border-bottom-width: 1px;
				border-bottom-color: #101010;
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

			.ps
			{
				width: 20%;
				float: right;
				margin-bottom: 10px;
			}

			.name
			{
				font-size: 16px;
				float:  left;
				width: 50%;
				color:#ffffff;
				background-color: #11eeee;
				border-radius: 5px;
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

			<body>

			<div id='mysidenav' class='sidenav'>
			<a href='javascript:void(0)' class='closebtn' onclick='close_nav()'>&times;</a>
			<a style='color:#44ffff'>".$name."</a><br /><br />".$menu.
			"<br /><br /><a  href='graph.php'><i class='fa fa-line-chart'></i> Graph of Exams</a><br />
			<a  href='../../login/change/pass.php'><i class='fa fa-key'></i> Change Password</a>
			<a onclick='logout()'><i class='fa fa-sign-out'></i> logout</a>
			</div>

			<div class='header'>
			<h3 align='center'>EXAMINATION REPORTS</h3>
			<span style='font-size:30px;cursor:pointer' onclick='open_nav()'>&#9776;</span>
			</div>

			<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
			".$table."<br /><button style='height:10px; width: 10%;float: right; border-style:solid; border-width: 1px;height: 20px; border-color: #88ffff;' >".$totalmarks."</button>
			<label style='color: #ff0000;float: right;'>Total marks: </label>".
			$NO_EXAM_BANNER."</body>
			</html> 
			");

	}
	else
	{
		header("location: ../../login/index.php");
	}
	?>
