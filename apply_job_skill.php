<?php
session_start();
require_once "dbcon.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['job_id'];
    $user_id = $_POST['user_id'];

    // Insert data into job_applied table
    $insert_query = "INSERT INTO job_applied (job_id, user_id) VALUES ('$job_id', '$user_id')";
    if ($conn->query($insert_query) === true) {
        // Insertion successful
        echo "Applied for the job successfully.";
        header("location:skill_search.php");
        exit;
    } else {
        // Insertion failed
        echo "Error applying for the job: " . $conn->error;
    }
}
?>
