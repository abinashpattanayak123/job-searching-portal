<?php
// Handle form submission at the beginning of the script
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    require_once "dbcon.php";
    $job_id = $_POST['jobid'];
    $job_title = $_POST['job_title'];
    $company = $_POST['company'];
    $location = $_POST['city'];
    $description = $_POST['description'];
    $salary = $_POST['salary'];
    $experience = $_POST['experience'];
    $skill1 = $_POST['skill1'];
    $skill2 = $_POST['skill2'];
    $skill3 = $_POST['skill3'];
    $skill4 = $_POST['skill4'];
    $skill5 = $_POST['skill5'];

    $dis = "INSERT INTO job (job_id, job_title, company_name, location, job_description, salary, required_experience, required_skill_1, required_skill_2, required_skill_3, required_skill_4, required_skill_5) VALUES ('$job_id', '$job_title', '$company', '$location', '$description', '$salary', '$experience', '$skill1', '$skill2', '$skill3', '$skill4', '$skill5')";
    $ress = $conn->query($dis);
    if ($ress) {
        // Redirect to prof_dash.php after successful job creation
        header("Location: prof_dash.php");
        exit; // Terminate script after redirect
    } else {
        header("Location: createjob.php?error=1");
        exit; // Terminate script after redirect
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create New Job</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 50px auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      margin-bottom: 20px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .form-group input[type="text"], .form-group input[type="number"], .form-group textarea, .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .form-group textarea {
      height: 100px;
    }

    .form-group input[type="submit"] {
      background-color: #ff6600; /* Orange color */
      color: #fff;
      border: none;
      padding: 15px;
      border-radius: 5px;
      cursor: pointer;
      width: 100%; /* Full width */
    }

    .form-group input[type="submit"]:hover {
      background-color: #cc5500; /* Darker shade of orange */
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Create New Job</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <label for="job-id">Job ID</label>
        <input type="number" id="jobid" name="jobid" required>
      </div>
      <div class="form-group">
        <label for="job-title">Job Title</label>
        <input type="text" id="job-title" name="job_title" required>
      </div>
      <div class="form-group">
        <label for="company">Company</label>
        <input type="text" id="company" name="company" required>
      </div>
      <div class="form-group">
        <label for="location">Location</label>
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
      </div>
      <div class="form-group">
        <label for="description">Job Description</label>
        <textarea id="description" name="description" required></textarea>
      </div>
      <div class="form-group">
        <label for="salary">Salary</label>
        <input type="number" id="salary" name="salary" required>
      </div>
      <div class="form-group">
        <label for="experience">Years of Experience Required</label>
        <input type="number" id="experience" name="experience" required>
      </div>
      <div class="form-group">
        <label for="skill">Required Skills</label>
        <input type="text" id="skill1" name="skill1" placeholder="enter skill-1" required>
        <input type="text" id="skill2" name="skill2" placeholder="enter skill-2" required>
        <input type="text" id="skill3" name="skill3" placeholder="enter skill-3" required>
        <input type="text" id="skill4" name="skill4" placeholder="enter skill-4" required>
        <input type="text" id="skill5" name="skill5" placeholder="enter skill-5" required>
      </div>
      <div class="form-group">
        <input type="submit" value="Create Job" name="submit">
      </div>
    </form>
  </div>
</body>
</html>
