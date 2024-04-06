<?php
session_start();

include 'config/connect.php';
include 'layouts/STUDENT_NAVBAR.php';

if (!isset($_SESSION['STUDENT_EMAIL'])) {
    header("Location: index.php");
    die();
}

$msg = "";

$select = mysqli_query($conn, "SELECT * FROM TBL_ACCOUNT WHERE EMAIL_ADDRESS='{$_SESSION['STUDENT_EMAIL']}'") or die('query failed');
if(mysqli_num_rows($select) > 0) {
    $row = mysqli_fetch_assoc($select);
    $_SESSION['FIRST_NAME'] = $row['FIRST_NAME'];
    $_SESSION['MIDDLE_NAME'] = $row['MIDDLE_NAME'];
    $_SESSION['LAST_NAME'] = $row['LAST_NAME'];
    $_SESSION['SUFFIX_NAME'] = $row['SUFFIX_NAME'];
    $_SESSION['CONTACT_NUMBER'] = $row['CONTACT_NUMBER'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $suffixName = $_POST['suffixName'];
    $contactNum = $_POST['contactNum'];

    $newPassword = md5($_POST['newPassword']);
    $confirmPassword = md5($_POST['confirmPassword']);

    if ($newPassword != $confirmPassword) {
        $msg = "<div class='alert alert-danger'>Password does not match!</div>";
    } else if (!empty($newPassword)) {
        $update = "UPDATE TBL_ACCOUNT SET FIRST_NAME='{$firstName}', MIDDLE_NAME='{$middleName}', LAST_NAME='{$lastName}', SUFFIX_NAME='{$suffixName}', CONTACT_NUMBER='{$contactNum}', PASSWORD='{$newPassword}' WHERE EMAIL_ADDRESS='{$_SESSION['STUDENT_EMAIL']}'";

        if (mysqli_query($conn, $update)) {
            $msg = "<div class='alert alert-success'>Profile updated successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Failed to update profile!</div>";
        }

    } else {
        $update = "UPDATE TBL_ACCOUNT SET FIRST_NAME='{$firstName}', MIDDLE_NAME='{$middleName}', LAST_NAME='{$lastName}', SUFFIX_NAME='{$suffixName}', CONTACT_NUMBER='{$contactNum}' WHERE EMAIL_ADDRESS='{$_SESSION['STUDENT_EMAIL']}'";

        if (mysqli_query($conn, $update)) {
            $msg = "<div class='alert alert-success'>Profile updated successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Failed to update profile!</div>";
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .box-area {
            width: 450px;
            margin-top: 10px;
        }

        .circular-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-4 p-3 bg-white shadow box-area">
            <div class="right-box">
                <div class="header-text mb-4">
                    <h3>Update Profile Information</h3>
                </div>
                <?php echo $msg; ?>
                <form id="myForm" action="" method="post" enctype="multipart/form-data" novalidate>
                    <img src="<?php echo $image; ?>" alt="Student Image" class="circular-image mb-5">
                    <label class="form-label">Profile Picture:</label>
                    <div class="input-group mb-2">
                        <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="form-control box">
                        <div class="invalid-feedback">Please select a valid image file (jpg, jpeg, png).</div>
                    </div>
                    <label class="form-label">First Name:</label>
                    <div class="input-group mb-2">
                        <input type="text" name="firstName" class="form-control bg-light fs-6" value="<?php echo $_SESSION['FIRST_NAME']; ?>" placeholder="Juan" required />
                        <div class="invalid-feedback">Please enter your first name.</div>
                    </div>
                    <label class="form-label">Middle Name:</label>
                    <div class="input-group mb-2">
                        <input type="text" name="middleName" class="form-control bg-light fs-6" value="<?php echo $_SESSION['MIDDLE_NAME']; ?>" placeholder="" />
                    </div>
                    <label class="form-label">Last Name:</label>
                    <div class="input-group mb-2">
                        <input type="text" name="lastName" class="form-control bg-light fs-6" value="<?php echo $_SESSION['LAST_NAME']; ?>" placeholder="Dela Cruz" required />
                        <div class="invalid-feedback">Please enter your last name.</div>
                    </div>
                    <label class="form-label">Suffix:</label>
                    <div class="input-group mb-2">
                        <select name="suffixName" id="suffixName" class="form-select bg-light fs-6">
                            <option value="jr">Jr</option>
                            <option value="sr">Sr</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>
                    </div>
                    <label class="form-label">Contact Number:</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control bg-light fs-6" name="contactNum" id="phone" value="<?php echo $_SESSION['CONTACT_NUMBER']; ?>" required pattern="[0-9]{11}" placeholder="Enter Phone Number" />
                        <div class="invalid-feedback">Please enter a valid 11-digit phone number.</div>
                    </div>
                    <label class="form-label">New Password:</label>
                    <div class="input-group mb-2">
                        <input type="password" name="newPassword" class="form-control bg-light fs-6" placeholder="Enter New Password" />
                    </div>
                    <label class="form-label">Confirm Password:</label>
                    <div class="input-group mb-2">
                        <input type="password" name="confirmPassword" class="form-control bg-light fs-6" placeholder="Confirm Password" />
                    </div>
                    <div class="input-group mb-2">
                        <button type="submit" name="submit" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;">
                            Submit
                        </button>
                    </div>
                </form>
                <div class="input-group mb-2">
                    <button type="button" onclick="window.location.href = 'student-dashboard.php';" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;"> Back </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        'use strict'
        var form = document.getElementById("myForm");

        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            let phoneInput = document.getElementById("phone");
            let phoneRegex = /^[0-9]{11}$/;
            if (!phoneRegex.test(phoneInput.value)) {
                phoneInput.setCustomValidity("Please enter a valid 11-digit phone number.");
            } else {
                phoneInput.setCustomValidity("");
            }

            form.classList.add('was-validated');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>