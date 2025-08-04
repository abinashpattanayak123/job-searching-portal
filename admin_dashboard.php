<?php
session_start();
require_once "dbcon.php";

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch the total number of users from the 'register' table
$query = "SELECT COUNT(user_id) AS total_users FROM register";
$result = $conn->query($query);

if ($result) {
    $data = $result->fetch_assoc();
    $total_users = $data['total_users'];
} else {
    $total_users = 0;
}

// Fetch the total number of jobs from the 'job' table
$job_query = "SELECT COUNT(job_id) AS total_jobs FROM job";
$job_result = $conn->query($job_query);

if ($job_result) {
    $job_data = $job_result->fetch_assoc();
    $total_jobs = $job_data['total_jobs'];
} else {
    $total_jobs = 0;
}

// Fetch the recent users
$recent_users_query = "SELECT user_id, name, email FROM register ORDER BY user_id DESC LIMIT 10";
$recent_users_result = $conn->query($recent_users_query);

$recent_users = [];
if ($recent_users_result) {
    while ($row = $recent_users_result->fetch_assoc()) {
        $recent_users[] = $row;
    }
} else {
    // Handle query error
    echo "Error in query: " . $conn->error;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .navbar {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            display: flex;
            padding: 20px;
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            padding: 15px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 240px);
        }
        .card {
            background-color: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card h3 {
            margin: 0;
            color: #007BFF;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .logout-button {
            background-color: #ff4b4b;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .logout-button:hover {
            background-color: #ff0000;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Admin Dashboard</h1>
</div>

<div class="container">
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="#" style="color: white;">Dashboard</a></li><br>
            <li><a href="#" style="color: white;">Manage Users</a></li><br>
            <li><a href="#" style="color: white;">Manage Jobs</a></li><br>
            <li><a href="#" style="color: white;">Settings</a></li>
        </ul>
        <a href="admin_login.php"><button class="logout-button">Logout</button></a>
    </div>

    <div class="content">
        <div class="card">
            <h3>Statistics</h3>
            <p>Total Users: <?php echo $total_users; ?></p>
            <p>Total Jobs: <?php echo $total_jobs; ?></p>
        </div>

        <div class="card table-container">
            <h3>Recent Users</h3>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><button>Edit</button> <button>Delete</button></td>
                        </tr>
                    <?php endforeach; ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
