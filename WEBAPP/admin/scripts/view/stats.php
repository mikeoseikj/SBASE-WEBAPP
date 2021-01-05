<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"] == true)
{

	print("
		<style type='text/css'>
		body
		{
			background-color: #000000;
		}
		th
		{
			font-size:16px;
			background-color: #88ffff;
		}

		table, th, td
		{
			font-family: monospace;
			border: solid 1px #99ffff;
			border-collapse: collapse;
			padding: 5px;
		}
		button
		{
			width: 100%;
			border: none;
			font-family: monospace;
			color: #ffffff;
			background-color: #303030;
		}
		h2
		{
			font-family: monospace;
			text-align: center;
			color: #000000;
			border: solid 1px #000000;
			border-radius: 5px;
			background-color: #99ffff;
		}
		</style>");

	print("<h1 align='center' style='font-family: monospace; color: #55ffff;'>SIMPLE DATABASE OVERVIEW</h1><br />");

	include('../../../login/connection.php');
	$conn = sql_connect();

	for($i = 0;$i < 5; $i++)
	{
		if($i == 0)
		{
			$field = "form";
			$field = "track";
			print("<h2>FORM</h2>");
		}
		elseif($i == 1)
		{
			$field = "track";
			print("<h2>TRACK</h2>");
		}
		elseif($i == 2)
		{
			$field = "department";
			print("<h2>DEPARTMENT</h2>");
		}
		elseif($i == 3)
		{
			$field = "class";
			print("<h2>CLASS</h2>");
		}
		elseif($i == 4)
		{
			$field = "subject";
			print("<h2>SUBJECT</h2>");
		}
		print("<table width='100%'><tr><th>".$field."</th><th>Tutors</th><th>Students</th></tr>");

		$sql = "SELECT * FROM ".$field."_info";
		$results = mysqli_query($conn, $sql);

		if(mysqli_num_rows($results) > 0)
		{
			while($rows = mysqli_fetch_assoc($results))
			{
				print("<tr><td><button>".$rows[$field]."</button></td>");

				$sql = "SELECT DISTINCT username FROM tutor_access_info WHERE ".$field."='".$rows[$field]."'";
				$ret = mysqli_query($conn, $sql);
				print("<td><button>".mysqli_num_rows($ret)."</button></td>");

				$sql = "SELECT DISTINCT username FROM student_subject_info WHERE ".$field."='".$rows[$field]."'";

				if($field == "subject")
					$sql = "SELECT DISTINCT username FROM student_subject_info WHERE ".$field."name='".$rows[$field]."'";

				$ret = mysqli_query($conn, $sql);
				print("<td><button>".mysqli_num_rows($ret)."</button></td></tr>");
			}

		}
		print("</table>");

	}//for loop

	print("<h2>ACCOUNTS STATES</h2>");

	//unaccessed accounts
	print("<table width='100%'><tr><th>Unused</th><th>Tutors</th><th>Students</th></tr>");
	$sql = "SELECT * FROM tutor_login_info";
	$x = mysqli_query($conn, $sql);
	$sql = "SELECT * FROM student_login_info";
	$y = mysqli_query($conn, $sql);
	print("<tr><td><button>unaccessed</button></td>");
	print("<td><button>".mysqli_num_rows($x)."</button></td>");
	print("<td><button>".mysqli_num_rows($y)."</button></td></tr>");

	//active unaccessed accounts
	$sql = "SELECT * FROM tutor_login_info WHERE status='1'";
	$x = mysqli_query($conn, $sql);
	$sql = "SELECT * FROM student_login_info WHERE status='1'";
	$y = mysqli_query($conn, $sql);
	print("<tr><td><button>active</button></td>");
	print("<td><button>".mysqli_num_rows($x)."</button></td>");
	print("<td><button>".mysqli_num_rows($y)."</button></td></tr>");

	//inactive unaccessed accounts
	$sql = "SELECT * FROM tutor_login_info WHERE status='0'";
	$x = mysqli_query($conn, $sql);
	$sql = "SELECT * FROM student_login_info WHERE status='0'";
	$y = mysqli_query($conn, $sql);
	print("<tr><td><button>inactive</button></td>");
	print("<td><button>".mysqli_num_rows($x)."</button></td>");
	print("<td><button>".mysqli_num_rows($y)."</button></td></tr></table>");


	//accessed accounts
	print("<table width='100%'><tr><th>Used</th><th>Tutors</th><th>Students</th></tr>");
	$sql = "SELECT * FROM tutor_slogin_info";
	$x = mysqli_query($conn, $sql);
	$sql = "SELECT * FROM student_slogin_info";
	$y = mysqli_query($conn, $sql);
	print("<tr><td><button>accessed</button></td>");
	print("<td><button>".mysqli_num_rows($x)."</button></td>");
	print("<td><button>".mysqli_num_rows($y)."</button></td></tr>");

	//active accessed accounts
	$sql = "SELECT * FROM tutor_slogin_info WHERE status='1'";
	$x = mysqli_query($conn, $sql);
	$sql = "SELECT * FROM student_slogin_info WHERE status='1'";
	$y = mysqli_query($conn, $sql);
	print("<tr><td><button>active</button></td>");
	print("<td><button>".mysqli_num_rows($x)."</button></td>");
	print("<td><button>".mysqli_num_rows($y)."</button></td></tr>");

	//inactive accessed accounts
	$sql = "SELECT * FROM tutor_slogin_info WHERE status='0'";
	$x = mysqli_query($conn, $sql);
	$sql = "SELECT * FROM student_slogin_info WHERE status='0'";
	$y = mysqli_query($conn, $sql);
	print("<tr><td><button>inactive</button></td>");
	print("<td><button>".mysqli_num_rows($x)."</button></td>");
	print("<td><button>".mysqli_num_rows($y)."</button></td></tr></table>");
	print("<br /><br /><br /><br />");

}
else
{
	header("location: ../../../login/index.php");
}


?>
