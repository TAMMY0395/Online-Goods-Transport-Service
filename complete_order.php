<?php
// Database connection (assuming it's already established)
$conn = new mysqli('localhost', 'root', '', 'tanmay');

if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
}

// Check if the order complete button is clicked
if (isset($_POST['complete_order'])) {
    $serviceId = $_POST['service_id'];

    // Update the order status to indicate it's completed
    $update_query = "UPDATE services SET status = 'Completed' WHERE service_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $serviceId);
    $update_stmt->execute();
    $update_stmt->close();

    // Update the driver's status back to 'Available'
    $update_driver_query = "UPDATE vehicle_registration SET status = 'Available' WHERE driver_name = ?";
    $update_driver_stmt = $conn->prepare($update_driver_query);
    $update_driver_stmt->bind_param("s", $driverName); // assuming $driverName is the currently logged in driver's name
    $update_driver_stmt->execute();
    $update_driver_stmt->close();

    // Redirect to driver interface page or any other page as needed
    header("Location: driver_interface.php");
    exit();
}

$conn->close();
?>
