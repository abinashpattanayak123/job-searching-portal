<?php
session_start();
if (!isset($_SESSION["data1"])) {
    header("Location: error_page.php");
    exit;
}
require_once "dbcon.php";

// Function to delete a job from the database
function deleteJob($conn, $jobId) {
    $sql = "DELETE FROM job WHERE job_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $jobId);
    return $stmt->execute();
}

// Check if a delete request is received
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_job_id'])) {
    $job_id = $_POST['delete_job_id'];
    if (deleteJob($conn, $job_id)) {
        echo "Job deleted successfully";
    } else {
        echo "Error deleting job: " . $conn->error;
    }
    exit;
}

$company_id = $_SESSION["data1"];
$dis = "SELECT * FROM company_register WHERE company_id = ?";
$stmt = $conn->prepare($dis);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$ress = $stmt->get_result();

if ($ress->num_rows > 0) {
    while ($data = $ress->fetch_assoc()) {
        $company_name = $data['company_name'];
    }
}

$sql = "SELECT * FROM job WHERE company_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $company_name);
$stmt->execute();
$result = $stmt->get_result();
?>
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

    .delete-btn {
      background-color: #ff6600;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: #cc5500;
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
    <h1>Created Jobs</h1>
    <?php
    if ($result->num_rows > 0) {
        echo "<div class='job-stats' id='job-stats'><strong>Total Jobs:</strong> " . $result->num_rows . "</div>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card' id='job_" . $row['job_id'] . "'>";
            echo "<h3>" . htmlspecialchars($row["job_title"]) . "</h3>";
            echo "<p><strong>Location:</strong> " . htmlspecialchars($row["location"]) . "</p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($row["job_description"]) . "</p>";
            echo "<p><strong>Salary:</strong> $" . htmlspecialchars($row["salary"]) . "</p>";
            echo "<p><strong>Experience Required:</strong> " . htmlspecialchars($row["required_experience"]) . " years</p>";
            echo "<p><strong>Required Skills:</strong> " . htmlspecialchars($row["required_skill_1"]) . ", " . htmlspecialchars($row["required_skill_2"]) . ", " . htmlspecialchars($row["required_skill_3"]) . ", " . htmlspecialchars($row["required_skill_4"]) . ", " . htmlspecialchars($row["required_skill_5"]) . "</p>";
            echo "<button class='delete-btn' onclick='deleteJob(" . $row['job_id'] . ")'>Delete</button>";
            echo "</div>";
        }
    } else {
        echo "<div class='job-stats'>No jobs found for $company_name</div>";
    }
    ?>
  </div>
  <script>
    function deleteJob(jobId) {
        if (confirm("Are you sure you want to delete this job?")) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var jobCard = document.getElementById("job_" + jobId);
                        if (jobCard) {
                            jobCard.remove();
                        }
                        var jobStats = document.getElementById('job-stats');
                        if (jobStats) {
                            var totalCount = parseInt(jobStats.textContent.match(/\d+/)[0]);
                            jobStats.textContent = "Total Jobs: " + (totalCount - 1);
                        }
                        alert(xhr.responseText);
                    } else {
                        alert("Failed to delete job. Please try again later.");
                    }
                }
            };
            xhr.open("POST", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("delete_job_id=" + jobId);
        }
    }
  </script>
</body>
</html>
<?php
$conn->close();
?>
