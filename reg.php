<?php
$success = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cname = $_POST['name'];
    $mail = $_POST['email'];
    $service = $_POST['ser'];
    $qty = $_POST['qty'];
    $phone = $_POST['phone'];

    // Check if an image file is uploaded
    if (!empty($_FILES["image"]["tmp_name"]) && file_exists($_FILES["image"]["tmp_name"])) {
        // File upload handling
        $targetDir = "C:/xampp1/htdocs/PP/uploads/ser/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $message = "Error: File is not a valid image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            $message = "Error: Your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedFormats)) {
            $message = "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // If the file is uploaded successfully
            $message = "File uploaded successfully!";
        } else {
            $message = "Error: There was an error uploading your file.";
        }
    }

    // Database connection
    $con = new mysqli('localhost', 'root', '', 'printing');

    if ($con->connect_error) {
        $message .= ' Connection failed: ' . $con->connect_error;
    } else {
        // Insert data into the database
        $stmt = $con->prepare("INSERT INTO `order` (`name`, `email`, `service`, `qty`, `phone`, `image`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis", $cname, $mail, $service, $qty, $phone, $targetFile);

        if ($stmt->execute()) {
            $success = true;
            $message .= " Registered successfully!";
            $stmt->close();
        } else {
            $message .= " Error: " . $stmt->error;
        }

        $con->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
    /* php */
.success-message {
    background-color: #4CAF50;
    color: white;
    padding: 20px;
    border-radius: 10px;
    animation: fadeIn 2s ease-in-out;
}

.error-message {
    background-color: #FF5733;
    color: white;
    padding: 20px;
    border-radius: 10px;
    animation: fadeIn 2s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}
</style>
</head>
<body>
    <?php if ($success): ?>
        <div class="success-message"><?php echo $message; ?></div>
    <?php elseif (!empty($message)): ?>
        <div class="error-message"><?php echo $message; ?></div>
    <?php endif; ?>
   
</body>
</html>
