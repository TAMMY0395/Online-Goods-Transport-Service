<?php
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Database connection here
    $con = new mysqli("localhost","root","","tanmay");
    if($con->connect_error) {
        die("Failed to connect :".$con->connect_error);
    } else {
        $stmt = $con->prepare("select * from entry where email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt_result = $stmt->get_result();
        if($stmt_result->num_rows > 0){
            $data =$stmt_result->fetch_assoc();
            if($data['password'] === $password) {
                echo "<script> alert('Login Successfully');
                document.location.href = 'Homepage.html';
                </script>";
            } 
            else {
                
                echo "<script> alert('Invalid email or password');
                document.location.href = 'registration.html';
                </script>";
            }
        } else {
            echo "<script> alert('Invalid email or password');
                document.location.href = 'registration.html';
                </script>";
        }
    }
?>
?>