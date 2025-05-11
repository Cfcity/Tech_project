<?php

include('../General/test.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <title>Document</title>
</head>
<body class="dashboard-container">
    <div class="centertop header">
        <a class="centerleft" href="../home_pages/hpstudent.php"><button style="height: 7vh; width: 10vw;" class="buttonr tablinks">Home</button></a>
        
    <table class="dashboard-table">
        <tr>
            <th colspan="8">Inquiry Information</th>
        </tr>
        <tr>
            <th>Inquiry ID</th>
            <th>Issue</th>
            <th>Description</th>
            <th>Created At</th>
        <tr>
            <?php
            $searchid = htmlspecialchars($_GET['Inq_ID']);
            $query = "SELECT * from inquiry where Inq_ID = $searchid"; 
            $result = mysqli_query($db, $query);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Inq_ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['issue']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "No results found.";
            }
            ?>
        </tr>
        <tr>
            <th colspan="8">Reply Information</th>

        </tr>
        <tr>
            <th>Reply ID</th>
            <th>Reply</th>
            <th>Staff Name</th>
            <th>Created At</th>
        </tr>
        <?php
        $query = "SELECT reply.Reply_ID, reply.reply, reply.StaffID, reply.created_at, staff.f_name, staff.l_name 
          FROM reply 
          JOIN staff ON reply.StaffID = staff.Staffid
          WHERE reply.Inq_ID = $searchid";
        $result = mysqli_query($db, $query);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Reply_ID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['reply']) . "</td>";
                echo "<td>" . htmlspecialchars($row['f_name']) . " " . htmlspecialchars($row['l_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No results found.</td></tr>";
        }
        ?>
        </tr>

    </table>
            
    



</body>
</html>