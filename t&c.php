<?php 
session_start(); // Start or resume the session
require_once "dbcon.php";

// Check if session data is set
if (!isset($_SESSION["data1"])) {
    // If session data is not set, redirect to another page or handle it accordingly
    header("Location: error_page.php");
    exit; // Ensure script execution stops after redirection
}

// Get session data
$company_id = $_SESSION["data1"];
//echo "Company ID: " . htmlspecialchars($company_id) . "<br>";

$dis = "SELECT * FROM company_register WHERE company_id = '$company_id'";
$ress = $conn->query($dis);

if ($ress === false) {
    // Query failed, output error for debugging
    die("Error: " . $conn->error . " with query " . $dis);
}

while ($data = $ress->fetch_assoc()) {
    $email = $data['company_email'];
    $name = $data['company_name'];
 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #77c7c5 3px solid;
        }
        header a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header ul li {
            display: inline;
            padding: 0 20px 0 20px;
        }
        .content {
            background: #fff;
            padding: 30px;
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #555;
        }
        p {
            margin: 15px 0;
        }
        a {
            color: #77c7c5;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="content">
            <h1>Terms and Conditions</h1>
            <p>Last updated: May 28, 2024</p>
            
            <h2>1. Introduction</h2>
            <p>Welcome to <?php echo htmlspecialchars($name); ?>. These Terms and Conditions govern your use of our website and services. By accessing or using our website, you agree to comply with and be bound by these terms. If you disagree with any part of these terms, please do not use our website.</p>
            
            <h2>2. Intellectual Property Rights</h2>
            <p>Unless otherwise stated, Company <?php echo htmlspecialchars($name); ?> and/or its licensors own the intellectual property rights for all material on this website. All intellectual property rights are reserved. You may access this from Company Name for your own personal use subjected to restrictions set in these terms and conditions.</p>
            
            <h2>3. User Obligations</h2>
            <p>As a user of our website, you agree to use the site responsibly and comply with all applicable laws and regulations. You must not:</p>
            <ul>
                <li>Republish material from our website</li>
                <li>Sell, rent, or sub-license material from our website</li>
                <li>Reproduce, duplicate, or copy material from our website</li>
                <li>Redistribute content from our website</li>
            </ul>
            
            <h2>4. Limitation of Liability</h2>
            <p>In no event shall <?php echo htmlspecialchars($name); ?>, nor any of its officers, directors, and employees, be liable to you for anything arising out of or in any way connected with your use of this website, whether such liability is under contract, tort, or otherwise, and Company Name, including its officers, directors, and employees shall not be liable for any indirect, consequential, or special liability arising out of or in any way related to your use of this website.</p>
            
            <h2>5. Indemnification</h2>
            <p>You hereby indemnify to the fullest extent Company Name from and against any and all liabilities, costs, demands, causes of action, damages, and expenses (including reasonable attorney's fees) arising out of or in any way related to your breach of any of the provisions of these terms.</p>
            
            <h2>6. Governing Law</h2>
            <p>These terms will be governed by and interpreted in accordance with the laws of the State of [Your State], and you submit to the non-exclusive jurisdiction of the state and federal courts located in [Your State] for the resolution of any disputes.</p>
            
            <h2>7. Changes to These Terms</h2>
            <p>We reserve the right, at our sole discretion, to modify or replace these terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>
            
            <p>By continuing to access or use our website after those revisions become effective, you agree to be bound by the revised terms. If you do not agree to the new terms, please stop using the website.</p>
            
            <h2>8. Contact Us</h2>
            <p>If you have any questions about these Terms and Conditions, please contact us:</p>
            <ul>
                <li>By email: <?php echo htmlspecialchars($email); ?></li>
                <li>By phone: (123) 456-7890</li>
                <li>By mail: 123 gajpat naggar, Mumbai, Maharastra, 751010</li>
            </ul>
        </div>
    </div>
</body>
</html>
