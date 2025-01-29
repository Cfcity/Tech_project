<?php session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Events</title>
  <link rel="stylesheet" href="../Service_forms/forms_styles.css">
</head>

<body  class="query_r" >
  <!-- Event input form -->
  <form action="events.php" method="post">
    <table>
      <tr>
        <td><label for="event_name">Event Name:</label></td>
        <td><input type="text" id="event_name" name="event_name" required><br></td>
      </tr>
      <tr>
        <td><label for="event_desc">Event Description:</label></td>
        <td><textarea id="event_desc" name="event_desc" required></textarea><br></td>
      </tr>
      <tr>
        <td><label for="event_time">Event Time:</label></td>
        <td><input type="datetime-local" id="event_time" name="event_time" required><br></td>
      </tr>
      <tr>
        <td><label for="event_type">Event Type:</label></td>
        <td>
          <select id="event_type" name="event_type" required>
            <option value="main">Main</option>
            <option value="upcoming">Upcoming</option>
          </select><br>
        </td>
      </tr>
      <tr>
        <td colspan="2"><button type="submit" name="submit_event">Add Event</button></td>
      </tr>
    </table>
  </form>

</body>

</html>