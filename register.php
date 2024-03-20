<?php
include 'config/connect.php';

$msg = "";

if(isset($_POST['submit'])){
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $middleName = mysqli_real_escape_string($conn, $_POST['middleName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $suffix = mysqli_real_escape_string($conn, $_POST['suffix']);
    $campus = mysqli_real_escape_string($conn, $_POST['campus']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, ($_POST['password']));

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'images/avatar/uploaded_img/'.$image;

    $select = "SELECT * FROM tb_account WHERE email_address='{$email}'";
    $result = mysqli_query($conn, $select);

    if(mysqli_num_rows($result) > 0){
        $msg = "<div class='alert alert-danger'>Email already exists.</div>";
    } else {
        if ($image_size > 2000000) {
            $msg = "<div class='alert alert-danger'>Image size must be less than 2mb.</div>";
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $sql = "INSERT INTO tb_account (first_name, middle_name, last_name, suffix_name, campus, email_address, password, image) VALUES ('{$firstName}', '{$middleName}', '{$lastName}', '{$suffix}', '{$campus}', '{$email}', '{$password}' , '$image')";
            $result = mysqli_query($conn, $sql);

            if($result){
                $msg = "<div class='alert alert-success'>Account successfully created.</div>";
            } else {
                $msg = "<div class='alert alert-danger'>Failed to create account.</div>";
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
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">ICCT Portal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent" style="margin-right: 70px;">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Register</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container d-flex justify-content-center align-items-center"> 
    <div class="row border rounded-2 p-3 bg-white shadow box-area">
      <div class="col-md-6 rounded-2 d-flex justify-content-center align-items-center flex-column left-box"
        style="background: #030067">
        <div class="featured-image mb-3">
          <img src="images/icct_logo.png" class="img-fluid" />
        </div>
      </div>
      <div class="col-md-6 right-box">
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
                <input type="text" name="firstName" class="form-control bg-light fs-6" placeholder="Juan" />
              </div>
              <label class="form-label">Middle Name:</label>
              <div class="input-group mb-2">
                <input type="text" name="middleName" class="form-control bg-light fs-6" placeholder="Martinez" />
              </div>
              <label class="form-label">Last Name:</label>
              <div class="input-group mb-2">
                <input type="text" name="lastName" class="form-control bg-light fs-6" placeholder="Dela Cruz" />
              </div>
              <label class="form-label">Suffix:</label>
              <div class="input-group mb-2">
                <select name="suffix" id="suffix" class="form-control bg-light fs-6">
                  <option value="">Suffix</option>
                  <option value="jr">Jr</option>
                  <option value="sr">Sr</option>
                  <option value="II">II</option>
                  <option value="III">III</option>
                  <option value="IV">IV</option>
                </select>
              </div>
              <label class="form-label">Campus:</label>
              <div class="input-group mb-2">
                <select id="campus" name="campus" class="form-control bg-light fs-6">
                  <option value="cainta">Cainta (Main)</option>
                  <option value="sanmateo">San Mateo</option>
                  <option value="antipolo">Antipolo</option>
                </select>
              </div>
              <label class="form-label">Email address:</label>
              <div class="input-group mb-2">
                <input type="email" name="email" class="form-control bg-light fs-6" placeholder="example@example.com" />
              </div>
              <label class="form-label">Password:</label>
              <div class="input-group mb-3">
                <input type="password" name="password" class="form-control bg-light fs-6" placeholder="********" />
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