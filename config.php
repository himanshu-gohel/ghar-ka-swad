<?php
$db_host = "mysql.railway.internal";       // from Railway MYSQLHOST
$db_user = "root";       // from MYSQLUSER
$db_pass = "OurQYtIpxPGhftGrKfCfxqQZEKTwKrsq";   // from MYSQLPASSWORD
$db_name = "railway";    // from MYSQLDATABASE

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>