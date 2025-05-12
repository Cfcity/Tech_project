<?php
include('../General/test.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="page-container">
    <div class="main-content">
        <div class="content-card">
            <h1>Edit Event</h1>
            <form method="POST" action="">
                <table class="data-table">
                    <tr>
                        <th colspan="7">Edit Event</th>
                    </tr>
                    <tr>
                        <th>Event ID</th>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Event Location</th>
                        <th>Event Description</th>
                        <th>Event Image</th>
                        <th>Event Type</th>
                    </tr>
                    <?php
                    if (isset($_GET['event_id'])) {
                        $event_id = mysqli_real_escape_string($db, $_GET['event_id']);
                        $query = "SELECT * FROM events WHERE event_id = '$event_id'";
                        $result = mysqli_query($db, $query);
                        if ($result && $row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['event_id']) . "<input type='hidden' name='event_id' value='" . htmlspecialchars($row['event_id']) . "'></td>";
                            echo "<td><input type='text' name='event_name' class='input' value='" . htmlspecialchars($row['event_name']) . "'></td>";
                            echo "<td><input type='datetime-local' name='event_time' class='input' value='" . date('Y-m-d\TH:i', strtotime($row['event_time'])) . "'></td>";
                            echo "<td><input type='text' name='event_location' class='input' value='" . htmlspecialchars($row['event_location']) . "'></td>";
                            echo "<td><textarea name='event_description' class='input' rows='4'>" . htmlspecialchars($row['event_desc']) . "</textarea></td>";
                            echo "<td><input type='text' name='event_image' class='input' value='" . htmlspecialchars($row['news_image']) . "'></td>";
                            echo "<td>
                                    <select name='event_type' class='input'>
                                        <option value=''>Select Event Type</option>
                                        <option value='main'" . ($row['event_type'] == 'main' ? ' selected' : '') . ">Main</option>
                                        <option value='upcoming'" . ($row['event_type'] == 'upcoming' ? ' selected' : '') . ">Upcoming</option>
                                    </select>
                                </td>";
                            echo "</tr>";
                        } else {
                            echo "<tr><td colspan='7'>Error fetching event details.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No event selected.</td></tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="7">
                            <button type="submit" name="update_event" class="primary-button">Update Event</button>
                        </td>
                    </tr>
            </form>
        </div>
    </div>
</body>

</html>