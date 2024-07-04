<?php
// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tanmay";

$conn = new mysqli($localhost, $root, $password, $tanmay);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch user data from the database based on email
$user_email = "user@example.com"; // Change this to the email you want to fetch data for

$sql = "SELECT * FROM entry WHERE email = '$user_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $username = $row["username"];
  $email = $row["email"];
  $phone = $row["phone"];
  $address = $row["address"];
} else {
  echo "No user found";
}

// Handle profile photo upload
if(isset($_FILES['profile-photo'])) {
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["profile-photo"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["profile-photo"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
  // Check if file already exists
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["profile-photo"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["profile-photo"]["tmp_name"], $target_file)) {
      echo "The file ". htmlspecialchars( basename( $_FILES["profile-photo"]["name"])). " has been uploaded.";
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
}
$conn->close();
?>