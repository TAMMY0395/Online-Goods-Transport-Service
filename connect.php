<?php
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $usertype = $_POST['usertype'];

    // Establishing connection to the database
    $conn = new mysqli('localhost','root','','tanmay');
    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }
    else {
        // Check if username or email already exists
        $check_query = "SELECT * FROM entry WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($check_query);
        if ($result->num_rows > 0) {
            // Username or email already exists
            echo "<script>alert('Username or email already exists. Please choose a different one.');</script>";
        } elseif ($password !== $confirmpassword) {
            // Passwords don't match
            echo "<script>alert('Passwords do not match. Please enter matching passwords.');</script>";
        } else {
            // Insertion query
            $stmt = $conn->prepare("INSERT INTO entry(username, email, password, confirmpassword, usertype) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $password, $confirmpassword, $usertype); 
            $stmt->execute();
            echo "<script>alert('Registered Successfully');
            document.location.href = 'Homepage.html';
            </script>";
            $stmt->close();
        }
        $conn->close();
    }
?>


