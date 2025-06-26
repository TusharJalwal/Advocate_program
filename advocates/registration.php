<?php
include("db.php");
include("secure.php");
$msg = "";
$errmsg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $enrollment = $_POST['enrollment_number'];
    $contact = $_POST['contact'];
    $district = $_POST['district'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $address = $district .','. $state.','. $pincode;


    if (!preg_match("/^[0-9]{10}$/", $contact)) {
        $errmsg = "Invalid contact number. It must be exactly 10 digits.";
    }
    if (!preg_match('/^\d{6}$/', $pincode)) {
        $errmsg = "Invalid pincode.";
    }
    
  

    $enc_mobile = simple_encrypt($contact, $key);
    $enc_email = simple_encrypt($email, $key);
    
    $check="SELECT * FROM users";
    $result = mysqli_query($conn,$check);
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

    if($user_exists){
        $errmsg="User already exist .";
    }else{
   // sql insertion query
        $sql = "INSERT INTO users (name, password, email_address, enrollment_number, contact, address)
        VALUES ('$name', '$password', '$enc_email', '$enrollment', '$enc_mobile', '$address')";



        if (mysqli_query($conn, $sql)) {
        $msg = "<div class='reg-success'>Registration successful! <a href='login.php'>Login Here</a></div>";
    }
        // } else {
        // // $msg = "<div class='reg-error'>Error: " . mysqli_error($conn) . "</div>";
        // }
 
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
            alert("<?php echo $errmsg; ?>\nPlease change your contact, enrollment number, or email.");
        </script>
    <?php endif; ?>
 

        <form method="post" action="" enctype="multipart/form-data" class="register-form">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Email:</label>
        <input type="email" pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name="email" required>

        <label>Enrollment No.:</label>
        <input type="text" name="enrollment_number"
               pattern="[A-Z][0-9]{4}[A-Z]{2}[0-9]{4}"
               title="Format: A1234BC1234" maxlength=11
               required>

        <label>Contact:</label>
        <input type="tel" name="contact"  oninput="this.value = this.value.replace(/[^0-9]/g, '') 
        "Format="Not start with 0" maxlength=10 pattern="[1-9]{1}[0-9]{9}" required>


        <label>State:</label>
        <select name="state" class="state">
            <option value="Rajasthan">Rajasthan</option>
            <option value="Uttrakhand">Uttrakhand</option>
            <option value="Punjab">Punjab</option>
           
        </select>

        <label>district:</label>
        <select name="district" id="district">
            <option value="jaipur">jaipur</option>
            <option value="jaisalmer">jaisalmer</option>
            <option value="kainchi">kainchi</option>
            <option value="chandigarh">chandigarh</option>
        </select>

        <label>Pin Code:</label>
        <input type="text" name="pincode"  oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength=6 required>

        <input type="submit" value="Register" class="register-btn">
        <div class="register-link">
            <a href="login.php">Login Here</a>
        </div>
    </form>
</div>
<script>

</script>
</body>
</html>
