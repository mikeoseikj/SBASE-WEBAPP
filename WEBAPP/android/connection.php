<?php
	function sql_connect()
	{
		$dbhost="localhost:3306";
		$dbuser="root";
		$dbpass="";
		$dbname="SBASE";
		
		$conn=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
		if(! $conn)
		exit;

		return $conn;
	}

?>
