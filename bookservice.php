<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/SMTP.php';

// Database connection
$conn = new mysqli('localhost', 'root', '', 'tanmay');

if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
}

// Get the form data
$vehicleType = $_POST['vehicleType'];
$pickupLocation = $_POST['PickupLocation'];
$destination = $_POST['Destination'];
$pickupDate = $_POST['PickupDate'];
$estimatedWeight = $_POST['EstimatedWeight'];
$cargoType = $_POST['CargoType'];
$companyName = $_POST['CompanyName'];
$contactPersonsName = $_POST['ContactpersonsName'];
$email = $_POST['Email'];
$contactNo = $_POST['ContactNo'];
$comments = $_POST['Comments'];

$stmt = $conn->prepare("INSERT into services (vehicleType, pickupLocation, destination, pickupDate, estimatedWeight, cargoType, companyName, contactPersonsName, email, contactNo, comments) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssss", $vehicleType, $pickupLocation, $destination, $pickupDate, $estimatedWeight, $cargoType, $companyName, $contactPersonsName, $email, $contactNo, $comments);

if ($stmt->execute()) {
    // Retrieve the newly inserted ID
    $service_id = $stmt->insert_id;

    // Close the statement
    $stmt->close();

    // Fetch data from vehicle_registration table
    $query = "SELECT vehicle_number, vehicle_make, vehicle_model, driver_name, driver_license, contact_number FROM vehicle_registration WHERE vehicleType = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $vehicleType);
    $stmt->execute();
    $result = $stmt->get_result();

    // Combine data from services and vehicle_registration tables
    $vehicle_data = '';
    while ($row = $result->fetch_assoc()) {
        $vehicle_data .= "Vehicle Number: {$row['vehicle_number']}\n";
        $vehicle_data .= "Vehicle Make: {$row['vehicle_make']}\n";
        $vehicle_data .= "Vehicle Model: {$row['vehicle_model']}\n";
        $vehicle_data .= "Driver Name: {$row['driver_name']}\n";
        $vehicle_data .= "Driver License: {$row['driver_license']}\n";
        $vehicle_data .= "Contact Number: {$row['contact_number']}\n";
        $update_query = "UPDATE vehicle_registration SET status = 'Blocked' WHERE driver_name = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("s", $row['driver_name']);
        $update_stmt->execute();
        $update_stmt->close();
    }

    // Send email using PHPMailer
    $mail = new PHPMailer(true); // Passing true enables exceptions

    try {
        //Server settings
        $mail->SMTPDebug = 0; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'tanmaymalekar27@gmail.com'; // SMTP username
        $mail->Password = 'zwxfdvcggynwhlii'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        //Recipients
        $mail->setFrom('tanmaymalekar27@gmail.com', 'Tanmay Malekar');
        $mail->addAddress($email); // Add a recipient

        // Content
     // Set email format to HTML
        $mail->Subject = 'Your Service Details and Tracking ID';
        $mail->Body    = "Dear Customer,\n\nHere are your service details:\n\nService ID: $service_id\nPickup Location: $pickupLocation\nDestination: $destination\nPickup Date: $pickupDate\nEstimated Weight: $estimatedWeight\nCargo Type: $cargoType\nCompany Name: $companyName\nContact Person's Name: $contactPersonsName\nEmail: $email\nContact Number: $contactNo\nComments: $comments\n\nVehicle Details:\n$vehicle_data\n\nYour Tracking ID: TRK" . time();

        $mail->send();
        echo "<script>alert('Information sent successfully check your Email for order details.'); window.location.href = 'bookservicepage.html';</script>";
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }

    // Close the connection
    $conn->close();
} else {
    echo "Error: " . $conn->error;
}
?>
