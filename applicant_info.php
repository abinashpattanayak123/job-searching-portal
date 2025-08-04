<?php
session_start();
require_once "dbcon.php";

// Check if user_id is set in the query string
if (!isset($_GET['user_id'])) {
    echo "No user ID provided.";
    exit;
}

// Get the user ID from the query string
$user_id = intval($_GET['user_id']);

// Fetch user details from the register table
$user_query = "SELECT * FROM register WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result && $user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    ?>
    <div>
        <h2>User Information</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user_data['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_data['phone']); ?></p>
        <!-- Add more user details here if needed -->
    </div>
    <?php
} else {
    echo "No user found with the given ID.";
}
?>
