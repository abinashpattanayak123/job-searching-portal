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
      max-width: 1200px;
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
      width: calc(50% - 20px);
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin: 0 20px 20px 0;
      box-sizing: border-box;
      display: inline-block;
      vertical-align: top;
    }

    .card h3 {
      color: #ff6600;
      margin-top: 0;
    }

    .card p {
      margin-top: 5px;
      margin-bottom: 10px;
    }

    .card p strong {
      color: #333;
    }

    .card p:last-child {
      margin-bottom: 0;
    }

    @media (max-width: 768px) {
      .card {
        width: 100%;
        margin-right: 0;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Created Jobs</h1>
    <?php
    session_start(); // Start or resume the session

    // Check if session data is set
    if (!isset($_SESSION["data1"])) {
        // If session data is not set, redirect to another page or handle it accordingly
        header("Location: error_page.php");
        exit; // Ensure script execution stops after redirection
    }

    // Get session data
    $company_id = $_SESSION["data1"];

    // Include database connection file
    require_once "dbcon.php";

    // Fetch company details
    $dis = "SELECT * FROM company_register WHERE company_id = ?";
    $stmt = $conn->prepare($dis);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $ress = $stmt->get_result();

    // Check if company details are fetched
    if ($ress && $ress->num_rows > 0) {
        while ($data = $ress->fetch_assoc()) {
            $company_name = $data['company_name'];
        }
    } else {
        echo "<div class='job-stats'>Company not found.</div>";
        exit; // Stop further execution
    }

    // Fetch jobs data from database for the current company
    $sql = "SELECT * FROM job WHERE company_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $company_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any jobs
    if ($result && $result->num_rows > 0) {
        echo "<div class='job-stats'><strong>Total Jobs:</strong> " . $result->num_rows . "</div>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='card'>";
            echo "<h3>" . htmlspecialchars($row["job_title"]) . "</h3>";
            echo "<p><strong>Location:</strong> " . htmlspecialchars($row["location"]) . "</p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($row["job_description"]) . "</p>";
            echo "<p><strong>Salary:</strong> " . htmlspecialchars($row["salary"]) . " rs</p>";
            echo "<p><strong>Experience Required:</strong> " . htmlspecialchars($row["required_experience"]) . " years</p>";
            echo "<p><strong>Required Skills:</strong> " . htmlspecialchars($row["required_skill_1"]) . ", " . htmlspecialchars($row["required_skill_2"]) . ", " . htmlspecialchars($row["required_skill_3"]) . ", " . htmlspecialchars($row["required_skill_4"]) . ", " . htmlspecialchars($row["required_skill_5"]) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='job-stats'>No jobs found for $company_name</div>";
    }

    // Close database connection
    $conn->close();
    ?>
  </div>
</body>
</html>
