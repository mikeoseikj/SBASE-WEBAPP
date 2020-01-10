<?php

session_start();
//check if user is logged in 
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"]==true && $_SESSION["user"]=="tutor")
{

//logout users out after 1 hour
	if((time()-$_SESSION["timestamp"]) > 3600)
	{
		print("<script>alert('session timeout');document.location.href='../../login/logout.php'</script>");
		exit;
	}


	include '../../login/connection.php';
	include '../../login/func.php';
	$conn=sql_connect();

//getting tutor's name
	$sql="SELECT tutorname FROM tutor_access_info WHERE username='".$_SESSION["username"]."' LIMIT 1";
	$ret=mysqli_query($conn,$sql);
	$name;
	while($rows=mysqli_fetch_assoc($ret))
		$name=$rows["tutorname"];
	print("
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>

		<style  type='text/css'>

		@-webkit-keyframes blinker 
		{
			from {opacity: 1.0;}
			to {opacity: 0.5;}
		}
		.board:hover
		{
			text-decoration: blink;
			-webkit-animation-name: blinker;
			-webkit-animation-duration: 0.7s;
			-webkit-animation-iteration-count:infinite;
			-webkit-animation-timing-function:ease-in-out;
			-webkit-animation-direction:alternate;
		}


		body
		{
			padding-top: 5%;
			background-color: #303030;
			font-family: monospace;
		}
		input[type=text]
		{
			font-family: monospace;
			background-color: #202020;
			color: #88ffff;
			border-style: solid;
			border-width: 1px;
			border-right-width: 0px;
			border-left-width: 0px;
			text-align: center;
			border-color: #88ffff;
			height: 40px;
			border-radius: 5px;

		}

		input[type=submit]
		{
			font-family: monospace;
			background-color: #88ffff;
			color:#ffffff;
			border-style:solid;
			border-width: 1px;
			border-color: #202020;
			height: 30px;
			border-radius: 6px;
			margin-left: 10px;
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
		</script>


		<div id='mysidenav' class='sidenav'>
		<a href='javascript:void(0)' class='closebtn' onclick='close_nav()'>&times;</a><br />
		<a style='color: #99ffff'>".$name."</a><br /><br />
		<a href='../../login/change/pass.php'><i class='fa fa-key'></i> Change Password</a>
		<br />
		<a href='../../login/logout.php'><i class='fa fa-sign-out'></i> logout</a>
		</div>


		<div class='header'>
		<h1 align='center'>WELCOME TO SBASE</h1>
		<span style='font-size:30px;cursor:pointer' onclick='open_nav()'>&#9776;</span>
		</div><br /><br /><br /><br /><br />

		<h2 align='center' style='color: #ddffff; background-color: #202020; border-radius: 5px; border-style:solid; border-width: 1px; border-color:#101010;'>select the class you want fill results for students</h2>
		"
	);



	$username=$_SESSION["username"];

	$username = sanitize_sql_input($username,14,"/[^a-zA-Z1-9.]/");

	if(empty($username))
	{
		print("<script>alert('not allowed');document.location.href='../../login/index.php'</script>");
		exit;
	}

	$sql="SELECT * FROM tutor_access_info WHERE username='".$username."'";
	$results=mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		while($rows=mysqli_fetch_assoc($results))
		{
			$board="<br /><br /><form class='board' action='manip.php' method='POST'>";
			$board.="<input type='text' readonly name=form value='".$rows["form"]."'></input>";
			$board.="<input type='text' readonly name=track value='".$rows["track"]."'></input>";
			$board.="<input type='text' readonly name=department value='".$rows["department"]."'></input>";
			$board.="<input type='text' readonly name=class value='".$rows["class"]."'></input>";
			$board.="<input type='text' readonly name=subject value='".$rows["subject"]."'></input>";
			$board.="<input type='submit' name='submit' value='select'></input></form>";
			print($board);
		}
	}


}

else
{
	header("location: ../../login/index.php");
	exit;
}
?>
