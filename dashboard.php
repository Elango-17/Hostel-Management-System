<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

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

// Retrieve user details from the database
$email = $_SESSION['email']; // Retrieve email address from session
$sql = "SELECT * FROM userregistration WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if user details are found
if ($result->num_rows > 0) {
    // Output user dashboard
    $user = $result->fetch_assoc();
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>User Dashboard</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            list-style:none;
            outline: none;
            border: none;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        .header {
           color: #000;
           padding: 10px 20px;
           text-align: center;
           border: 2px;
           backdrop-filter: blur(25px);
}
.sidebar {
    position: fixed;
    top: 90px;
    left: 0;
    width: 150px; /* Adjust as needed */
    height: calc(100vh - 40px); /* Cover entire viewport height, minus header height */
    border: 2px ;
    backdrop-filter: blur(25px);
    padding: 20px;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
li {
    margin-bottom: 10px;
}
a {
    display: block;
    padding: 15px;
    background-color: #000;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}
a:hover {
    background-color: #012;
}


        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
            border: 2px ;
            backdrop-filter: blur(25px);
         
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            border: 2px ;
            backdrop-filter: blur(25px);
        }

        /* footer */
.footer{
    background: #000016;
    color: #fff;
    text-align: center;
    padding: 0.7em;
    margin-top:170px;
    position: sticky;
  }

        
    </style>

    </head>
    <body>

    <div class="header">
        <h1>User Dashboard</h1>
    </div>

    <div class="sidebar">
        <ul>
            <li><a href="#">My Profile</a></li>
            <li><a href="bookhostel.php">Book Hostel</a></li>
            <li><a href="room-details.php">Room</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="container">

        <table>
            <tr>
                <th colspan="2">Welcome, <?php echo $user['first_name']; ?>!</th>
            </tr>
            <tr>
                <td>Register No</td>
                <td><?php echo $user['register_no']; ?></td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><?php echo $user['first_name']; ?></td>
            </tr>
            <tr>
                <td>Middle Name</td>
                <td><?php echo $user['middle_name']; ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo $user['last_name']; ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo $user['gender']; ?></td>
            </tr>
            <tr>
                <td>Contact Number</td>
                <td><?php echo $user['contact_num']; ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo $user['email']; ?></td>
            </tr>
        </table>
    </div>
    <footer class="footer">
            <i class="bx bxs-envelop"></i><p>Developed by  @Elango</p>
    </footer>
    </body>
    </html>
    <?php
} else {
    // Handle case where user details are not found
    echo "User details not found.";
}

$stmt->close();
$conn->close();
?>
