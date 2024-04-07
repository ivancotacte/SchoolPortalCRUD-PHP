
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

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>