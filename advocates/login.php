<?php
session_start();
include("db.php");

$err = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE name='$user'";
    $result = mysqli_query($conn, $sql);
    
   
    if (mysqli_num_rows($result)) { 
        $user_data = mysqli_fetch_assoc($result);

        if (password_verify($pass, $user_data['password'])) {
            $_SESSION['username'] = $user_data['name'];
            header("Location: welcome.php");
            exit();
        } else {
            $err = "Invalid username or password.";
        }
       
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php if (!empty($err)) : ?>
<script>
    alert("<?php echo addslashes($err); ?>");
</script>
<?php endif; ?>

<div class="container_login">
    <h2>Login Form</h2>
    <form method="post" action="" class="loginform">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" value="Login" class="login-btn">
        <div class="login-link">
            <a href="registration.php">Register Here</a>
        </div>
    </form>
</div>

</body>
</html>
