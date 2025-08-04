<?php
// Make sure there's no whitespace or HTML output before this block
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "dbcon.php"; // Assuming this file includes database connection code

    // insert data
    $userid = $_POST['userid'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $location = $_POST['city'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password == $confirm_password) {
        $qry = "INSERT INTO register (user_id, name, email, mobile, gender, location, password) VALUES ('$userid','$name', '$email','$mobile','$gender','$location','$password')";
        $res = $conn->query($qry);
        if ($res) {
            header("Location: login.php");
            exit(); // It's a good practice to call exit after header to stop further script execution
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Passwords do not match!";
    }
}
?>
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
  .card select {
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
  <h2>Registration Form</h2>
  <form action="register.php" method="post" onsubmit="return validateForm()">
    <input type="text" name="userid" placeholder="User ID" required>
    <br>
    <input type="text" name="name" placeholder="Name" required>
    <br>
    <input type="email" name="email" placeholder="Email" required>
    <br>
    <select name="gender" required>
      <option value="" disabled selected>Select Gender</option>
      <option value="male">Male</option>
      <option value="female">Female</option>
      <option value="other">Other</option>
    </select>
    <br>
    <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number (10 digits)" required>
    <br>
    <select name="city" required>
      <option value="" disabled selected>Select your City</option>
      <option value="mumbai">Mumbai</option>
      <option value="delhi">Delhi</option>
      <option value="bangalore">Bangalore</option>
      <option value="hyderabad">Hyderabad</option>
      <option value="ahmedabad">Ahmedabad</option>
      <option value="chennai">Chennai</option>
      <option value="kolkata">Kolkata</option>
      <option value="pune">Pune</option>
      <option value="jaipur">Jaipur</option>
      <option value="lucknow">Lucknow</option>
      <option value="kanpur">Kanpur</option>
      <option value="nagpur">Nagpur</option>
      <option value="indore">Indore</option>
      <option value="thane">Thane</option>
      <option value="bhopal">Bhopal</option>
      <option value="visakhapatnam">Visakhapatnam</option>
      <option value="pimpri-chinchwad">Pimpri-Chinchwad</option>
      <option value="patna">Patna</option>
      <option value="vadodara">Vadodara</option>
      <option value="ghaziabad">Ghaziabad</option>
      <option value="ludhiana">Ludhiana</option>
      <option value="agra">Agra</option>
      <option value="nashik">Nashik</option>
      <option value="faridabad">Faridabad</option>
      <option value="meerut">Meerut</option>
      <option value="rajkot">Rajkot</option>
      <option value="kalyan-dombivli">Kalyan-Dombivli</option>
      <option value="vasai-virar">Vasai-Virar</option>
      <option value="varanasi">Varanasi</option>
      <option value="srinagar">Srinagar</option>
      <option value="aurangabad">Aurangabad</option>
      <option value="dhanbad">Dhanbad</option>
      <option value="amritsar">Amritsar</option>
      <option value="navi mumbai">Navi Mumbai</option>
      <option value="allahabad">Allahabad</option>
      <option value="ranchi">Ranchi</option>
      <option value="howrah">Howrah</option>
      <option value="coimbatore">Coimbatore</option>
      <option value="jabalpur">Jabalpur</option>
      <option value="gwalior">Gwalior</option>
      <option value="vijayawada">Vijayawada</option>
      <option value="jodhpur">Jodhpur</option>
      <option value="madurai">Madurai</option>
      <option value="raipur">Raipur</option>
      <option value="kota">Kota</option>
      <option value="guwahati">Guwahati</option>
      <option value="chandigarh">Chandigarh</option>
      <option value="solapur">Solapur</option>
      <option value="hubli-dharwad">Hubli-Dharwad</option>
      <option value="bareilly">Bareilly</option>
      <option value="moradabad">Cuttack</option>
      <option value="mysore">Mysore</option>
      <option value="tiruchirappalli">Tiruchirappalli</option>
      <option value="tiruppur">Bhubaneswar</option>
      <option value="tumkur">Tumkur</option>
      <option value="tirunelveli">Tirunelveli</option>
      <option value="tirupati">Tirupati</option>
      <option value="guntur">Guntur</option>
      <option value="rajahmundry">Rajahmundry</option>
      <option value="warangal">Warangal</option>
    </select>
    <br>
    <input type="password" id="password" name="password" placeholder="Password" required>
    <br>
    <input type="password" name="confirm_password" placeholder="Re-enter Password" required>
    <br>
    <input type="submit" name="Register">
  </form>
  <p>Already have an account? <a href="login.php" class="register-link">Login here</a></p>
</div>

<script>
  function validateForm() {
    var mobile = document.getElementById("mobile").value;
    var password = document.getElementById("password").value;

    // Regular expressions for validation
    var mobileRegex = /^\d{10}$/; // 10 digits
    var passwordRegex = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/; // Password requirements

    if (!mobileRegex.test(mobile)) {
      alert("Mobile number must be 10 digits.");
      return false;
    }

    if (!passwordRegex.test(password)) {
      alert("Password must contain at least one uppercase letter, one digit, one special character, and be at least 8 characters long.");
      return false;
    }

    return true;
  }
</script>

</body>
</html>
