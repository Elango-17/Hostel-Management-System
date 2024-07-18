<?php
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

// Check if all required fields are filled
if (empty($_POST['register_no']) || empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['gender']) || empty($_POST['contact_num']) || empty($_POST['email']) || empty($_POST['password'])) {
    echo "Please fill out all required fields.";
    exit;
}

// Retrieve form data
$register_no = $_POST['register_no'];
$fname = $_POST['first_name'];
$mname = isset($_POST['middle_name']) ? $_POST['middle_name'] : ''; // Middle name is optional
$lname = $_POST['last_name'];
$gender = $_POST['gender'];
$contactno = $_POST['contact_num'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

// Insert data into users table
$stmt = $conn->prepare("INSERT INTO userregistration (register_no, first_name, middle_name, last_name, gender, contact_num, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Error in prepared statement: " . $conn->error);
}

$stmt->bind_param("ssssssss", $register_no, $fname, $mname, $lname, $gender, $contactno, $email, $password);

// Your registration logic goes here...

// Check if registration was successful
if ($stmt->execute() === TRUE) {
    // Registration successful, display success message
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Success</title>
        <style>

               body {
                   font-family: Arial, sans-serif;
                   margin: 0;
                   padding: 0;
                   display: flex;
                   justify-content: center;
                   align-items: center;
                   height: 100vh;
                   background-color: #f4f4f4;}  

              #registration-success {
                 text-align: center;
                 background-color: #fff;
                 padding: 20px;
                 border-radius: 10px;
                 box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);}
                 
        </style>

    </head>
    <body>
        <div id="registration-success">
            <h1>Successfully Registered!</h1>
            <p>You can now proceed to the login page.</p>
        </div>
        <script>
            setTimeout(function() {
            window.location.href = "index.html";
            }, 3000); // 3000 milliseconds (3 seconds) delay
        </script>
    </body>
    </html>
    <?php
    exit(); // Stop further execution of PHP code
}  
else {
    echo "Error: " . $stmt->error;
}

$stmt->close();

$conn->close();
?>
