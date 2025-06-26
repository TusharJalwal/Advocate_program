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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .container_wel {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            margin-top: 10px;
            color: #333;
        }

        .profile-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid #007BFF;
        }

        .profile-container {
            margin-top: 20px;
            text-align: left;
        }

        .profile-container ul {
            list-style: none;
            padding: 0;
        }

        .profile-container li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .profile-container strong {
            color: #555;
        }

        .update-form {
            margin-top: 30px;
            text-align: left;
        }

        .update-form h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .update-form input[type="date"],
        .update-form input[type="file"],
        .update-form input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .update-form input[type="submit"] {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .update-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .logout-link {
            display: inline-block;
            margin-top: 20px;
            color: #007BFF;
            font-weight: bold;
            text-decoration: none;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
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
