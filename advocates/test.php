<?php
include("db.php");
include("secure.php");
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $password = $_POST['password']; 
    $email = $_POST['email'];
    $enrollment = $_POST['enrollment_number'];
    $contact = $_POST['contact'];
    $district = $_POST['district'];
   
    
    if (!preg_match("/^[0-9]{10}$/", $contact)) {
        $msg = "Invalid contact number. It must be exactly 10 digits.";
    }
    
    if (strlen($contact) != 10) {
        $msg = "Please provide a phone number of 10 digits!!";
      }else{
            echo $contact;
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

    <form method="post" action="test.php" enctype="multipart/form-data" class="register-form">
        <label>Name:</label>
        <input type="text" name="name" >

        <label>Password:</label>
        <input type="password" name="password" >

        <label>Email:</label>
        <input type="email" name="email" >

        <label>Enrollment No.:</label>
        <input type="text" name="enrollment_number"
               pattern="[A-Z][0-9]{4}[A-Z]{2}[0-9]{4}"
               title="Format: A1234BC1234"
               >

        <label>Contact:</label>
        <input type="tel" name="contact" maxlength=10 pattern="[1-9]{1}[0-9]{9}">


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
        <input type="text" name="pincode" >

        <input type="submit" value="Register" class="register-btn">
        <div class="register-link">
            <a href="login.php">Login Here</a>
        </div>
    </form>
</div>

</body>
</html>
