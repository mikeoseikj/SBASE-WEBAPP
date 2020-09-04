<?php

if(isset($_SESSION["username"]) && $_SESSION["password"])
{

	function sql_connect()
	{
		$config = include(dirname(__DIR__).'/config.php');
	
		$conn = mysqli_connect($config->dbhost, $config->dbuser, $config->dbpass, 'SBASE');
		if(! $conn)
		{
			print("<script>alert('DATABASE CONNECTION PROBLEM');document.location.href='index.php'</script>");
			exit;
		}

		return $conn;
	}


}
else
{
	header("location: index.php");
}
?>
