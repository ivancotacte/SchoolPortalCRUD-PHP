
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
<style>
    .profile-picture {
        width: 150px;
        height: 150px;
        border-radius: 50%;
    }
</style>
<body>

<div class="container d-flex justify-content-center align-items-center">
    <div class="row border rounded-2 p-3 bg-white shadow box-area">
        <div class="col-md-4 rounded-2 d-flex justify-content-center align-items-center flex-column">
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
        </div>
        <div class="col-md-8 right-box">
            <h3> Personal Information </h3>
            <p>ID: <span><?php echo $_SESSION['SESSION_ID']; ?></span></p>
            <p>First Name: <span><?php echo $_SESSION['first_name']; ?></span></p>
            <p>MIddle Name: <span><?php echo $_SESSION['middle_name']; ?></span></p>
            <p>Last Name: <span><?php echo $_SESSION['last_name']; ?></span></p>
            <p>Suffix: <span><?php echo $_SESSION['suffix_name']; ?></span></p>
            <p>Campus: <span><?php echo $_SESSION['campus']; ?></span></p>
            <p>Email Address: <span><?php echo $_SESSION['email_address']; ?></span></p>
            <p>Role: <span><?php echo $_SESSION['user_role']; ?></span></p>

            <div class="mt-3">
                <button type="button" onclick="window.location.href = 'edit_profile.php';" class="btn btn-primary me-2 w-50" > Edit Profile </button>
                <button type="button" onclick="window.location.href = 'logout.php';" class="btn btn-secondary w-45"> LogOut </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
