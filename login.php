<?php
session_start();

// Database connection
$conn = new mysqli('localhost','root','','tanmay');
if ($conn->connect_error) {
    die('Connection Failed: '. $conn->connect_error);
}
  
// Retrieve form data
$email = $_POST['email'];
$password = $_POST['password'];

// Retrieve user from database
$stmt = $conn->prepare("SELECT * FROM entry WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt_result = $stmt->get_result();

if ($stmt_result->num_rows == 1) {
    $data = $stmt_result->fetch_assoc();
    if (password_verify($password, $data['password'])) {
        // Valid username and password
        $_SESSION['email'] = $data['email'];
        // Redirect based on successful login
        header('Location: homepage.html');
    } else {
        // Invalid password
        echo "<script>alert('Invalid email or Password'); window.location.href = 'index.html';</script>";
    }
} else {
    // Invalid username
    echo "<script>alert('Invalid email or Password'); window.location.href = 'index.html';</script>";
}

$stmt->close();
$conn->close();
?>
