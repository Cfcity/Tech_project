<?php
include('../General/test.php');
include('../Staff view/staff.php');

// Establish database connection
$db = mysqli_connect('localhost', 'root', '', 'test');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ezily</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body style="background-color: rgb(63,63,63); height: 100vh;">
    <table>
        <tr>
            <td>
                <div class="sideheader">
                    <div class="center">
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'home')" id="defaultOpen">Home</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'Overview')">Overview</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'Query')">Querys</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'services')">Services</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'events')">Events</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'about')">About</button>
                    </div>
                </div>
            </td>

            <!-- Content / Tabs ------------------------------------------------------------------------------------------------ -->
            <td>
                <div id="Query" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                        </div>
                    </div>
                </div>

                <div id="home" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <!-- Content removed -->
                        </div>
                    </div>
                </div>

                <div id="Overview" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <!-- Content removed -->
                        </div>
                    </div>
                </div>

                <div id="services" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <!-- Content removed -->
                        </div>
                    </div>
                </div>

                <div id="events" class="tabcontent">
                    <table>
                        <tr>
                            <th colspan="5">Events</th>
                        </tr>
                        <tr>
                            <th colspan="5">
                                <form method='POST' action=''>
                                    <input type='submit' name='add_event' value='Add event'>
                            </th>
                          </tr>
                        <?php

                        $Query = "SELECT * FROM events";
                        $result = mysqli_query($db, $Query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            echo "<tr>";
                            echo "<th>Event ID</th>";
                            echo "<th>Event Name</th>";
                            echo "<th>Description</th>";
                            echo "<th>Date and Time </th>";
                            echo "<th>Type</th>";
                            echo "<th colspan='2'>Action</th>";
                            echo "</tr>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['event_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['event_desc']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['event_time']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['event_type']) . "</td>";
                                echo "<td> 
                                        <form method='POST' action=''>
                                            <input type='hidden' name='event_id' value='" . htmlspecialchars($row['event_id']) . "'>
                                            <input type='submit' name='Delete_event' value='Delete_event'>
                                        </form>
                                    </td>
                                    <td>
                                        <form method='POST' action=''>
                                            <input type='hidden' name='event_id' value='" . htmlspecialchars($row['event_id']) . "'>
                                            <input type='submit' name='Edit_event' value='Edit event'>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<tr><td colspan='6'>No events found.</td></tr>";
                        }
                        ?>


                </div>


                <div id="about" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <!-- Content removed -->
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

<script src="../source.js"></script>

</html>