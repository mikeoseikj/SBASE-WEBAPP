<?php


if(isset($_SESSION["username"]) && $_SESSION["password"])
{

	function sql_connect()
	{
		$dbhost="localhost:3306";
		$dbuser="root";
		$dbpass="";
		$dbname="SBASE";
		$conn=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
		if(! $conn)
		{
			print("<script>alert('".mysqli_connect_error()."');document.location.href='index.php'</script>");
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
