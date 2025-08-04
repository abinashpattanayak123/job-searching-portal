<?php
session_start(); // Start or resume the session
require_once "dbcon.php";

// Check if session data is set
if (!isset($_SESSION["data1"])) {
    // If session data is not set, redirect to another page or handle it accordingly
    header("Location: error_page.php");
    exit; // Ensure script execution stops after redirection
}

// Get session data
$company_id = $_SESSION["data1"];
//echo "Company ID: " . htmlspecialchars($company_id) . "<br>";

// Fetch company information
$dis = "SELECT * FROM company_register WHERE company_id = ?";
$stmt = $conn->prepare($dis);
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}
$stmt->bind_param("i", $company_id);
$stmt->execute();
$ress = $stmt->get_result();

// Check if query was successful
if ($ress) {
    if ($ress->num_rows > 0) {
        while ($data = $ress->fetch_assoc()) {
            $company_name = htmlspecialchars($data['company_name']);
        }
    } else {
        // Handle case when no rows are returned
        echo "No company found with the given ID.";
        exit;
    }
} else {
    // Handle query error
    echo "Error in query: " . $conn->error;
    exit;
}

// Fetch job details by company name
$job_query = "SELECT job_id, job_title, job_description FROM job WHERE company_name = ?";
$stmt = $conn->prepare($job_query);
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}
$stmt->bind_param("s", $company_name);
$stmt->execute();
$job_result = $stmt->get_result();

$jobs = [];
if ($job_result) {
    if ($job_result->num_rows > 0) {
        while ($job_data = $job_result->fetch_assoc()) {
            $jobs[] = $job_data;
        }
    } else {
        echo "No jobs found for the company $company_name.";
        exit;
    }
} else {
    echo "Error in query: " . $conn->error;
    exit;
}

// Fetch user IDs who applied for each job and group them by job_id
$job_ids = array_column($jobs, 'job_id');
if (empty($job_ids)) {
    echo "No job IDs found.";
    exit;
}

$job_ids_placeholder = implode(',', array_fill(0, count($job_ids), '?'));
$applied_query = "SELECT job_id, user_id FROM job_applied WHERE job_id IN ($job_ids_placeholder)";
$stmt = $conn->prepare($applied_query);
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}
$stmt->bind_param(str_repeat('i', count($job_ids)), ...$job_ids);
$stmt->execute();
$applied_result = $stmt->get_result();

$applications = [];
$user_ids = [];
if ($applied_result) {
    while ($applied_data = $applied_result->fetch_assoc()) {
        $applications[$applied_data['job_id']][] = $applied_data['user_id'];
        $user_ids[] = $applied_data['user_id'];
    }
} else {
    echo "Error in query: " . $conn->error;
    exit;
}

// Fetch user details for all user IDs
if (empty($user_ids)) {
    echo "No user IDs found.";
    exit;
}

$user_ids_placeholder = implode(',', array_fill(0, count($user_ids), '?'));
$user_query = "SELECT user_id, name, mobile, email, location, gender FROM register WHERE user_id IN ($user_ids_placeholder)";
$stmt = $conn->prepare($user_query);
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}
$stmt->bind_param(str_repeat('i', count($user_ids)), ...$user_ids);
$stmt->execute();
$user_result = $stmt->get_result();

$users = [];
if ($user_result) {
    while ($user_data = $user_result->fetch_assoc()) {
        $users[$user_data['user_id']] = [
            'name' => htmlspecialchars($user_data['name']),
            'mobile' => htmlspecialchars($user_data['mobile']),
            'email' => htmlspecialchars($user_data['email']),
            'location' => htmlspecialchars($user_data['location']),
            'gender' => htmlspecialchars($user_data['gender']),
        ];
    }
} else {
    echo "Error in query: " . $conn->error;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .job-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
        }
        .job-card h2 {
            margin-top: 0;
            color: #ff6600;
        }
        .applicants {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .user-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: #f1f1f1;
            flex: 1 0 200px; /* flex-grow, flex-shrink, and flex-basis */
            max-width: 200px;
        }
        .user-card h3 {
            margin: 5px 0;
            color: #333;
        }
        .user-card p {
            margin: 5px 0;
            color: #555;
        }
        .user-card button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .user-card button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>Job Applications for <?php echo htmlspecialchars($company_name); ?></h1>

<?php foreach ($jobs as $job): ?>
    <div class="job-card">
        <div>
            <h2><?php echo htmlspecialchars($job['job_title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($job['job_description'])); ?></p>
            <h3>Applicants:</h3>
            <div class="applicants">
                <?php if (isset($applications[$job['job_id']])): ?>
                    <?php foreach ($applications[$job['job_id']] as $user_id): ?>
                        <?php $user = $users[$user_id] ?? null; ?>
                        <?php if ($user): ?>
                            <div class="user-card">
                                <h3><?php echo $user['name']; ?></h3>
                                <p><strong>User ID:</strong> <?php echo $user_id; ?></p>
                                <p><strong>Phone:</strong> <?php echo $user['mobile']; ?></p>
                                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                                <p><strong>Address:</strong> <?php echo $user['location']; ?></p>
                                <p><strong>Gender:</strong> <?php echo $user['gender']; ?></p>
                                <button class="downloadBtn">Download CV</button>
                            </div>
                        <?php else: ?>
                            <p>No user information available for User ID: <?php echo htmlspecialchars($user_id); ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No applicants for this job.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<script>
    document.querySelectorAll('.downloadBtn').forEach(button => {
        button.addEventListener('click', () => {
            alert('Downloading CV...');
        });
    });
</script>
</body>
</html>
