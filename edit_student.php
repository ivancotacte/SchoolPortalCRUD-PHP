<?php
session_start();

// Redirect to login if ADMIN_EMAIL session is not set
if (!isset($_SESSION['ADMIN_EMAIL'])) {
    header("Location: index.php");
    die();
}

include 'config/connect.php';

$msg = "";
$id = $_GET['id']; // Get student ID from URL

// Retrieve student information based on ID
$query = "SELECT * FROM tb_account WHERE id = $id AND user_role = 'student'";
$result = mysqli_query($conn, $query);

if (!$result) {
    $msg = "<div class='alert alert-danger'>Error</div>";
}

// If result is retrieved, assign information to variables
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $first_name = $row['first_name'];
    $middle_name = $row['middle_name'];
    $last_name = $row['last_name'];
    $suffix_name = $row['suffix_name'];
    $campus = $row['campus'];
    $course = $row['course']; // Add this line to retrieve course information
    $email_address = $row['email_address'];
} else {
    $msg = "<div class='alert alert-danger'>Student not found.</div>";
}

// Update student information when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix_name = $_POST['suffix_name'];
    $campus = $_POST['campus'];
    $course = $_POST['course']; // Add this line to get course information
    $email_address = $_POST['email_address'];

    // Query to update student information
    $update_query = "UPDATE tb_account SET first_name = '$first_name', middle_name = '$middle_name', last_name = '$last_name', suffix_name = '$suffix_name', campus = '$campus', course = '$course', email_address = '$email_address' WHERE id = $id";

    if (mysqli_query($conn, $update_query)) {
        $msg = "<div class='alert alert-success'>Student information updated successfully.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Error updating student information: " . mysqli_error($conn) . "</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title> Edit Student Infomation </title>
</head>

<style>
    .box-area {
        width: 450px;
        margin-top: 10px;
    }
</style>

<body>
    <?php include 'layouts/admin-navbar.php'; ?>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-4 p-3 bg-white shadow box-area">
            <div class="right-box">
                <h3> Edit Student Infomation </h3>
                <?php echo $msg; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo $middle_name; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="suffix" class="form-label">Suffix</label>
                        <select class="form-select" id="suffix" name="suffix_name">
                            <option value="" <?php if($suffix_name == '') echo 'selected'; ?>>None</option>
                            <option value="Jr" <?php if($suffix_name == 'Jr') echo 'selected'; ?>>Jr</option>
                            <option value="Sr" <?php if($suffix_name == 'Sr') echo 'selected'; ?>>Sr</option>
                            <option value="II" <?php if($suffix_name == 'II') echo 'selected'; ?>>II</option>
                            <option value="III" <?php if($suffix_name == 'III') echo 'selected'; ?>>III</option>
                            <option value="IV" <?php if($suffix_name == 'IV') echo 'selected'; ?>>IV</option>
                        </select>
                    </div>
<div class="mb-3">
    <label for="course" class="form-label">Course</label>
    <select class="form-select" id="course" name="course">
        <option value="" <?php if($suffix_name == '') echo 'selected'; ?>>None</option>
        <option value="BSIT" <?php if($course == 'BSIT') echo 'selected'; ?>>Bachelor of Science in Information Technology</option>
        <option value="BSCS" <?php if($course == 'BSCS') echo 'selected'; ?>>Bachelor of Science in Computer Science</option>
        <option value="BSCE" <?php if($course == 'BSCE') echo 'selected'; ?>>Bachelor of Science in Computer Engineering</option>
        <option value="BSIS" <?php if($course == 'BSIS') echo 'selected'; ?>>Bachelor of Science in Information Science</option>
        <option value="ABCom" <?php if($course == 'ABCom') echo 'selected'; ?>>Bachelor of Arts in Communication (Masscom)</option>
        <option value="ABEng" <?php if($course == 'ABEng') echo 'selected'; ?>>Bachelor of Arts in English</option>
        <option value="ABPolSci" <?php if($course == 'ABPolSci') echo 'selected'; ?>>Bachelor of Arts in Political Science</option>
        <option value="ABPsych" <?php if($course == 'ABPsych') echo 'selected'; ?>>Bachelor of Arts in Psychology</option>
        <option value="BSM" <?php if($course == 'BSM') echo 'selected'; ?>>Bachelor of Sciences in Mathematics</option>
    </select>
</div>
                    <div class="mb-3">
                        <label for="campus" class="form-label">Campus</label>
                        <select class="form-select" id="campus" name="campus">
                            <option value="cainta" <?php if($campus == 'cainta') echo 'selected'; ?>>Cainta (Main)</option>
                            <option value="sanmateo" <?php if($campus == 'sanmateo') echo 'selected'; ?>>San Mateo</option>
                            <option value="antipolo" <?php if($campus == 'antipolo') echo 'selected'; ?>>Antipolo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="email_address" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email_address" name="email_address" value="<?php echo $email_address; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Update</button>
                    <a href="admin-dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>