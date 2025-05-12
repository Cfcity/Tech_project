<?php
include('../General/test.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/main.css">
  <title>Events</title>
  <style>
    .events-container {
      max-width: 900px;
      margin: 4rem auto 0 auto;
      background: #f3f4f6;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(37, 99, 235, 0.07);
      padding: 2rem 2rem 1rem 2rem;
    }

    .events-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1.5rem;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
    }

    .events-table th,
    .events-table td {
      padding: 1rem;
      border-bottom: 1px solid #e5e7eb;
      text-align: center;
    }

    .events-table th {
      background-color: #2563eb;
      color: #f1f5f9;
      font-weight: 600;
    }

    .events-table tr:last-child td {
      border-bottom: none;
    }

    .events-table input[type="text"],
    .events-table input[type="datetime-local"],
    .events-table textarea,
    .events-table select {
      width: 95%;
      padding: 0.5rem;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 1rem;
      background: #f9fafb;
    }

    .events-table input[type="submit"] {
      background-color: #2563eb;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 10px 24px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.2s;
      margin-top: 0.5rem;
    }

    .events-table input[type="submit"]:hover {
      background-color: #1e40af;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 1.5rem;
      color: #2563eb;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.2s;
    }

    .back-link:hover {
      color: #1e40af;
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="events-container">
    <!-- Event Input Form -->
    <form action="" method="post" enctype="multipart/form-data">
      <table class="events-table">
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
            <td colspan="5">
              <!-- Hidden staff_id field (using the session staff ID) -->
              <input type="hidden" name="staff_id" value="<?php echo isset($_SESSION['Staffid']) && !empty($_SESSION['Staffid']) ? $_SESSION['Staffid'] : '0'; ?>">
              <input type="submit" name="submit_event" value="Add Event">
            </td>
          </tr>
          <tr>
            <td colspan="5" class="center-text">
              <a href="../home_pages/home_admin.php?role=<?php echo $role ?>" class="primary-button">Home</a>
            </td>
          </tr>

        </tfoot>
      </table>
    </form>
    <?php
    // Display status message if there is one
    if (isset($status_message)) {
      echo "<div style='margin-top: 30px; text-align: center;'>{$status_message}</div>";
    }
    ?>
  </div>
</body>

</html>