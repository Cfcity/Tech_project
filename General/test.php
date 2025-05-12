<?php
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Session Initialization
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Role and Session Namespace Setup
$role = isset($_GET['role']) ? $_GET['role'] : (isset($_POST['role']) ? $_POST['role'] : (isset($_SESSION['current_role']) ? $_SESSION['current_role'] : null));

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
function setRoleSessionData($key, $value)
{
    global $role;
    if ($role !== null) {
        $_SESSION['roles'][$role][$key] = $value;
    }
}

function getRoleSessionData($key, $default = null)
{
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
// PASSWORD POLICY FUNCTIONS
/**
 * Validates password strength according to security requirements
 * @param string $password The password to validate
 * @return array [is_valid, message] indicating if password is valid and any error message
 */
function validatePassword($password) {
    $errors = [];
    
    // Check minimum length (12 characters recommended)
    if (strlen($password) < 12) {
        $errors[] = "Password must be at least 12 characters long";
    }
    
    // Check for uppercase letters
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must include at least one uppercase letter";
    }
    
    // Check for lowercase letters
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must include at least one lowercase letter";
    }
    
    // Check for numbers
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must include at least one number";
    }
    
    // Check for special characters
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = "Password must include at least one special character";
    }
    
    // Check if password contains common patterns
    $common_patterns = ['password', '123456', 'qwerty', 'admin', 'welcome'];
    foreach ($common_patterns as $pattern) {
        if (stripos($password, $pattern) !== false) {
            $errors[] = "Password contains a common pattern ($pattern) that is easily guessed";
            break;
        }
    }
    
    // Return result
    if (empty($errors)) {
        return [true, "Password meets all requirements"];
    } else {
        return [false, implode("<br>", $errors)];
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Registration - User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg_user'])) {
    $errors = 0;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Form validation
    if (empty($username)) { $errors = 1; echo "<p>Please fill in the username.</p>"; }
    if (empty($email)) { $errors = 2; echo "<p>Please fill in the email.</p>"; }
    if (empty($password)) { $errors = 3; echo "<p>Please fill in the password.</p>"; }
    
    // Validate password strength
    if (!empty($password)) {
        $password_validation = validatePassword($password);
        if (!$password_validation[0]) {
            $errors = 4;
            echo "<p style='color: red;'>" . $password_validation[1] . "</p>";
        }
    }

    if ($errors == 0) {
        // Check if username already exists - USING PREPARED STATEMENT
        $check_query = "SELECT * FROM user WHERE username = ?";
        $check_stmt = mysqli_prepare($db, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            echo "<p style='color: red;'>Username already exists. Please choose another username.</p>";
        } else {
            // Hash the password before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user - USING PREPARED STATEMENT
            $query = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                // Get the newly inserted user ID
                $user_id = mysqli_insert_id($db);
                
                // Store user data in both role-specific and global session
                setRoleSessionData('username', $username);
                setRoleSessionData('email', $email);
                setRoleSessionData('Id', $user_id);
                
                // Also store directly in the session for backup
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = $user_id;
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
                echo '<script type="text/javascript">';
                echo 'window.open("../General/signup2.php?role=' . $role . '&user_id=' . $user_id . '", "_self");';
                echo '</script>';
            } else {
                echo "<p style='color: red;'>Error: " . mysqli_error($db) . "</p>";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}

// Registration - Faculty
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_faculty'])) {
    $errors = 0;
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $bio = $_POST['bio'];
    $dept_id = $_POST['dept_id']; // changed from department
    $role_value = isset($_POST['role']) ? $_POST['role'] : 2;
    
    // Get user ID from various possible sources
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 
              (getRoleSessionData('Id') ? getRoleSessionData('Id') : 
              (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 
              (isset($_GET['user_id']) ? $_GET['user_id'] : null)));
    
    if (!$user_id) {
        echo "<p style='color: red;'>Error: User does not exist. Please register first. UserId: " . $user_id . "</p>";
        exit();
    }

    if (empty($f_name) || empty($l_name) || empty($bio) || empty($dept_id)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        // Update user - USING PREPARED STATEMENT
        $qu = "UPDATE user SET bio = ?, role = ? WHERE Id = ?";
        $stmt = mysqli_prepare($db, $qu);
        mysqli_stmt_bind_param($stmt, "sii", $bio, $role_value, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Insert into staff - USING PREPARED STATEMENT
            $qs = "INSERT INTO staff (Id, f_name, l_name, dept_id) VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($db, $qs);
            mysqli_stmt_bind_param($stmt2, "issi", $user_id, $f_name, $l_name, $dept_id);
            
            if (mysqli_stmt_execute($stmt2)) {
                echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
                header("Location: ../home_pages/hpfinance.php?role=faculty");
                exit();
            } else {
                echo "<p style='color: red;'>Error inserting into staff table: " . mysqli_error($db) . "</p>";
            }
            mysqli_stmt_close($stmt2);
        } else {
            echo "<p style='color: red;'>Error updating user table: " . mysqli_error($db) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }
}

// Registration - Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_student'])) {
    $errors = 0;
    $Stu_fname = $_POST['Stu_fname'];
    $Stu_lname = $_POST['Stu_lname'];
    $Stu_bio = $_POST['Stu_bio'];
    $dept_id = $_POST['Stu_dept_id']; // changed from Stu_department
    $role_value = isset($_POST['role']) ? $_POST['role'] : 3;
    
    // Get user ID from various possible sources
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 
              (getRoleSessionData('Id') ? getRoleSessionData('Id') : 
              (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 
              (isset($_GET['user_id']) ? $_GET['user_id'] : null)));
    
    if (!$user_id) {
        echo "<p style='color: red;'>Error: User does not exist. Please register first. UserId: " . $user_id . "</p>";
        exit();
    }

    if (empty($Stu_fname) || empty($Stu_lname) || empty($Stu_bio) || empty($dept_id)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        // Update user - USING PREPARED STATEMENT
        $qu = "UPDATE user SET bio = ?, role = ? WHERE Id = ?";
        $stmt = mysqli_prepare($db, $qu);
        mysqli_stmt_bind_param($stmt, "sii", $Stu_bio, $role_value, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Insert into students - USING PREPARED STATEMENT
            $qs = "INSERT INTO students (Id, Stu_fname, Stu_lname, Stu_dept_id) VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($db, $qs);
            mysqli_stmt_bind_param($stmt2, "issi", $user_id, $Stu_fname, $Stu_lname, $dept_id);
            
            if (mysqli_stmt_execute($stmt2)) {
                // Get the new studentid from the students table - USING PREPARED STATEMENT
                $student_query = "SELECT studentid FROM students WHERE Id = ? AND Stu_dept_id = ? LIMIT 1";
                $stmt3 = mysqli_prepare($db, $student_query);
                mysqli_stmt_bind_param($stmt3, "ii", $user_id, $dept_id);
                mysqli_stmt_execute($stmt3);
                $student_result = mysqli_stmt_get_result($stmt3);
                
                if ($student_result && $student_row = mysqli_fetch_assoc($student_result)) {
                    $studentid = $student_row['studentid'];
                    setRoleSessionData('Stu_dept_id', $dept_id);
                    setRoleSessionData('studentid', $studentid);
                    $_SESSION['studentId'] = $studentid;
                }
                mysqli_stmt_close($stmt3);
                
                echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
                header("Location: ../home_pages/hpstudent.php?role=student");
                exit();
            } else {
                echo "<p style='color: red;'>Error inserting into students table: " . mysqli_error($db) . "</p>";
            }
            mysqli_stmt_close($stmt2);
        } else {
            echo "<p style='color: red;'>Error updating user table: " . mysqli_error($db) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }
}

// Login - Authenticate User 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_user'])) {
    $errors = 0;
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($errors == 0) {
        // Query for user - USING PREPARED STATEMENT
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $results = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($results) == 1) {
            $row = mysqli_fetch_assoc($results);
            
            // Verify the hashed password
            if (password_verify($password, $row['password'])) {
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
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                // Set session timeout
                $_SESSION['last_activity'] = time();

                if ($row['role'] == 1) {
                    echo '<script type="text/javascript">';
                    echo 'window.open("../home_pages/home_admin.php?role=admin", "_self");';
                    echo '</script>';
                } else if ($row['role'] == 2) {
                    // Get staff ID - USING PREPARED STATEMENT
                    $query = "SELECT Staffid FROM staff WHERE Id = ?";
                    $stmt2 = mysqli_prepare($db, $query);
                    mysqli_stmt_bind_param($stmt2, "i", $row['Id']);
                    mysqli_stmt_execute($stmt2);
                    $result = mysqli_stmt_get_result($stmt2);
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        $staffRow = mysqli_fetch_assoc($result);
                        setRoleSessionData('Staffid', $staffRow['Staffid']);
                        $_SESSION['Staffid'] = $staffRow['Staffid'];
                    } else {
                        die("Error: Staff ID not found in the database.");
                    }
                    mysqli_stmt_close($stmt2);
                    
                    echo '<script type="text/javascript">';
                    echo 'window.open("../home_pages/hpfinance.php?role=faculty", "_self");';
                    echo '</script>';
                } else if ($row['role'] == 3) {
                    // Get student ID - USING PREPARED STATEMENT
                    $query = "SELECT studentid, Stu_dept_id FROM students WHERE Id = ?";
                    $stmt2 = mysqli_prepare($db, $query);
                    mysqli_stmt_bind_param($stmt2, "i", $row['Id']);
                    mysqli_stmt_execute($stmt2);
                    $result = mysqli_stmt_get_result($stmt2);
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        $studentRow = mysqli_fetch_assoc($result);
                        setRoleSessionData('studentid', $studentRow['studentid']);
                        setRoleSessionData('Stu_dept_id', $studentRow['Stu_dept_id']);
                        $_SESSION['studentId'] = $studentRow['studentid'];
                    }
                    mysqli_stmt_close($stmt2);
                    
                    echo '<script type="text/javascript">';
                    echo 'window.open("../home_pages/hpstudent.php?role=student", "_self");';
                    echo '</script>';
                }
            } else {
                echo "<p class='centertop' style='z-index: 3; left: 25%'> Incorrect username / password</p>";
            }
        } else {
            echo "<p class='centertop' style='z-index: 3; left: 25%'> Incorrect username / password</p>";
        }
        mysqli_stmt_close($stmt);
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Reply Handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $replyto = $_POST['replyto'];
    // Set inquiry status to 'pending' when opened for reply - USING PREPARED STATEMENT
    $updateStatus = "UPDATE inquiry SET status = 'pending' WHERE Inq_ID = ?";
    $stmt = mysqli_prepare($db, $updateStatus);
    mysqli_stmt_bind_param($stmt, "i", $replyto);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    echo '<script type="text/javascript">';
    echo 'window.open("../Service_forms/Reply.php?replyto=' . $replyto . '&role=' . $role . '", "_self");';
    echo '</script>';
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Send Reply
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Send'])) {
    $replyto = $_POST['replyto'];
    $reply = $_POST['reply'];
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
        // Insert the reply - USING PREPARED STATEMENT
        $query = "INSERT INTO reply (Inq_ID, reply, Staffid) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "isi", $replyto, $reply, $staffId);
        
        if (mysqli_stmt_execute($stmt)) {
            // Update inquiry status to 'replied' - USING PREPARED STATEMENT
            $updateStatus = "UPDATE inquiry SET status = 'replied' WHERE Inq_ID = ?";
            $stmt2 = mysqli_prepare($db, $updateStatus);
            mysqli_stmt_bind_param($stmt2, "i", $replyto);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
            
            header("Location: ../home_pages/hpfinance.php?role=faculty");
            echo "<script>alert('Reply sent successfully.'); window.location.href='../home_pages/hpfinance.php?role=faculty';</script>";
            exit();
        } else {
            echo "Error: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
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
    $issue = $_POST['issue'];
    $department = $_POST['department'];
    $description = $_POST['description'];
    $inq_type = $_POST['inq_type'];

    if (empty($issue)) {
        $errors++;
        echo "Issue field is required.<br>";
    }
    if (empty($department)) {
        $errors++;
        echo "Department field is required.<br>";
    }
    if (empty($description)) {
        $errors++;
        echo "Description field is required.<br>";
    }

    if ($errors == 0) {
        // Insert inquiry - USING PREPARED STATEMENT
        $query = "INSERT INTO inquiry (issue, department, description, inq_type, studentId) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $issue, $department, $description, $inq_type, $studentId);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Query submitted successfully.');</script>";
        } else {
            echo "Error: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Event Addition
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_event'])) {
    header("Location: ../Staff view/events_staff.php?role=" . $role);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit_event'])) {
    $event_name = $_POST['event_name'];
    $event_desc = $_POST['event_desc'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_type = $_POST['event_type'];
    $staff_id = $_POST['staff_id'];
    $news_image = "../images/default_event.jpg";
    
    // Insert event - USING PREPARED STATEMENT
    $query = "INSERT INTO events (event_name, event_desc, event_time, event_location, event_type, news_image) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $event_name, $event_desc, $event_time, $event_location, $event_type, $news_image);
    
    if (mysqli_stmt_execute($stmt)) {
        $status_message = "<p style='text-align: center; color: green;'>Event added successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        $status_message = "<p style='color: red;'>Error adding event: " . mysqli_error($db) . "</p>";
    }
    mysqli_stmt_close($stmt);
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Event Deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Delete_event'])) {
    $event_id = $_POST['event_id'];
    
    // Delete event - USING PREPARED STATEMENT
    $query = "DELETE FROM events WHERE event_id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='text-align: center; color: green;'>Event deleted successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        echo "<p style='color: red;'>Error deleting event: " . mysqli_error($db) . "</p>";
    }
    mysqli_stmt_close($stmt);
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Event Editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Edit_event'])) {
    $event_id = $_POST['event_id'];
    header("Location: ../Admin-pages/edit_event.php?event_id=" . $event_id . "&role=" . $role);
    exit();
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
//update event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_desc = $_POST['event_description'];
    $news_image = $_POST['event_image'];
    $event_type = $_POST['event_type'];

    // Update event - USING PREPARED STATEMENT
    $query = "UPDATE events SET event_name=?, event_time=?, event_location=?, event_desc=?, news_image=?, event_type=? WHERE event_id=?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ssssssi", $event_name, $event_time, $event_location, $event_desc, $news_image, $event_type, $event_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='text-align: center; color: green;'>Event updated successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        echo "<p style='color: red;'>Error updating event: " . mysqli_error($db) . "</p>";
    }
    mysqli_stmt_close($stmt);
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Service Deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_service'])) {
    header("Location: ../Service_forms/delete_service.php?role=" . $role);
    exit();
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Service delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_delete'])) {
    $service_id = $_POST['service_id'];
    
    // Delete service - USING PREPARED STATEMENT
    $query = "DELETE FROM service WHERE ser_id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $service_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='text-align: center; color: green;'>Service deleted successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        echo "<p style='color: red;'>Error deleting service: " . mysqli_error($db) . "</p>";
    }
    mysqli_stmt_close($stmt);
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Add Service
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_service'])) {
    header("Location: ../Service_forms/add_service.php?role=" . $role);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Service_add'])) {
    $service_name = $_POST['ser_name'];
    $service_details = $_POST['ser_details'];
    $service_date = $_POST['ser_date'];
    $lecturer_name = $_POST['ser_lecturer'];
    $location = $_POST['ser_location'];
    
    // Secure file upload handling
    $service_image = null;
    if (isset($_FILES['ser_image']) && $_FILES['ser_image']['error'] == UPLOAD_ERR_OK) {
        // Define allowed file types and max size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if ($_FILES['ser_image']['size'] > $max_size) {
            echo "<p style='color: red;'>File is too large. Maximum size is 2MB.</p>";
        } else {
            // Check file type using mime
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $file_type = $finfo->file($_FILES['ser_image']['tmp_name']);
            
            if (!in_array($file_type, $allowed_types)) {
                echo "<p style='color: red;'>Invalid file type. Only JPG, PNG and GIF are allowed.</p>";
            } else {
                // Generate unique filename to prevent overwriting
                $file_ext = pathinfo($_FILES['ser_image']['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid('service_', true) . '.' . $file_ext;
                $target_dir = "../images/";
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['ser_image']['tmp_name'], $target_file)) {
                    $service_image = $target_file;
                } else {
                    echo "<p style='color: red;'>Error uploading file.</p>";
                }
            }
        }
    }
    
    // Insert service using prepared statement
    $query = "INSERT INTO service (ser_name, ser_details, ser_date, ser_lecturer, ser_location, ser_img) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $service_name, $service_details, $service_date, $lecturer_name, $location, $service_image);
    
    if (mysqli_stmt_execute($stmt)) {
        // Create service file securely
        $filename = "../services/" . preg_replace('/[^a-z0-9_\-\.]/i', '_', $service_name) . ".php";
        
        // Create file content with sanitized variables
        $content = "
<?php
  include('../General/test.php');
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>" . htmlspecialchars($service_name) . "</title>
    <link rel='stylesheet' href='../css/main.css'>
</head>
<body class='dashboard-container'>
    <table class='dashboard-table' style='max-width:700px;margin:2rem auto;'>
        <tr>
            <th colspan='2' style='font-size:1.5rem;'>" . htmlspecialchars($service_name) . "</th>
        </tr>
        <tr>
            <td><strong>Details:</strong></td>
            <td>" . htmlspecialchars($service_details) . "</td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td>" . htmlspecialchars($service_date) . "</td>
        </tr>
        <tr>
            <td><strong>Lecturer:</strong></td>
            <td>" . htmlspecialchars($lecturer_name) . "</td>
        </tr>
        <tr>
            <td><strong>Location:</strong></td>
            <td>" . htmlspecialchars($location) . "</td>
        </tr>
        <tr>
            <td colspan='2' style='text-align:center;'>
                <img src='" . htmlspecialchars($service_image ?? '../images/default_service.jpg') . "' alt='Service Image' style='width: 100%; max-width: 600px;'>
            </td>
        </tr>
        <tr>
            <td colspan='2' style='text-align:center;'>
                <?php
                if (\$role === 'admin' || \$role == 1) {
                    echo \"<a href='../home_pages/home_admin.php?role=\$role'>Back to Home</a>\";
                } else if (\$role === 'faculty' || \$role == 2) {
                    echo \"<a href='../home_pages/hpfinance.php?role=\$role'>Back to Home</a>\";
                } else if (\$role === 'student' || \$role == 3) {
                    echo \"<a href='../home_pages/hpstudent.php?role=\$role'>Back to Home</a>\";
                } else {
                    echo \"<a href='../index.php'>Back to Home</a>\";
                }
                ?>
            </td>
        </tr>
    </table>
</body>
</html>";

        // Write file securely
        if (file_put_contents($filename, $content) !== false) {
            // Set appropriate permissions
            chmod($filename, 0644);
            
            echo "<p style='text-align: center; color: green;'>Service added successfully!</p>";
            header("Location: ../home_pages/home_admin.php?role=" . $role);
            exit();
        } else {
            echo "<p style='color: red;'>Error creating service page.</p>";
        }
    } else {
        echo "<p style='color: red;'>Error adding service: " . mysqli_error($db) . "</p>";
    }
    mysqli_stmt_close($stmt);
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Review Inquiry
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Review'])) {
    $Inq_ID = $_POST['Inq_ID'];
    // Validate Inq_ID is numeric
    if (!is_numeric($Inq_ID)) {
        die("Invalid inquiry ID");
    }
    header("Location: ../Service_forms/review.php?Inq_ID=" . (int)$Inq_ID . "&role=" . $role);
    exit();
}

//

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// CSRF Protection Functions
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Add CSRF token to forms
if (!isset($_SESSION['csrf_token'])) {
    generateCsrfToken();
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Rate Limiting for Login Attempts
function checkLoginAttempts($username, $db) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $now = time();
    $window = 15 * 60; // 15 minute window
    
    // Check if IP is blocked
    $query = "SELECT COUNT(*) as attempts, MAX(time) as last_attempt 
              FROM login_attempts 
              WHERE ip = ? AND time > ?";
    $stmt = mysqli_prepare($db, $query);
    $window_start = $now - $window;
    mysqli_stmt_bind_param($stmt, "si", $ip, $window_start);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if ($data['attempts'] >= 5) {
        // Too many attempts
        $last_attempt = $data['last_attempt'];
        $wait_time = $window - ($now - $last_attempt);
        die("Too many login attempts. Please wait " . ceil($wait_time/60) . " minutes before trying again.");
    }
    
    return true;
}

function recordLoginAttempt($username, $success, $db) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = time();
    
    $query = "INSERT INTO login_attempts (username, ip, time, success) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    $success_int = $success ? 1 : 0;
    mysqli_stmt_bind_param($stmt, "ssii", $username, $ip, $time, $success_int);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Improved Logout Functionality
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])) {
    // Unset all session variables
    $_SESSION = array();
    
    // Delete session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
    
    // Regenerate session ID for security
    session_start();
    session_regenerate_id(true);
    session_destroy();
    
    // Redirect to login page
    header("Location: ../General/Technical project.php");
    exit();
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Input Validation Functions
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Password Reset Functionality
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['reset_password'])) {
    $email = $_POST['email'];
    
    if (!validateEmail($email)) {
        echo "<p style='color: red;'>Invalid email address.</p>";
    } else {
        // Check if email exists
        $query = "SELECT Id FROM user WHERE email = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['Id'];
            
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = time() + 3600; // 1 hour expiration
            
            // Store token in database
            $query = "INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?) 
                      ON DUPLICATE KEY UPDATE token = ?, expires = ?";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "isisi", $user_id, $token, $expires, $token, $expires);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            // Send reset email (in a real application)
            $reset_link = "https://yourdomain.com/reset_password.php?token=$token";
            // mail($email, "Password Reset", "Click here to reset your password: $reset_link");
            
            echo "<p style='color: green;'>Password reset link has been sent to your email.</p>";
        } else {
            echo "<p style='color: green;'>If an account exists with this email, a reset link has been sent.</p>";
        }
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Helper functions for templates
function getUsername() {
    return isset($_SESSION['username']) ? sanitizeInput($_SESSION['username']) : '';
}

function getEmail() {
    return isset($_SESSION['email']) ? sanitizeInput($_SESSION['email']) : '';
}

function getSuccess() {
    return isset($_SESSION['success']) ? sanitizeInput($_SESSION['success']) : '';
}

function getUserId() {
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Close database connection at the end
register_shutdown_function(function() use ($db) {
    if ($db) {
        mysqli_close($db);
    }
});

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Session timeout handling
if (isset($_SESSION['last_activity'])) {
    $session_timeout = 1800; // 30 minutes in seconds
    
    // Check if session has expired
    if (time() - $_SESSION['last_activity'] > $session_timeout) {
        // Session expired, destroy it
        session_unset();
        session_destroy();
        header("Location: ../General/Technical project.php?timeout=1");
        exit();
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:");

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Error reporting (should be disabled in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable error display in production
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
?>