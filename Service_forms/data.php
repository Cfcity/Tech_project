<?php
// Initialize variables to avoid "undefined" errors
$issue = "";
$department = "";
$description = "";
$img = "";
$errors = 0; 

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'test');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_query'])) {
    // Sanitize and retrieve form data
    $issue = mysqli_real_escape_string($db, $_POST['issue']);
    $department = mysqli_real_escape_string($db, $_POST['department']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $inq_type = mysqli_real_escape_string($db, $_POST['inq_type']);

    // Input validation (example)
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

    if ($errors == 0){ 
        $query = "INSERT INTO inquiry (issue, department, description, inq_type) VALUES ('$issue', '$department', '$description', '$inq_type')";
        if (mysqli_query($db, $query)) {
            echo "Query submitted successfully.";
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}

mysqli_close($db);
?>