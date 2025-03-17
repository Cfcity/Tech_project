<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/styles.css">
  <title>Events</title>
</head>

<body style="text-align: center;">
  <!-- Event input form -->
  <form action="staff.php" method="post">
    <table border="1" style="position: absolute; top: 10vh; width: 99%;" text-align="center">
      <tr>
        <th>Event Name</th>
        <th>Event Description</th>
        <th>Event Time</th>
        <th>Event image</th>
        <th>Event type</th>
        <td></td>
      </tr>
      <tr>
        <td><input type="text" id="event_name" name="event_name" required><br></td>
        <td><textarea id="event_desc" name="event_desc" required></textarea><br></td>
        <td><input type="datetime-local" id="event_time" name="event_time" required><br></td>
        <td><input accept="image/*" type="file" name="news_image" id="news_image" required></td>
        <td><select id="event_type" name="event_type" required>
            <option value="main">Main</option>
            <option value="upcoming">Upcoming</option>
          </select><br></td>
          <td><input type="number" name="priority" id="priority" placeholder="priority"></td>
      </tr>
      <tr>
        <td colspan="6">
          <hr size="3" color="black">
        </td>
      </tr>
      <tr>
        <td colspan="6"> <button type="submit" name="submit_event">Add Event</button></td>
      </tr>
    </table>
  </form>
</body>

</html>