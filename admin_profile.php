<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["email"])) {
    header("Location: admin.html");
    exit;
}

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

// Delete course if requested
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM courses WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Course deleted successfully');</script>";
    } else {
        echo "Error deleting course: " . $conn->error;
    }
}

// Delete room if requested
if (isset($_GET['deleted_id'])) {
    $deleted_id = $_GET['deleted_id'];
    $sql_rooms = "DELETE FROM rooms WHERE id = $deleted_id";
    if ($conn->query($sql_rooms) === TRUE) {
        echo "<script>alert('Room deleted successfully');</script>";
    } else {
        echo "Error deleting room: " . $conn->error;
    }
}

// Delete student record if requested
if (isset($_GET['deletedd_id'])) {
    $deletedd_id = $_GET['deletedd_id'];
    $sql_delete = "DELETE FROM registration WHERE id = $deletedd_id";
    if ($conn->query($sql_delete) === TRUE) {
        echo "<script>alert('Student record deleted successfully');</script>";
    } else {
        echo "Error deleting student record: " . $conn->error;
    }
}


// Fetch courses from the database
$sql = "SELECT id, course_code, course_sn, course_fn FROM courses";
$result = $conn->query($sql);

// Fetch rooms from the database
$sql_rooms = "SELECT id,  seater, room_no, fees FROM rooms";
$result_rooms = $conn->query($sql_rooms);

// Fetch student records from the database
$sql_students = "SELECT * FROM registration";
$result_students = $conn->query($sql_students);

// Fetch user access logs from the database
$sql_logs = "SELECT * FROM userlog";
$result_logs = $conn->query($sql_logs);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="adminprofile.css">

    <style>

    /* Student Registration Form Styles */

    #registrationForm {
        display: none;
        color: black;
        width: 50%;
        margin-left: 23%;
        padding: 20px;
        border: 2px;
        background:  rgba(255,255,255,0.3);
        backdrop-filter: blur(25px);
        border-radius: 20px;
        border-left: 1px solid rgba(255,255,255,0.3);
        border-top: 1px solid rgba(255,255,255,0.3);
        box-shadow: 20px 20px 40px -6px rgba(0,0,0,0.2);
    }

    #registrationForm input[type="text"],
    #registrationForm input[type="email"],
    #registrationForm textarea,
    #registrationForm input[type="datetime-local"],
    #registrationForm input[type="date"] {
        width: calc(100% - 20px);
        padding: 8px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    #registrationForm input[type="submit"] {
        background-color: black;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 20px;
    }

    #registrationForm input[type="submit"]:hover {
        background-color: #333;
    }

    #registrationForm label {
        margin-top: 10px;
        display: block;
    }

    #logs-table h2 {
    text-align: left;
    margin-left: 20%;
}

#logs-table table {
    width: 60%;
    border-collapse: collapse;
    margin-top: 20px;
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(25px);
    border-left: 1px solid rgba(255, 255, 255, 0.3);
    border-top: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 20px 20px 40px -6px rgba(0, 0, 0, 0.2);
}

#logs-table th,
#logs-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

#logs-table th {
    background-color: #f2f2f2;
}

#logs-table tr:nth-child(even) td {
    background-color: #f2f2f2;
}

#logs-table tr:hover td {
    background-color: #ddd;
}
/* footer */
.footer{
    background: #000016;
    color: #fff;
    text-align: center;
    padding: 0.4em;
    margin-top:540px;
    position: sticky;
  }




</style>

</head>
<body>

    
    <div class="header">
        <h1>Hostel Management System</h1>
    </div>

    <div class="sidebar">
        <!-- <h2>Welcome Admin <?php echo $_SESSION["email"]; ?>  </h2> -->
        <ul>
            <li><a href="#" onclick="toggleDetails('courses')">Courses</a></li>
            <div id="courses-details" class="user-details">
                <button class="button" onclick="toggleAddCourseForms()">Add Course</button>
                <button class="button" onclick="toggleDetails('courses', this)">Manage Course</button>
            </div>
            <li><a href="#" onclick="toggleDetails('hostel')">Hostel Rooms</a></li>
            <div id="hostel-details" class="user-details">
                <button class="button" onclick="toggleAddCourseForm()" >Add Rooms</button>
                <button class="button"  onclick="toggleManageRooms('hostel', this)" >Manage Rooms</button>
            </div>

            <li><a href="#"   onclick="toggleRegistrationForm()" >Student Registration</a></li>
            <li><a href="#" onclick="toggleStudentsTable()">Manage Student</a></li>
            <li><a href="#" onclick="toggleUserLogsTable()">User Access Logs</a></li>
        </ul>
        <a href="logout.php">Logout</a>
    </div>



    <div class="content">

        <!-- Add Course Form -->

        <div id="add-course-form" class="form-container">
            <h3>Add Course</h3>
            <form action="add_course.php" method="POST"> <!-- Specify action and method accordingly -->
                <div>
                    <label for="course-code">Course Code:</label>
                    <input type="text" id="course-code" name="course-code" required>
                </div>
                <div>
                    <label for="course-name">Course Name(Short):</label>
                    <input type="text" id="course-name" name="course-sn" required>
                </div>
                <div>
                    <label for="course-name">Course Name(Full):</label>
                    <input type="text" id="course-name" name="course-fn" required>
                </div>
                <button type="submit" name="submit" >Submit</button>
            </form>
        </div>

        <!-- Add Room Form -->

        <div id="add-room-form" class="form-container">
            <h3>Add Room</h3>
            <form action="add_room.php" method="POST"> <!-- Specify action and method accordingly -->
                <div>
                    <label for="room-number">Seater No:</label>
                    <input type="text" id="room-number" name="seater" required>
                </div>
                <div>
                    <label for="room-type">Room Number:</label>
                    <input type="text" id="room-type" name="room-number" required>
                </div>

                <div>
                    <label for="room-type">Fees(per student):</label>
                    <input type="text" id="room-type" name="fees" required>
                </div>
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>

    
    <!-- manage table -->
    
    <div id="courses-table" class="content_table" style="display: none;"> <!-- Initially hidden -->
     <h2>Manage Courses</h2>
        <table>
            <tr>
                <th>Course Code</th>
                <th>Course Name (Short)</th>
                <th>Course Name (Full)</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['course_code']."</td>";
                    echo "<td>".$row['course_sn']."</td>";
                    echo "<td>".$row['course_fn']."</td>";
                    echo "<td><a href='?delete_id=".$row['id']."'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No courses found</td></tr>";
            }
            ?>
        </table>
    </div>

     <!-- Manage Rooms table -->

     <div id="rooms-table" class="content_table" style="display: none;"> <!-- Initially hidden -->
     <h2>Manage Rooms</h2>
        <table>
            <tr>
                <th>Seater</th>
                <th>Room Number</th> 
                <th>Fees (per student)</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result_rooms->num_rows > 0) {
              while($row = $result_rooms->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['seater']."</td>";
                echo "<td>".$row['room_no']."</td>"; // Corrected the column name
                echo "<td>".$row['fees']."</td>";
                echo "<td><a href='?deleted_id=".$row['id']."'>Delete</a></td>";            
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No rooms found</td></tr>";
        }
        ?>
        </table>
     </div>

     <!-- student registration -->
    <div id="registrationForm" style="display: none;"> <!-- Modified div -->

    <h2>Student Registration Form</h2>
    <form action="registration_process.php" method="POST">
        <!-- Room Related Info -->
        <label for="roomno">Room No:</label>
        <input type="text" id="roomno" name="roomno" required><br><br>

        <label for="seater">Seater:</label>
        <input type="text" id="seater" name="seater" required><br><br>

        <label for="feespm">Fees Per Month:</label>
        <input type="text" id="feespm" name="feespm" required><br><br>

        <label for="foodstatus">Food Status:</label>
        <input type="text" id="foodstatus" name="foodstatus" required><br><br>

        <label for="stayfrom">Stay From:</label>
        <input type="date" id="stayfrom" name="stayfrom" required><br><br>

        <label for="duration">Duration:</label>
        <input type="text" id="duration" name="duration" required><br><br>

        <label for="course">Course:</label>
        <input type="text" id="course" name="course" required><br><br>

        <!-- Personal Information -->
        <label for="register_no">Register No:</label>
        <input type="text" id="register_no" name="register_no"><br><br>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="middle_name">Middle Name:</label>
        <input type="text" id="middle_name" name="middle_name"><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="gender">Gender:</label>
        <input type="text" id="gender" name="gender" required><br><br>

        <label for="contact_num">Contact Number:</label>
        <input type="text" id="contact_num" name="contact_num" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>

        <label for="egycontactno">Emergency Contact Number:</label>
        <input type="text" id="egycontactno" name="egycontactno" required><br><br>

        <!-- Guardian Information -->
        <label for="guardianName">Guardian Name:</label>
        <input type="text" id="guardianName" name="guardianName" required><br><br>

        <label for="guardianRelation">Guardian Relation:</label>
        <input type="text" id="guardianRelation" name="guardianRelation" required><br><br>

        <label for="guardianContactno">Guardian Contact Number:</label>
        <input type="text" id="guardianContactno" name="guardianContactno" required><br><br>

        <!-- Correspondence Address -->
        <label for="corresAddress">Correspondence Address:</label>
        <textarea id="corresAddress" name="corresAddress" rows="4" cols="50" required></textarea><br><br>

        <label for="corresCIty">City:</label>
        <input type="text" id="corresCIty" name="corresCIty" required><br><br>

        <label for="corresState">State:</label>
        <input type="text" id="corresState" name="corresState" required><br><br>

        <label for="corresPincode">Pincode:</label>
        <input type="text" id="corresPincode" name="corresPincode" required><br><br>

        <!-- Permanent Address -->
        <label for="pmntAddress">Permanent Address:</label>
        <textarea id="pmntAddress" name="pmntAddress" rows="4" cols="50" required></textarea><br><br>

        <label for="pmntCity">City:</label>
        <input type="text" id="pmntCity" name="pmntCity" required><br><br>

        <label for="pmnatetState">State:</label>
        <input type="text" id="pmnatetState" name="pmnatetState" required><br><br>

        <label for="pmntPincode">Pincode:</label>
        <input type="text" id="pmntPincode" name="pmntPincode" required><br><br>

        <!-- Posting Date -->
        <label for="postingDate">Posting Date:</label>
        <input type="datetime-local" id="postingDate" name="postingDate" required><br><br>

        <!-- Submit Button -->
        <input type="submit" value="Submit">
    </form>
    </div>  

    <!-- manage students -->

    <div id="students-table" class="content_table" style="display: none;"> <!-- Initially hidden -->
    <h2>Manage Students</h2>
    <table>
        <tr>
            <th>Student Name</th>
            <th>Reg no</th>
            <th>Contact no</th>
            <th>Room no</th>
            <th>Seater</th>
            <th>Staying From</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result_students->num_rows > 0) {
            while($row = $result_students->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['first_name']." ".$row['middle_name']." ".$row['last_name']."</td>";
                echo "<td>".$row['register_no']."</td>";
                echo "<td>".$row['contact_num']."</td>";
                echo "<td>".$row['roomno']."</td>";
                echo "<td>".$row['seater']."</td>";
                echo "<td>".$row['stayfrom']."</td>";
                echo "<td><a href='?deletedd_id=".$row['id']."'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No student records found</td></tr>";
        }
        ?>
    </table>
  </div>

  <!-- user access logs -->

  <div id="logs-table" class="content_table" style="display: none;"> <!-- Initially hidden -->
    <h2>User Access Logs</h2>
    <table>
        <tr>
            <th>User Email</th>
            <th>IP Address</th>
            <th>City</th>
            <th>Country</th>
            <th>Login Time</th>
        </tr>
        <?php
        if ($result_logs->num_rows > 0) {
            while($row = $result_logs->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['userEmail']."</td>";
                echo "<td>".$row['IP']."</td>";
                echo "<td>".$row['city']."</td>";
                echo "<td>".$row['country']."</td>";
                echo "<td>".$row['LoginTime']."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No user access logs found</td></tr>";
        }
        ?>
    </table>
</div>

</div>


    <footer class="footer">
            <i class="bx bxs-envelop"></i><p>Developed by  @Elango</p>
    </footer>

    <script>

     function toggleDetails(section, button) {
       var detailsElement = document.getElementById(section + '-details');
      if (section === 'courses') {
        // Toggle visibility of the "Manage Courses" section and table
        detailsElement.classList.toggle('show-details');
        var coursesTable = document.getElementById('courses-table');
        coursesTable.style.display = (button.classList.contains('active')) ? 'block' : 'none';
        button.classList.toggle('active'); // Toggle the 'active' class on the button

        // Hide "Manage Rooms" table and its button
        var roomsTable = document.getElementById('rooms-table');
        roomsTable.style.display = 'none';
        var roomsButton = document.querySelector('#hostel-details button.active');
        if (roomsButton) roomsButton.classList.remove('active');
    } else if (section === 'hostel') {
        // Toggle visibility of the "Manage Rooms" section and table
        detailsElement.classList.toggle('show-details');
        var roomsTable = document.getElementById('rooms-table');
        roomsTable.style.display = (button.classList.contains('active')) ? 'block' : 'none';
        button.classList.toggle('active'); // Toggle the 'active' class on the button

        // Hide "Manage Courses" table and its button
        var coursesTable = document.getElementById('courses-table');
        coursesTable.style.display = 'none';
        var coursesButton = document.querySelector('#courses-details button.active');
        if (coursesButton) coursesButton.classList.remove('active');
    }
    }   
        // Function to toggle the visibility of the add course form
        function toggleAddCourseForms() {
            var formContainer = document.getElementById('add-course-form');
            formContainer.style.display = (formContainer.style.display === 'none') ? 'block' : 'none';
        }

        function toggleAddCourseForm() {
            var formContainer = document.getElementById('add-room-form');
            formContainer.style.display = (formContainer.style.display === 'none') ? 'block' : 'none';
        }

        function toggleManageRooms() {
            var roomsTable = document.getElementById('rooms-table');
            roomsTable.style.display = (roomsTable.style.display === 'none') ? 'block' : 'none';
        }   
        
                // Function to toggle visibility of the student registration form
        function toggleRegistrationForm() {
            var registrationForm = document.getElementById('registrationForm');
            if (registrationForm.style.display === 'none') {
                registrationForm.style.display = 'block';
            } else {
                registrationForm.style.display = 'none';
            }
        }

    function toggleStudentsTable() {
        var studentsTable = document.getElementById('students-table');
        if (studentsTable.style.display === 'none') {
            studentsTable.style.display = 'block';
        } else {
            studentsTable.style.display = 'none';
        }
    }  

    function toggleUserLogsTable() {
    var logsTable = document.getElementById('logs-table');
    if (logsTable.style.display === 'none') {
        logsTable.style.display = 'block';
    } else {
        logsTable.style.display = 'none';
    }
}

    </script>
</body>
</html>
