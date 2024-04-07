<?php
session_start();

if (!isset($_SESSION['OWNER_EMAIL'])) {
    header("Location: index.php");
    die();
}

include 'config/connect.php';
include 'layouts/OWNER_NAVBAR.php'; 

$msg = "";

$roles = ['student', 'admin', 'owner'];

$placeholders = str_repeat('?,', count($roles) - 1) . '?';
$sql = "SELECT * FROM TBL_ACCOUNT WHERE USER_ROLE IN ($placeholders)";

$stmt = $conn->prepare($sql);
$stmt->execute($roles);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);



function formatCampus($campus) {
    switch ($campus) {
        case 'sanmateo':
            return 'San Mateo';
        case 'antipolo':
            return 'Antipolo';
        case 'cainta':
            return 'Cainta';
        case 'cubao':
            return 'Cubao';
        case 'binangonan':
            return 'Binangonan';
        default:
            return $campus;
    }
}

function formatUserRole($role) {
    switch ($role) {
        case 'student':
            return 'Student';
        case 'owner':
            return 'Owner';
        case 'admin':
            return 'Admin';
        default:
            return $role;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container d-flex rounded-4 p-3">
        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Student ID</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Suffix</th>
                    <th scope="col">Course</th>
                    <th scope="col">Campus</th>
                    <th scope="col">Contact No.</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row) { ?>
                    <tr>
                        <td><?php echo $row['STUDENT_NUMBER']; ?></td>
                        <td><?php echo $row['FIRST_NAME'] . " " . $row['MIDDLE_NAME'] . " " . $row['LAST_NAME']; ?></td>
                        <td><?php echo ucwords($row['SUFFIX_NAME']); ?></td>
                        <td><?php echo $row['COURSE']; ?></td>
                        <td><?php echo formatCampus($row['CAMPUS']); ?></td>
                        <td><?php echo $row['CONTACT_NUMBER']; ?></td>
                        <td><?php echo $row['EMAIL_ADDRESS']; ?></td>
                        <td><?php echo $row['CREATED_AT']; ?></td>
                        <td><?php echo formatUserRole($row['USER_ROLE']); ?></td>
                        <td>
                            <a href="owner-edit-profile.php?StudentNum=<?php echo $row['STUDENT_NUMBER'] ?>" class='btn btn-primary me-2'> Edit </a>
                            <a href="delete.php?StudentNum=<?php echo $row['STUDENT_NUMBER'] ?>" class='btn btn-danger'> Delete </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
