
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

// Fetch the user ID and email
$userdata = $ress1->fetch_assoc();
$userid = $userdata['user_id'];
$name = $userdata['name'];
$email = $userdata['email'];

// Query to fetch user's profile picture based on email
$qry_img = "SELECT photo FROM img WHERE email = '$email'";
$ress_img = $conn->query($qry_img);

// Check if query was successful and if it returned any result
if ($ress_img && $ress_img->num_rows > 0) {
    $profile_data = $ress_img->fetch_assoc();
    $profile_picture = $profile_data['photo'];
} else {
    // Set profile picture to null if not found in the database
    $profile_picture = null;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>terms & conditions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
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

      /* Add this new CSS */
      .dash-page > div:not(.no-hover) {
        transition: background-color 0.3s, transform 0.3s;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
      }

      .dash-page > div:not(.no-hover):hover {
        background-color: #e07416; /* Change this color as needed */
        transform: scale(1.1); /* Adjust the scale as needed */
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
      <nav
        class="flex justify-between px-1 py-1 mr-1 bg-[#f1f1f1] z-50 w-full h-[45px] fixed"
      >
        <img
          src="./company/job-seekers-high-resolution-logo-black-transparent.png"
          class="ml-3 h-12"
          alt=""
        />
        <div
          class="toggle-button p-2 border-2 rounded-md active:text-orange-500 lg:hidden"
        >
          <i class="fa-solid fa-bars fa-lg"></i>
        </div>
      </nav>
      <div class="content flex">
        <div class="sidebar-div px-1 lg:inline-block max-w-[23%]">
          <div
            class="sidebar h-full w-56 bg-slate-200 lg:block hidden relative z-50"
          >
            <div class="total-sidebar w-full h-40 flex justify-center">
              <div class="image-div h-8 flex justify-center">
                <div class="img-bg bg-blue-600 w-56 p-12 relative z-0"></div>
                <div
                  class="img-photo absolute top-12 rounded-full border-2 border-white h-28 w-28 z-10 bg-white"
                >
                  <img
                    class="object-cover rounded-full h-28 w-28"
                    src="<?php echo $profile_picture; ?>"
                    alt=""
                  />
                </div>
              </div>
            </div>
            <div
              class="name-div flex flex-col justify-center mt-2 h-20 border-b-4 border-white"
            >
              <p class="text-xl font-semibold text-center" id="nav_name"></p>
              <p class="font-sans text-center" id=nav_id></p>
            </div>
            <div
              class="dash-page w-56 h-60 bg-slate-200 flex flex-col items-center"
            >
              <div class="myprofile py-3 w-full">
                <p class="font-serif text-xl"><a href="userProfile3.php">My Profile</a></p>
              </div>
              <div class="Dashboard py-3 w-full">
                <p class="font-serif text-xl"><a href="search_job.php">Dashboard</a></p>
              </div>
              <div class="terms&condition py-3 w-full">
                <p class="font-serif text-xl">
                  <a href="terms&condition.php">Terms & Condition</a>
                </p>
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
        <div class="dashboard w-full relative z-0">
          <div
            class="top-menu bg-gradient-to-r from-gray-200 to-gray-400 p-14 mt-1 ml-2 w-full text-center flex flex-col lg:h-[650px]">
              <h1
                class="text-center text-orange-600 text-3xl font-serif underline">
                Terms and Conditions
              </h1>
              <div class="terms text-start mt-3">
                
                    <p class="text-lg mt-3"><span class="mr-2 font-semibold">1.</span> Acceptance of Terms: By accessing and using this job portal, you agree to comply with these terms and conditions. If you disagree, please refrain from using the portal.
                    </p>
                
                
                    <p class="text-lg mt-3"><span class="mr-2 font-semibold">2.</span> User Responsibilities: Users must provide accurate and complete information during registration and job application processes. Misrepresentation or fraudulent activities are strictly prohibited.
                    </p>
                

                <p class="text-lg mt-3">
                    <span class="mr-2 font-semibold">3.</span> Privacy Policy: Personal information collected is subject to our privacy policy, which ensures the protection and lawful processing of user data.
                </p>

                <p class="text-lg mt-3">
                    <span class="mr-2 font-semibold">4.</span> Prohibited Conduct: Users must not post any offensive, inappropriate, or illegal content. Any such activity will result in immediate termination of access.
                </p>

                <p class="text-lg mt-3"><span class="mr-2 font-semibold">5.</span> Intellectual Property: All content, including logos, graphics, and software, is the intellectual property of the job portal and protected by copyright laws. Unauthorized use is prohibited.
                </p>

                <p class="text-lg mt-3">
                    <span class="mr-2 font-semibold">6.</span> Liability Limitation: The job portal is not liable for any direct or indirect damages resulting from the use or inability to use the service. Users acknowledge that the portal does not guarantee job placements.
                </p>

                <p class="text-lg mt-3">
                    <span class="mr-2 font-semibold">7.</span> Modification of Terms: The portal reserves the right to modify these terms at any time. Continued use after changes constitute acceptance of the new terms.
                </p>

                <p class="text-lg mt-3">
                    <span class="mr-2 font-semibold">8.</span> Governing Law: These terms are governed by and construed in accordance with the laws of the jurisdiction in which the company operates. Any disputes will be resolved in the appropriate courts of this jurisdiction.
                </p>
            </div>
              </div>
            </div>
          </div>
        </div>
      <footer class="mt-36">
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
              <p>
                We're here to help! If you have any questions, feedback, or need
                assistance with our job portal, please reach out to us.
              </p>
              <p>
                <span class="font-gilroy">Email: </span>support@jobportal.com
              </p>
              <p><span class="font-gilroy">Phone: </span>+1 (555) 123-4567</p>
              <p>
                <span class="font-gilroy">Address:</span> 123 Career Lane,
                Jobville, NY 10001, USA
              </p>
              <br />
              <p class="font-semibold text-lg">Customer Support Hours:</p>
              <p>Monday to Friday: 9:00 AM - 6:00 PM (EST)</p>
              <p>Saturday: 10:00 AM - 4:00 PM (EST)</p>
              <p>Sunday: Closed</p>
              <br />
              <p>
                You can also fill out our
                <span class="text-blue-500 text-lg hover:text-orange-500"
                  ><a href="contactus.php">contact form </a></span
                >
                on our website, and we'll get back to you as soon as possible.
                We look forward to assisting you!
              </p>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Get the toggle button and the sidebar elements
        const toggleButton = document.querySelector(".toggle-button");
        const sidebar = document.querySelector(".sidebar");

        // Initially hide the sidebar (except on large screens)
        sidebar.classList.add("hidden");

        // Event listener for toggle button click
        toggleButton.addEventListener("click", function () {
          // Toggle the sidebar visibility
          sidebar.classList.toggle("hidden");
        });
      });
      var userid = "<?php echo $userid; ?>";
            var name = "<?php echo $name; ?>";

      if(userid && name) {
                document.getElementById("nav_id").innerHTML = userid;
                document.getElementById("nav_name").innerHTML = name;
            }
    </script>
  </body>
</html>