<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Created Jobs</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1200px; /* Adjust as needed */
      margin: 50px auto;
      padding: 0 20px;
      box-sizing: border-box;
    }

    h1 {
      margin-bottom: 20px;
      text-align: center;
    }

    .job-stats {
      margin-bottom: 10px;
      text-align: center;
    }

    .card {
      width: calc(50% - 20px); /* 50% width minus margin */
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin: 0 20px 20px 0;
      box-sizing: border-box;
      display: inline-block;
      vertical-align: top;
    }

    .delete-btn {
      background-color: green;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: gray;
    }

    .card h3 {
      color: #ff6600; /* Orange color */
      margin-top: 0;
    }

    .card p {
      margin-top: 5px;
      margin-bottom: 10px;
    }

    .card p strong {
      color: #333; /* Dark text color */
    }

    .card p:last-child {
      margin-bottom: 0;
    }

    @media (max-width: 768px) {
      .card {
        width: 100%; /* Full width on smaller screens */
        margin-right: 0;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Jobs</h1>
    <?php

session_start(); // Start or resume the session
require_once "dbcon.php";

// Check if session data is set
if (!isset($_SESSION["userid"])) {
    // If session data is not set, redirect to an error page or handle it accordingly
    header("Location: error_page.php");
    exit; // Ensure script execution stops after redirection
}

// Get session data
$user_id = $_SESSION["userid"]; 

$dis = "SELECT * FROM register WHERE user_id = '$user_id'";

$ress = $conn->query($dis);

if ($ress === false) {
    // Query failed, output error for debugging
    die("Error: " . $conn->error . " with query " . $dis);
}

while ($data = $ress->fetch_assoc()) {
    $email = $data['email'];
    $name = $data['name'];
    $mobile = $data['mobile'];
    $userid = $data['user_id'];
    $location = $data['location'];
}


// Fetch user skills from user_skill table
$dis1 = "SELECT * FROM user_skill WHERE user_id = '$user_id'";
$ress1 = $conn->query($dis1);

if ($ress1 === false) {
    // Query failed, output error for debugging
    die("Error: " . $conn->error . " with query " . $dis1);
}

// Initialize skill variables with empty strings
$skill1 = $skill2 = $skill3 = $skill4 = $skill5 = "";

// Check if any skills were fetched
if ($ress1->num_rows > 0) {
    $data1 = $ress1->fetch_assoc(); // Fetch from $ress1 not $ress
    $skill1 = $data1['skill1'];
    $skill2 = $data1['skill2'];
    $skill3 = $data1['skill3'];
    $skill4 = $data1['skill4'];
    $skill5 = $data1['skill5'];
}


    // Fetch jobs data from database for the current company
    $sql = "SELECT * FROM job WHERE location = '$location'";
    $result = $conn->query($sql);

    // Check if there are any jobs
    if ($result->num_rows > 0) {
        // Output jobs data in cards
        echo "<div class='job-stats'> $result->num_rows <strong> Jobs found on </strong> ".$location. "</div>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='card'>";
            echo "<p><strong>job id:</strong> " . $row["job_id"] . "</p>";
            echo "<h3>" . $row["job_title"] . "</h3>";
            echo "<p><strong>Location:</strong> " . $row["location"] . "</p>";
            echo "<p><strong>Description:</strong> " . $row["job_description"] . "</p>";
            echo "<p><strong>Salary:</strong> " . $row["salary"] . " LPA</p>";
            echo "<p><strong>Experience Required:</strong> " . $row["required_experience"] . " years</p>";
            echo "<p><strong>Required Skills:</strong> " . $row["required_skill_1"] . ", ".$row["required_skill_2"] . ", ".$row["required_skill_3"] .", ".$row["required_skill_4"] .", ".$row["required_skill_5"] ."</p>";
            ?>
            <form action="apply_job_location.php" method="post">
                <input type="hidden" name="job_id" value="<?php echo $row['job_id']; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <button type="submit" class="delete-btn">Apply Now</button>
            </form>
            <?php
            echo "</div>";

        }
    } else {
        echo "<div class='job-stats'>No jobs found in $location</div>";
    }

    // Close database connection
    $conn->close();
    ?>
  </div>
</body>
</html>
 