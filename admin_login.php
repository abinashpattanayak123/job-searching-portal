<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            box-sizing: border-box; /* Ensure padding is included in the element's width and height */
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 22px); /* Adjust width to account for padding and border */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Ensure padding and border are included in the width */
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px; /* Add margin for spacing */
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .login-container .register-link {
            display: block;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
        }
        .login-container .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<a href="index.html" class="back-button">Back</a>

<div class="login-container">
    <h2>Admin Login</h2>
    <form action="admin_login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <?php
    session_start();
    require_once "dbcon.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare and execute query to check the credentials
        $query = "SELECT * FROM admin WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Error preparing query: " . $conn->error);
        }
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $_SESSION['admin'] = $username;
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "Invalid username or password.";
        }
    }
    ?>
</div>

</body>
</html>
