<?php
// Initialize variables to avoid "undefined" errors
$username = "";
$email = "";
$password = "";
$conpassword = "";
$role = 3;
$errors = 0;

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'test');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg_user'])) {
    // Sanitize and retrieve form data
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $conpassword = mysqli_real_escape_string($db, $_POST['conpassword']);
    $role = mysqli_real_escape_string($db, $_POST['role']);



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

    if ($role == 3) {
        echo "<p>Please select a role</p>";
        $errors = 6;
    }

    // If no errors, insert into database
    if ($errors == 0) {
        $query = "INSERT INTO user (username, email, password, conpassword, role) VALUES ('$username', '$email', '$password', '$conpassword', '$role')";
        if (mysqli_query($db, $query)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['email'] = $email;
            echo "<p style='text-align: center; color: green;'>Registration successful!</p>";
            echo '<script type="text/javascript">';
            echo 'window.open("../General/signup2.php", "_self");';
            echo '</script>';
        } else {
            echo "<p style='color: red;'>Error: " . mysqli_error($db) . "</p>";
        }
    }
}

// Login - send data to database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_user'])) {

    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);


    // Check data to see if it username and password matches  
    if ($errors == 0) {

        $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {

            $_SESSION['username'] = $username;
            $_SESSION['success'] = "Logged in";

            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['Id'] = $Id;
            if ($role == 1) {
                echo '<script type="text/javascript">';
                echo 'window.open("../General/home.php", "_self");';
                echo '</script>';
            } else if ($role == 2) {
                echo '<script type="text/javascript">';
                echo 'window.open("../General/Faculty_signup.php", "_self");';
                echo '</script>';
            } else if ($role == 3) {
                echo '<script type="text/javascript">';
                echo 'window.open("../General/student_signup.php", "_self");';
                echo '</script>';
            }
        } else {
            echo "<p class = 'centertop'> Incorrect username / password</p>";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $replyto = $_POST['replyto'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    echo '<script type="text/javascript">';
    echo 'window.open("../Service_forms/Reply.php", "_self");';
    echo '</script>';
    echo $replyto;
}

// Close the database connection
mysqli_close($db);
