<?php
$db_host = getenv("MYSQLHOST");
$db_user = getenv("MYSQLUSER");
$db_pass = getenv("MYSQLPASSWORD");
$db_name = getenv("MYSQLDATABASE");

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>