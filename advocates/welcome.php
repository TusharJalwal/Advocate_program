<?php
session_start();
include("db.php");
include("secure.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE name='$username'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Check user exists
if (!$user) {
    echo "User not found.";
    exit();
}

// Decryption
$dec_email = simple_decrypt($user['email_address'], $key);
$dec_mobile = simple_decrypt($user['contact'], $key);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
   
</head>
<body>

<?php if (isset($_GET['error'])): ?>
<script>
    alert("<?php echo addslashes($_GET['error']); ?>");
</script>
<?php endif; ?>

<div class="container_wel">
    <?php if (!empty($user['photograph'])): ?>
        <div class="profile-photo">
            <img src="<?php echo htmlspecialchars($user['photograph']); ?>" alt="User Logo">
        </div>
    <?php endif; ?>

    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>

    <div class="profile-container">
        <h3>Your Profile Details:</h3>
        <ul>
            <li><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></li>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($dec_email); ?></li>
            <li><strong>Enrollment No.:</strong> <?php echo htmlspecialchars($user['enrollment_number']); ?></li>
            <li><strong>Contact:</strong> <?php echo htmlspecialchars($dec_mobile); ?></li>
            <li><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></li>
          
        </ul>
    </div>

    <div class="update-form">
        <h3>Update Profile</h3>
        <form method="post" action="updateprofile.php" enctype="multipart/form-data">
            Update DOB:
            <input type="date" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>">

            Upload New Photo:
            <input type="file" name="photo" accept="image/*">

            <input type="submit" value="Update Profile">
        </form>
    </div>

    <a href="logout.php" class="logout-link">Logout</a>
</div>

</body>
</html>
