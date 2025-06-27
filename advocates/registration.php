<?php
include("db.php");
include("secure.php");

$msg = "";
$errmsg = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $enrollment = $_POST['enrollment_number'];
    $contact = $_POST['contact'];
    $district = $_POST['district'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $address = $district . ',' . $state . ',' . $pincode;

    
    if (!preg_match("/^[0-9]{10}$/", $contact)) {
        $errmsg[] = "Invalid contact number. It must be exactly 10 digits.";
    }

    if (!preg_match('/^\d{6}$/', $pincode)) {
        $errmsg[] = "Invalid pincode. It must be exactly 6 digits.";
    }

    if (!preg_match('/^[A-Z][0-9]{4}[A-Z]{2}[0-9]{4}$/', $enrollment)) {
        $errmsg[] = "Enrollment number format is wrong. Required: A1234BC5678";
    }

    $enc_mobile = simple_encrypt($contact, $key);
    $enc_email = simple_encrypt($email, $key);

    
    if (count($errmsg) === 0) {
        $check = "SELECT * FROM users";
        $result = mysqli_query($conn, $check);
        $user_exists = false;

        while ($user = mysqli_fetch_assoc($result)) {
            if (
                $user['name'] === $name ||
                simple_decrypt($user['email_address'], $key) === $email ||
                $user['enrollment_number'] === $enrollment ||
                simple_decrypt($user['contact'], $key) === $contact
            ) {
                $user_exists = true;
                break;
            }
        }

        if ($user_exists) {
            $errmsg[] = "User already exists with the same name, email, contact, or enrollment number.";
        }
    }

    
    if (count($errmsg) === 0) {
        $sql = "INSERT INTO users (name, password, email_address, enrollment_number, contact, address)
                VALUES ('$name', '$password', '$enc_email', '$enrollment', '$enc_mobile', '$address')";

        if (mysqli_query($conn, $sql)) {
            $msg = "<div class='reg-success'>Registration successful! <a href='login.php'>Login Here</a></div>";
        } else {
            $errmsg[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="register-page">

<div class="register-container">
    <h2 class="register-title">Register New User</h2>

    <?php echo $msg; ?>

    <?php if (!empty($errmsg)) : ?>
        <script>
            alert("<?php echo implode('\n', array_map('addslashes', $errmsg)); ?>");
        </script>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="register-form">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Password:</label>
        <input type="password" minlength="8" name="password" id="pwd" required>

        <label>Email:</label>
        <input type="email" pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name="email" required>

        <label>Enrollment No.:</label>
        <input type="text" name="enrollment_number"
               placeholder="Ex: A1234BC5678"
               title="Format: A1234BC5678"
               maxlength="11"
               pattern="[A-Z]{1}[0-9]{4}[A-Z]{2}[0-9]{4}"
               required>

        <label>Contact:</label>
        <input type="tel" name="contact"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
               maxlength="10"
               pattern="[1-9]{1}[0-9]{9}"
               required>

        <label>State:</label>
        <select name="state" class="state" required>
            <option value="">--Select State--</option>
            <option value="Rajasthan">Rajasthan</option>
            <option value="Uttrakhand">Uttrakhand</option>
            <option value="Punjab">Punjab</option>
        </select>

        <label>District:</label>
        <select name="district" id="district" required>
            <option value="">--Select District--</option>
            <option value="jaipur">Jaipur</option>
            <option value="jaisalmer">Jaisalmer</option>
            <option value="kainchi">Kainchi</option>
            <option value="chandigarh">Chandigarh</option>
        </select>

        <label>Pin Code:</label>
        <input type="text" name="pincode"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
               maxlength="6"
               required>

        <input type="submit" value="Register" class="register-btn">

        <div class="register-link">
            <a href="login.php">Login Here</a>
        </div>
    </form>
</div>

</body>
</html>
