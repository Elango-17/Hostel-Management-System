<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "Elango@3355"; // Change this to your database password
$dbname = "hostel_management"; // Change this to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $roomno = $_POST['roomno'];
    $seater = $_POST['seater'];
    $feespm = $_POST['feespm'];
    $foodstatus = $_POST['foodstatus'];
    $stayfrom = $_POST['stayfrom'];
    $duration = $_POST['duration'];
    $course = $_POST['course'];
    $register_no = $_POST['register_no'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $contact_num = $_POST['contact_num'];
    $email = $_POST['email'];
    $egycontactno = $_POST['egycontactno'];
    $guardianName = $_POST['guardianName'];
    $guardianRelation = $_POST['guardianRelation'];
    $guardianContactno = $_POST['guardianContactno'];
    $corresAddress = $_POST['corresAddress'];
    $corresCIty = $_POST['corresCIty'];
    $corresState = $_POST['corresState'];
    $corresPincode = $_POST['corresPincode'];
    $pmntAddress = $_POST['pmntAddress'];
    $pmntCity = $_POST['pmntCity'];
    $pmnatetState = $_POST['pmnatetState'];
    $pmntPincode = $_POST['pmntPincode'];
    $postingDate = $_POST['postingDate'];

    // Prepare SQL statement
    $sql = "INSERT INTO registration (roomno, seater, feespm, foodstatus, stayfrom, duration, course, register_no, first_name, middle_name, last_name, gender, contact_num, email, egycontactno, guardianName, guardianRelation, guardianContactno, corresAddress, corresCIty, corresState, corresPincode, pmntAddress, pmntCity, pmnatetState, pmntPincode, postingDate)
    VALUES ('$roomno', '$seater', '$feespm', '$foodstatus', '$stayfrom', '$duration', '$course', '$register_no', '$first_name', '$middle_name', '$last_name', '$gender', '$contact_num', '$email', '$egycontactno', '$guardianName', '$guardianRelation', '$guardianContactno', '$corresAddress', '$corresCIty', '$corresState', '$corresPincode', '$pmntAddress', '$pmntCity', '$pmnatetState', '$pmntPincode', '$postingDate')";

    if ($conn->query($sql) === TRUE) {
        $login_error = "New Record Added Successfully!!";
        echo "<script>alert('$login_error'); window.location.href='admin_profile.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
}
?>
