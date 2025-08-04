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

while ($data = $ress->fetch_assoc()) {
    $email = $data['email'];
    $name = $data['name'];
    $mobile = $data['mobile'];
    $userid = $data['user_id'];
    $location = $data['location'];
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

        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
        <div class="logo h-11">
            <img class="w-full h-full object-contain" src="./job-seekers-high-resolution-logo-black-transparent.png" alt="job-seekers">
        </div>
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
                            <img class="object-cover rounded-full h-28 w-28" src="./img.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="name-div flex flex-col justify-center mt-2 h-20 border-b-4 border-white">
                    <p class="text-xl font-semibold text-center" id="nav_name"><?php echo $name; ?></p>
                    <p class="font-sans text-center" id="nav_id">ID: <?php echo $userid; ?></p>
                </div>
                <div class="dash-page w-56 h-60 bg-slate-200 flex flex-col items-center">
                    <div class="myprofile py-3 w-full">
                        <p class="font-serif text-xl"><a href="">My Profile</a></p>
                    </div>
                    <div class="dashboard py-3 w-full">
                        <a href="search_job.php"><p class="font-serif text-xl">Dashboard</p></a>
                    </div>
                    <div class="applyjob py-3 w-full">
                        <p class="font-serif text-xl"><a href="">Find Candidate</a></p>
                    </div>
                    <div class="logout py-3 no-hover">
                        <a href="login.php"><button class="px-3 py-1 bg-red-600 rounded-md">Logout</button></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content flex flex-col items-center w-full max-h-full bg-slate-200 overflow-x-auto">
            <div class="flex flex-col items-center mt-4">
                <h1 class="font-bold text-[30px]">Profile</h1>
                <div class="relative mt-2">
                    <div class="img-photo rounded-full border-2 border-white h-28 w-28 z-50 bg-white">
                        <img class="object-cover rounded-full h-28 w-28" src="./img.jpg" alt="">
                    </div>
                </div>
                <input type="text" class="p-2 mt-4 border-2 border-gray-300 rounded-md w-64" id="name"disabled >
                <input type="text" class="p-2 mt-4 border-2 border-gray-300 rounded-md w-64" disabled id="email">
                <input type="text" class="p-2 mt-4 border-2 border-gray-300 rounded-md w-64" disabled id="mobile">

                <p class="font-semibold">My Resume</p>
                <input type="file" accept=".pdf,.doc,.docx" class="p-2 mt-1 border-2 border-gray-300 rounded-md w-64">
                <button id="add-skill-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md mt-2">Add Skill</button>
                <div id="skills-box" class="w-64 border-2 border-gray-300 rounded-md p-4 mt-2 overflow-y-auto" style="max-height: 200px;">
                    <p class="text-center font-semibold">Skills</p>
                    <div id="skills-list"></div>
                </div>
            </div>
            <form id="skill-modal" class="modal" action="userProfile.php" method="post">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 class="text-xl font-bold mb-4">Add Skills</h2>
                    <div class="flex flex-col mb-4">
                        <label for="skill1" class="mb-2">Skill 1:</label>
                        <input type="text" id="skill1" name="skill1" class="p-2 border-2 border-gray-300 rounded-md">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="skill2" class="mb-2">Skill 2:</label>
                        <input type="text" id="skill2" name="skill2" class="p-2 border-2 border-gray-300 rounded-md">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="skill3" class="mb-2">Skill 3:</label>
                        <input type="text" id="skill3" name="skill3" class="p-2 border-2 border-gray-300 rounded-md">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="skill4" class="mb-2">Skill 4:</label>
                        <input type="text" id="skill4" name="skill4" class="p-2 border-2 border-gray-300 rounded-md">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="skill5" class="mb-2">Skill 5:</label>
                        <input type="text" id="skill5" name="skill5" class="p-2 border-2 border-gray-300 rounded-md">
                    </div>
                    <button type="submit" id="submit-skill-btn" class="px-4 py-2 bg-green-600 text-white rounded-md" name="add">Add Skills</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "dbcon.php"; // Assuming this file includes database connection code

    // insert data
    $skill1 = $_POST['skill1'];
    $skill2 = $_POST['skill2'];
    $skill3 = $_POST['skill3'];
    $skill4 = $_POST['skill4'];
    $skill5 = $_POST['skill5'];
    
    

    // Check if passwords match
    if(isset($_POST['add'])){
        $qry = "INSERT INTO user_skill (user_id,skill1,skill2,skill3,skill4,skill5) VALUES ('$userid','$skill1','$skill2','$skill3','$skill4','$skill5')";
        $res = $conn->query($qry);
    }
}

?>
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

        // Update the addSkill function to collect skills from form inputs
        function addSkill(event) {
            event.preventDefault();
            const skillInputs = document.querySelectorAll('#skill-modal input[type="text"]');
            const skillsList = document.getElementById('skills-list');
            const existingSkills = Array.from(skillsList.querySelectorAll('span')).map(skillElement => skillElement.textContent);
            const newSkills = [];

            skillInputs.forEach(function(input) {
                const skill = input.value.trim();
                if (skill && !existingSkills.includes(skill)) {
                    newSkills.push(skill);
                }
            });

            // Add collected skills to the skills list
            newSkills.forEach(function(skill) {
                const skillElement = document.createElement('div');
                skillElement.className = 'flex justify-between items-center p-2 bg-gray-100 border-b border-gray-300';
                skillElement.innerHTML = `<span>${skill}</span><i class="fas fa-trash delete-icon"></i>`;
                skillsList.appendChild(skillElement);

                // Add event listener to the delete icon
                skillElement.querySelector('.delete-icon').addEventListener('click', function() {
                    skillsList.removeChild(skillElement);
                });
            });

            modal.style.display = 'none';

            // Reset form inputs
            skillInputs.forEach(function(input) {
                input.value = '';
            });
        }

        document.getElementById('skill-modal').addEventListener('submit', addSkill);

        // Update the modal with the current skills
        function updateModal() {
            const skillsList = document.getElementById('skills-list');
            const skillInputs = document.querySelectorAll('#skill-modal input[type="text"]');
            
            // Find the input fields in the modal that are not already filled with skills
            const emptyInputs = Array.from(skillInputs).filter(input => !input.value.trim());

            // Find the next skill in the first 5 skills of the skills list to populate the empty input fields
            let nextSkillIndex = 0;
            for (let i = 0; i < emptyInputs.length && nextSkillIndex < 5; i++, nextSkillIndex++) {
                const skillElement = skillsList.children[nextSkillIndex];
                if (skillElement) {
                    const skillSpan = skillElement.querySelector('span');
                    if (skillSpan) {
                        const skillText = skillSpan.textContent;
                        // Check if the skill is not already in the input fields
                        const existingSkills = Array.from(skillInputs).map(input => input.value.trim());
                        if (!existingSkills.includes(skillText)) {
                            emptyInputs[i].value = skillText;
                        } else {
                            // If skill is already in input fields, decrement the loop counter
                            i--;
                        }
                    }
                }
            }
        }

        const modal = document.getElementById('skill-modal');
        const addSkillBtn = document.getElementById('add-skill-btn');
        const closeModal = document.querySelector('.close');
        const submitSkillBtn = document.getElementById('submit-skill-btn');

        addSkillBtn.addEventListener('click', function() {
            modal.style.display = 'block';
            updateModal();
        });

        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        submitSkillBtn.addEventListener('click', addSkill);

        const skillInput = document.getElementById('skill-input');
        skillInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                addSkill();
            } 
        });
    });

    var userid = "<?php echo $id; ?>";
    var name = "<?php echo $name; ?>";
    if (userid && name) {
        document.getElementById("nav_id").innerHTML = "ID: " + userid;
        document.getElementById("nav_name").innerHTML = name;
    }
    var username= "<?php echo $name; ?>";
    document.getElementById("name").value = username;
    
</script>
</div>
</body>
</html>
