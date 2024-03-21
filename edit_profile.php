<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include 'config/connect.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix_name = $_POST['suffix_name'];
    $contact_number = $_POST['contact_number']; 

    $new_password = mysqli_real_escape_string($conn, md5($_POST['new_password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));

    if ($new_password != $confirm_password) {
        $msg = "New password and confirm password do not match";
    } elseif (!empty($new_password)) {
       $update_query = "UPDATE tb_account SET first_name='$first_name', middle_name='$middle_name', last_name='$last_name', suffix_name='$suffix_name', password='$new_password', contact_number='$contact_number' WHERE email_address='{$_SESSION['SESSION_EMAIL']}'";

        if (mysqli_query($conn, $update_query)) {
            $msg = "Personal information and password updated successfully";

            $_SESSION['first_name'] = $first_name;
            $_SESSION['middle_name'] = $middle_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['suffix_name'] = $suffix_name;
            $_SESSION['contact_number'] = $contact_number; 
        } else {
            $msg = "Error updating information: " . mysqli_error($conn);
        }
    } else {
        $update_query = "UPDATE tb_account SET first_name='$first_name', middle_name='$middle_name', last_name='$last_name', suffix_name='$suffix_name', contact_number='$contact_number' WHERE email_address='{$_SESSION['SESSION_EMAIL']}'";

        if (mysqli_query($conn, $update_query)) {
            $msg = "Personal information updated successfully";

            $_SESSION['first_name'] = $first_name;
            $_SESSION['middle_name'] = $middle_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['suffix_name'] = $suffix_name;
            $_SESSION['contact_number'] = $contact_number; 
        } else {
            $msg = "Error updating information: " . mysqli_error($conn);
        }
    }

    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'images/avatar/uploaded_img/'.$update_image;

    if(!empty($update_image)){
        if ($update_image_size > 2000000) {
            $msg = "Image size must be less than 2mb.";
        } else {
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
            $update_image_query = "UPDATE tb_account SET image='$update_image' WHERE email_address='{$_SESSION['SESSION_EMAIL']}'";

            if (mysqli_query($conn, $update_image_query)) {
                $msg = "Profile picture updated successfully";
            } else {
                $msg = "Error updating profile picture: " . mysqli_error($conn);
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
    <title>Dashboard</title>
</head>
<style>
  .box-area {
    width: 450px;
    margin-top: 10px;
  }

  .profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
  }
</style>
<body>
<?php
    include 'layouts/student-navbar.php';
?>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row border rounded-4 p-3 bg-white shadow box-area">
        <div class="right-box">
            <h3>Update Profile Information</h3>
            <?php if ($msg): ?>
                <div class="alert alert-info"><?php echo $msg; ?></div>
            <?php endif; ?>
            <form action="" method="post" enctype="multipart/form-data">
              <?php
                  $select = mysqli_query($conn, "SELECT * FROM `tb_account` WHERE id = '{$_SESSION['SESSION_ID']}'") or die('query failed');
                  if(mysqli_num_rows($select) > 0){
                      $fetch = mysqli_fetch_assoc($select);
                  }
                  if($fetch['image'] == ''){
                      echo '<div class="input-group mb-2 justify-content-center align-items-center">';
                      echo '<img src="images/avatar/default-avatar.png" alt="Profile Picture" class="profile-picture" >';
                      echo '</div>';
                  }else{
                      echo '<div class="input-group mb-2 justify-content-center align-items-center">';
                      echo '<img src="images/avatar/uploaded_img/'.$fetch['image'].'" alt="Profile Picture" class="profile-picture">';
                      echo '</div>';
                  }
              ?>
              <label class="form-label"> Profile Picture: </label>
              <div class="input-group mb-2">
                <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="form-control box">
              </div>
              <label class="form-label">First Name:</label>
              <div class="input-group mb-2">
                <input type="text" name="first_name" class="form-control bg-light fs-6" value="<?php echo $_SESSION['first_name']; ?>" />
              </div>
              <label class="form-label">Middle Name:</label>
              <div class="input-group mb-2">
                <input type="text" name="middle_name" class="form-control bg-light fs-6" value="<?php echo $_SESSION['middle_name']; ?>" />
              </div>
              <label class="form-label">Last Name:</label>
              <div class="input-group mb-2">
                <input type="text" name="last_name" class="form-control bg-light fs-6" value="<?php echo $_SESSION['last_name']; ?>" />
              </div>
              <label class="form-label">Suffix:</label>
              <div class="input-group mb-2">
                    <select name="suffix_name" id="suffix" class="form-control bg-light fs-6">
                        <option value="">Suffix</option>
                        <option value="jr" <?php echo ($_SESSION['suffix_name'] == 'jr') ? 'selected' : ''; ?>>Jr</option>
                        <option value="sr" <?php echo ($_SESSION['suffix_name'] == 'sr') ? 'selected' : ''; ?>>Sr</option>
                        <option value="II" <?php echo ($_SESSION['suffix_name'] == 'II') ? 'selected' : ''; ?>>II</option>
                        <option value="III" <?php echo ($_SESSION['suffix_name'] == 'III') ? 'selected' : ''; ?>>III</option>
                        <option value="IV" <?php echo ($_SESSION['suffix_name'] == 'IV') ? 'selected' : ''; ?>>IV</option>
                    </select>
              </div>
              <label class="form-label"> Contact Number: </label>
              <div class="input-group mb-2">
                  <input type="number" name="contact_number" class="form-control bg-light fs-6" value="<?php echo $fetch['contact_number']; ?>" />
              </div>
              <label class="form-label">New Password:</label>
              <div class="input-group mb-2">
                <input type="password" name="new_password" class="form-control bg-light fs-6" />
              </div>
              <label class="form-label">Confirm Password:</label>
              <div class="input-group mb-3">
                <input type="password" name="confirm_password" class="form-control bg-light fs-6" />
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>