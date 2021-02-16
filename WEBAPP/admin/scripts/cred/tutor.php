<?php

session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true && $_SESSION["loggedin"] == true)
{

	print(
		"
		<style type='text/css'>
		body
		{
			background-color: #303030
			color:#88ffff;
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
			height: 100%;
			width: 100%;
			border: 0px;
			font-family: monospace;
		}
		h1
		{
			color:#88ffff;
			text-align: center;
			font-family:monospace;
		}
		</style>
		");


	include('../../../login/connection.php');
	$conn = sql_connect();

	$sql = "SELECT * FROM tutor_login_info";
	$results = mysqli_query($conn, $sql);

	if(mysqli_num_rows($results) > 0)
	{
		print("<h1 style='font-family:monospace; color: #88ffff' align='center'>CREDENTIALS FOR NEWLY ADDED TUTORS</h1>");
		print("<table width='100%'><tr><th>Name</th><th>Username</th><th>Password</th><th>State</th></tr>");

		while($rows = mysqli_fetch_assoc($results))
		{
			$sql = "SELECT tutorname FROM tutor_access_info WHERE username='".$rows["username"]."' LIMIT 1";
			$ret = mysqli_query($conn, $sql);
			while($lane = mysqli_fetch_assoc($ret))
				$name = $lane["tutorname"];

			$state = "inactive";
			if($rows["status"] == 1)
				$state = "active";
			print
			(
				"<tr><td><button>".$name."</button></td>".
				"<td><button>".$rows["username"]."</button></td>".
				"<td><button>".$rows["password"]."</button></td>".
				"<td><button>".$state."</button></td></tr>"

			);
		}
		print("</table>");
	}
	else
	{
		print("<h1>NO NEWLY ADDED TUTOR</h1>");
	}

}
else
{
	header("location: ../../../login/index.php");
}
?>
