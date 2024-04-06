
<?php
date_default_timezone_set('Asia/Manila');
require_once realpath(__DIR__ . "/../vendor/autoload.php");
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$servername = $_ENV['DATABASE_HOST'];
$username = $_ENV['DATABASE_USER'];
$password = $_ENV['DATABASE_PASSWORD'];
$dbname = $_ENV['DATABASE_NAME'];

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "Connection Failed";
}

?>