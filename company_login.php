<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Page</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="output.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
  .back-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #ff7f50; /* Orange color */
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
  }

  .back-btn:hover {
    background-color: #ff6347; /* Darker orange color on hover */
  }

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
    width: 300px;
    text-align: center;
  }

  .card input[type="text"],
  .card input[type="password"],
  .card input[type="submit"] {
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
  }

  .register-link {
    text-decoration: none;
    color: #007bff;
    font-weight: bold;
  }

  .register-link:hover {
    text-decoration: underline;
  }

  .show-password {
    margin-top: 4px;
    margin-left: 4px;
    display: inline-block;
  }

  .show-label {
    display: inline-block;
    font-size: 13px;
    margin-left: 0px;
  }
</style>
</head>
<body>

<a href="index.html" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>

<div class="card">
  <h2>Recruiter Login</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="text" name="company_id" placeholder="Company ID" required><br>
    <input type="password" name="password" id="password" placeholder="Password" required>
    <br>
    <input type="checkbox" id="show-password" class="show-password">
    <label for="show-password" class="show-label">Show Password</label>
    <br>
    <input type="submit" name="login" value="Login">
  </form>
  <p>Don't have an account? <a href="company_register.php" class="register-link">Register here</a></p>
</div>

<?php
session_start();
require_once "dbcon.php"; // Assuming this file contains database connection details

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $company_id = $_POST['company_id'];
    $password = $_POST['password'];
    $dis = "SELECT * FROM company_register";
    $ress = $conn->query($dis);
    if ($ress->num_rows > 0) {
        while ($data = $ress->fetch_assoc()) {
             if($data['company_id']==$company_id && $data['company_password']==$password ){
              session_start(); // Start or resume the session

              // Set session variables
              $_SESSION["data1"] = $company_id;
            
              header("Location:prof_dash.php");
              exit;
             }
        }
    } 
    // If user not found or login fails, redirect back to login page
    header("Location: company_login.php?error=1");
    exit;
}
?>

<script>
document.getElementById("show-password").addEventListener("change", function() {
  var passwordField = document.getElementById("password");
  if (this.checked) {
    passwordField.type = "text";
  } else {
    passwordField.type = "password";
  }
});
</script>

</body>
</html>
