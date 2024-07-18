<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "Elango@3355";
    $database = "hostel_management";

    // Create connection
    $connection = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Get email and password from the form
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare a SQL query to fetch admin details
    $query = "SELECT * FROM `admin` WHERE email = ? AND password = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists with the provided credentials
    if ($result->num_rows == 1) {
        // Admin exists, set session variable and redirect to admin profile page
        $_SESSION["email"] = $email;
        header("Location: admin_profile.php");
        exit;
    } else {
        // Admin does not exist with the provided credentials, show error message
        $login_error = "Invalid email or password. Please try again.";
        echo "<script>alert('$login_error'); window.location.href='admin.html';</script>";
    }

    // Close prepared statement
    $stmt->close();

    // Close database connection
    $connection->close();
} else {
    // Redirect to admin login page if accessed directly without POST request
    header("Location: admin.html");
    exit;
}
?>
