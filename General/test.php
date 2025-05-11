<?php
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Session Initialization
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Role and Session Namespace Setup
$role = isset($_GET['role']) ? $_GET['role'] :
       (isset($_POST['role']) ? $_POST['role'] :
       (isset($_SESSION['current_role']) ? $_SESSION['current_role'] : null));

if ($role !== null) {
    $_SESSION['current_role'] = $role;
}

if (!isset($_SESSION['roles'])) {
    $_SESSION['roles'] = [
        'admin' => [],
        'faculty' => [],
        'student' => []
    ];
}

// Helper functions for session data
function setRoleSessionData($key, $value) {
    global $role;
    if ($role !== null) {
        $_SESSION['roles'][$role][$key] = $value;
    }
}

function getRoleSessionData($key, $default = null) {
    global $role;
    if ($role !== null && isset($_SESSION['roles'][$role][$key])) {
        return $_SESSION['roles'][$role][$key];
    }
    return $default;
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Variable Initialization
$username = "";
$email = "";
$password = "";
$conpassword = "";
$role_default = 3; // Default role (student)
$errors = 0;
$replyto = [];
$service_id = "";
$issue = "";
$department = "";
$description = "";
$img = "";

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Database Connection
$db = mysqli_connect('localhost', 'root', '', 'tech_db');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Registration - User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg_user'])) {
    $errors = 0;
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $conpassword = mysqli_real_escape_string($db, $_POST['conpassword']);

    if (empty($username)) { $errors = 1; echo "<p>Please fill in the username.</p>"; }
    if (empty($email)) { $errors = 2; echo "<p>Please fill in the email.</p>"; }
    if (empty($password)) { $errors = 3; echo "<p>Please fill in the password.</p>"; }
    if (empty($conpassword)) { $errors = 4; echo "<p>Please confirm your password.</p>"; }
    if ($password != $conpassword) { echo "<p>Incorrect password confirmation.</p>"; $errors = 5; }

    if ($errors == 0) {
        $query = "INSERT INTO user (username, email, password, conpassword) VALUES ('$username', '$email', '$password', '$conpassword')";
        if (mysqli_query($db, $query)) {
            setRoleSessionData('username', $username);
            setRoleSessionData('email', $email);
            $qs = "SELECT Id FROM user WHERE username = '$username'";
            $result = mysqli_query($db, $qs);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                setRoleSessionData('Id', $row['Id']);
            } else {
                die("Error: User ID not found in the database.");
            }
            echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
            echo '<script type="text/javascript">';
            echo 'window.open("../General/signup2.php?role=' . $role . '", "_self");';
            echo '</script>';
        } else {
            echo "<p style='color: red;'>Error: " . mysqli_error($db) . "</p>";
        }
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Registration - Faculty
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_faculty'])) {
    $errors = 0;
    $f_name = mysqli_real_escape_string($db, $_POST['f_name']);
    $l_name = mysqli_real_escape_string($db, $_POST['l_name']);
    $bio = mysqli_real_escape_string($db, $_POST['bio']);
    $department = mysqli_real_escape_string($db, $_POST['department']);
    $role_value = isset($_POST['role']) ? mysqli_real_escape_string($db, $_POST['role']) : 2;

    if (empty($f_name) || empty($l_name) || empty($bio) || empty($department)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        $qu = "UPDATE user SET bio = '$bio', role = '$role_value' WHERE Id = '" . getRoleSessionData('Id') . "'";
        if (mysqli_query($db, $qu)) {
            $qs = "INSERT INTO staff (Id, f_name, l_name, department) VALUES ('" . getRoleSessionData('Id') . "', '$f_name', '$l_name', '$department')";
            if (mysqli_query($db, $qs)) {
                echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
                header("Location: ../home_pages/hpfinance.php?role=faculty");
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
// Registration - Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_student'])) {
    $errors = 0;
    $Stu_fname = mysqli_real_escape_string($db, $_POST['Stu_fname']);
    $Stu_lname = mysqli_real_escape_string($db, $_POST['Stu_lname']);
    $Stu_bio = mysqli_real_escape_string($db, $_POST['Stu_bio']);
    $Stu_department = mysqli_real_escape_string($db, $_POST['Stu_department']);
    $role_value = isset($_POST['role']) ? mysqli_real_escape_string($db, $_POST['role']) : 3;

    if (empty($Stu_fname) || empty($Stu_lname) || empty($Stu_bio) || empty($Stu_department)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        $qu = "UPDATE user SET bio = '$Stu_bio', role = '$role_value' WHERE Id = '" . getRoleSessionData('Id') . "'";
        if (mysqli_query($db, $qu)) {
            $qs = "INSERT INTO students (Id, Stu_fname, Stu_lname, Stu_department) VALUES ('" . getRoleSessionData('Id') . "', '$Stu_fname', '$Stu_lname', '$Stu_department')";
            if (mysqli_query($db, $qs)) {
                // Ensure Stu_department is stored in the session
                setRoleSessionData('Stu_department', $Stu_department);
                echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
                header("Location: ../home_pages/hpstudent.php?role=student");
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_user'])) {
    $errors = 0;
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if ($errors == 0) {
        $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $row = mysqli_fetch_assoc($results);
            if ($row['role'] == 1) {
                $role = 'admin';
            } else if ($row['role'] == 2) {
                $role = 'faculty';
            } else if ($row['role'] == 3) {
                $role = 'student';
            }
            $_SESSION['current_role'] = $role;
            setRoleSessionData('username', $username);
            setRoleSessionData('success', "Logged in");
            setRoleSessionData('email', $row['email']);
            setRoleSessionData('role', $row['role']);
            setRoleSessionData('Id', $row['Id']);

            if ($row['role'] == 1) {
                echo '<script type="text/javascript">';
                echo 'window.open("../home_pages/home_admin.php?role=admin", "_self");';
                echo '</script>';
            } else if ($row['role'] == 2) {
                $query = "SELECT Staffid FROM staff WHERE Id = '" . $row['Id'] . "'";
                $result = mysqli_query($db, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $staffRow = mysqli_fetch_assoc($result);
                    setRoleSessionData('Staffid', $staffRow['Staffid']);
                    $_SESSION['Staffid'] = $staffRow['Staffid'];
                } else {
                    die("Error: Staff ID not found in the database.");
                }
                echo '<script type="text/javascript">';
                echo 'window.open("../home_pages/hpfinance.php?role=faculty", "_self");';
                echo '</script>';
            } else if ($row['role'] == 3) {
                $query = "SELECT studentid, Stu_department FROM students WHERE Id = '" . $row['Id'] . "'";
                $result = mysqli_query($db, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $studentRow = mysqli_fetch_assoc($result);
                    setRoleSessionData('studentid', $studentRow['studentid']);
                    setRoleSessionData('Stu_department', $studentRow['Stu_department']);
                    $_SESSION['studentId'] = $studentRow['studentid'];
                }
                echo '<script type="text/javascript">';
                echo 'window.open("../home_pages/hpstudent.php?role=student", "_self");';
                echo '</script>';
            }
        } else {
            echo "<p class='centertop' style='z-index: 3; left: 25%'> Incorrect username / password</p>";
        }
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Reply Handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $replyto = $_POST['replyto'];
    // Set inquiry status to 'pending' when opened for reply
    $updateStatus = "UPDATE inquiry SET status = 'pending' WHERE Inq_ID = '" . mysqli_real_escape_string($db, $replyto) . "'";
    mysqli_query($db, $updateStatus);
    echo '<script type="text/javascript">';
    echo 'window.open("../Service_forms/Reply.php?replyto=' . $replyto . '&role=' . $role . '", "_self");';
    echo '</script>';
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Send Reply
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Send'])) {
    $replyto = mysqli_real_escape_string($db, $_POST['replyto']);
    $reply = mysqli_real_escape_string($db, $_POST['reply']);
    if (empty($reply)) {
        $errors++;
        echo "Message field is required.<br>";
    }
    if ($errors == 0) {
        $staffId = getRoleSessionData('Staffid');
        if (!$staffId && isset($_SESSION['Staffid'])) {
            $staffId = $_SESSION['Staffid'];
        }
        if (!$staffId) {
            die("Error: Staff ID is not set in the session. Please log in again.");
        }
        // Insert the reply
        $query = "INSERT INTO reply (Inq_ID, reply, Staffid) VALUES ('$replyto', '$reply', '$staffId')";
        if (mysqli_query($db, $query)) {
            // Update inquiry status to 'replied'
            $updateStatus = "UPDATE inquiry SET status = 'replied' WHERE Inq_ID = '$replyto'";
            mysqli_query($db, $updateStatus);
            header("Location: ../home_pages/hpfinance.php?role=faculty");
            echo "<script>alert('Reply sent successfully.'); window.location.href='../home_pages/hpfinance.php?role=faculty';</script>";
            exit();
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Submit Student/Faculty Query
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['s_submit_query']) || isset($_POST['f_submit_query']))) {
    $studentId = getRoleSessionData('studentid');
    if (!$studentId && isset($_SESSION['studentId'])) {
        $studentId = $_SESSION['studentId'];
    }
    if (!$studentId) {
        die("Error: Student ID is not set in the session. Please log in again.");
    }
    $issue = mysqli_real_escape_string($db, $_POST['issue']);
    $department = mysqli_real_escape_string($db, $_POST['department']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $inq_type = mysqli_real_escape_string($db, $_POST['inq_type']);

    if (empty($issue)) { $errors++; echo "Issue field is required.<br>"; }
    if (empty($department)) { $errors++; echo "Department field is required.<br>"; }
    if (empty($description)) { $errors++; echo "Description field is required.<br>"; }

    if ($errors == 0) {
        $query = "INSERT INTO inquiry (issue, department, description, inq_type, studentId) 
                  VALUES ('$issue', '$department', '$description', '$inq_type', '$studentId')";
        if (mysqli_query($db, $query)) {
            echo "<script>alert('Query submitted successfully.');</script>";
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Event Addition
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_event'])){
    header("Location: ../Staff view/events_staff.php?role=" . $role);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit_event'])) {
    $event_name = mysqli_real_escape_string($db, $_POST['event_name']);
    $event_desc = mysqli_real_escape_string($db, $_POST['event_desc']);
    $event_time = mysqli_real_escape_string($db, $_POST['event_time']);
    $event_location = mysqli_real_escape_string($db, $_POST['event_location']);
    $event_type = mysqli_real_escape_string($db, $_POST['event_type']);
    $staff_id = mysqli_real_escape_string($db, $_POST['staff_id']);
    $news_image = "../images/default_event.jpg";
    $query = "INSERT INTO events (event_name, event_desc, event_time, event_location, event_type, news_image) 
              VALUES ('$event_name', '$event_desc', '$event_time', '$event_location', '$event_type', '$news_image')";
    if (mysqli_query($db, $query)) {
        $status_message = "<p style='text-align: center; color: green;'>Event added successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        $status_message = "<p style='color: red;'>Error adding event: " . mysqli_error($db) . "</p>";
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Event Deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Delete_event'])) {
    $event_id = mysqli_real_escape_string($db, $_POST['event_id']);
    $query = "DELETE FROM events WHERE event_id = '$event_id'";
    if (mysqli_query($db, $query)) {
        echo "<p style='text-align: center; color: green;'>Event deleted successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        echo "<p style='color: red;'>Error deleting event: " . mysqli_error($db) . "</p>";
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Event Editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Edit_event'])) {
    $event_id = mysqli_real_escape_string($db, $_POST['event_id']);
    header("Location: ../Admin-pages/edit_event.php?event_id=" . $event_id . "&role=" . $role);
    exit();
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Service Deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_service'])) {
    header("Location: ../Service_forms/delete_service.php?role=" . $role);
    exit();
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Add Service
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_service'])) {
    header("Location: ../Service_forms/add_service.php?role=" . $role);
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Service_add'])) {
    $service_name = mysqli_real_escape_string($db, $_POST['ser_name']);
    $service_details = mysqli_real_escape_string($db, $_POST['ser_details']);
    $service_date = mysqli_real_escape_string($db, $_POST['ser_date']);
    $lecturer_name = mysqli_real_escape_string($db, $_POST['ser_lecturer']);
    $location = mysqli_real_escape_string($db, $_POST['ser_location']);
    if (isset($_FILES['ser_image']) && $_FILES['ser_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["ser_image"]["name"]);
        move_uploaded_file($_FILES["ser_image"]["tmp_name"], $target_file);
        $service_image = $target_file;
    } else {
        $service_image = null;
    }
    $query = "INSERT INTO service (ser_name, ser_details, ser_date, ser_lecturer, ser_location, ser_img) 
              VALUES ('$service_name', '$service_details', '$service_date', '$lecturer_name', '$location', '$service_image')";
    if (mysqli_query($db, $query)) {
        echo "<p style='text-align: center; color: green;'>Service added successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        echo "<p style='color: red;'>Error adding service: " . mysqli_error($db) . "</p>";
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Review Inquiry
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Review'])) {
    $Inq_ID = mysqli_real_escape_string($db, $_POST['Inq_ID']);
    header("Location: ../Service_forms/review.php?Inq_ID=" . $Inq_ID . "&role=" . $role);
    exit();
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Helper functions for templates
function getUsername() {
    return getRoleSessionData('username', '');
}
function getEmail() {
    return getRoleSessionData('email', '');
}
function getSuccess() {
    return getRoleSessionData('success', '');
}
function getUserId() {
    return getRoleSessionData('Id', '');
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
