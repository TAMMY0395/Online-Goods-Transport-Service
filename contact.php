<?php
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $conn = new mysqli('localhost','root','','tanmay');
    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }
    else{
    $stmt = $conn->prepare("insert into customerentry(id, name, email, message)
        values(?, ?, ?, ?)");
    $stmt->bind_param("isss",$id, $name, $email, $message);
    $stmt->execute();
    echo "<script> alert('Message Send Successfully');
    document.location.href = 'contactpage.html';
    </script>";
    $stmt->close();
    $conn->close();
    }
?>