<?php
session_start(); // Start or resume the session
require_once "dbcon.php";
$user_id = $_SESSION["userid"];
// Check if session data is set
if (!isset($_SESSION["userid"])) {
    // If session data is not set, redirect to an error page or handle it accordingly
    header("Location: error_page.php");
    exit; // Ensure script execution stops after redirection
}
// Query to get user_id from register table
$qry = "SELECT * FROM register WHERE user_id = '$user_id'";
$ress1 = $conn->query($qry);

// Check if query was successful and if it returned any result
if ($ress1 === false || $ress1->num_rows == 0) {
    die("Error: Unable to retrieve user ID from database.");
}

// Fetch the user ID
$userdata = $ress1->fetch_assoc();
$userid = $userdata['user_id'];


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    // Sanitize the input data
    $skills = htmlspecialchars($_POST['skills']);
    $location = htmlspecialchars($_POST['location']);

    // Store the data in session variables
    $_SESSION['skills'] = $skills;
    $_SESSION['location'] = $location;
    $_SESSION['user_id'] = $userid;

    // Redirect to the search results page
    header("Location: search_main.php");
    exit; // Ensure script execution stops after redirection
}

// Get session data


$dis = "SELECT * FROM register WHERE user_id = '$user_id'";
$ress = $conn->query($dis);

if ($ress === false) {
    // Query failed, output error for debugging
    die("Error: " . $conn->error . " with query " . $dis);
}

while ($data = $ress->fetch_assoc()) {
    $email = $data['email'];
    $name = $data['name'];
    $mobile = $data['mobile'];
    $userid = $data['user_id'];
    $location = $data['location'];
}

// Fetch user skills from user_skill table
$dis1 = "SELECT * FROM user_skill WHERE user_id = '$user_id'";
$ress1 = $conn->query($dis1);

if ($ress1 === false) {
    // Query failed, output error for debugging
    die("Error: " . $conn->error . " with query " . $dis1);
}

// Initialize skill variables with empty strings
$skill1 = $skill2 = $skill3 = $skill4 = $skill5 = "";

// Check if any skills were fetched
if ($ress1->num_rows > 0) {
    $data1 = $ress1->fetch_assoc(); // Fetch from $ress1 not $ress
    $skill1 = $data1['skill1'];
    $skill2 = $data1['skill2'];
    $skill3 = $data1['skill3'];
    $skill4 = $data1['skill4'];
    $skill5 = $data1['skill5'];
}
// Fetch image from database
$imageQuery = "SELECT photo FROM img WHERE email = ?";
$stmt4 = $conn->prepare($imageQuery);
$stmt4->bind_param("s", $email);
$stmt4->execute();
$result = $stmt4->get_result();
$imageData = $result->fetch_assoc();
$profileImage = isset($imageData['photo']) ? $imageData['photo'] : "";

// Now $profileImage contains the filename of the user's profile image, if it exists

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing Apply Job</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>
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
<body class="h-screen w-screen bg-[#F1F1F1] overflow-x-hidden">
    <div class="main w-full h-full relative">
        <nav class="w-full h-[45px] fixed flex justify-between px-1 py-1 mr-1 bg-[#f1f1f1] z-0">
            <img src="logo.png" class=" ml-3 h-12" alt="">
            <div class="toggle-button p-2 border-2 rounded-md active:text-orange-500 lg:hidden">
                <i class="fa-solid fa-bars fa-lg "></i>
            </div>
        </nav>
        <div class="content flex">
            <div class="sidebar-div px-1 lg:inline-block max-w-[23%]">
                <div class="sidebar h-full w-56 bg-slate-200 lg:block hidden relative z-50">
                    <div class="total-sidebar w-full h-40 flex justify-center">
                        <div class="image-div h-8 flex justify-center">
                            <div class="img-bg bg-blue-600 w-56 p-12 relative z-0"></div>
                            <div class="img-photo absolute top-12 rounded-full border-2 border-white h-28 w-28 z-50 bg-white">
                                <img class="object-cover rounded-full h-28 w-28" src="<?php echo $profileImage ? 'job_portal/' . $profileImage : 'default_image.jpg'; ?>" alt="">
                            </div>
                        </div>                
                    </div>
                    <div class="name-div flex flex-col justify-center mt-2 h-20 border-b-4 border-white">
                        <p class="text-xl font-semibold text-center" id="nav_name"></p>
                        <p class="font-sans text-center" id="nav_id"></p>
                    </div>
                    <div class="dash-page w-56 h-60 bg-slate-200 flex flex-col items-center">
                        <div class="myprofile py-3 w-full">
                            <p class="font-serif text-xl"><a href="userProfile3.php">My Profile</a></p>
                        </div>
                        <div class="Dashboard py-3 w-full">
                            <p class="font-serif text-xl"><a href="search_job.php">Dashboard</a></p>
                        </div>
                        <div class="terms&condition py-3 w-full">
                            <p class="font-serif text-xl"><a href="terms&condition.php">Terms & Condition</a></p>
                        </div>
                        <div class="applyjob py-3 w-full">
                            <p class="font-serif text-xl"><a href="contactus.php">Contact Us</a></p>
                        </div>
                        <div class="logout py-3 no-hover">
                            <a href="login.php"><button class="px-3 py-1 bg-red-600 rounded-md">Logout</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard w-full z-0 relative">
                <div class="top-menu bg-gradient-to-r from-blue-500 to-blue-900 p-14 mt-1 ml-2 relative z-0 w-full text-center flex flex-col lg:h-[250px]">
                    <h1 class="text-white font-bold text-3xl">Find Your Dream Job</h1>
                    <h2 class="text-white font-sans mb-2">Explore thousands of job opportunities</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                        <input type="text" name="skills" placeholder="Enter your skills" class="p-2" id="skills">
                        <input type="text" name="location" placeholder="Location" class="p-2" id="location_input">
                        <input type="submit" value="Search" class="p-2 bg-orange-500 text-white hover:cursor-pointer" name="search">
                    </form>
                </div>
                <div class="bottom-menu p-12 mt-1 ml-2 relative z-0 w-full text-center bg-slate-300 lg:h-[65%]">
                    <div class="job-cards w-full h-full lg:flex justify-center">
                        <div class="job-card1 bg-gray-100 m-2 p-5 h-48 rounded-md lg:w-80">
                            <p class="font-semibold text-xl underline">Search For Jobs As Per Your Skills</p>
                            <div class="gap-2 mt-3">
                                <span class="text-slate-600 font-sans border-2 border-gray-500 rounded-lg"><?php echo htmlspecialchars($skill1); ?></span>
                                <span class="text-slate-600 font-sans border-2 border-gray-500 rounded-lg"><?php echo htmlspecialchars($skill2); ?></span>
                                <span class="text-slate-600 font-sans border-2 border-gray-500 rounded-lg"><?php echo htmlspecialchars($skill3); ?></span>
                                <span class="text-slate-600 font-sans border-2 border-gray-500 rounded-lg"><?php echo htmlspecialchars($skill4); ?></span>
                                <span class="text-slate-600 font-sans border-2 border-gray-500 rounded-lg"><?php echo htmlspecialchars($skill5); ?></span> <br>
                                <a href="skill_search.php"><button class="bg-[#f97316] px-4 py-[2px] rounded-xl mt-2 hover:bg-green-600 hover:cursor-pointer">Search Now➡️</button></a>
                                
                            </div>
                        </div>

                        <div class="job-card1 bg-gray-100 m-2 p-5 h-48 rounded-md lg:w-80">
                            <p class="font-semibold text-xl underline">Search For Available Jobs in Your Location</p>
                            <div class="gap-2 mt-3">
                                <span class="text-slate-600 font-sans border-2 border-gray-500 rounded-lg px-2"><?php echo $location; ?></span> <br>
                                <a href="location_search.php"><button class="bg-[#f97316] px-4 py-[2px] rounded-xl mt-2 hover:bg-green-600 cursor-pointer">Search Now➡️</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span></span>
        <footer class="mt-40">
            <div class="footer w-full h-[400px] bg-gray-50">
                <p class="font-gilroy text-2xl text-center">Explore Big Companies</p>
                <div class="footer-img flex justify-evenly flex-wrap mt-5">
                    <a href="https://r.search.yahoo.com/_ylt=AwrO_ZEl6U5mE1EEq.JXNyoA;_ylu=Y29sbwNncTEEcG9zAzEEdnRpZAMEc2VjA3Ny/RV=2/RE=1717657125/RO=10/RU=https%3a%2f%2fwww.capgemini.com%2f/RK=2/RS=NriN1kqUemUbBXo4t_hJLt1nf40-"><img class="h-[70px] w-[190px]" src="WhatsApp Image 2024-05-18 at 1.18.56 AM.jpeg" alt=""></a>
                    <a href=""><img class="h-[70px] w-[190px]" src="WhatsApp Image 2024-05-18 at 1.18.57 AM (1).jpeg" alt=""></a>
                    <a href="https://r.search.yahoo.com/_ylt=Awrg0LAA605msXoGVsdXNyoA;_ylu=Y29sbwNncTEEcG9zAzIEdnRpZAMEc2VjA3Ny/RV=2/RE=1717657601/RO=10/RU=https%3a%2f%2fmindfireinc.com%2fcareers%2f/RK=2/RS=4FGCqM7oo1jEWrsHl2K_NrdqI_o-"><img class="h-[70px] w-[190px]" src="WhatsApp Image 2024-05-18 at 1.18.57 AM (2).jpeg" alt=""></a>
                    <a href="https://r.search.yahoo.com/_ylt=Awr9zNZm6k5mPn4E469XNyoA;_ylu=Y29sbwNncTEEcG9zAzEEdnRpZAMEc2VjA3Ny/RV=2/RE=1717657446/RO=10/RU=https%3a%2f%2fwww.tcs.com%2f/RK=2/RS=JAMc3yh1uWdF1._XxY3lLY4hEW0-"><img class="h-[80px] w-[190px]" src="WhatsApp Image 2024-05-18 at 1.18.57 AM (3).jpeg" alt=""></a>
                    <a href="https://r.search.yahoo.com/_ylt=AwrOtOLh6k5mIckFW5xXNyoA;_ylu=Y29sbwNncTEEcG9zAzEEdnRpZAMEc2VjA3Ny/RV=2/RE=1717657570/RO=10/RU=https%3a%2f%2fwww.ltimindtree.com%2f/RK=2/RS=UgOh0XYMFetCDzY9WSg8MRONpW8-"><img class="h-[70px] w-[190px]" src="WhatsApp Image 2024-05-18 at 1.18.57 AM (4).jpeg" alt=""></a>
                    <a href="https://r.search.yahoo.com/_ylt=AwrOtyWO6k5mIE0G211XNyoA;_ylu=Y29sbwNncTEEcG9zAzEEdnRpZAMEc2VjA3Ny/RV=2/RE=1717657487/RO=10/RU=https%3a%2f%2fwww2.deloitte.com%2fus%2fen.html/RK=2/RS=yB9jFmdo5K0UuHPCbg6DIy7nQ4M-"><img class="h-[70px] w-[190px]" src="WhatsApp Image 2024-05-18 at 1.18.57 AM.jpeg" alt=""></a>
                </div>
        

                <div class="contact-us bg-[#cbd5e1]">
                    <h3 class="text-center text-3xl text-[#ea580c]">Contact Us</h3> 
                    <div class="cont-1 px-3">
                        <p>We're here to help! If you have any questions, feedback, or need assistance with our job portal, please reach out to us.</p>
                        <p><span class="font-gilroy">Email: </span>support@jobportal.com</p>
                        <p><span class="font-gilroy">Phone: </span>+1 (555) 123-4567</p>
                        <p><span class="font-gilroy">Address:</span> 123 Career Lane, Jobville, NY 10001, USA</p>
                        <br>
                        <p class="font-semibold text-lg">Customer Support Hours:</p>
                        <p>Monday to Friday: 9:00 AM - 6:00 PM (EST)</p>
                        <p>Saturday: 10:00 AM - 4:00 PM (EST)</p>
                        <p>Sunday: Closed</p> <br>
                        <p>You can also fill out our <span class="text-blue-500 text-lg hover:text-orange-500"><a href="contactus.php">contact form </a></span>on our website, and we'll get back to you as soon as possible. We look forward to assisting you!</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
   
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.querySelector('.toggle-button');
            const sidebar = document.querySelector('.sidebar');

            // Initially hide the sidebar (except on large screens)
            sidebar.classList.add('hidden');

            // Event listener for toggle button click
            toggleButton.addEventListener('click', function () {
                sidebar.classList.toggle('hidden');
            });

            // PHP variables to JavaScript
            var userid = "<?php echo $userid; ?>";
            var name = "<?php echo $name; ?>";

            // Check if variables are assigned correctly
            console.log('User ID:', userid);
            console.log('Name:', name);

            // Set the inner HTML of nav_id and nav_name
            if(userid && name) {
                document.getElementById("nav_id").innerHTML = userid;
                document.getElementById("nav_name").innerHTML = name;
            }
        });
    </script>
    
    
</body>
</html>
