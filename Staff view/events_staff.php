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
    <table border="1" style="position: absolute; top: 10vh; width: 99%; text-align: center;">
      <thead>
        <tr>
          <th>Event Name</th>
          <th>Event Description</th>
          <th>Event Time</th>
          <th>Event Image</th>
          <th>Event Type</th>
          <th>Priority</th>
        </tr>
      </thead>
      <tbody>
      <form action="" method="post" enctype="multipart/form-data">

        <tr>
                    <!-- Event Name -->
          <td>
            <input type="text" id="event_name" name="event_name" required>
          </td>

          <!-- Event Description -->
          <td>
            <textarea id="event_desc" name="event_desc" required></textarea>
          </td>

          <!-- Event Time -->
          <td>
            <input type="datetime-local" id="event_time" name="event_time" required>
          </td>

          <!-- Event Image -->
          <td>
            <input accept="image/*" type="file" name="news_image" id="news_image" required>
          </td>

          <!-- Event Type -->
          <td>
            <select id="event_type" name="event_type" required>
              <option value="main">Main</option>
              <option value="upcoming">Upcoming</option>
            </select>
          </td>

          <!-- Priority -->
          <td>
            <input type="number" name="priority" id="priority" placeholder="Priority">
          </td>
        </tr>
      </tbody>
        <tr>
          <td colspan="6"> 
            <input type="submit" name="submit_event" value="Add Event"></input>
          </td>
        </tr>
    </table>
  </form>

</body>

</html>