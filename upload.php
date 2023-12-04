<?php
if(isset($_POST["submit"])){
    $target_dir = "C:/xampp1/htdocs/PP/uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // Insert image information into the database
            $image_name = $_FILES["fileToUpload"]["name"];
            $image_path = $target_file;
            
            // Establish a database connection (you should set your own credentials)
            $conn = new mysqli("localhost", "root", "", "printing");

            // Check for a successful connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to insert image information into the database
            $sql = "INSERT INTO image_gallery (image_name, image_path) VALUES ('$image_name', '$image_path')";

            // Execute the SQL query
            if ($conn->query($sql) === TRUE) {
                echo "Image uploaded successfully and added to the database.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            // Close the database connection
            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}
?>
