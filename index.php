<?php

    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: dashboard.php");
        die();
    }

    include 'config.php';
    $msg = "";

    if(isset($_POST['submit'])){
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));

        $select = "SELECT * FROM tb_account WHERE email_address='{$email}' AND password='{$password}'";
        $result = mysqli_query($conn, $select);

        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);

            if($row['user_role'] == 'admin'){
                header("Location: admin.php");
                die();
            } else if ($row['user_role'] == 'student'){
                $_SESSION['SESSION_EMAIL'] = $row['email_address'];
                $_SESSION['SESSION_ID'] = $row['id'];

                header("Location: dashboard.php");
                die();
            } 

        } else {
            $msg = "<div class='alert alert-danger'>Invalid email or password.</div>";
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
    <title> LogIn </title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"> ICCT Portal </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent" style="margin-right: 70px;">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php"> Login </a></li>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php"> Register </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row border rounded-4 p-3 bg-white shadow box-area">
      <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box"
        style="background: #030067">
        <div class="featured-image mb-3">
          <img src="image/icct_logo.png" class="img-fluid" />
        </div>
      </div>
      <div class="col-md-6 right-box">
        <div class="row align-items-center">
          <div class="header-text mb-4">
            <h2>Log In</h2>
            <p>Welcone to ICCT Portal, To access ICCT Portal, please make sure you meet the following
              requirements:</p>
          </div>
          <?php echo $msg; ?>
          <form action="" method="post">
            <label class="form-label"> Email address / Student ID No. </label>
            <div class="input-group mb-2">
              <input type="email" name="email" class="form-control bg-light fs-6"
                placeholder="example@example.com" />
            </div>
            <label class="form-label"> Password: </label>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control bg-light fs-6"
                placeholder="********" />
            </div>
            <div class="input-group mb-2">
              <button type="submit" name="submit" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;">
                LogIn
              </button>
            </div>
          </form>
          <div class="input-group mb-2">
            <button class="btn btn-lg btn-light w-100 fs-6 ">
              <img src="image/google_logo.png" style="width: 20px" class="me-2" /><small>Sign In with Google</small>
            </button>
          </div>
          <div class="input-group mb-2">
            <button class="btn btn-lg btn-light w-100 fs-6 ">
              <img src="image/microsoft_logo.png" style="width: 20px" class="me-2" /><small>Sign In with Google</small>
            </button>
          </div>
        </div>
      </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>