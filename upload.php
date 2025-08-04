<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <h1>Upload image</h1>
        Image: <input type="file" name="image"><br>
        <input type="submit" name="submit">
    </form>

    <?php
    require_once "dbcon.php";

    if (isset($_POST['submit'])) {
        $image = $_FILES['image'];
        $imagename = $image['name'];
        $targetDir = "job_portal/";
        $imagepath = $targetDir . $imagename;

        // Check if the target directory exists, if not create it
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Attempt to move the uploaded file
        if (move_uploaded_file($image['tmp_name'], $imagepath)) {
            echo "Upload Successfully!<br>";

            $qry = "INSERT INTO img (photo) VALUES ('$imagename')";

            if ($conn->query($qry) === TRUE) {
                echo "Data Inserted Successfully!<br>";
            } else {
                echo "Error: " . $conn->error . "<br>";
            }
        } else {
            echo "Error uploading file.<br>";
        }
    }

    // Fetch and display the image
    $qry1 = "SELECT * FROM img";
    $res = $conn->query($qry1);

    if ($res->num_rows > 0) {
        while ($sng = $res->fetch_assoc()) {
            echo "<p><img src='job_portal/" . $sng['photo'] . "' alt='Image' width='200'></p>";
        }
    } else {
        echo "No images found.";
    }

    $conn->close();
    ?>
</body>
</html>
