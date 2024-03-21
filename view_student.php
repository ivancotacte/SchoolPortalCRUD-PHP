<?php
include 'config/connect.php';
include 'layouts/admin-navbar.php';

if(isset($_GET['id'])) {
    $student_id = $_GET['id'];

    $query = "SELECT * FROM tb_account WHERE id = $student_id";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="p-3 border rounded-2 bg-white shadow">
                    <h2 class="mb-4">Student Information</h2>
                    <p><strong>ID:</strong> <?php echo $student['id']; ?></p>
                    <p><strong>Name:</strong> <?php echo $student['first_name'] . " " . $student['middle_name'] . " " . $student['last_name']; ?></p>
                    <p><strong>Suffix:</strong> <?php echo $student['suffix_name']; ?></p>
                    <p><strong>Course:</strong> <?php echo $student['course']; ?></p>
                    <p><strong>Campus:</strong> <?php echo $student['campus']; ?></p>
                    <p><strong>Contact #:</strong> <?php echo $student['contact_number']; ?></p>
                    <p><strong>Email:</strong> <?php echo $student['email_address']; ?></p>
                    <p><strong>Created At:</strong> <?php echo $student['created_at']; ?></p>
                    <a href="admin-dashboard.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

<?php
    } else {
        echo "Student not found.";
    }
} else {
    echo "Invalid request.";
}
?>