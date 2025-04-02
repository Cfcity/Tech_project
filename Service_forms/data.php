<?php
// Check if a session is already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables to avoid "undefined" errors
$issue = "";
$department = "";
$description = "";
$img = "";
$errors = 0;

// Retrieve the student ID from the session


// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'test');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['s_submit_query']) || isset($_POST['f_submit_query']))) {
    // Sanitize and retrieve form data
    if (isset($_SESSION['studentId'])) {
        $studentId = $_SESSION['studentId'];
    } else {
        die("Error: Student ID is not set in the session. Please log in again.");
    }

    $issue = mysqli_real_escape_string($db, $_POST['issue']);
    $department = mysqli_real_escape_string($db, $_POST['department']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $inq_type = mysqli_real_escape_string($db, $_POST['inq_type']);

    // Input validation
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
        // Insert the query into the database
        $query = "INSERT INTO inquiry (issue, department, description, inq_type, studentId) 
                  VALUES ('$issue', '$department', '$description', '$inq_type', '$studentId')";
        if (mysqli_query($db, $query)) {
            echo "Query submitted successfully.";
            if (isset($_POST['f_submit_query'])) {
                header("Location: ../Service_forms/R_finance.php");
            } elseif (isset($_POST['s_submit_query'])) {
                header("Location: ../Service_forms/R_subjects.php");
            }
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Send'])) {
    // Sanitize and retrieve form data
    $replyto = mysqli_real_escape_string($db, $_POST['replyto']);
    $reply = mysqli_real_escape_string($db, $_POST['reply']);

    // Input validation
    if (empty($reply)) {
        $errors++;
        echo "Message field is required.<br>";
    }

    if ($errors == 0) {
        // Check if Staffid is set in the session
        if (isset($_SESSION['Staffid'])) {
            $staffId = $_SESSION['Staffid'];
        } else {
            die("Error: Staff ID is not set in the session. Please log in again.");
        }

        // Debugging: Check the value of Staffid
        echo "Debug: Staff ID = " . $staffId;

        // Insert the reply into the database
        $query = "INSERT INTO reply (Inq_ID, reply, Staffid) VALUES ('$replyto', '$reply', '$staffId')";
        if (mysqli_query($db, $query)) {
            echo "Reply sent successfully.";
            header("Location: ../home_pages/hpfinance.php");
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}

mysqli_close($db);
?>