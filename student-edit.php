<?php
session_start();

if (!isset($_SESSION['STUDENT_EMAIL'])) {
    header("Location: index.php");
    die();
}

include 'config/connect.php';
include 'layouts/STUDENT_NAVBAR.php';

$msg = "";

$stmt = $conn->prepare("SELECT * FROM TBL_ACCOUNT WHERE EMAIL_ADDRESS = ?");
$stmt->execute([$_SESSION['STUDENT_EMAIL']]);
$result = $stmt->fetch();

if ($result) {
    $_SESSION['IMAGE'] = $result['IMAGE'];
    $_SESSION['STUDENT_NUMBER'] = $result['STUDENT_NUMBER'];
    $_SESSION['FIRST_NAME'] = $result['FIRST_NAME'];
    $_SESSION['MIDDLE_NAME'] = $result['MIDDLE_NAME'];  
    $_SESSION['LAST_NAME'] = $result['LAST_NAME'];
    $_SESSION['SUFFIX_NAME'] = $result['SUFFIX_NAME'];    
    $_SESSION['CONTACT_NUMBER'] = $result['CONTACT_NUMBER'];
}

if (isset($_POST['submit'])) {
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $suffixName = $_POST['suffixName'];
    $contactNumber = $_POST['contactNumber'];

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($_FILES['image']['size'] == 0 || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $defaultImage = "images/avatar/default-avatar.png";
        $image = $defaultImage;
    } else {
        $imageFolder = "images/avatar/uploaded_img/"; 
        $imageExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $imageName = $_SESSION['STUDENT_NUMBER'] . '.' . $imageExtension;
        $imagePath = $imageFolder . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
        $image = $imagePath;
    }

    if ($password !== $confirmPassword) {
        $msg = "<div class='alert alert-danger' role='alert'>Passwords do not match.</div>";
    } else if (!empty($password)) {
        $hashedPassword = md5($password);

        $stmt = $conn->prepare("UPDATE TBL_ACCOUNT SET IMAGE = ?, FIRST_NAME = ?, MIDDLE_NAME = ?, LAST_NAME = ?, SUFFIX_NAME = ?, CONTACT_NUMBER = ?, PASSWORD = ? WHERE EMAIL_ADDRESS = ?");
        $stmt->execute([$image, $firstName, $middleName, $lastName, $suffixName, $contactNumber, $hashedPassword, $_SESSION['STUDENT_EMAIL']]);
        $msg = "<div class='alert alert-success' role='alert'>Profile updated successfully.</div>";
    } else {
        $stmt = $conn->prepare("UPDATE TBL_ACCOUNT SET IMAGE = ?, FIRST_NAME = ?, MIDDLE_NAME = ?, LAST_NAME = ?, SUFFIX_NAME = ?, CONTACT_NUMBER = ? WHERE EMAIL_ADDRESS = ?");
        $stmt->execute([$image, $firstName, $middleName, $lastName, $suffixName, $contactNumber, $_SESSION['STUDENT_EMAIL']]);
        $msg = "<div class='alert alert-success' role='alert'>Profile updated successfully.</div>";
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
            width: 440px;
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
                    <h3> Edit Profile </h3>
                </div>
                <div class="mb-1">
                    <img src="<?php echo $_SESSION['IMAGE']; ?>" alt="Student Image" class="circular-image mb-5">
                </div>
                <?php echo $msg; ?>
                <form id="myForm" action="" method="post" enctype="multipart/form-data" novalidate>
                        <label class="form-label">Profile Picture:</label>
                        <div class="input-group mb-2">
                            <input type="file" name="image" class="form-control bg-light fs-6" accept="image/jpg, image/jpeg, image/png" />
                        </div>
                        <label class="form-label">First Name:</label>
                        <div class="input-group mb-2">
                            <input type="text" name="firstName" value="<?php echo $_SESSION['FIRST_NAME']; ?>" class="form-control bg-light fs-6" placeholder="Juan" required />
                            <div class="invalid-feedback">Please enter your first name.</div>
                        </div>
                        <label class="form-label">Middle Name:</label>
                        <div class="input-group mb-2">
                            <input type="text" name="middleName" value="<?php echo $_SESSION['MIDDLE_NAME']; ?>" class="form-control bg-light fs-6" placeholder="" />
                        </div>
                        <label class="form-label">Last Name:</label>
                        <div class="input-group mb-2">
                            <input type="text" name="lastName" value="<?php echo $_SESSION['LAST_NAME']; ?>" class="form-control bg-light fs-6" placeholder="Dela Cruz" required />
                            <div class="invalid-feedback">Please enter your last name.</div>
                        </div>
                        <label class="form-label">Suffix:</label>
                        <div class="input-group mb-2">
                            <select name="suffixName" id="suffixName" class="form-select bg-light fs-6">
                                <option value="">Select suffix</option>
                                <option value="jr" <?php echo ($_SESSION['SUFFIX_NAME'] == 'jr') ? 'selected' : ''; ?>>Jr</option>
                                <option value="sr" <?php echo ($_SESSION['SUFFIX_NAME'] == 'sr') ? 'selected' : ''; ?>>Sr</option>
                                <option value="II" <?php echo ($_SESSION['SUFFIX_NAME'] == 'II') ? 'selected' : ''; ?>>II</option>
                                <option value="III" <?php echo ($_SESSION['SUFFIX_NAME'] == 'III') ? 'selected' : ''; ?>>III</option>
                                <option value="IV" <?php echo ($_SESSION['SUFFIX_NAME'] == 'IV') ? 'selected' : ''; ?>>IV</option>
                            </select>
                        </div>
                        <label class="form-label">Contact Number:</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control bg-light fs-6" name="contactNumber" value="<?php echo $_SESSION['CONTACT_NUMBER']; ?>" id="phone" pattern="[0-9]{11}" placeholder="Enter Phone Number" />
                            <div class="invalid-feedback">Please enter a valid 11-digit phone number.</div>
                        </div>
                        <label class="form-label">Password:</label>
                        <div class="input-group mb-2">
                            <input type="password" name="password" class="form-control bg-light fs-6" placeholder="********" />
                            <div class="invalid-feedback">Please enter your password.</div>
                        </div>
                        <label class="form-label">Confirm Password:</label>
                        <div class="input-group mb-3">
                            <input type="password" name="confirmPassword" class="form-control bg-light fs-6" placeholder="********" />
                        </div>
                        <div class="input-group mb-2">
                            <button type="submit" name="submit" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;">
                                Submit
                            </button>
                        </div>
                        <div class="input-group mb-2">
                            <button type="button" onclick="window.location.href = 'student-dashboard.php';" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;"> Back </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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

            let passwordInput = document.getElementsByName("password")[0];
            let confirmPasswordInput = document.getElementsByName("confirmPassword")[0];
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity("Passwords do not match.");
            } else {
                confirmPasswordInput.setCustomValidity("");
            }

            form.classList.add('was-validated');
        });
    </script>
</body>
</html>