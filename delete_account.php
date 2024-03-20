<?php
session_start();

if (!isset($_SESSION['ADMIN_EMAIL'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

include 'config/connect.php';

if(isset($_POST['delete_id'])) {
    $id_to_delete = $_POST['delete_id'];
    
    $id_to_delete = mysqli_real_escape_string($conn, $id_to_delete);
    
    $delete_query = "DELETE FROM tb_account WHERE id = '$id_to_delete'";
    
    if(mysqli_query($conn, $delete_query)) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    exit;
}
?>
