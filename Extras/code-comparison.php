// CURRENT CODE                                     | // IMPROVED CODE
<?php                                              | <?php
if (session_status() == PHP_SESSION_NONE) {        | // Start a secure session
    session_start(); // Start the session if it    | if (session_status() == PHP_SESSION_NONE) {
    // hasn't been started already                 |     ini_set('session.cookie_httponly', 1);
}                                                  |     ini_set('session.cookie_secure', 1); // For HTTPS
                                                   |     ini_set('session.use_only_cookies', 1);
                                                   |     session_start();
                                                   | }
                                                   | 
// Initialize variables to avoid "undefined" errors | // Initialize variables
$username = "";                                    | $username = "";
$email = "";                                       | $email = "";
$password = "";                                    | $password = "";
$conpassword = "";                                 | $conpassword = "";
$role = 3;                                         | $role = 3;
$errors = 0;                                       | $errors = [];
$replyto = [];                                     | $replyto = "";
                                                   | 
// Connect to the database                         | // Database configuration - best practice: store in separate config file
$db = mysqli_connect('localhost', 'root', '',      | require_once 'config.php'; // Contains DB credentials
    'test');                                       | 
                                                   | try {
if (!$db) {                                        |     $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    die("Connection failed: " . mysqli_connect_    |     if ($db->connect_error) {
        error());                                  |         throw new Exception("Connection failed: " . $db->connect_error);
}                                                  |     }
                                                   |     // Set charset for security
                                                   |     $db->set_charset("utf8mb4");
                                                   | } catch (Exception $e) {
                                                   |     // Log error instead of displaying to user
                                                   |     error_log("Database connection error: " . $e->getMessage());
                                                   |     die("A database error occurred. Please try again later.");
                                                   | }
                                                   | 
// Check if form is submitted                      | // CSRF protection function
if ($_SERVER["REQUEST_METHOD"] == "POST" &&        | function generateCSRFToken() {
    isset($_POST['reg_user'])) {                   |     if (!isset($_SESSION['csrf_token'])) {
    // Reset errors                                |         $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $errors = 0;                                   |     }
                                                   |     return $_SESSION['csrf_token'];
    // Sanitize and retrieve form data             | }
    $username = mysqli_real_escape_string($db,     |
        $_POST['username']);                       | function validateCSRFToken($token) {
    $email = mysqli_real_escape_string($db,        |     return isset($_SESSION['csrf_token']) && 
        $_POST['email']);                          |            hash_equals($_SESSION['csrf_token'], $token);
    $password = mysqli_real_escape_string($db,     | }
        $_POST['password']);                       |
    $conpassword = mysqli_real_escape_string($db,  | // Registration form processing
        $_POST['conpassword']);                    | if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg_user'])) {
    $role = mysqli_real_escape_string($db,         |     $errors = [];
        $_POST['role']);                           |     
                                                   |     // Validate CSRF token
                                                   |     if (!isset($_POST['csrf_token']) || 
                                                   |         !validateCSRFToken($_POST['csrf_token'])) {
                                                   |         $errors[] = "Invalid form submission";
                                                   |     }
                                                   |     
    // Validate form fields                        |     // Sanitize and validate input
    if (empty($username)) {                        |     $username = filter_input(INPUT_POST, 'username', 
        $errors = 1;                               |         FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        echo "<p>Please fill in the username.</p>";|     $email = filter_input(INPUT_POST, 'email', 
    }                                              |         FILTER_SANITIZE_EMAIL);
    if (empty($email)) {                           |     $password = $_POST['password'] ?? '';
        $errors = 2;                               |     $conpassword = $_POST['conpassword'] ?? '';
        echo "<p>Please fill in the email.</p>";   |     $role = filter_input(INPUT_POST, 'role', 
    }                                              |         FILTER_SANITIZE_NUMBER_INT);
    if (empty($password)) {                        |     
        $errors = 3;                               |     // Validate form fields
        echo "<p>Please fill in the password.</p>";|     if (empty($username)) {
    }                                              |         $errors[] = "Please fill in the username.";
    if (empty($conpassword)) {                     |     } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $errors = 4;                               |         $errors[] = "Username must be 3-20 characters and contain 
        echo "<p>Please confirm your password.</p>";|             only letters, numbers, and underscores.";
    }                                              |     }
                                                   |     
    // Check if passwords match                    |     if (empty($email)) {
    if ($password != $conpassword) {               |         $errors[] = "Please fill in the email.";
        echo "<p>Incorrect password                |     } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            confirmation.</p>";                    |         $errors[] = "Please enter a valid email address.";
        $errors = 5;                               |     }
    }                                              |     
                                                   |     if (empty($password)) {
    if ($role == 3) {                              |         $errors[] = "Please fill in the password.";
        echo "<p>Please select a role</p>";        |     } elseif (strlen($password) < 8) {
        $errors = 6;                               |         $errors[] = "Password must be at least 8 characters long.";
    }                                              |     }
                                                   |     
                                                   |     if (empty($conpassword)) {
                                                   |         $errors[] = "Please confirm your password.";
                                                   |     }
                                                   |     
                                                   |     if ($password !== $conpassword) {
                                                   |         $errors[] = "Passwords do not match.";
                                                   |     }
                                                   |     
                                                   |     if ($role < 1 || $role > 3) {
                                                   |         $errors[] = "Please select a valid role.";
                                                   |     }
                                                   |     
                                                   |     // Check if username or email already exists
                                                   |     $stmt = $db->prepare("SELECT COUNT(*) FROM user WHERE 
                                                   |         username = ? OR email = ?");
                                                   |     $stmt->bind_param("ss", $username, $email);
                                                   |     $stmt->execute();
                                                   |     $stmt->bind_result($count);
                                                   |     $stmt->fetch();
                                                   |     $stmt->close();
                                                   |     
                                                   |     if ($count > 0) {
                                                   |         $errors[] = "Username or email already exists.";
                                                   |     }
                                                   |     
    // If no errors, insert into database          |     // If no errors, insert into database
    if ($errors == 0) {                            |     if (empty($errors)) {
        $query = "INSERT INTO user (username,      |         // Hash the password
        email, password, conpassword, role)        |         $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        VALUES ('$username', '$email', '$password',|         
        '$conpassword', '$role')";                 |         // Use prepared statements to prevent SQL injection
                                                   |         $stmt = $db->prepare("INSERT INTO user (username, email, 
        if (mysqli_query($db, $query)) {           |             password, role) VALUES (?, ?, ?, ?)");
            $_SESSION['username'] = $username;     |         $stmt->bind_param("sssi", $username, $email, 
            $_SESSION['role'] = $role;             |             $hashedPassword, $role);
            $_SESSION['email'] = $email;           |         
            echo "<p style='text-align: center;    |         if ($stmt->execute()) {
                color: green;'>Registration        |             // Store user info in session
                successful!</p>";                  |             $_SESSION['user_id'] = $stmt->insert_id;
            echo '<script type="text/javascript">';|             $_SESSION['username'] = $username;
            echo 'window.open("../General/         |             $_SESSION['role'] = $role;
                signup2.php", "_self");';          |             $_SESSION['email'] = $email;
            echo '</script>';                      |             
        } else {                                   |             // Regenerate session ID to prevent session fixation
            echo "<p style='color: red;'>Error: "  |             session_regenerate_id(true);
                . mysqli_error($db) . "</p>";      |             
        }                                          |             // Set success message in session
                                                   |             $_SESSION['success_message'] = "Registration successful!";
                                                   |             
                                                   |             // Redirect to next page
                                                   |             header("Location: ../General/signup2.php");
                                                   |             exit();
                                                   |         } else {
                                                   |             $errors[] = "Database error: " . $stmt->error;
                                                   |             error_log("Registration error: " . $stmt->error);
                                                   |         }
                                                   |         $stmt->close();
    }                                              |     }
}                                                  | }
                                                   | 
// Login - send data to database                   | // Login form processing
if ($_SERVER["REQUEST_METHOD"] == "POST" &&        | if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_user'])) {
    isset($_POST['login_user'])) {                 |     $errors = [];
    // Reset errors                                |     
    $errors = 0;                                   |     // Validate CSRF token
                                                   |     if (!isset($_POST['csrf_token']) || 
                                                   |         !validateCSRFToken($_POST['csrf_token'])) {
                                                   |         $errors[] = "Invalid form submission";
                                                   |     }
                                                   |     
    $username = mysqli_real_escape_string($db,     |     // Sanitize input
        $_POST['username']);                       |     $username = filter_input(INPUT_POST, 'username', 
    $password = mysqli_real_escape_string($db,     |         FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_POST['password']);                       |     $password = $_POST['password'] ?? '';
                                                   |     
    // Check data to see if it username and        |     if (empty($username)) {
    // password matches                            |         $errors[] = "Username is required";
    if ($errors == 0) {                            |     }
        $query = "SELECT * FROM user WHERE         |     
            username = '$username' AND password =  |     if (empty($password)) {
            '$password'";                          |         $errors[] = "Password is required";
        $results = mysqli_query($db, $query);      |     }
                                                   |     
        if (mysqli_num_rows($results) == 1) {      |     if (empty($errors)) {
            $row = mysqli_fetch_assoc($results);   |         // Use prepared statement for login
            $_SESSION['username'] = $username;     |         $stmt = $db->prepare("SELECT Id, username, email, password, 
            $_SESSION['success'] = "Logged in";    |             role FROM user WHERE username = ?");
            $_SESSION['email'] = $row['email'];    |         $stmt->bind_param("s", $username);
            $_SESSION['role'] = $row['role'];      |         $stmt->execute();
            $_SESSION['Id'] = $row['Id'];          |         $result = $stmt->get_result();
                                                   |         
            if ($row['role'] == 1) {               |         if ($result->num_rows === 1) {
                echo '<script type="text/javascript">';|         $user = $result->fetch_assoc();
                echo 'window.open("../General/     |             
                    home.php", "_self");';         |             // Verify the password
                echo '</script>';                  |             if (password_verify($password, $user['password'])) {
            } else if ($row['role'] == 2) {        |                 // Store user data in session
                echo '<script type="text/javascript">';|             $_SESSION['user_id'] = $user['Id'];
                echo 'window.open("../home_pages/  |                 $_SESSION['username'] = $user['username'];
                    hpfinance.php", "_self");';    |                 $_SESSION['email'] = $user['email'];
                echo '</script>';                  |                 $_SESSION['role'] = $user['role'];
            } else if ($row['role'] == 3) {        |                 $_SESSION['logged_in'] = true;
                echo '<script type="text/javascript">';|             
                echo 'window.open("../home_pages/  |                 // Regenerate session ID for security
                    hpstudent.php", "_self");';    |                 session_regenerate_id(true);
                echo '</script>';                  |                 
            }                                      |                 // Log successful login
                                                   |                 error_log("User login: $username (ID: {$user['Id']})");
                                                   |                 
                                                   |                 // Redirect based on role
                                                   |                 switch ($user['role']) {
                                                   |                     case 1:
                                                   |                         header("Location: ../General/home.php");
                                                   |                         break;
                                                   |                     case 2:
                                                   |                         header("Location: ../home_pages/hpfinance.php");
                                                   |                         break;
                                                   |                     case 3:
                                                   |                         header("Location: ../home_pages/hpstudent.php");
                                                   |                         break;
                                                   |                     default:
                                                   |                         header("Location: ../General/home.php");
                                                   |                 }
                                                   |                 exit();
                                                   |             } else {
                                                   |                 // Wrong password
                                                   |                 $errors[] = "Invalid username or password";
                                                   |                 // Add delay to prevent brute force
                                                   |                 sleep(1);
                                                   |             }
        } else {                                   |         } else {
            echo "<p class='centertop' style='z-index: 3; |         // Username not found
                left: 25%'> Incorrect username /    |         $errors[] = "Invalid username or password";
                password</p>";                     |         // Add delay to prevent brute force
        }                                          |         sleep(1);
    }                                              |     }
}                                                  |     $stmt->close();
                                                   | }
                                                   | 
if ($_SERVER["REQUEST_METHOD"] == "POST" &&        | // Handle reply functionality
    isset($_POST['reply'])) {                      | if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $replyto = $_POST['replyto'];                  |     // Validate CSRF token
    echo '<script type="text/javascript">';        |     if (isset($_POST['csrf_token']) && validateCSRFToken($_POST['csrf_token'])) {
    echo 'window.open("../Service_forms/          |         // Sanitize the input
        Reply.php?replyto=' . $replyto .           |         $replyto = filter_input(INPUT_POST, 'replyto', 
        '", "_self");';                            |             FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    echo '</script>';                              |         
}                                                  |         if (!empty($replyto)) {
                                                   |             // Redirect to reply page
                                                   |             header("Location: ../Service_forms/Reply.php?replyto=" . 
                                                   |                 urlencode($replyto));
                                                   |             exit();
                                                   |         }
                                                   |     }
                                                   | }
                                                   | 
if ($_SERVER["REQUEST_METHOD"] == "POST" &&        | // Student signup handling
    isset($_POST['Signup_student'])) {             | if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Signup_student'])) {
    // Retrieve the user ID                        |     // Validate CSRF token
    $query = "SELECT Id FROM user WHERE username   |     if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        = '$username' AND email = '$email'";       |         die("Invalid form submission");
    $result = mysqli_query($db, $query);           |     }
                                                   |     
    if ($result && mysqli_num_rows($result) == 1) {|     // Check if user is logged in
        $row = mysqli_fetch_assoc($result);        |     if (!isset($_SESSION['user_id'])) {
        $userId = $row['Id'];                      |         die("User not logged in");
                                                   |     }
        // Insert the user ID into the student table|     
        $insertQuery = "INSERT INTO student (id)   |     $userId = $_SESSION['user_id'];
            VALUES ('$userId')";                   |     
        if (mysqli_query($db, $insertQuery)) {     |     // Use prepared statement
            echo "<p style='text-align: center;    |     $stmt = $db->prepare("INSERT INTO student (id) VALUES (?)");
                color: green;'>Student registration |     $stmt->bind_param("i", $userId);
                successful!</p>";                  |     
        } else {                                   |     if ($stmt->execute()) {
            echo "<p style='color: red;'>Error: "  |         $_SESSION['success_message'] = "Student registration successful!";
                . mysqli_error($db) . "</p>";      |         header("Location: ../General/profile.php");
        }                                          |         exit();
    } else {                                       |     } else {
        echo "<p style='color: red;'>Error: User   |         error_log("Student registration error: " . $stmt->error);
            not found.</p>";                       |         $_SESSION['error_message'] = "Registration failed. Please try again.";
    }                                              |         header("Location: ../General/signup2.php");
}                                                  |         exit();
                                                   |     }
                                                   |     $stmt->close();
                                                   | }
                                                   | 
if ($_SERVER["REQUEST_METHOD"] == "POST" &&        | // Faculty signup handling
    isset($_POST['Signup_faculty'])) {             | if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Signup_faculty'])) {
    // Retrieve the user ID                        |     // Validate CSRF token
    $query = "SELECT Id FROM user WHERE username   |     if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        = '$username' AND email = '$email'";       |         die("Invalid form submission");
    $result = mysqli_query($db, $query);           |     }
                                                   |     
    if ($result && mysqli_num_rows($result) == 1) {|     // Check if user is logged in
        $row = mysqli_fetch_assoc($result);        |     if (!isset($_SESSION['user_id'])) {
        $userId = $row['Id'];                      |         die("User not logged in");
                                                   |     }
        // Insert the user ID into the faculty table|     
        $insertQuery = "INSERT INTO staff (id)     |     $userId = $_SESSION['user_id'];
            VALUES ('$userId')";                   |     
        if (mysqli_query($db, $insertQuery)) {     |     // Use prepared statement
            echo "<p style='text-align: center;    |     $stmt = $db->prepare("INSERT INTO staff (id) VALUES (?)");
                color: green;'>Faculty registration |     $stmt->bind_param("i", $userId);
                successful!</p>";                  |     
        } else {                                   |     if ($stmt->execute()) {
            echo "<p style='color: red;'>Error: "  |         $_SESSION['success_message'] = "Faculty registration successful!";
                . mysqli_error($db) . "</p>";      |         header("Location: ../General/profile.php");
        }                                          |         exit();
    } else {                                       |     } else {
        echo "<p style='color: red;'>Error: User   |         error_log("Faculty registration error: " . $stmt->error);
            not found.</p>";                       |         $_SESSION['error_message'] = "Registration failed. Please try again.";
    }                                              |         header("Location: ../General/signup2.php");
}                                                  |         exit();
                                                   |     }
                                                   |     $stmt->close();
                                                   | }
                                                   | 
// Close the database connection                   | // Display any error messages
mysqli_close($db);                                 | if (!empty($errors)) {
?>                                                 |     echo '<div class="error-container">';
                                                   |     foreach ($errors as $error) {
                                                   |         echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
                                                   |     }
                                                   |     echo '</div>';
                                                   | }
                                                   | 
                                                   | // Close the database connection
                                                   | $db->close();
                                                   | ?>
