<?php
session_start(); // Start or resume the session
require_once "dbcon.php"; // Include the database connection file



// Check if session data is set
if (!isset($_SESSION['skills']) || !isset($_SESSION['location']) || !isset($_SESSION['user_id'])) {
    // If session data is not set, redirect to an error page or handle it accordingly
    header("Location: error_page.php");
    exit; // Ensure script execution stops after redirection
}

// Get session data
$skills = $_SESSION['skills'];
$location = $_SESSION['location'];
$user_id = $_SESSION['user_id'];


// Separate skills by comma
$skillArray = explode(',', $skills);

// Ensure there are at least 5 elements in the skill array
$skill1 = isset($skillArray[0]) ? trim($skillArray[0]) : '';
$skill2 = isset($skillArray[1]) ? trim($skillArray[1]) : '';
$skill3 = isset($skillArray[2]) ? trim($skillArray[2]) : '';
$skill4 = isset($skillArray[3]) ? trim($skillArray[3]) : '';
$skill5 = isset($skillArray[4]) ? trim($skillArray[4]) : '';

// Perform the search query
$sql = "SELECT * FROM job WHERE location = ? AND (
    (required_skill_1 = ? OR required_skill_2 = ? OR required_skill_3 = ? OR required_skill_4 = ? OR required_skill_5 = ?) +
    (required_skill_1 = ? OR required_skill_2 = ? OR required_skill_3 = ? OR required_skill_4 = ? OR required_skill_5 = ?) >= 2
)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssss", $location, $skill1, $skill1, $skill1, $skill1, $skill1, $skill2, $skill2, $skill2, $skill2, $skill2);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the results
$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}


// Handle the apply action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id']) ) {
    $job_id = $_POST['job_id'];
    
    // Fetch user_id from session
    $user_id = $_SESSION['user_id'];
    $qry = "SELECT * FROM register WHERE user_id = '$user_id'";
    $ress1 = $conn->query($qry);

// Check if query was successful and if it returned any result
if ($ress1 === false || $ress1->num_rows == 0) {
    die("Error: Unable to retrieve user ID from database.");
}

// Fetch the user ID
$userdata = $ress1->fetch_assoc();
$userid = $userdata['user_id'];
    
    $insert_query = "INSERT INTO job_applied (job_id, user_id) VALUES ('$job_id', '$userid')";
    if ($conn->query($insert_query) === true) {
        // Insertion successful
       
    } else {
        // Insertion failed
        echo "Error applying for the job: " . $conn->error;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen bg-[#F1F1F1] overflow-x-hidden">
    <div class="main w-full h-full relative">
        <h1 class="text-3xl font-bold text-center mt-4">Search Results</h1>
        <div class="flex flex-wrap justify-center mt-4">
            <?php if (count($jobs) > 0): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="bg-white p-4 m-2 w-80 rounded shadow-md">
                        <h2 class="text-xl font-bold"><?php echo htmlspecialchars($job['job_title']); ?></h2>
                        <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company_name']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                        <p><strong>Required Skills:</strong></p>
                        <ul>
                            <li><?php echo htmlspecialchars($job['required_skill_1']); ?></li>
                            <li><?php echo htmlspecialchars($job['required_skill_2']); ?></li>
                            <li><?php echo htmlspecialchars($job['required_skill_3']); ?></li>
                            <li><?php echo htmlspecialchars($job['required_skill_4']); ?></li>
                            <li><?php echo htmlspecialchars($job['required_skill_5']); ?></li>
                        </ul>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($job['job_description']); ?></p>
                        <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></p>
                        <p><strong>Experience Required:</strong> <?php echo htmlspecialchars($job['required_experience']); ?></p>
                        <form method="post" action="search_main.php">
                            <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                            <button type="submit" name="submit" class="bg-blue-500 text-white px-4 py-2 mt-2 rounded" id="btn">Apply Now</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-xl">No jobs found matching your skills and location.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- <script>
       const btn = document.getElementById("btn");
        btn.addEventListener('click',function(){
            btn.innerHTML="Applied";
        });
    </script> -->
</body>
</html>
