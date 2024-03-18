<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include 'config.php';
include 'layout/dashboard_navbar.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix_name = $_POST['suffix_name'];
    $campus = $_POST['campus'];


    $update_query = "UPDATE tb_account SET first_name='$first_name', middle_name='$middle_name', last_name='$last_name', suffix_name='$suffix_name', campus='$campus' WHERE email_address='{$_SESSION['SESSION_EMAIL']}'";

    if (mysqli_query($conn, $update_query)) {
        $msg = "Personal information updated successfully";

        $_SESSION['first_name'] = $first_name;
        $_SESSION['middle_name'] = $middle_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['suffix_name'] = $suffix_name;
        $_SESSION['campus'] = $campus;
    } else {
        $msg = "Error updating information: " . mysqli_error($conn);
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
</style>
<body>

<div class="container d-flex justify-content-center align-items-center">
    <div class="row border rounded-2 p-3 bg-white shadow box-area">
        <div class="right-box">
            <h3>Update Profile Information</h3>
            <?php if ($msg): ?>
                <div class="alert alert-info"><?php echo $msg; ?></div>
            <?php endif; ?>
            <form method="post" action="">
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
              <label class="form-label">Campus:</label>
              <div class="input-group mb-3">
                <select id="campus" name="campus" class="form-control bg-light fs-6">
                    <option value="cainta" <?php echo ($_SESSION['campus'] == 'cainta') ? 'selected' : ''; ?>>Cainta (Main)</option>
                    <option value="sanmateo" <?php echo ($_SESSION['campus'] == 'sanmateo') ? 'selected' : ''; ?>>San Mateo</option>
                    <option value="antipolo" <?php echo ($_SESSION['campus'] == 'antipolo') ? 'selected' : ''; ?>>Antipolo</option>
                </select>
              </div>
              <div class="input-group mb-2">
                <button type="submit" name="submit" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;">
                  Submit
                </button>
              </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>