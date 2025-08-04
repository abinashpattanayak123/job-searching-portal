<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION["userid"])) {
    header("Location: error_page.php");
    exit;
}

$user_id = $_SESSION["userid"];

// Query to get user_id from register table
$qry = "SELECT * FROM register WHERE user_id = '$user_id'";
$ress1 = $conn->query($qry);

// Check if query was successful and if it returned any result
if ($ress1 === false || $ress1->num_rows == 0) {
    die("Error: Unable to retrieve user ID from database.");
}

// Fetch the user ID
$userdata = $ress1->fetch_assoc();
$userid = $userdata['user_id'];

$dis = "SELECT * FROM user_skill WHERE user_id = '$user_id'";
$ress = $conn->query($dis);

if ($ress === false) {
    die("Error: " . $conn->error . " with query " . $dis);
}

while ($data = $ress->fetch_assoc()) {
    $skill1 = $data['skill1'];
    $skill2 = $data['skill2'];
    $skill3 = $data['skill3'];
    $skill4 = $data['skill4'];
    $skill5 = $data['skill5'];
}

$job_query = "SELECT *, 
              ((required_skill_1 = '$skill1') + 
               (required_skill_2 = '$skill2') + 
               (required_skill_3 = '$skill3') + 
               (required_skill_4 = '$skill4') + 
               (required_skill_5 = '$skill5') + 
               (required_skill_1 = '$skill2') + 
               (required_skill_1 = '$skill3') + 
               (required_skill_1 = '$skill4') + 
               (required_skill_1 = '$skill5') + 
               (required_skill_2 = '$skill3') + 
               (required_skill_2 = '$skill4') + 
               (required_skill_2 = '$skill5') + 
               (required_skill_3 = '$skill4') + 
               (required_skill_3 = '$skill5') + 
               (required_skill_4 = '$skill5')) as match_count 
              FROM job 
              HAVING match_count >= 2";

$job_result = $conn->query($job_query);

if ($job_result === false) {
    die("Error: " . $conn->error . " with query " . $job_query);
}
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
        .apply-btn {
            background-color: green;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .apply-btn:hover {
            background-color: gray;
        }
        .apply-btn.applied {
            background-color: gray;
            cursor: not-allowed;
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
    <script>
        function applyForJob(jobId, userId) {
            var form = document.getElementById('apply-form-' + jobId);
            var jobInput = document.createElement('input');
            jobInput.type = 'hidden';
            jobInput.name = 'job_id';
            jobInput.value = jobId;
            form.appendChild(jobInput);

            var userInput = document.createElement('input');
            userInput.type = 'hidden';
            userInput.name = 'user_id';
            userInput.value = userId;
            form.appendChild(userInput);

            // Change the button text to 'Applied' and disable it
            var button = document.querySelector('button[data-job-id="' + jobId + '"]');
            button.textContent = 'Applied';
            button.disabled = true;
            button.classList.add('applied');

            form.submit();
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Jobs</h1>
    <?php
    if ($job_result->num_rows > 0) {
        while ($job = $job_result->fetch_assoc()) {
            $job_id = $job['job_id'];
            echo '<div class="card">';
            echo '<h3>' . htmlspecialchars($job['job_title']) . '</h3>';
            echo '<p><strong>Company:</strong> ' . htmlspecialchars($job['company_name']) . '</p>';
            echo '<p><strong>Location:</strong> ' . htmlspecialchars($job['location']) . '</p>';
            echo '<p><strong>Description:</strong> ' . htmlspecialchars($job['job_description']) . '</p>';
            echo '<p><strong>Skills:</strong> ' . htmlspecialchars($job['required_skill_1']) . ', ' . htmlspecialchars($job['required_skill_2']) . ', ' . htmlspecialchars($job['required_skill_3']) . ', ' . htmlspecialchars($job['required_skill_4']) . ', ' . htmlspecialchars($job['required_skill_5']) . '</p>';
            echo '<form id="apply-form-' . $job_id . '" action="apply_job_skill.php" method="POST" style="display: none;"></form>';
            echo '<button class="apply-btn" data-job-id="' . $job_id . '" onclick="applyForJob(\'' . $job_id . '\', \'' . $userid . '\')">Apply</button>';
            echo '</div>';
        }
    } else {
        echo '<p>No matching jobs found.</p>';
    }
    ?>
</div>
</body>
</html>
