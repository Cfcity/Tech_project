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

    <table border="1" style="width: 100%; border-collapse: collapse; background-color: white; color: black; text-align:center;">
        <tr>
            <th colspan="6">Edit Event</th>
        </tr>

        <tr>

            <?php
            $event_id = $_GET['event_id'];
            // Updated query to include a join with the staff table
            $query = "
                          SELECT events.event_id, events.event_name, events.event_time, 
                                 staff.f_name, staff.l_name, staff.department AS staff_department
                          FROM events
                          LEFT JOIN staff ON events.staffid = staff.Staffid
                          WHERE events.event_id = '$event_id'";
            $result = mysqli_query($db, $query); // Use $db for the database connection
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                echo "<tr><th colspan='3'> Staff Information </th></tr>";
                echo " <th colspan='2'>Staff Name</th>
                        <th>Staff Department</th>
                        </tr>";
                echo "<tr><td>" . htmlspecialchars($row['f_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['l_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['staff_department']) . "</td></tr>";
                echo "<tr><th colspan='3'> Event Information </th></tr>";
                echo "<tr><th>Event ID</th>
                        <th>Event Name</th>
                        <th>Event Date</th></tr> ";
                echo "<tr><td><input type='number' name='event_id' value='" . htmlspecialchars($row['event_id']) . "'</td>";
                echo "<td><input type='text' name='event_name' value='" . htmlspecialchars($row['event_name']) . "'</td>";
                echo "<td><input type='datetime-local' name='event_time' value='" . htmlspecialchars($row['event_time']) . "'></td></tr>";
            } else {
                echo "<td colspan='7'>Error fetching event details.</td>";
            }
            ?>
        </tr>
    </table>

</body>

</html>