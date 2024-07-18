<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["email"])) {
    header("Location: admin_profile.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Retrieve course code and course name from the form
    $coursecode = $_POST['course-code']; // Assuming the form field name is "course-code"
    $coursesn = $_POST['course-sn']; 
    $coursefn = $_POST['course-fn'];// Assuming the form field name is "course-name"
    
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
    $stmt = $conn->prepare("INSERT INTO courses (course_code, course_sn, course_fn) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $coursecode, $coursesn,  $coursefn);

    // Execute the statement
    if ($stmt->execute()) {
        // Course inserted successfully, show alert message and remain on the same page
        $login_error = "Course added Successfully!!";
        echo "<script>alert('$login_error'); window.location.href='admin_profile.php';</script>";
    } else {
        // Error occurred while inserting course
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
