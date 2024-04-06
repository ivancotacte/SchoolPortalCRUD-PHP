<?php
function generateRandomNumber() {
    return str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
}

include 'config/connect.php';
include 'layouts/HOME_NAVBAR.php';

$msg = "";

if(isset($_POST['submit'])){
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $middleName = mysqli_real_escape_string($conn, $_POST['middleName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $suffix = mysqli_real_escape_string($conn, $_POST['suffix']);
    $campus = mysqli_real_escape_string($conn, $_POST['campus']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $confirmPassword = mysqli_real_escape_string($conn, md5($_POST['confirmPassword']));

    $passwordStrength = strlen($_POST['password']) < 8 ? "Weak" : "Strong";

    if ($password !== $confirmPassword) {
        $msg = "<div class='alert alert-danger'>Passwords do not match.</div>";
    } else {
        $randomNumber = generateRandomNumber();
        $studentNumber = strtoupper($lastName) . $randomNumber;

        $image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_folder = 'images/avatar/uploaded_img/' . $studentNumber . '.' . $image_extension;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = "<div class='alert alert-danger'>Invalid email format.</div>";
        } else {
            $select = "SELECT * FROM TBL_ACCOUNT WHERE EMAIL_ADDRESS='{$email}'";
            $result = mysqli_query($conn, $select);

            if(mysqli_num_rows($result) > 0){
                $msg = "<div class='alert alert-danger'>Email already exists.</div>";
            } else {
                if ($_FILES['image']['size'] > 2000000) {
                    $msg = "<div class='alert alert-danger'>Image size must be less than 2mb.</div>";
                } else {
                    move_uploaded_file($_FILES['image']['tmp_name'], $image_folder);
                    $current_time = date("Y-m-d H:i:s");

                    $sql = "INSERT INTO TBL_ACCOUNT (STUDENT_NUMBER, FIRST_NAME, MIDDLE_NAME, LAST_NAME, SUFFIX_NAME, CAMPUS, COURSE, CONTACT_NUMBER, EMAIL_ADDRESS, PASSWORD, IMAGE, CREATED_AT) VALUES ('$studentNumber', '$firstName', '$middleName', '$lastName', '$suffix', '$campus', '$course', '$contact_number', '$email', '$password' , '$image_folder', '$current_time')";
                    $result = mysqli_query($conn, $sql);

                    if($result){
                        $msg = "<div class='alert alert-success'>Account successfully created.</div>";
                    } else {
                        $msg = "<div class='alert alert-danger'>Failed to create account.</div>";
                    }
                }
            }
        }
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
    <title>Register</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100"> 
        <div class="row border rounded-4 p-3 bg-white shadow box-area">
            <div class="col-md-5 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #030067">
                <div class="featured-image mb-3">
                    <img src="images/icct_logo.png" class="img-fluid" />
                </div>
            </div>
            <div class="col-md-7 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>Student Register</h2>
                        <p>To register ICCT Portal, please make sure you meet the following requirements:</p>
                    </div>
                    <div>
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
                            <label class="form-label"> Course: </label>
                            <div class="input-group mb-2">
                                <select id="course" name="course" class="form-select bg-light fs-6" required>
                                    <option value=""></option>
                                    <option value="BSIT"> BSIT - Bachelor of Science in Information Technology </option>
                                    <option value="BSCS"> BSCS - Bachelor of Science in Computer Science </option>
                                    <option value="BSCE"> BSCE - Bachelor of Science in Computer Engineering </option>
                                    <option value="BSIS"> BSIS - Bachelor of Science in Information Science </option>
                                    <option value="ABCom"> ABCom - Bachelor of Arts in Communication (Masscom) </option>
                                    <option value="ABEng"> ABEng - Bachelor of Arts in English </option>
                                    <option value="ABPolSci"> ABPolSci - Bachelor of Arts in Political Science </option>
                                    <option value="ABPsych"> ABPsych - Bachelor of Arts in Psychology </option>
                                    <option value="BSM"> BSM - Bachelor of Sciences in Mathematics </option>
                                </select>
                            </div>
                            <label class="form-label">Campus:</label>
                            <div class="input-group mb-2">
                                <select id="campus" name="campus" class="form-select bg-light fs-6" required>
                                    <option value="cainta">Cainta (Main)</option>
                                    <option value="sanmateo">San Mateo</option>
                                    <option value="antipolo">Antipolo</option>
                                </select>
                            </div>
                            <label class="form-label"> Contact Number: </label>
                            <div class="input-group mb-2">
                                <input type="number" name="contact_number" class="form-control bg-light fs-6" required />
                            </div>
                            <label class="form-label">Email address:</label>
                            <div class="input-group mb-2">
                                <input type="email" name="email" class="form-control bg-light fs-6" placeholder="example@example.com" required />
                            </div>
                            <label class="form-label">Password:</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control bg-light fs-6" placeholder="********" required />
                            </div>
                            <label class="form-label">Confirm Password:</label>
                            <div class="input-group mb-3">
                                <input type="password" name="confirmPassword" class="form-control bg-light fs-6" placeholder="********" required />
                            </div>
                            <div class="input-group mb-2">
                                <button type="submit" name="submit" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;">
                                    Submit
                                </button>
                            </div>
                        </form>
                        <div class="input-group mb-2">
                            <button type="button" onclick="window.location.href = 'index.php';" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;"> Back to Login </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>