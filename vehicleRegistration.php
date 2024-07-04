<?php
// Retrieve form data
$vehicleNumber = $_POST['vehicleNumber'];
$vehicleMake = $_POST['vehicleMake'];
$vehicleModel = $_POST['vehicleModel'];
$vehicleType = $_POST['vehicleType'];
$driverName = $_POST['driverName'];
$driverLicense = $_POST['driverLicense'];
$contactNumber = $_POST['contactNumber'];
$driverAddress = $_POST['driverAddress'];

// Validate form data
if (empty($vehicleNumber) || empty($vehicleMake) || empty($vehicleModel) || empty($vehicleType) || empty($driverName) || empty($driverLicense) || empty($contactNumber) || empty($driverAddress)) {
    echo "All fields are required";
    exit;
}

if (!preg_match("/^[A-Za-z]{2}\s\d{2}\s[A-Za-z]{2}\s\d{4}$/", $vehicleNumber)) {
    echo "Please enter a valid vehicle number (e.g., MH 02 TM 0395)";
    exit;
}

if (!preg_match("/^\d{10}$/", $contactNumber)) {
    echo "Please enter a valid 10-digit contact number";
    exit;
}

if (!preg_match("/^[a-zA-Z0-9]*$/", $driverLicense)) {
    echo "Please enter a valid driver's license number";
    exit;
}

// Create connection
$conn = new mysqli('localhost', 'root', '', 'tanmay');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement to insert data into database using prepared statement
$stmt = $conn->prepare("INSERT INTO vehicle_registration (vehicle_number, vehicle_make, vehicle_model, vehicleType, driver_name, driver_license, contact_number, driver_address) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $vehicleNumber, $vehicleMake, $vehicleModel, $vehicleType, $driverName, $driverLicense, $contactNumber, $driverAddress);

if ($stmt->execute()) {
    echo "<script>alert('Vehicle's Information sent successfully'); window.location.href = 'vehicleRegistration.html';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close statement and connection
$stmt->close();
$conn->close();


