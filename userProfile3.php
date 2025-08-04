<?php
session_start(); // Start or resume the session
require_once "dbcon.php";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the target directory for uploaded images and resumes
$targetDir = "job_portal/";

// Check if session data is set
if (!isset($_SESSION["userid"])) {
    header("Location: error_page.php");
    exit;
}

// Get session data
$user_id = $_SESSION["userid"];

// Fetch user data from register table
$dis = "SELECT * FROM register WHERE user_id = ?";
$stmt = $conn->prepare($dis);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$ress = $stmt->get_result();

if ($ress === false) {
    die("Error: " . $conn->error . " with query " . $dis);
}

$data = $ress->fetch_assoc();
$email = $data['email'];
$name = $data['name'];
$mobile = $data['mobile'];
$userid = $data['user_id'];
$location = $data['location'];

// Fetch user skills from user_skill table
$dis1 = "SELECT * FROM user_skill WHERE user_id = ?";
$stmt1 = $conn->prepare($dis1);
$stmt1->bind_param("s", $user_id);
$stmt1->execute();
$ress1 = $stmt1->get_result();

if ($ress1 === false) {
    die("Error: " . $conn->error . " with query " . $dis1);
}

$skill1 = $skill2 = $skill3 = $skill4 = $skill5 = "";

if ($ress1->num_rows > 0) {
    $data1 = $ress1->fetch_assoc();
    $skill1 = $data1['skill1'];
    $skill2 = $data1['skill2'];
    $skill3 = $data1['skill3'];
    $skill4 = $data1['skill4'];
    $skill5 = $data1['skill5'];
}

// Image upload
if (isset($_POST['profile_submit'])) {
    $image = $_FILES['image'];
    $imagename = time() . '_' . basename($image['name']);
    $imagepath = $targetDir . $imagename;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($image['tmp_name'], $imagepath)) {
        // Delete existing image record
        $deleteQry = "DELETE FROM img WHERE email = ?";
        $stmt2 = $conn->prepare($deleteQry);
        $stmt2->bind_param("s", $email);
        $stmt2->execute();

        // Insert new image record
        $qry = "INSERT INTO img (photo, email) VALUES (?, ?)";
        $stmt3 = $conn->prepare($qry);
        $stmt3->bind_param("ss", $imagename, $email);
        if ($stmt3->execute()) {
            echo "Upload and data insertion successful!<br>";
        } else {
            echo "Error: " . $conn->error . "<br>";
        }
    } else {
        echo "Error uploading file.<br>";
    }
}

// Resume upload
if (isset($_POST['resume_submit'])) {
    $resume = $_FILES['resume'];
    $resumename = time() . '_' . basename($resume['name']);
    $resumepath = $targetDir . $resumename;

    if (move_uploaded_file($resume['tmp_name'], $resumepath)) {
        // Insert new resume record
        $qry = "INSERT INTO resume (resume, email) VALUES (?, ?)";
        $stmt4 = $conn->prepare($qry);
        $stmt4->bind_param("ss", $resumename, $email);
        if ($stmt4->execute()) {
            echo "Resume upload and data insertion successful!<br>";
        } else {
            echo "Error: " . $conn->error . "<br>";
        }
    } else {
        echo "Error uploading resume.<br>";
    }
}

// Fetch image from database
$imageQuery = "SELECT photo FROM img WHERE email = ?";
$stmt5 = $conn->prepare($imageQuery);
$stmt5->bind_param("s", $email);
$stmt5->execute();
$result = $stmt5->get_result();
$imageData = $result->fetch_assoc();
$profileImage = isset($imageData['photo']) ? $targetDir . $imageData['photo'] : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Gillroy";
        }

        html,
        body {
            height: 100%;
            width: 100%;
        }

        .main {
            height: 100%;
            width: 100%;
            position: relative;
        }

        .delete-icon {
            cursor: pointer;
            color: red;
            margin-left: 10px;
        }
        .dash-page > div:not(.no-hover) {
            transition: background-color 0.3s, transform 0.3s;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .dash-page > div:not(.no-hover):hover {
            background-color: #e07416;
            transform: scale(1.10);
        }
        .no-hover {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>
</head>
<body class="overflow-x-hidden">
<div class="main">
    <div class="nav p-3 flex justify-between bg-gray-100 w-full relative">
        <div class="toggle-button p-2 mr-2 border-2 rounded-md active:text-orange-500 lg:hidden">
            <i class="fa-solid fa-bars fa-lg "></i>
        </div>
    </div>
    <div class="content flex">
        <div class="sidebar-div px-1 lg:inline-block w-[20%] bg-slate-100">
            <div class="sidebar h-full w-56 bg-slate-200 lg:block hidden relative z-50">
                <div class="total-sidebar w-full h-40 flex justify-center">
                    <div class="image-div h-8 flex justify-center">
                        <div class="img-bg bg-blue-600 w-56 p-12 relative z-0"></div>
                        <div class="img-photo absolute top-12 rounded-full border-2 border-white h-28 w-28 z-50 bg-white">
                            <img class="object-cover rounded-full h-28 w-28" src="<?php echo htmlspecialchars($profileImage); ?>" alt="">
                        </div>
                    </div>
                </div>
                <div class="name-div flex flex-col justify-center mt-2 h-20 border-b-4 border-white">
                    <p class="text-xl font-semibold text-center" id="nav_name"><?php echo htmlspecialchars($name); ?></p>
                    <p class="font-sans text-center" id="nav_id"><?php echo htmlspecialchars($userid); ?></p>
                </div>
                <div class="dash-page w-56 h-60 bg-slate-200 flex flex-col items-center">
                    <div class="myprofile py-3 w-full ">
                        <p class="font-serif text-xl"><a href="userProfile3.php">My Profile</a></p>
                    </div>
                    <div class="dashboard py-3 w-full">
                        <p class="font-serif text-xl"><a href="search_job.php">Dashboard</a></p>
                    </div>
                    <div class="applyjob py-3 w-full">
                        <p class="font-serif text-xl"><a href="terms&condition.php">Terms & Condition</a></p>
                    </div>
                    <div class="applyjob py-3 w-full">
                        <p class="font-serif text-xl"><a href="contactus.php">Contact Us</a></p>
                    </div>
                    <div class="logout py-3 no-hover">
                        <a href="login.php"><button class="px-3 py-1 bg-red-600 rounded-md">logout</button></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content flex flex-col items-center w-full max-h-full bg-slate-200 overflow-x-auto">
            <div class="flex flex-col items-center mt-4">
                <h1 class="font-bold text-[30px] ">Profile</h1>
                <div class="relative mt-2">
                    <div class="img-photo rounded-full border-2 border-white h-28 w-28 z-50 bg-white">
                        <img class="object-cover rounded-full h-28 w-28" name="profilepicture" src="<?php echo htmlspecialchars($profileImage); ?>"  alt="">
                    </div>
                </div>
                
                <form action="userProfile3.php" method="post" enctype="multipart/form-data" class="flex flex-col items-center mt-2">
                    <label for="profilePicture" class="font-semibold mt-4">Upload Profile Picture</label>
                    <input type="file" name="image" accept=".jpg,.jpeg" class="p-2 mt-2 border-2 border-gray-300 rounded-md w-64">
                    <input type="submit" value="Upload" class="px-3 py-1 mt-4 bg-blue-600 text-white rounded-md cursor-pointer" name="profile_submit">
                    <input type="text" value="<?php echo "NAME: ".htmlspecialchars($name); ?>" class="p-2 mt-4 border-2 border-gray-300 rounded-md w-64" disabled>
                    <input type="text" value="<?php echo "EMAIL: ".htmlspecialchars($email); ?>" class="p-2 mt-4 border-2 border-gray-300 rounded-md w-64" disabled>
                    <input type="text" value="<?php echo "MOBILE: ".htmlspecialchars($mobile); ?>" class="p-2 mt-4 border-2 border-gray-300 rounded-md w-64" disabled>
                   
                </form>
                
                <form action="userProfile3.php" method="post" enctype="multipart/form-data" class="flex flex-col items-center mt-2">
                    <label for="resume" class="font-semibold mt-4">Upload Resume</label>
                    <input type="file" name="resume" accept=".pdf,.doc,.docx" class="p-2 mt-2 border-2 border-gray-300 rounded-md w-64">
                    <input type="submit" value="Upload" class="px-3 py-1 mt-4 bg-blue-600 text-white rounded-md cursor-pointer" name="resume_submit">
                </form>
                
                <p class="font-semibold mt-4">My Skills</p>
                <input type="text" placeholder="Enter skills" value="<?php echo htmlspecialchars(trim($skill1 . ", " . $skill2 . ", " . $skill3 . ", " . $skill4 . ", " . $skill5, ", ")); ?>" class="p-2 mt-4 border-2 border-gray-300 rounded-md w-64" disabled><br>
                
                <a href="addskill.php"><button class="px-3 py-1 bg-blue-600 rounded-md">Edit Skill</button></a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
