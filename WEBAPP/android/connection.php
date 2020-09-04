<?php
	function sql_connect()
	{
		$config = include(dirname(__DIR__).'/config.php');
		$conn=mysqli_connect($config->dbhost, $config->dbuser, $config->dbpass,"SBASE");
		
		if(! $conn)
			exit;

		return $conn;
	}

?>
