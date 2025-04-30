<?php

 include('../General/test.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <table>
        <tr><th colspan="2">Edit Event</th></tr>
        <tr>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Event Location</th>
            <th colspan="2">Staff Name</th>
            <th>Staff Department</th>
        </tr>
        <tr>

            <?php
                $event_id = $_GET['event_id'];
                // Updated query to include a join with the staff table
                $query = "
                          SELECT events.event_id, events.event_name, events.event_date, events.event_location, 
                                 staff.f_name, staff.l_name, staff.department AS staff_department
                          FROM events
                          LEFT JOIN staff ON events.staff_id = staff.Id
                          WHERE events.event_id = '$event_id'";
                $result = mysqli_query($db, $query); // Use $db for the database connection
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    echo "<td>" . htmlspecialchars($row['event_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['event_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['event_location']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['f_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['l_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['staff_department']) . "</td>";
                } else {
                    echo "<td colspan='7'>Error fetching event details.</td>";
                }
            ?>
        </tr>
    </table>

</body>
</html>