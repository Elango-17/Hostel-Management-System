<?php
session_start();

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "Elango@3355";
$dbname = "hostel_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$email = $_POST['email'];
$password = $_POST['password'];

// Fetch user from database
$sql = "SELECT * FROM userregistration WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        // Password is correct, insert user access log
        $sql_log = "INSERT INTO userlog (userEmail, IP, city, country) VALUES (?, ?, ?, ?)";
        $stmt_log = $conn->prepare($sql_log);
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $city = "Unknown";
        $country = "Unknown";
        $stmt_log->bind_param("ssss", $email, $ip_address, $city, $country);
        $stmt_log->execute();
        $stmt_log->close();
        
        // Set session variables and redirect
        $_SESSION['email'] = $row['email'];
        header("Location: dashboard.php");
        exit;
    } else {
        // Invalid email or password, redirect back to login page with alert
        $login_error = "Invalid email or password. Please try again.";
        echo "<script>alert('$login_error'); window.location.href='index.html';</script>";
        exit;
    }
} else {
    // Invalid email or password, redirect back to login page with alert
    $login_error = "Invalid email or password. Please try again.";
    echo "<script>alert('$login_error'); window.location.href='index.html';</script>";
    exit;
}

$conn->close();
?>

