<?php
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'tanmay');

    if ($conn->connect_error) {
        die('Connection Failed : ' . $conn->connect_error);
    }

    // Fetch data from the database
    $sql = "SELECT * FROM services";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='villan'>";
            echo "<strong>Vehicle Type:</strong> " . $row['vehicleType'] . "<br>";
            echo "<strong>Pickup Location:</strong> " . $row['pickupLocation'] . "<br>";
            echo "<strong>Destination:</strong> " . $row['destination'] . "<br>";
            echo "<strong>Pickup Date:</strong> " . $row['pickupDate'] . "<br>";
            echo "<strong>Estimated Weight:</strong> " . $row['estimatedWeight'] . "<br>";
            echo "<strong>Cargo Type:</strong> " . $row['cargoType'] . "<br>";
            echo "<strong>Company Name:</strong> " . $row['companyName'] . "<br>";
            echo "<strong>Contact Person's Name:</strong> " . $row['contactPersonsName'] . "<br>";
            echo "<strong>Email:</strong> " . $row['email'] . "<br>";
            echo "<strong>Contact No:</strong> " . $row['contactNo'] . "<br>";
            echo "<strong>Comments:</strong> " . $row['comments'] . "<br>";
            echo "</div>";
        }
    } else {
        echo "<div class='villan'>0 results</div>";
    }

    // Close connection
    $conn->close();
    ?>