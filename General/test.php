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
// Registration - User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg_user'])) {
    $errors = 0;
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    // Remove conpassword variable

    if (empty($username)) { $errors = 1; echo "<p>Please fill in the username.</p>"; }
    if (empty($email)) { $errors = 2; echo "<p>Please fill in the email.</p>"; }
    if (empty($password)) { $errors = 3; echo "<p>Please fill in the password.</p>"; }
    // Remove password confirmation check

    if ($errors == 0) {
        // First check if username already exists
        $check_query = "SELECT * FROM user WHERE username = '$username'";
        $check_result = mysqli_query($db, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            echo "<p style='color: red;'>Username already exists. Please choose another username.</p>";
        } else {
            // Hash the password before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            if (mysqli_query($db, $query)) {
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
                
                echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
                echo '<script type="text/javascript">';
                echo 'window.open("../General/signup2.php?role=' . $role . '&user_id=' . $user_id . '", "_self");';
                echo '</script>';
            } else {
                echo "<p style='color: red;'>Error: " . mysqli_error($db) . "</p>";
            }
        }
    }
}

// Registration - Faculty
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_faculty'])) {
    $errors = 0;
    $f_name = mysqli_real_escape_string($db, $_POST['f_name']);
    $l_name = mysqli_real_escape_string($db, $_POST['l_name']);
    $bio = mysqli_real_escape_string($db, $_POST['bio']);
    $dept_id = mysqli_real_escape_string($db, $_POST['dept_id']); // changed from department
    $role_value = isset($_POST['role']) ? mysqli_real_escape_string($db, $_POST['role']) : 2;
    
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
        $qu = "UPDATE user SET bio = '$bio', role = '$role_value' WHERE Id = '$user_id'";
        if (mysqli_query($db, $qu)) {
            $qs = "INSERT INTO staff (Id, f_name, l_name, dept_id) VALUES ('$user_id', '$f_name', '$l_name', '$dept_id')";
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

// Registration - Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_student'])) {
    $errors = 0;
    $Stu_fname = mysqli_real_escape_string($db, $_POST['Stu_fname']);
    $Stu_lname = mysqli_real_escape_string($db, $_POST['Stu_lname']);
    $Stu_bio = mysqli_real_escape_string($db, $_POST['Stu_bio']);
    $dept_id = mysqli_real_escape_string($db, $_POST['Stu_dept_id']); // changed from Stu_department
    $role_value = isset($_POST['role']) ? mysqli_real_escape_string($db, $_POST['role']) : 3;
    
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
        // Update bio and role in user table
        $qu = "UPDATE user SET bio = '$Stu_bio', role = '$role_value' WHERE Id = '$user_id'";
        if (mysqli_query($db, $qu)) {
            // Insert into students table (no bio column)
            $qs = "INSERT INTO students (Id, Stu_fname, Stu_lname, Stu_dept_id) VALUES ('$user_id', '$Stu_fname', '$Stu_lname', '$dept_id')";
            if (mysqli_query($db, $qs)) {
                // Get the new studentid from the students table
                $student_query = "SELECT studentid FROM students WHERE Id = '$user_id' AND Stu_dept_id = '$dept_id' LIMIT 1";
                $student_result = mysqli_query($db, $student_query);
                if ($student_result && $student_row = mysqli_fetch_assoc($student_result)) {
                    $studentid = $student_row['studentid'];
                    setRoleSessionData('Stu_dept_id', $dept_id);
                    setRoleSessionData('studentid', $studentid);
                    $_SESSION['studentId'] = $studentid;
                }
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

// Login - Authenticate User (update student join)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_user'])) {
    $errors = 0;
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if ($errors == 0) {
        $query = "SELECT * FROM user WHERE username = '$username'";
        $results = mysqli_query($db, $query);

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
                    $query = "SELECT studentid, Stu_dept_id FROM students WHERE Id = '" . $row['Id'] . "'";
                    $result = mysqli_query($db, $query);
                    if ($result && mysqli_num_rows($result) > 0) {
                        $studentRow = mysqli_fetch_assoc($result);
                        setRoleSessionData('studentid', $studentRow['studentid']);
                        setRoleSessionData('Stu_dept_id', $studentRow['Stu_dept_id']);
                        $_SESSION['studentId'] = $studentRow['studentid'];
                    }
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
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_event'])) {
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
//update event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_event'])) {
    $event_id = mysqli_real_escape_string($db, $_POST['event_id']);
    $event_name = mysqli_real_escape_string($db, $_POST['event_name']);
    $event_time = mysqli_real_escape_string($db, $_POST['event_time']);
    $event_location = mysqli_real_escape_string($db, $_POST['event_location']);
    $event_desc = mysqli_real_escape_string($db, $_POST['event_description']);
    $news_image = mysqli_real_escape_string($db, $_POST['event_image']);
    $event_type = mysqli_real_escape_string($db, $_POST['event_type']);

    $query = "UPDATE events SET event_name='$event_name', event_time='$event_time', event_location='$event_location', event_desc='$event_desc', news_image='$news_image', event_type='$event_type' WHERE event_id='$event_id'";
    if (mysqli_query($db, $query)) {
        echo "<p style='text-align: center; color: green;'>Event updated successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        echo "<p style='color: red;'>Error updating event: " . mysqli_error($db) . "</p>";
    }
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
    $service_id = mysqli_real_escape_string($db, $_POST['service_id']);
    $query = "DELETE FROM service WHERE ser_id = '$service_id'";
    if (mysqli_query($db, $query)) {
        echo "<p style='text-align: center; color: green;'>Service deleted successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        echo "<p style='color: red;'>Error deleting service: " . mysqli_error($db) . "</p>";
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Add Service
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_service'])) {
    header("Location: ../Service_forms/add_service.php?role=" . $role);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Service_add'])) {
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

        $filename = "../services/" . $service_name . ".php";
        $file = fopen($filename, "w");

        if ($file) {
            $content = "
<?php
  include('../General/test.php');
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$service_name</title>
    <link rel='stylesheet' href='../css/main.css'>
</head>
<body class='dashboard-container'>
    <table class='dashboard-table' style='max-width:700px;margin:2rem auto;'>
        <tr>
            <th colspan='2' style='font-size:1.5rem;'>$service_name</th>
        </tr>
        <tr>
            <td><strong>Details:</strong></td>
            <td>$service_details</td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td>$service_date</td>
        </tr>
        <tr>
            <td><strong>Lecturer:</strong></td>
            <td>$lecturer_name</td>
        </tr>
        <tr>
            <td><strong>Location:</strong></td>
            <td>$location</td>
        </tr>
        <tr>
            <td colspan='2' style='text-align:center;'>
                <img src='$service_image' alt='no available image' style='width: 100%; max-width: 600px;'>
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
</html>
";
            fwrite($file, $content);
            fclose($file);

            echo "<p style='text-align: center; color: green;'>Service added successfully!</p>";
            header("Location: ../home_pages/home_admin.php?role=" . $role);
            exit();
        } else {
            echo "Error creating file.";
        }
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
//Delete Inquiry and reply
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['del_inquiry'])) {
    $Inq_ID = mysqli_real_escape_string($db, $_POST['Inq_ID']);
    $query = "DELETE FROM inquiry WHERE Inq_ID = '$Inq_ID'";
    $query2 = "DELETE FROM reply WHERE Inq_ID = '$Inq_ID'";
    if (mysqli_query($db, $query) && mysqli_query($db, $query2)) {
        echo "<p style='text-align: center; color: green;'>Inquiry deleted successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        echo "<p style='color: red;'>Error deleting inquiry: " . mysqli_error($db) . "</p>";
    }
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
//Delete reply 
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['del_reply'])) {
    $Reply_ID = mysqli_real_escape_string($db, $_POST['Reply_ID']);
    $query = "DELETE FROM reply WHERE Reply_ID = '$Reply_ID'";
    // Get the related inquiry ID before deleting the reply
    $inqResult = mysqli_query($db, "SELECT Inq_ID FROM reply WHERE Reply_ID = '$Reply_ID'");
    $inqRow = mysqli_fetch_assoc($inqResult);
    $Inq_ID = $inqRow ? $inqRow['Inq_ID'] : null;

    if (mysqli_query($db, $query)) {
        // If there are no more replies for this inquiry, set status to 'pending'
        if ($Inq_ID) {
            $checkReplies = mysqli_query($db, "SELECT COUNT(*) as cnt FROM reply WHERE Inq_ID = '$Inq_ID'");
            $cntRow = mysqli_fetch_assoc($checkReplies);
            if ($cntRow && $cntRow['cnt'] == 0) {
                $updateStatus = "UPDATE inquiry SET status = 'unread' WHERE Inq_ID = '$Inq_ID'";
                mysqli_query($db, $updateStatus);
            }
        }
        echo "<p style='text-align: center; color: green;'>Reply deleted successfully!</p>";
        header("Location: ../home_pages/home_admin.php?role=" . $role);
        exit();
    } else {
        echo "<p style='color: red;'>Error deleting reply: " . mysqli_error($db) . "</p>";
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------
//view users
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['view_users'])) {
    header("Location: ../Admin-pages/view_users.php?role=" . $role);
    exit();
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Meeting Addition (redirect to meeting form)
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['new_meeting'])) {
    header("Location: ../Service_forms/meeting.php?role=" . $role);
    exit();
}

// Handle meeting form submission
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_meeting'])) {
    $meeting_name = mysqli_real_escape_string($db, $_POST['meeting_name']);
    $meeting_time = mysqli_real_escape_string($db, $_POST['meeting_time']);
    $meeting_location = mysqli_real_escape_string($db, $_POST['meeting_location']);
    $meeting_desc = mysqli_real_escape_string($db, $_POST['meeting_desc']);

    if (empty($meeting_name) || empty($meeting_time) || empty($meeting_location)) {
        echo "<div class='center-text' style='color:red; margin-top:1rem;'>All fields except description are required.</div>";
    } else {
        $query = "INSERT INTO meetings (meeting_name, meeting_time, meeting_location, meeting_desc) VALUES ('$meeting_name', '$meeting_time', '$meeting_location', '$meeting_desc')";
        if (mysqli_query($db, $query)) {
            echo "<div class='center-text' style='color:green; margin-top:1rem;'>Meeting created successfully!</div>";
            header("Location: ../home_pages/home_admin.php?role=" . $role);
            exit();
        } else {
            echo "<div class='center-text' style='color:red; margin-top:1rem;'>Error creating meeting: " . mysqli_error($db) . "</div>";
        }
    }
}

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
//Logout

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])){

    session_unset();
    session_destroy();
    header("Location: ../General/Technical project.php");
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Helper functions for templates
function getUsername()
{
    return getRoleSessionData('username', '');
}
function getEmail()
{
    return getRoleSessionData('email', '');
}
function getSuccess()
{
    return getRoleSessionData('success', '');
}
function getUserId()
{
    return getRoleSessionData('Id', '');
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------

?>
