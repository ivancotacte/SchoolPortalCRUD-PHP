<?php
session_start();

require_once realpath(__DIR__ . "/vendor/autoload.php");
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!isset($_SESSION['ADMIN_EMAIL'])) {
    header("Location: index.php");
    die();
}

include 'config/connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $middleName = mysqli_real_escape_string($conn, $_POST['middleName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $suffix = mysqli_real_escape_string($conn, $_POST['suffix']);
    $campus = mysqli_real_escape_string($conn, $_POST['campus']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = generateRandomPassword();

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'images/avatar/uploaded_img/' . $image;

    $select = "SELECT * FROM tb_account WHERE email_address='{$email}'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $msg = "<div class='alert alert-danger'>Email already exists.</div>";
    } elseif (empty($_POST['email'])) {
        $msg = "<div class='alert alert-danger'>Please provide an email address.</div>";
    } else {
        if ($image_size > 2000000) {
            $msg = "<div class='alert alert-danger'>Image size must be less than 2mb.</div>";
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $current_time = date("Y-m-d H:i:s");
            $sql = "INSERT INTO tb_account (first_name, middle_name, last_name, suffix_name, campus, course, contact_number, email_address, password, image, created_at) VALUES ('{$firstName}', '{$middleName}', '{$lastName}', '{$suffix}', '{$campus}', '{$course}', '{$contact_number}', '{$email}', '{$password}' , '$image', '$current_time')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "<div class='alert alert-success'>Student has been added successfully.</div>";
                $subject = "Account Created";
                $message = "Hello $firstName $lastName,\n\nYour account has been created. Below are your login credentials:\n\nEmail: $email\nPassword: $password\n\nPlease keep this information secure. If you receive this email, please change your password.\n\nBest regards,\nThe Admin Team";
                sendEmail($email, $subject, $message);
            } else {
                $msg = "<div class='alert alert-danger'>Failed to create account.</div>";
            }
        }
    }
}

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .box-area {
            width: 450px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include 'layouts/admin-navbar.php'; ?>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-4 p-3 bg-white shadow box-area">
            <div class="right-box">
                <div class="header-text mb-4">
                    <h2>Add Student</h2>
                </div>
                <?php echo $msg; ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <label class="form-label">Profile Picture:</label>
                    <div class="input-group mb-2">
                        <input type="file" name="image" class="form-control bg-light fs-6" accept="image/jpg, image/jpeg, image/png" />
                    </div>
                    <label class="form-label">First Name:</label>
                    <div class="input-group mb-2">
                        <input type="text" name="firstName" class="form-control bg-light fs-6" placeholder="Juan" required />
                    </div>
                    <label class="form-label">Middle Name:</label>
                    <div class="input-group mb-2">
                        <input type="text" name="middleName" class="form-control bg-light fs-6" placeholder="Martinez" />
                    </div>
                    <label class="form-label">Last Name:</label>
                    <div class="input-group mb-2">
                        <input type="text" name="lastName" class="form-control bg-light fs-6" placeholder="Dela Cruz" required />
                    </div>
                    <label class="form-label">Suffix:</label>
                    <div class="input-group mb-2">
                        <select name="suffix" id="suffix" class="form-select bg-light fs-6">
                            <option value="">Suffix</option>
                            <option value="jr">Jr</option>
                            <option value="sr">Sr</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>
                    </div>
                    <label class="form-label">Course:</label>
                    <div class="input-group mb-2">
                        <select id="course" name="course" class="form-select bg-light fs-6" required>
                            <option value=""> None </option>
                            <option value="BSIT"> BSIT - Bachelor of Science in Information Technology </option>
                            <option value="BSCS"> BSCS - Bachelor of Science in Computer Science </option>
                            <option value="BSCE"> BSCE - Bachelor of Science in Computer Engineering </option>
                            <option value="BSIS"> BSIS - Bachelor of Science in Information Science </option>
                            <option value="ABCom"> ABCom - Bachelor of Arts in Communication (Masscom) </option>
                            <option value="ABEng"> ABEng - Bachelor of Arts in English </option>
                            <option value="ABPolSci"> ABPolSci - Bachelor of Arts in Political Science< /option>
                            <option value="ABPsych" >ABPsych - Bachelor of Arts in Psychology </option>
                            <option value="BSM"> BSM - Bachelor of Sciences in Mathematics </option>
                        </select>
                    </div>
                    <label class="form-label">Campus:</label>
                    <div class="input-group mb-2">
                        <select id="campus" name="campus" class="form-control bg-light fs-6" required>
                            <option value="cainta">Cainta (Main)</option>
                            <option value="sanmateo">San Mateo</option>
                            <option value="antipolo">Antipolo</option>
                        </select>
                    </div>
                    <label class="form-label">Contact Number:</label>
                    <div class="input-group mb-2">
                        <input type="number" name="contact_number" class="form-control bg-light fs-6" required />
                    </div>
                    <label class="form-label">Email address:</label>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control bg-light fs-6" placeholder="example@example.com" required />
                    </div>
                    <div class="input-group mb-2">
                        <button type="submit" name="submit" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;">
                            Submit
                        </button>
                    </div>
                </form>
                <div class="input-group mb-2">
                    <button type="button" onclick="window.location.href = 'admin-dashboard.php';" class="btn w-100 fs-6" style="background-color: #030067; color: #ececec;">Back</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>