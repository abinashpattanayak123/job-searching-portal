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

// Check if form data is set
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['job_id'];
    $user_id = $_POST['user_id'];

    // Insert into applied_job table
    $sql = "INSERT INTO job_applied (job_id, user_id) VALUES ('$job_id', '$user_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Application successful!";
        header("Location: location_search.php"); // Redirect to a success page or another page as needed
        exit; // Ensure script execution stops after redirection
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
}
?>
