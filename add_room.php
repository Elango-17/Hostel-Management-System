<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["email"])) {
    header("Location: admin_profile.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Retrieve room details from the form
    $seater = $_POST['seater'];
    $roomnumber = $_POST['room-number'];
    $roomfees = $_POST['fees'];
    
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "Elango@3355";
    $dbname = "hostel_management";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO rooms (seater, room_no, fees) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $seater, $roomnumber, $roomfees);

    // Execute the statement
    if ($stmt->execute()) {
        // Room inserted successfully, show alert message and remain on the same page
        $login_error = "Room added Successfully!!";
        echo "<script>alert('$login_error'); window.location.href='admin_profile.php';</script>";
    } else {
        // Error occurred while inserting room
        echo "Error: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to admin profile page if accessed directly without form submission
    header("Location: admin_profile.php");
    exit;
}
?>
