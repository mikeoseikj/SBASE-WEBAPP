<?php


session_start();

if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["loggedin"]==true && $_SESSION["user"]=="student")
{

//logout users out after 5 minutes
	if((time()-$_SESSION["timestamp"]) > 300)
	{
		print("<script>alert('session timeout');document.location.href='../../login/logout.php'</script>");
		exit;
	}


	print("<html>");
	include '../../login/connection.php';
	$conn=sql_connect();

	$username=$_SESSION["username"];

//get exam names
	$sql="SELECT exam FROM student_overrall_marks WHERE username='".$username."'";
	$exams=array();
	$results=mysqli_query($conn,$sql);

	if(mysqli_num_rows($results) > 0)
	{
		while($rows=mysqli_fetch_assoc($results))
		{
			array_push($exams,$rows["exam"]);
		}
	}
	else
	{
	}

//get values to fill sections of piechart
	$chart="";
	for($i=0;$i<count($exams);$i++)
	{
		$sql="SELECT * FROM student_overrall_marks WHERE username='".$username."' AND exam='".$exams[$i]."'";
		$results=mysqli_query($conn,$sql);
		if(mysqli_num_rows($results) > 0)
		{
			while($x=mysqli_fetch_assoc($results))
			{
				if($i==(count($exams)-1))
					{$chart.="['".$exams[$i]."' ,".$x["marks"]."]";}
				else
					{$chart.="['".$exams[$i]."' ,".$x["marks"]."],";}

			}
		}
	}

	print("
		<html>
		<body>
		<marquee><h2 align='center'>GRAPHICAL VIEW OF TRANSCRIPT</h2></marquee>
		<style type='text/css'>
		h2
		{
			color: #66ffff;
			background-color: #101010;
			border-radius: 5px;
		}
		body
		{
			font-family:monospace;
			background-color: #000000;
		}
#piechart 
		{
			position:fixed;
			top: 0;
			left: 0;
			width:100%;
			height:500px;
		}
		.footer 
		{
			position:fixed;
			font-family:sans-serif;
			left: 0;
			bottom: 0;
			width: 100%;
			background-color:#0a0a0a;
			color: white;
			padding:10px;
		}
		</style> 
		<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script> 
		<br /><br /><br /><br /><br /><br />
		<script type='text/javascript'>
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() 
		{
			var data = google.visualization.arrayToDataTable([
			['field', 'exams'],".$chart."]);

			var options = {'width':'100%', 'height':'100%', backgroundColor: 'transparent',is3D: true,fontName: 'monospace',  legend: {textStyle: { color: 'violet' }}};

			var chart = new google.visualization.PieChart(document.getElementById('piechart'));
			chart.draw(data, options);
		}
		</script>
		<div id='piechart'></div>

		<!--footer for this website-->
		<div style='height:120px' class='footer'>
		<div>
		</body>
		</html>
		");

}
else
{
	header("location: ../../login/index.php");
}
?>
