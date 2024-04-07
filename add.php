<?php
session_start();

if (!isset($_SESSION['OWNER_EMAIL'])) {
    header("Location: index.php");
    die();
} else if (isset($_SESSION['ADMIN_EMAIL'])) {
    header("Location: admin-dashboard.php");
    die();
} 

include 'config/connect.php';
include 'layouts/OWNER_NAVBAR.php'; 

$msg = "";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $suffix = $_POST['suffix'];
    $course = $_POST['course'];
    $campus = $_POST['campus'];
    $contactNum = $_POST['contactNum'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM TBL_ACCOUNT WHERE EMAIL_ADDRESS = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $msg = "<div class='alert alert-danger' role='alert'>Email address already exists.</div>";
    } else {
            if ($_FILES['image']['size'] == 0 || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $defaultImage = "images/avatar/default-avatar.png";
                $image = $defaultImage;
            } else {
                $imageFolder = "images/avatar/uploaded_img/"; 
                $imageExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $imageName = $studentNumber . '.' . $imageExtension;
                $imagePath = $imageFolder . $imageName;
                move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
                $image = $imagePath;
            }
            $password = generateRandomPassword();
            $hashedPassword = md5($password);

            $randomNumber = generateRandomNumber();
            $studentNumber = strtoupper($lastName) . $randomNumber;

            $current_time = date("Y-m-d H:i:s");

            $stmt = $conn->prepare("INSERT INTO TBL_ACCOUNT (STUDENT_NUMBER, FIRST_NAME, MIDDLE_NAME, LAST_NAME, SUFFIX_NAME, COURSE, CAMPUS, CONTACT_NUMBER, EMAIL_ADDRESS, PASSWORD, IMAGE, CREATED_AT) VALUES (:studentNumber, :firstName, :middleName, :lastName, :suffix, :course, :campus, :contactNum, :email, :hashedPassword, :profilePicture, :created_at)");
            $stmt->bindParam(':studentNumber', $studentNumber);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':middleName', $middleName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':course', $course);
            $stmt->bindParam(':campus', $campus);
            $stmt->bindParam(':contactNum', $contactNum);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':hashedPassword', $hashedPassword);
            $stmt->bindParam(':profilePicture', $image);
            $stmt->bindParam(':created_at', $current_time);
            $stmt->execute();

            $subject = "Account Registration";
            $message = "Your account has been successfully created. Here are your login credentials:\n\nStudent Number: $studentNumber\nEmail: $email\nPassword: $password\n\nPlease keep this information secure. If you receive this email, please change your password.";
            sendEmail($email, $subject, $message);
    }
}

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['GMAIL_EMAIL'];
        $mail->Password   = $_ENV['GMAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('cotactearmenion@gmail.com', 'GROUP 10 - LFSA322N002');
        $mail->addAddress($to);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        
        $msg = "<div class='alert alert-success'>Student has been added successfully.</div>";
    } catch (Exception $e) {
        $msg = "<div class='alert alert-danger'>An error occurred while sending the email.</div>";
    }
}

function generateRandomNumber() {
    return str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
}

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title> Add Student </title>
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
                    <h3> Add Student </h3>
                </div>
                <?php echo $msg; ?>
                <form id="myForm" action="" method="post" enctype="multipart/form-data" novalidate>
                        <label class="form-label">Profile Picture:</label>
                        <div class="input-group mb-2">
                            <input type="file" name="image" class="form-control bg-light fs-6" accept="image/jpg, image/jpeg, image/png" />
                        </div>
                        <label class="form-label">First Name:</label>
                        <div class="input-group mb-2">
                            <input type="text" name="firstName" class="form-control bg-light fs-6" placeholder="Juan" required />
                            <div class="invalid-feedback">Please enter your first name.</div>
                        </div>
                        <label class="form-label">Middle Name:</label>
                        <div class="input-group mb-2">
                            <input type="text" name="middleName" class="form-control bg-light fs-6" placeholder="" />
                        </div>
                        <label class="form-label">Last Name:</label>
                        <div class="input-group mb-2">
                            <input type="text" name="lastName" class="form-control bg-light fs-6" placeholder="Dela Cruz" required />
                            <div class="invalid-feedback">Please enter your last name.</div>
                        </div>
                        <label class="form-label">Suffix:</label>
                        <div class="input-group mb-2">
                            <select name="suffix" id="suffix" class="form-select bg-light fs-6">
                                <option value="">Select suffix</option>
                                <option value="jr">Jr</option>
                                <option value="sr">Sr</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                        <label class="form-label">Course:</label>
                        <div class="input-group mb-2">
                            <select name="course" id="course" class="form-select bg-light fs-6" required>
                                <option value="">Select course</option>
                                <option value="BSIT">BSIT - Bachelor of Science in Information Technology</option>
                                <option value="BSCS">BSCS - Bachelor of Science in Computer Science</option>
                                <option value="BSCE">BSCE - Bachelor of Science in Computer Engineering</option>
                                <option value="BSIS">BSIS - Bachelor of Science in Information Science</option>
                                <option value="ABCom">ABCom - Bachelor of Arts in Communication (Masscom)</option>
                                <option value="ABEng">ABEng - Bachelor of Arts in English</option>
                                <option value="ABPolSci">ABPolSci - Bachelor of Arts in Political Science</option>
                                <option value="ABPsych">ABPsych - Bachelor of Arts in Psychology</option>
                                <option value="BSM">BSM - Bachelor of Sciences in Mathematics</option>
                            </select>
                            <div class="invalid-feedback">Please select your course.</div>
                        </div>
                        <label class="form-label">Campus:</label>
                        <div class="input-group mb-2">
                            <select name="campus" id="campus" class="form-select bg-light fs-6" required>
                                <option value="">Select campus</option>
                                <option value="cainta">Cainta (Main)</option>
                                <option value="cubao">Cubao</option>
                                <option value="sanmateo">San Mateo</option>
                                <option value="antipolo">Antipolo</option>
                                <option value="binangonan">Binangonan</option>
                            </select>
                            <div class="invalid-feedback">Please select your campus.</div>
                        </div>  
                        <label class="form-label">Contact Number:</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control bg-light fs-6" name="contactNum" id="phone" pattern="[0-9]{11}" placeholder="Enter Phone Number" />
                            <div class="invalid-feedback">Please enter a valid 11-digit phone number.</div>
                        </div>
                        <label class="form-label">Email address:</label>
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control bg-light fs-6" placeholder="example@example.com" required />
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                        <div class="input-group mb-2">
                            <button type="submit" name="submit" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;">Submit</button>
                        </div>
                        <div class="input-group mb-2">
                            <button type="button" onclick="window.location.href = 'student-dashboard.php';" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;">Back</button>
                        </div>
                </div>
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