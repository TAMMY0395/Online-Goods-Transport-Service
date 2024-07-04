<?php
// Database connection (assuming it's already established)
$conn = new mysqli('localhost', 'root', '', 'tanmay');

if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
}

// Fetch orders assigned to the driver
$query = "SELECT * FROM vehicle_registration WHERE driver_name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $driverName); // assuming $driverName is the currently logged in driver's name
$stmt->execute();
$result = $stmt->get_result();

// Display orders assigned to the driver
while ($row = $result->fetch_assoc()) {
    echo "Customer Information: <br>";
    echo "Service ID: " . $row['service_id'] . "<br>";
    echo "Pickup Location: " . $row['pickupLocation'] . "<br>";
    echo "Destination: " . $row['destination'] . "<br>";
    echo "Pickup Date: " . $row['pickupDate'] . "<br>";
    // Display other fields as needed

    // Add order complete button
    echo "<form action='complete_order.php' method='post'>";
    echo "<input type='hidden' name='service_id' value='" . $row['service_id'] . "'>";
    echo "<input type='submit' name='complete_order' value='Order Complete'>";
    echo "</form>";
}

$stmt->close();
$conn->close();
?>
