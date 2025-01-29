<?php 
$db = mysqli_connect('localhost', 'root', '', 'test');

$event_name = "";
$event_desc = "";
$event_time = "";
$event_type = "";
$errors = 0;

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'test');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_event'])) {
  $event_name = mysqli_real_escape_string($db, $_POST['event_name']);
  $event_desc = mysqli_real_escape_string($db, $_POST['event_desc']);
  $event_time = mysqli_real_escape_string($db, $_POST['event_time']);
  $event_type = mysqli_real_escape_string($db, $_POST['event_type']);

  if ($errors == 0) { 
    $query = "INSERT INTO events (event_name, event_desc, event_time, event_type) VALUES ('$event_name', '$event_desc', '$event_time', '$event_type')";
    if (mysqli_query($db, $query)) {
      echo "Event added successfully.";
    } else {
      echo "Error: " . mysqli_error($db);
    }
  }
}
mysqli_close($db);

?> 