<?php
include 'config/connect.php';

$StudentNumber = $_GET["StudentNum"];

$sql = "DELETE FROM TBL_ACCOUNT WHERE STUDENT_NUMBER = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$StudentNumber]);

header("Location: owner-dashboard.php");
?>