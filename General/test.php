<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables to avoid "undefined" errors
$username = "";
$email = "";
$password = "";
$conpassword = "";
$role = 3;
$errors = 0;
$replyto = [];

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'test');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Registration - send data to database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg_user'])) {
    // Reset errors
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

    // Check if passwords match
    if ($password != $conpassword) {
        echo "<p>Incorrect password confirmation.</p>";
        $errors = 5;
    }

    // If no errors, insert into database
    if ($errors == 0) {
        $query = "INSERT INTO user (username, email, password, conpassword) VALUES ('$username', '$email', '$password', '$conpassword')";
        if (mysqli_query($db, $query)) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            $qs = "SELECT Id FROM user WHERE username = '$username'";
            $result = mysqli_query($db, $qs);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['Id'] = $row['Id'];
            } else {
                die("Error: User ID not found in the database.");
            }

            echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
            echo '<script type="text/javascript">';
            echo 'window.open("../General/signup2.php", "_self");';
            echo '</script>';
        } else {
            echo "<p style='color: red;'>Error: " . mysqli_error($db) . "</p>";
        }
    }
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Faculty Signup - send data to database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_faculty'])) {
    $errors = 0;

    $f_name = mysqli_real_escape_string($db, $_POST['f_name']);
    $l_name = mysqli_real_escape_string($db, $_POST['l_name']);
    $bio = mysqli_real_escape_string($db, $_POST['bio']);
    $department = mysqli_real_escape_string($db, $_POST['department']);
    $role = isset($_POST['role']) ? mysqli_real_escape_string($db, $_POST['role']) : 2;

    if (empty($f_name) || empty($l_name) || empty($bio) || empty($department)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        // Ensure the user table is updated with bio and role
        $qu = "UPDATE user SET bio = '$bio', role = '$role' WHERE Id = '" . $_SESSION['Id'] . "'";
        if (mysqli_query($db, $qu)) {
            // Debugging: Check if user ID exists
            $query = "SELECT Id FROM user WHERE Id = '" . $_SESSION['Id'] . "'";
            $result = mysqli_query($db, $query);
            if (!$result || mysqli_num_rows($result) == 0) {
                die("Error: User ID not found in the database.");
            }

            // Insert into the staff table
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

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Student Signup - send data to database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_student'])) {
    $errors = 0;

    $Stu_fname = mysqli_real_escape_string($db, $_POST['Stu_fname']);
    $Stu_lname = mysqli_real_escape_string($db, $_POST['Stu_lname']);
    $Stu_bio = mysqli_real_escape_string($db, $_POST['Stu_bio']);
    $Stu_department = mysqli_real_escape_string($db, $_POST['Stu_department']);
    $role = isset($_POST['role']) ? mysqli_real_escape_string($db, $_POST['role']) : 3;

    if (empty($Stu_fname) || empty($Stu_lname) || empty($Stu_bio) || empty($Stu_department)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        // Update the user table with bio and role
        $qu = "UPDATE user SET bio = '$Stu_bio', role = '$role' WHERE Id = '" . $_SESSION['Id'] . "'";
        if (mysqli_query($db, $qu)) {
            // Insert into the students table
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

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Login - send data to database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_user'])) {
    $errors = 0;

    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if ($errors == 0) {
        $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $row = mysqli_fetch_assoc($results);
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "Logged in";
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['Id'] = $row['Id'];

            if ($row['role'] == 1) {
                echo '<script type="text/javascript">';
                echo 'window.open("../home_pages/home.php", "_self");';
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

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// Reply Handling

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $replyto = $_POST['replyto'];
    echo '<script type="text/javascript">';
    echo 'window.open("../Service_forms/Reply.php?replyto=' . $replyto . '", "_self");';
    echo '</script>';
}

// Close the database connection
mysqli_close($db);
