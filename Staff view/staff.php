<?php 
$db = mysqli_connect('localhost', 'root', '', 'test');

$event_name = "";
$event_desc = "";
$event_time = "";
$event_type = "";
$priority = 0;
$errors = 0;

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_event'])) {
  $event_name = mysqli_real_escape_string($db, $_POST['event_name']);
  $event_desc = mysqli_real_escape_string($db, $_POST['event_desc']);
  $event_time = mysqli_real_escape_string($db, $_POST['event_time']);
  $event_type = mysqli_real_escape_string($db, $_POST['event_type']);
  $news_image = "../images/" . mysqli_real_escape_string($db, $_POST['news_image']);
  $priority = mysqli_real_escape_string($db, $_POST['priority']);

  // Check if the priority already exists
  $stmt_check_priority = $db->prepare("SELECT * FROM events WHERE priority = ?");
  $stmt_check_priority->bind_param("i", $priority);
  $stmt_check_priority->execute();
  $result_check_priority = $stmt_check_priority->get_result();

  if ($result_check_priority->num_rows > 0) {
    echo "<script>
      if (confirm('Priority $priority already exists. Do you want to change it?')) {
        window.location.href = 'staff.php?change_priority=$priority&event_name=$event_name&event_desc=$event_desc&event_time=$event_time&event_type=$event_type';
      } else {
        window.location.href = 'staff.php';
      }
    </script>";
  } else {
    // Insert the event with the given priority
    $stmt_insert_event = $db->prepare("INSERT INTO events (event_name, event_desc, event_time, event_type, news_image, priority) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_insert_event->bind_param("sssssi", $event_name, $event_desc, $event_time, $event_type, $news_image, $priority);
    if ($stmt_insert_event->execute()) {
      echo "Event added successfully.";
      echo "<script>window.open('../Staff view/events_staff.php', '_self');</script>";
    } else {
      echo "Error: " . $stmt_insert_event->error;
    }
  }
}

// Handle priority change
if (isset($_GET['change_priority'])) {
  $priority = $_GET['change_priority'];
  $event_name = $_GET['event_name'];
  $event_desc = $_GET['event_desc'];
  $event_time = $_GET['event_time'];
  $event_type = $_GET['event_type'];

  // Retrieve all events and store them in an array with priority as the index
  $query_all_events = "SELECT * FROM events ORDER BY priority ASC";
  $result_all_events = mysqli_query($db, $query_all_events);
  $events = [];

  while ($row = mysqli_fetch_assoc($result_all_events)) {
    $events[$row['priority']] = $row;
  }

  // Update the priorities of the existing events
  foreach ($events as $key => $event) {
    if ($key >= $priority) {
      $new_priority = $key + 1;
      $stmt_update_priority = $db->prepare("UPDATE events SET priority = ? WHERE id = ?");
      $stmt_update_priority->bind_param("ii", $new_priority, $event['id']);
      $stmt_update_priority->execute();
    }
  }

  // Insert the new event with the given priority
  $stmt_insert_event = $db->prepare("INSERT INTO events (event_name, event_desc, event_time, event_type, priority) VALUES (?, ?, ?, ?, ?)");
  $stmt_insert_event->bind_param("ssssi", $event_name, $event_desc, $event_time, $event_type, $priority);
  if ($stmt_insert_event->execute()) {
    echo "Event added successfully with updated priorities.";
    echo "<script>window.open('../Staff view/events_staff.php', '_self');</script>";
  } else {
    echo "Error: " . $stmt_insert_event->error;
  }
}

// Image from events table
$query = "SELECT image FROM events WHERE event_type='main' ORDER BY priority ASC LIMIT 1";
/* Query to select the event with the lowest priority
$stmt_lowest_priority = $db->prepare("SELECT * FROM events WHERE event_type='main' ORDER BY priority ASC LIMIT 1");
$stmt_lowest_priority->execute();
$result_lowest_priority = $stmt_lowest_priority->get_result();

if ($result_lowest_priority->num_rows == 1) {
    $lowest_priority_event = $result_lowest_priority->fetch_assoc();
    echo "<h2>Main Event</h2>";
    echo "<p>Event Name: " . $lowest_priority_event['event_name'] . "</p>";
    echo "<p>Event Description: " . $lowest_priority_event['event_desc'] . "</p>";
    echo "<p>Event Time: " . $lowest_priority_event['event_time'] . "</p>";
}

// Query to select the rest of the events with higher priority
$query_upcoming_events = "(SELECT * FROM events WHERE event_type='main' AND priority > (SELECT priority FROM events WHERE event_type='main' ORDER BY priority ASC LIMIT 1)) OR (SELECT * FROM events WHERE event_type='upcoming') ORDER BY priority ASC";
$result_upcoming_events = mysqli_query($db, $query_upcoming_events);

if ($result_upcoming_events && mysqli_num_rows($result_upcoming_events) > 0) {
    echo "<h2>Upcoming Events</h2>";
    $r = 0;
    while ($row = mysqli_fetch_assoc($result_upcoming_events) && $r < 4) {
        $r++;
        echo "<p>Event Name: " . $row['event_name'] . "</p>";
        echo "<p>Event Description: " . $row['event_desc'] . "</p>";
        echo "<p>Event Time: " . $row['event_time'] . "</p>";
    }
}*/

//mysqli_close($db);
?>