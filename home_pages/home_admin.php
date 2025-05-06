<?php
include('../General/test.php');
// Establish database connection
$db = mysqli_connect('localhost', 'root', '', 'tech_db');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Admin</title>
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
                    <table border="1" style="text-align: center; height: 100%; width: 100%; ">
                        <tr height="10%">
                            <td width="43%" colspan="5">Home</td>
                            <td width="20%" colspan="2">Account</td>
                        </tr>
                        <tr>
                            <th colspan="7">Queries</th>
                        </tr>
                        <tr>
                            <th width="5%">Inquiry ID</th>
                            <th width="10%">Department addressed</th>
                            <th width="10%">Time</th>
                            <th width="7%">Issue</th>
                            <th>Description</th>
                            <th>Student name</th>
                            <th width="10%">Review</th>
                        </tr>
                        <?php
                        if ($db) {
                            $query = "SELECT inquiry.Inq_ID, inquiry.department, inquiry.created_at, inquiry.issue, inquiry.description, students.Stu_fname, students.Stu_lname 
                                      FROM inquiry 
                                      JOIN students ON inquiry.studentid = students.studentid";

                            $result = mysqli_query($db, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row["Inq_ID"] . "</td>";
                                    echo "<td>" . $row["department"] . "</td>";
                                    echo "<td>" . $row["created_at"] . "</td>";
                                    echo "<td>" . $row["issue"] . "</td>";
                                    echo "<td>" . $row["description"] . "</td>";
                                    echo "<td>" . $row["Stu_fname"] . " " . $row["Stu_lname"] . "</td>";
                                    echo "<td><form action='' method='post'>
                                                    <button name='Review' type='submit'>Review</button> 
                                                    <input type='hidden' name='Inq_ID' value='" . $row["Inq_ID"] . "'>
                                                    </form></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No inquiries found.</td></tr>";
                            }
                        } else {
                            echo "Database connection failed.";
                        }
                        ?>
                    </table>
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

                        </div>
                    </div>
                </div>

                <div id="services" class="tabcontent">
                    <table border="1" width="100%" height="100%" style="text-align: center;">
                        <tr height="10%">
                            <td width="43%" colspan="3">Home</td>
                            <td width="20%" colspan="2">Account</td>
                        </tr>
                        <tr>
                            <th>
                                Admin options
                            </th>
                            <td>
                                <form action="" method="post">
                                    <input type="submit" value="Add Service" name="add_service">
                                </form>
                            </td>
                            <td>
                                <form action="" method="post">
                                    <input type="submit" value="Edit Services" name="edit_service">
                                </form>
                            </td>
                            <td>
                                <form action="" method="post">
                                    <input type="submit" value="Delete Service" name="delete_service">
                                </form>
                            </td>
                        </tr>
                        <tr height="45%">
                            <td width="10%" rowspan="2">Services</td>
                            <?php
                            $Query = "SELECT * FROM service";
                            $result = mysqli_query($db, $Query);

                            if ($result && mysqli_num_rows($result) > 0) {
                                $service_count = 0; // Initialize a counter
                                while ($row = mysqli_fetch_assoc($result)) {
                                    if ($service_count >= 6) {
                                        break; // Stop the loop after 6 services (2 rows × 3 services)
                                    }

                                    // Add a new row after every 3 services
                                    if ($service_count > 0 && $service_count % 3 == 0) {
                                        echo "</tr><tr>";
                                    }

                                    // Display the service
                                    echo "<td><a href='../Service_forms/" . htmlspecialchars($row['Ser_name']) . ".php'>" . htmlspecialchars($row['Ser_name']) . "</a></td>";
                                    $service_count++; // Increment the counter
                                }

                                // Fill the remaining slots with "No services available" if fewer than 6 services
                                while ($service_count < 6) {
                                    if ($service_count > 0 && $service_count % 3 == 0) {
                                        echo "</tr><tr>"; // Add a new row after every 3 slots
                                    }
                                    echo "<td>No services available</td>";
                                    $service_count++;
                                }
                                echo "</tr>"; // Close the last row
                            } else {
                                // If no services are found, display "No services available" in 2 rows
                                echo "<tr><td>No services available</td><td>No services available</td><td>No services available</td></tr>";
                                echo "<tr><td>No services available</td><td>No services available</td><td>No services available</td></tr>";
                            }
                            ?>
                        </tr>
                    </table>
                </div>


                <div id="events" class="tabcontent">
                    <table border="1" style="width: 100%; border-collapse: collapse; background-color: white; color: black; text-align:center;">
                        <tr height="10%">
                            <td width="43%" colspan="5">Home</td>
                            <td width="20%" colspan="2">Account</td>
                        </tr>
                        <tr>
                            <th colspan="7">Events</th>
                        </tr>
                        <tr>
                            <th colspan="7">
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