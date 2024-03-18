<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbicctportal_crud";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "Connection Failed";
}

?>