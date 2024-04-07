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
    $_SESSION['STUDENT_NUMBER'] = $result['STUDENT_NUMBER'];
    $_SESSION['FIRST_NAME'] = $result['FIRST_NAME'];
    $_SESSION['MIDDLE_NAME'] = $result['MIDDLE_NAME'];
    $_SESSION['LAST_NAME'] = $result['LAST_NAME'];  
    $_SESSION['SUFFIX_NAME'] = $result['SUFFIX_NAME'];
    $_SESSION['COURSE'] = $result['COURSE'];

    switch ($result['CAMPUS']) {
        case 'sanmateo':
            $_SESSION['CAMPUS'] = 'San Mateo';
            break;
        case 'antipolo':
            $_SESSION['CAMPUS'] = 'Antipolo';  
            break;
        case 'cainta':
            $_SESSION['CAMPUS'] = 'Cainta';
            break;
    }

    $_SESSION['CONTACT_NUMBER'] = $result['CONTACT_NUMBER'];
    $_SESSION['EMAIL_ADDRESS'] = $result['EMAIL_ADDRESS'];
    $_SESSION['USER_ROLE'] = $result['USER_ROLE'];
} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Student Dashboard</title>
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
                    <h3>Personal Information</h3>
                </div>
                <img src="<?php echo $image; ?>" alt="Student Image" class="circular-image mb-5">

                <div class="mb-1">
                    <p><strong>Student Number:</strong> <?php echo $_SESSION['STUDENT_NUMBER']; ?></p>
                </div>
                <div class="mb-1">
                    <p><strong>First Name:</strong> <?php echo $_SESSION['FIRST_NAME']; ?></p>
                </div>
                <div class="mb-1">
                    <p><strong>Middle Name:</strong> <?php echo $_SESSION['MIDDLE_NAME']; ?></p>
                </div>
                <div class="mb-1">
                    <p><strong>Last Name:</strong> <?php echo $_SESSION['LAST_NAME']; ?></p>
                </div>
                <div class="mb-1">
                    <p><strong>Suffix Name:</strong> <?php echo $_SESSION['SUFFIX_NAME']; ?></p>
                </div>
                <div class="mb-1">
                    <p><strong>Course:</strong> <?php echo $_SESSION['COURSE']; ?></p>
                </div>
                <div class="mb-1">
                    <p><strong>Campus:</strong> <?php echo $_SESSION['CAMPUS']; ?></p>
                </div>
                <div class="mb-1">
                    <p><strong>Contact Number:</strong> <?php echo $_SESSION['CONTACT_NUMBER']; ?></p>
                </div>
                <div class="mb-1">
                    <p><strong>Email Address:</strong> <?php echo $_SESSION['EMAIL_ADDRESS']; ?></p>
                </div>
                <div class="mb-3">
                    <p><strong>User Role:</strong> <?php echo $_SESSION['USER_ROLE']; ?></p>
                </div>


                <div class="input-group mb-2">
                    <button type="button" onclick="window.location.href = 'student-edit.php';" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;"> Edit Profile </button>
                </div>
                <div class="input-group mb-2">
                    <button type="button" onclick="window.location.href = 'logout.php';" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;"> Logout </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>