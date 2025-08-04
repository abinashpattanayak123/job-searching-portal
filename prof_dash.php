<?php
session_start(); // Start or resume the session
require_once "dbcon.php";

// Check if session data is set
if (!isset($_SESSION["data1"])) {
    
    header("Location: error_page.php");
    exit; 
}

// Get session data
$company_id = $_SESSION["data1"];
//echo "Company ID: " . htmlspecialchars($company_id) . "<br>";

$dis = "SELECT * FROM company_register WHERE company_id = ?";
$stmt = $conn->prepare($dis);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$ress = $stmt->get_result();


$email = $company_name = $company_mobile = $company_website = $image = "";

if ($ress) {
    if ($ress->num_rows > 0) {
        while ($data = $ress->fetch_assoc()) {
            $email = htmlspecialchars($data['company_email']);
            $company_name = htmlspecialchars($data['company_name']);
            $company_mobile = htmlspecialchars($data['company_mobile']);
            $company_website = htmlspecialchars($data['company_website']);
            $image = htmlspecialchars($data['company_logo']); 
        }
    } else {
        
        echo "No company found with the given ID.";
    }
} else {
    // Handle query error
    echo "Error in query: " . $conn->error;
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $upload_dir = 'uploads/';
    
    // Ensure the upload directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create directory if it does not exist
    }

    $uploaded_file = $upload_dir . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploaded_file)) {
        $image_path = $uploaded_file;
        $update_image_query = "UPDATE company_register SET company_logo = ? WHERE company_id = ?";
        $stmt = $conn->prepare($update_image_query);
        $stmt->bind_param("si", $image_path, $company_id);

        if ($stmt->execute()) {
           
            $image = $image_path; 
        } else {
            echo "Error saving image: " . $conn->error;
        }
    } else {
        echo "Error uploading image.";
    }
}
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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Gillroy";
        }
        html, body {
            height: 100%;
            width: 100%;
        }
        .main {
            height: 100%;
            width: 100%;
            position: relative;
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
            <div class="logo h-11">
                <img class="w-full h-full object-contain" src="logo.png" alt="job-seekers">
            </div>
            <div class="toggle-button p-2 mr-2 border-2 rounded-md active:text-orange-500 lg:hidden">
                <i class="fa-solid fa-bars fa-lg"></i>
            </div>
        </div>
        <div class="content flex">
            <div class="sidebar-div px-1 lg:inline-block w-[20%] bg-slate-100">
                <div class="sidebar h-full w-56 bg-slate-200 lg:block hidden relative z-50">
                    <div class="total-sidebar w-full h-40 flex justify-center">
                        <div class="image-div h-8 flex justify-center">
                            <div class="img-bg bg-blue-600 w-56 p-12 relative z-0"></div>
                            <div class="img-photo absolute top-12 rounded-full border-2 border-white h-28 w-28 z-50 bg-white">
                                <img id="sidebar_image" class="object-cover rounded-full h-28 w-28" src="<?php echo $image ?: './img.jpg'; ?>" alt="">
                            </div>
                        </div>                
                    </div>
                    <div class="name-div flex flex-col justify-center mt-2 h-20 border-b-4 border-white">
                        <p class="text-xl font-semibold text-center" id="nav_name"></p>
                        <p class="font-sans text-center" id="nav_id"></p>
                    </div>
                    <div class="dash-page w-56 h-60 bg-slate-200 flex flex-col items-center">
                        <div class="myprofile py-3 w-full">
                            <a href="viewjob.php"><p class="font-serif text-xl">My Jobs</p></a>
                        </div>
                        <div class="dashboard py-3 w-full">
                            <a href="t&c.php"><p class="font-serif text-xl">Terms & conditions</p></a>
                        </div>
                        <div class="applyjob py-3 w-full">
                           <a href="view_applications.php"> <p class="font-serif text-xl">Find Candidate</p></a>
                        </div>
                        <div class="logout py-3 no-hover">
                            <a href="company_login.php"><button class="px-3 py-1 bg-red-600 rounded-md">Logout</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-content inline-block w-full max-h-full bg-slate-200 overflow-x-auto w[100%] ml-[-25px]">
                <div class="profile-card bg-[#ffffff] p-3 mt-2 ml-3 mr-4 relative">
                    <p class="text-xl font-semibold text-[#2473b3] mb-4">Profile Details</p>
                    <div class="profile-details lg:flex flex-wrap">
                        <div class="name-detail pl-1 pr-1 flex">
                            <i class="fa-solid fa-user mt-3 p-2 text-blue-700"></i>
                            <div class="name">
                                <p class="text-base font-sans text-[#6c757d] ml-11">Company Name:</p>
                                <p class="text-lg font-serif text-[#797979] mb-4 ml-11" id="company_name"></p>
                            </div>
                        </div>
                        <div class="email-detail pl-1 pr-1 flex">
                            <i class="fa-solid fa-envelope mt-3 p-2 text-blue-700"></i>
                            <div class="email">
                                <p class="text-base font-sans text-[#6c757d] ml-11">Email</p>
                                <p class="text-lg font-serif text-[#797979] mb-4 ml-11" id="company_email"></p>
                            </div>
                        </div>
                        <div class="mobile-detail pl-1 pr-1 flex">
                            <i class="fa-solid fa-mobile-screen mt-3 p-2 text-blue-700"></i>
                            <div class="mobile">
                                <p class="text-base font-sans text-[#6c757d] ml-11">Contact No.</p>
                                <p class="text-lg text-[#797979] mb-4 ml-11" id="company_mobile"></p>
                            </div>
                        </div>
                        <div class="qualification pl-1 pr-1 flex">
                            <i class="fa-solid fa-school mt-3 p-2 text-blue-700"></i>
                            <div class="h-qualification">
                                <p class="text-base font-sans text-[#6c757d] ml-11">Website</p>
                                <p class="text-lg text-[#797979] mb-4 ml-11" id="company_website"></p>
                            </div>
                        </div>
                        <div class="profile-pic pl-1 pr-1 flex">
                            <i class="fa-regular fa-image mt-3 p-2 text-blue-700"></i>
                            <div class="h-qualification">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="input-group mb-3">
                                        <input type="file" name="image" class="form-control" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" required>
                                        <input type="hidden" name="company_email" value="<?php echo $email; ?>">
                                        <label class="input-group-text" for="inputGroupFile01">Upload</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="crud-card bg-gray-200 h-full p-3 mt-2 relative flex flex-wrap justify-center gap-x-3">
                    <div class="create-div h-[120px] w-[210px] p-3 bg-[#4a9ad8] rounded-md cursor-pointer" onclick="window.location.href='createjob.php';">
                        <p class="text-white font-sans text-2xl">Create-Jobs</p>
                    </div>
                    <div class="read-div h-[120px] w-[210px] p-3 bg-[#ffc107] rounded-md cursor-pointer" onclick="window.location.href='viewjob.php';">
                        <p class="text-white font-sans text-2xl">View-Jobs</p>
                    </div>
                    <div class="update-div h-[120px] w-[210px] p-3 bg-[#8bc34a] rounded-md cursor-pointer" onclick="window.location.href='view_applications.php';">
                        <p class="text-white font-sans text-2xl">View-Applications</p>
                    </div>
                    <div class="delete-div h-[120px] w-[210px] p-3 bg-[#f44336] rounded-md cursor-pointer" onclick="window.location.href='deletejob.php';">
                        <p class="text-white font-sans text-2xl">Delete-Jobs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the toggle button and the sidebar elements
            const toggleButton = document.querySelector('.toggle-button');
            const sidebar = document.querySelector('.sidebar');

            // Initially hide the sidebar (except on large screens)
            sidebar.classList.add('hidden');

            // Event listener for toggle button click
            toggleButton.addEventListener('click', function () {
                // Toggle the sidebar visibility
                sidebar.classList.toggle('hidden');
            });
        });

        var company_id = "<?php echo $company_id; ?>";
        document.getElementById("nav_id").innerHTML = company_id;

        var company_name = "<?php echo $company_name; ?>";
        document.getElementById("company_name").innerHTML = company_name;

        var company_email = "<?php echo $email; ?>";
        document.getElementById("company_email").innerHTML = company_email;

        var company_mobile = "<?php echo $company_mobile; ?>";
        document.getElementById("company_mobile").innerHTML = company_mobile;

        document.getElementById("nav_name").innerHTML = company_name;

        var company_website = "<?php echo $company_website; ?>";
        document.getElementById("company_website").innerHTML = company_website;

        var sidebar_image = "<?php echo $image ?: './img.jpg'; ?>";
        document.getElementById("sidebar_image").src = sidebar_image;
    </script>
</body>
</html>
