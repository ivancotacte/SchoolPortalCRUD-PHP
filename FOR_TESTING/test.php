<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <title> Student Dashboard </title>
</head>
<style>
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
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #030067">
                <?php
                    $select = mysqli_query($conn, "SELECT * FROM tb_account WHERE email_address='{$_SESSION['SESSION_EMAIL']}'") or die('query failed');
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
            <div class="col-md-6 right-box">
                <h3> Personal Information </h3>
                <?php
                    $select = mysqli_query($conn, "SELECT * FROM tb_account WHERE email_address='{$_SESSION['SESSION_EMAIL']}'") or die('query failed');
                    if(mysqli_num_rows($select) > 0) {
                        $fetch = mysqli_fetch_assoc($select);

                        echo '<p>ID: <span>'.$fetch['id'].'</span></p>';
                        echo '<p>First Name: <span>'.$fetch['first_name'].'</span></p>';
                        echo '<p>Middle Name: <span>'.$fetch['middle_name'].'</span></p>';
                        echo '<p>Last Name: <span>'.$fetch['last_name'].'</span></p>';
                        echo '<p>Suffix: <span>'.$fetch['suffix_name'].'</span></p>';
                        echo '<p>Campus: <span>'.$fetch['course'].'</span></p>';
                        echo '<p>Campus: <span>'.$fetch['campus'].'</span></p>';
                        echo '<p>Contact Number: <span>'.$fetch['contact_number'].'</span></p>';
                        echo '<p>Email Address: <span>'.$fetch['email_address'].'</span></p>';
                        echo '<p>Role: <span>'.$fetch['user_role'].'</span></p>';
                        
                    }
                ?>
                <div class="mt-3">
                    <button type="button" onclick="window.location.href = 'edit_profile.php';" class="btn btn-primary me-2 w-30" > Edit Profile </button>
                    <button type="button" onclick="window.location.href = 'logout.php';" class="btn btn-secondary w-30"> LogOut </button>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>