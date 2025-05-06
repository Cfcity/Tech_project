<?php
include('../General/test.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/styles.css">
  <title>Events</title>
</head>

<body style="text-align: center;">

  <!-- Event Input Form -->
  <form action="" method="post" enctype="multipart/form-data">
    <table border="1" style="position: absolute; top: 10vh; width: 99%; text-align: center;">
      <thead>
        <tr>
          <th>Event Name</th>
          <th>Event Description</th>
          <th>Event Date</th>
          <th>Event Location</th>
          <th>Event Type</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <!-- Event Name -->
          <td>
            <input type="text" id="event_name" name="event_name" required>
          </td>

          <!-- Event Description -->
          <td>
            <textarea id="event_desc" name="event_desc" required></textarea>
          </td>

          <!-- Event Date/Time -->
          <td>
            <input type="datetime-local" id="event_time" name="event_time" required>
          </td>

          <!-- Event Location -->
          <td>
            <input type="text" id="event_location" name="event_location" required>
          </td>

          <!-- Event Type -->
          <td>
            <select id="event_type" name="event_type" required>
              <option value="main">Main</option>
              <option value="upcoming">Upcoming</option>
            </select>
          </td>

        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="6">
            <!-- Hidden staff_id field (using the session staff ID) -->
            <input type="hidden" name="staff_id" value="<?php echo isset($_SESSION['Staffid']) && !empty($_SESSION['Staffid']) ? $_SESSION['Staffid'] : '0'; ?>">
            <input type="submit" name="submit_event" value="Add Event">
          </td>
        </tr>
      </tfoot>
    </table>
  </form>

  <?php
  // Display status message if there is one
  if (isset($status_message)) {
    echo "<div style='margin-top: 300px; text-align: center;'>{$status_message}</div>";
  }
  ?>

</body>

</html>