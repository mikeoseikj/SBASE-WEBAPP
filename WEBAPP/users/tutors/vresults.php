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


	print(
		"
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
		button
		{
			height: 100%;
			width: 100%;
			border: 0px;
			font-family: monospace;
		}
		</style>
		");


    include('../../login/connection.php');
    include('../../login/func.php');

	$form = sanitize_sql_input($_GET["form"],"/[^a-zA-Z0-9\-_() ]/");
	$track = sanitize_sql_input($_GET["track"],"/[^a-zA-Z0-9\-_() ]/");
	$department = sanitize_sql_input($_GET["department"], "/[^a-zA-Z0-9\-_() ]/");
	$class = sanitize_sql_input($_GET["class"],"/[^a-zA-Z0-9\-_() ]/");
    $subject = sanitize_sql_input($_GET["subject"],"/[^a-zA-Z0-9\-_() ]/");
    $exam = sanitize_sql_input($_GET["exam"],"/[^a-zA-Z0-9\-_() ]/");


	if(empty($form) || empty($track) || empty($department) || empty($class) || empty($subject) || empty($exam))
	{
		print("<script>alert('not allowed');document.location.href='../../login/index.php'</script>");
		exit;
	}

	
	$conn = sql_connect();

	$sql = "SELECT * FROM student_subject_info WHERE form='".$form."' AND track='".$track."' AND department='".$department."' AND class='".$class."' AND subjectname='".$subject."'";


	$results = mysqli_query($conn,$sql);


	if(mysqli_num_rows($results) > 0)
	{
		print("<h1 style='font-family:monospace; color: #88ffff' align='center'>RANK VIEW</h1>");
		print("<table width='100%'><tr><th>Name</th><th>Class marks</th><th>Exam marks</th><th>Total marks</th><th>Rank</th></tr>");

		while($rows = mysqli_fetch_assoc($results))
		{
			$sql = "SELECT * FROM student_results WHERE username='".$rows["username"]."' AND exam='".$exam."' AND subjectname='".$subject."'";

			$ret = mysqli_query($conn,$sql);
			if(mysqli_num_rows($ret) > 0)
			{

//getting subject position(rank) 
				$sql = "SELECT * FROM student_results WHERE exam='".$exam."' AND subjectname='".$subject."' AND form='".$form."' AND track='".$track."' AND department='".$department."' AND class='".$class."'  ORDER BY totalmarks DESC";

				$pos = 1;
				$x = mysqli_query($conn,$sql);
				while($y = mysqli_fetch_assoc($x))
				{
					if($y["username"] == $rows["username"])
						break;
					$pos++;
				}


				while($lane = mysqli_fetch_assoc($ret))
				{

					print
					(
						"<tr><td><button>".$rows["studentname"]."</button></td>".
						"<td><button>".$lane["classmarks"]."</button></td>".
						"<td><button>".$lane["exammarks"]."</button></td>".
						"<td><button>".$lane["totalmarks"]."</button></td>".
						"<td><button>".$pos."</button></td></tr>"

					);
				}}
			}print("</table>");}

			else
			{
				print("<h1>NO STUDENTS</h1>");
			}


		}


		else
		{
			header("location: page.php");
		}
		?>
