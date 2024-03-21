<?php
session_start();

if (!isset($_SESSION['ADMIN_EMAIL'])) {
    header("Location: index.php");
    die();
}

include 'config/connect.php';
include 'layouts/admin-navbar.php';

$msg = "";

$query = "SELECT * FROM tb_account WHERE user_role = 'student'";
$result = mysqli_query($conn, $query);

if (!$result) {
    $msg = "Error: " . $query . "<br>" . mysqli_error($conn);
}

if (isset($_POST['delete'])) {
    $id_to_delete = $_POST['delete_id'];
    $delete_query = "DELETE FROM tb_account WHERE id = $id_to_delete";
    if (mysqli_query($conn, $delete_query)) {
        $msg = "Record deleted successfully";
        header("Refresh:0");
    } else {
        $msg = "Error deleting record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="row justify-content-center">
            <div class="col-md-1000">
                <div class="p-3 border rounded-2 bg-white shadow">
                    <h2 class="mb-4">Student Accounts</h2>
                    <button type="button" onclick="window.location.href = 'add_student.php';" class="btn btn-primary"> Add Student </button>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Suffix</th>
                                <th>Course</th>
                                <th>Campus</th>
                                <th>Contact #</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'] . "</td>";
                                echo "<td>" . $row['suffix_name'] . "</td>";
                                echo "<td>" . $row['course'] . "</td>";
                                echo "<td>" . $row['campus'] . "</td>";
                                echo "<td>" . $row['contact_number'] . "</td>";
                                echo "<td>" . $row['email_address'] . "</td>";
                                echo "<td>" . $row['created_at'] . "</td>";
                                echo "<td>";
                                echo "<button class='btn btn-success me-2' onclick='viewProfile(" . $row['id'] . ")'>View</button>";
                                echo "<button class='btn btn-primary me-2' onclick='editProfile(" . $row['id'] . ")'>Edit</button>";
                                echo "<button class='btn btn-danger' onclick='deleteAccount(" . $row['id'] . ")'>Delete</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div id="msg"><?php echo $msg; ?></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteAccount(id) {
            if (confirm("Are you sure you want to delete this account?")) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == XMLHttpRequest.DONE) {
                        if (xhr.status == 200) {
                            location.reload();
                        } else {
                            document.getElementById("msg").innerHTML = "Error deleting account: " + xhr.responseText;
                        }
                    }
                };
                xhr.open("POST", "delete_account.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send("delete_id=" + id);
            }
        }

        function editProfile(id) {
            window.location.href = "edit_student.php?id=" + id;
        }
        function viewProfile(id) {
        window.location.href = "view_student.php?id=" + id;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>