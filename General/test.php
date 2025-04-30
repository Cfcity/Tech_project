<?php

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Session Initialization
// Start a session if it hasn't already been started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Variable Initialization
// Initialize variables to store user input and other data
$username = "";
$email = "";
$password = "";
$conpassword = "";
$role = 3; // Default role is set to 3 (e.g., student)
$errors = 0; // Error counter
$replyto = []; // Placeholder for reply handling

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Database Connection
// Establish a connection to the MySQL database
$db = mysqli_connect('localhost', 'root', '', 'test');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Registration - Send Data to Database
// Handles user registration by validating input and inserting data into the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg_user'])) {
    $errors = 0;

    // Sanitize and retrieve form data
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $conpassword = mysqli_real_escape_string($db, $_POST['conpassword']);

    // Validate form fields
    if (empty($username)) {
        $errors = 1;
        echo "<p>Please fill in the username.</p>";
    }
    if (empty($email)) {
        $errors = 2;
        echo "<p>Please fill in the email.</p>";
    }
    if (empty($password)) {
        $errors = 3;
        echo "<p>Please fill in the password.</p>";
    }
    if (empty($conpassword)) {
        $errors = 4;
        echo "<p>Please confirm your password.</p>";
    }
    if ($password != $conpassword) {
        echo "<p>Incorrect password confirmation.</p>";
        $errors = 5;
    }

    // Insert into database if no errors
    if ($errors == 0) {
        $query = "INSERT INTO user (username, email, password, conpassword) VALUES ('$username', '$email', '$password', '$conpassword')";
        if (mysqli_query($db, $query)) {
            // Store user data in session
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            // Retrieve the user ID
            $qs = "SELECT Id FROM user WHERE username = '$username'";
            $result = mysqli_query($db, $qs);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['Id'] = $row['Id'];
            } else {
                die("Error: User ID not found in the database.");
            }

            // Redirect to the next step
            echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
            echo '<script type="text/javascript">';
            echo 'window.open("../General/signup2.php", "_self");';
            echo '</script>';
        } else {
            echo "<p style='color: red;'>Error: " . mysqli_error($db) . "</p>";
        }
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Faculty Signup - Send Data to Database
// Handles faculty registration by updating user data and inserting into the staff table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_faculty'])) {
    $errors = 0;

    // Sanitize and retrieve form data
    $f_name = mysqli_real_escape_string($db, $_POST['f_name']);
    $l_name = mysqli_real_escape_string($db, $_POST['l_name']);
    $bio = mysqli_real_escape_string($db, $_POST['bio']);
    $department = mysqli_real_escape_string($db, $_POST['department']);
    $role = isset($_POST['role']) ? mysqli_real_escape_string($db, $_POST['role']) : 2;

    // Validate form fields
    if (empty($f_name) || empty($l_name) || empty($bio) || empty($department)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        // Update user table
        $qu = "UPDATE user SET bio = '$bio', role = '$role' WHERE Id = '" . $_SESSION['Id'] . "'";
        if (mysqli_query($db, $qu)) {
            // Insert into staff table
            $qs = "INSERT INTO staff (Id, f_name, l_name, department) VALUES ('" . $_SESSION['Id'] . "', '$f_name', '$l_name', '$department')";
            if (mysqli_query($db, $qs)) {
                echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
                header("Location: ../home_pages/hpfinance.php");
                exit();
            } else {
                echo "<p style='color: red;'>Error inserting into staff table: " . mysqli_error($db) . "</p>";
            }
        } else {
            echo "<p style='color: red;'>Error updating user table: " . mysqli_error($db) . "</p>";
        }
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Student Signup - Send Data to Database
// Handles student registration by updating user data and inserting into the students table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_student'])) {
    $errors = 0;

    // Sanitize and retrieve form data
    $Stu_fname = mysqli_real_escape_string($db, $_POST['Stu_fname']);
    $Stu_lname = mysqli_real_escape_string($db, $_POST['Stu_lname']);
    $Stu_bio = mysqli_real_escape_string($db, $_POST['Stu_bio']);
    $Stu_department = mysqli_real_escape_string($db, $_POST['Stu_department']);
    $role = isset($_POST['role']) ? mysqli_real_escape_string($db, $_POST['role']) : 3;

    // Validate form fields
    if (empty($Stu_fname) || empty($Stu_lname) || empty($Stu_bio) || empty($Stu_department)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        // Update user table
        $qu = "UPDATE user SET bio = '$Stu_bio', role = '$role' WHERE Id = '" . $_SESSION['Id'] . "'";
        if (mysqli_query($db, $qu)) {
            // Insert into students table
            $qs = "INSERT INTO students (Id, Stu_fname, Stu_lname, Stu_department) VALUES ('" . $_SESSION['Id'] . "', '$Stu_fname', '$Stu_lname', '$Stu_department')";
            if (mysqli_query($db, $qs)) {
                echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
                header("Location: ../home_pages/hpstudent.php");
                exit();
            } else {
                echo "<p style='color: red;'>Error inserting into students table: " . mysqli_error($db) . "</p>";
            }
        } else {
            echo "<p style='color: red;'>Error updating user table: " . mysqli_error($db) . "</p>";
        }
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Login - Authenticate User
// Handles user login by validating credentials and redirecting based on role
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_user'])) {
    $errors = 0;

    // Sanitize and retrieve form data
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if ($errors == 0) {
        // Query to check user credentials
        $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $row = mysqli_fetch_assoc($results);
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "Logged in";
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['Id'] = $row['Id'];

            // Redirect based on role
            if ($row['role'] == 1) {
                echo '<script type="text/javascript">';
                echo 'window.open("../home_pages/home_admin.php", "_self");';
                echo '</script>';
            } else if ($row['role'] == 2) {
                $query = "SELECT Staffid FROM staff WHERE Id = '" . $_SESSION['Id'] . "'";
                $result = mysqli_query($db, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $_SESSION['Staffid'] = $row['Staffid'];
                } else {
                    die("Error: Staff ID not found in the database.");
                }

                echo '<script type="text/javascript">';
                echo 'window.open("../home_pages/hpfinance.php", "_self");';
                echo '</script>';
            } else if ($row['role'] == 3) {
                $query = "SELECT studentid FROM students WHERE Id = '" . $_SESSION['Id'] . "'";
                $result = mysqli_query($db, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $_SESSION['studentid'] = $row['studentid'];
                }

                echo '<script type="text/javascript">';
                echo 'window.open("../home_pages/hpstudent.php", "_self");';
                echo '</script>';
            }
        } else {
            echo "<p class='centertop' style='z-index: 3; left: 25%'> Incorrect username / password</p>";
        }
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Reply Handling
// Handles reply functionality by redirecting to the reply page
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $replyto = $_POST['replyto'];
    echo '<script type="text/javascript">';
    echo 'window.open("../Service_forms/Reply.php?replyto=' . $replyto . '", "_self");';
    echo '</script>';
}
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Event Addition
// Handles event addition by inserting data into the database
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_event'])){


    header("Location: ../Staff view/events_staff.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit_event'])) {
    $event_name = mysqli_real_escape_string($db, $_POST['event_name']);
    $event_desc = mysqli_real_escape_string($db, $_POST['event_desc']);
    $event_time = mysqli_real_escape_string($db, $_POST['event_time']);
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $event_type = mysqli_real_escape_string($db, $_POST['event_type']);
    $news_image = "../images/" . mysqli_real_escape_string($db, $_POST['news_image']);
    $priority = mysqli_real_escape_string($db, $_POST['priority']);

    // Insert the event into the database
    $query = "INSERT INTO events (event_name, event_desc, event_time, event_type, id, news_image, priority) VALUES ('$event_name', '$event_desc', '$event_time', '$event_type', '$id' , '$news_image', '$priority')";
    if (mysqli_query($db, $query)) {
        echo "<p style='text-align: center; color: green;'>Event added successfully!</p>";
        header("Location: ../home_pages/home_admin.php");
        exit();
    } else {
        echo "<p style='color: red;'>Error adding event: " . mysqli_error($db) . "</p>";
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Event Deletion   
// Handles event deletion by removing the event from the database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'])) {
    $event_id = mysqli_real_escape_string($db, $_POST['event_id']);
    $query = "DELETE FROM events WHERE event_id = '$event_id'";
    if (mysqli_query($db, $query)) {
        echo "<p style='text-align: center; color: green;'>Event deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error deleting event: " . mysqli_error($db) . "</p>";
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Event Editing
// Handles event editing by redirecting to the edit page with the event ID

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_event'])) {
    $event_id = mysqli_real_escape_string($db, $_POST['event_id']);
    echo '<script type="text/javascript">';
    echo 'window.open("../Admin_pages/Edit_event.php?event_id=' . $event_id . '", "_self");';
    echo '</script>';
}


