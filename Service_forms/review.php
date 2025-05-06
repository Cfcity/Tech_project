<?php

include('../General/test.php');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
$db

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">'
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/form_styles.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Document</title>
</head>

<body>

    <table border="1" style="width: 100%; position:absolute; top: 1%; height: 98vh; border-collapse: collapse; text-align: center;">
        <th colspan="7">
            Query information
        </th>
        </tr>
        <?php
        if (isset($_GET['Inq_ID'])) {
            $service_id = htmlspecialchars($_GET['Inq_ID']);
        } elseif (isset($_POST['Inq_ID'])) {
            $service_id = htmlspecialchars($_POST['Inq_ID']);
        } else {
            die("No Inquiry ID provided.");
        }

        $query = "
        SELECT reply.Reply_ID, reply.reply, reply.StaffID, reply.created_at, 
               staff.Staffid, staff.f_name, staff.l_name, staff.department,   
               inquiry.Inq_ID, inquiry.issue, inquiry.inq_type, inquiry.status, inquiry.description, inquiry.studentid, 
               students.Stu_fname, students.Stu_lname 
        FROM inquiry
        LEFT JOIN reply ON reply.Inq_ID = inquiry.Inq_ID
        LEFT JOIN staff ON reply.StaffID = staff.Staffid
        JOIN students ON inquiry.studentid = students.studentid
        WHERE inquiry.Inq_ID = $service_id
         ";

        ?>
        <tr>
            <th colspan="7">Inquiry Information</th>
        </tr>
        <tr>
            <th>Inquiry ID</th>
            <th>Issue</th>
            <th>Type</th>
            <th>Status</th>
            <th colspan="2">Description</th>
            <th>Student Name</th>
        </tr>
        <tr>
            <?php
            $result = mysqli_query($db, $query); // Execute the query
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Inq_ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['issue']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['inq_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td colspan='2'>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Stu_fname']) . " " . htmlspecialchars($row['Stu_lname']) . "</td>";
                    echo "</tr>";

            ?>
        <tr>
            <th colspan="7">Reply Information</th>
        </tr>
        <tr>
            <th>Reply ID</th>
            <th colspan="2">Reply</th>
            <th>Date</th>
            <th colspan="2">Staff Name</th>
            <th>Staff Department</th>
    <?php
                    // Display reply information if available
                    if (!empty($row['Reply_ID'])) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Reply_ID']) . "</td>";
                        echo "<td colspan='2'>" . htmlspecialchars($row['reply']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['f_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['l_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='5'>No reply available for this inquiry.</td></tr>";
                    }
                }
            } else {
                echo "<tr><td colspan='7'>Error fetching inquiry details.</td></tr>";
            }
    ?>
    </table>

</body>

</html>