<?php

print("<body bgcolor='#000000'></body>");
session_start();
unset($_SESSION["username"]);
unset($_SESSION["password"]);
unset($_SESSION["loggedin"]);
unset($_SESSION["superuser"]);
unset($_SESSION["user"]);
unset($_SESSION["timestamp"]);
unset($_SESSION["first"]);

header("location: index.php");
?>
