<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Form</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #dcdcdc; /* Dull gray background color */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .card {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 400px;
    text-align: left;
    margin: auto;
  }

  .card input[type="text"],
  .card input[type="email"],
  .card input[type="tel"],
  .card input[type="password"],
  .card select,
  .card textarea {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  .card input[type="submit"] {
    background-color: #ff7f50; /* Orange color */
    color: white;
    border: none;
    cursor: pointer;
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    font-size: 16px;
  }

  .register-link {
    text-decoration: none;
    color: #007bff;
    font-weight: bold;
  }

  .register-link:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>

<div class="card">
  <h2>Registration Form</h2><h5>only for recruiter</h5>
  <form id="registrationForm" action="company_register.php" method="post">
    <input type="text" name="company_id" placeholder="Company ID" required>
    <br>
    <input type="text" name="company_name" placeholder="Company Name" required>
    <br>
    <input type="email" name="company_email" placeholder="Company Email" required>
    <br>
    <input type="tel" name="company_mobile" placeholder="Company Mobile Number" required>
    <br>
    <input type="text" name="company_logo" placeholder="Company logo" required>
    <br>
    <span>Address:</span>
    <textarea name="company_address" style="width:395px; height:80px;" required></textarea>
    <br>
    <input type="text" name="company_website" placeholder="Company website" required>
    <br>
    <input type="password" name="company_password" placeholder="Password" 
           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
           title="Password must be at least 8 characters long and include at least one uppercase letter, one number, and one special character." required>
    <br>
    <input type="password" name="confirm_password" placeholder="Re-enter Password" required>
    <br>
    <input type="submit" name="Register">
  </form>
  <p>Already have an account? <a href="company_login.php" class="register-link">Login here</a></p>
</div>

<script>
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    var password = document.querySelector('input[name="company_password"]').value;
    var confirmPassword = document.querySelector('input[name="confirm_password"]').value;

    var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

    if (!passwordPattern.test(password)) {
        alert('Password must be at least 8 characters long and include at least one uppercase letter, one number, and one special character.');
        event.preventDefault();
        return;
    }

    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        event.preventDefault();
    }
});
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "dbcon.php"; // Assuming this file includes database connection code

    // Retrieve form data
    $userid = $_POST['company_id'];
    $name = $_POST['company_name'];
    $email = $_POST['company_email'];
    $mobile = $_POST['company_mobile'];
    $logo = $_POST['company_logo'];
    $address = $_POST['company_address'];
    $website = $_POST['company_website'];
    $password = $_POST['company_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password == $confirm_password) {
        $qry = "INSERT INTO company_register (company_id, company_name, company_email, company_mobile, company_password, company_address, company_logo, company_website) 
                VALUES ('$userid','$name', '$email','$mobile','$password','$address','$logo','$website')";
        $res = $conn->query($qry);
        if ($res) {
            header("Location: company_login.php");
        } else {
            echo "Error: " . $qry . "<br>" . $conn->error;
        }
    } else {
        echo "Passwords do not match!";
    }
}
?>

</body>
</html>
