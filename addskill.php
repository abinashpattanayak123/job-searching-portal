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

$dis = "SELECT * FROM register WHERE user_id = '$user_id'";
$ress = $conn->query($dis);

if ($ress === false) {
    // Query failed, output error for debugging
    die("Error: " . $conn->error . " with query " . $dis);
}

$data = $ress->fetch_assoc();
$email = $data['email'];
$name = $data['name'];
$mobile = $data['mobile'];
$userid = $data['user_id'];
$location = $data['location'];

// Insert data logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skill1 = $_POST['skill1'];
    $skill2 = $_POST['skill2'];
    $skill3 = $_POST['skill3'];
    $skill4 = $_POST['skill4'];
    $skill5 = $_POST['skill5'];
    
    $qry = "INSERT INTO user_skill (user_id, skill1, skill2, skill3, skill4, skill5) VALUES ('$userid', '$skill1', '$skill2', '$skill3', '$skill4', '$skill5')";
    if ($conn->query($qry) === true) {
        echo "Skills added successfully.";
        header("Location: userProfile3.php");
    } else {
        echo "Error: " . $qry . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>
</head>
<body class="overflow-x-hidden">
   <form class="flex flex-col items-center" method="POST" action="">
                        <label for="skill1" class="mt-3">Skill 1</label>
                        <input type="text" id="skill1" name="skill1" class="p-2 mt-1 border-2 border-gray-300 rounded-md w-64" required>
                        <label for="skill2" class="mt-2">Skill 2</label>
                        <input type="text" id="skill2" name="skill2" class="p-2 mt-1 border-2 border-gray-300 rounded-md w-64" required>
                        <label for="skill3" class="mt-2">Skill 3</label>
                        <input type="text" id="skill3" name="skill3" class="p-2 mt-1 border-2 border-gray-300 rounded-md w-64" required>
                        <label for="skill4" class="mt-2">Skill 4</label>
                        <input type="text" id="skill4" name="skill4" class="p-2 mt-1 border-2 border-gray-300 rounded-md w-64" required>
                        <label for="skill5" class="mt-2">Skill 5</label>
                        <input type="text" id="skill5" name="skill5" class="p-2 mt-1 border-2 border-gray-300 rounded-md w-64" required>
                        <button type="submit" class="px-3 py-1 mt-4 bg-green-600 text-white rounded-lg text-[20px]" name="submit">Submit</button>
    </form> 
</body>
</html>