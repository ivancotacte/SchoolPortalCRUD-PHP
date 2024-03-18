<?php
    session_start();
    if (!isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: index.php");
        die();
    }

    include 'config.php';
    include 'layout/dashboard_navbar.php';
    $msg = "";

    $query = mysqli_query($conn, "SELECT * FROM tb_account WHERE email_address='{$_SESSION['SESSION_EMAIL']}'");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['middle_name'] = $row['middle_name'];
        $_SESSION['last_name'] = $row['last_name'];
        $_SESSION['suffix_name'] = $row['suffix_name'];
        $_SESSION['campus'] = $row['campus'];
        $_SESSION['email_address'] = $row['email_address'];
        $_SESSION['user_role'] = $row['user_role'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title> Dashboard </title>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center">
    <div class="row border rounded-2 p-3 bg-white shadow box-area">
        <div class="col-md-6 rounded-2 d-flex justify-content-center align-items-center flex-column">
            <h1 class="text-center"> Welcome to ICCT Portal </h1>
        </div>
        <div class="col-md-6 right-box">
            <h3> Personal Information </h3>
            <p>ID: <span><?php echo $_SESSION['SESSION_ID']; ?></span></p>
            <p>First Name: <span><?php echo $_SESSION['first_name']; ?></span></p>
            <p>MIddle Name: <span><?php echo $_SESSION['middle_name']; ?></span></p>
            <p>Last Name: <span><?php echo $_SESSION['last_name']; ?></span></p>
            <p>Suffix: <span><?php echo $_SESSION['suffix_name']; ?></span></p>
            <p>Campus: <span><?php echo $_SESSION['campus']; ?></span></p>
            <p>Email Address: <span><?php echo $_SESSION['email_address']; ?></span></p>
            <p>Role: <span><?php echo $_SESSION['user_role']; ?></span></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>