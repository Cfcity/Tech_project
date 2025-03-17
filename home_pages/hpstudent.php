<?php
include('../General/test.php');
include('../Staff view/staff.php');
include('../Extras/trial.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ezily</title>
    <link rel="stylesheet" href="../css/styles.css">
    <?php 
        $_SESSION['lastaccessed'] = "Home"; 
        $_SESSION['lastaccurl'] = "../home_pages/hpstudent.php";
    ?>
</head>

<body style="background-color: rgb(63,63,63); background-size:cover;">
    <table>
        <tr>
            <td>
                <div class="sideheader">
                    <div class="center">
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'home')" id="defaultOpen">Home</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'create')">Create</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'library')">Querys</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'services')">Services</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'events')">Events</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'about')">About</button>
                    </div>
                </div>
            </td>

            <!-- Content / Tabs ------------------------------------------------------------------------------------------------ -->
            <td>
                <div id="create" class="tabcontent">
                    <table border="1" style="height:100%; width: 100%;" align="center">
                        <tr>
                            <th>Event</th>
                            <th>Description</th>
                            <th>Countdown</th>
                        </tr>
                        <tr>
                            <th colspan="3">Main</th>
                        </tr>
                        <!-- Urgent section -->
                        <?php
                        if ($db) {
                            // Query to select the event with the lowest priority
                            $query_lowest_priority = "SELECT * FROM events WHERE event_type='main' ORDER BY priority ASC LIMIT 1";
                            $result_lowest_priority = mysqli_query($db, $query_lowest_priority);

                            if ($result_lowest_priority && mysqli_num_rows($result_lowest_priority) > 0) {
                                $lowest_priority_event = mysqli_fetch_assoc($result_lowest_priority);
                                if ($lowest_priority_event) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($lowest_priority_event['event_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($lowest_priority_event['event_desc']) . "</td>";
                                    echo "<td>" . htmlspecialchars($lowest_priority_event['event_time']) . "</td>";
                                    echo "</tr>";
                                } else {
                                    echo "<tr><td colspan='3'>Error retrieving main event details.</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No main event found.</td></tr>";
                            }

                            // Query to select upcoming events
                            $query_upcoming_events = "SELECT * FROM events WHERE 
                            (event_type='main' AND priority > (SELECT priority FROM events WHERE event_type='main' ORDER BY priority ASC LIMIT 1))
                            OR event_type='upcoming' 
                            ORDER BY priority ASC";                            $result_upcoming_events = mysqli_query($db, $query_upcoming_events);

                            if ($result_upcoming_events && mysqli_num_rows($result_upcoming_events) > 0) {
                                echo "<tr><th colspan='3'>Upcoming Events</th></tr>";
                                $r = 0;
                                while (($row = mysqli_fetch_assoc($result_upcoming_events)) && $r < 4) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['event_desc']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['event_time']) . "</td>";
                                    echo "</tr>";
                                    $r++;
                                }
                            } else {
                                echo "<tr><td colspan='3'>No upcoming events found.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>Database connection failed.</td></tr>";
                        }
                        ?>
                    </table>
                </div>

                <div id="home" class="tabcontent">
                    <table border="1" width="100%" height="100%" style="text-align: center;">
                        <tr height="10%">
                            <td width="20%">Unknown</td>
                            <td width="60%" colspan="2">Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></td>
                            <td width="20%">Account</td>
                        </tr>
                        <tr>
                            <td width="20%"> Important aspect <br> Most used</td>
                            <td colspan="3" style="background-image: url(<?php echo "../images/" . $image; ?>);" > 

                                Important information
                            </td>
                        </tr>
                        <tr>
                            <td width="20%"> Last accessed<?php  htmlspecialchars($_SESSION['lastaccessed']) ?></td>
                            <td width="30%"></td>
                            <td width="30%"></td>
                            <td></td>
                        </tr>
                    </table>
                </div>

                <div id="library" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <table style="width: 100%;">
                                <tr>
                                    <th colspan="3" style="height: 10%;">
                                        <h2>Select Query type</h2>
                                    </th>
                                </tr>
                                <form action="">
                                    <tr style="height: 90%;">
                                        <td><button class="servicebubbles" onclick="financial()">Financial</button></td>
                                        <td><button class="servicebubbles" onclick="">Sonis / E-learning</button></td>
                                        <td><button class="servicebubbles" onclick="">Subject selection & Electives</button></td>
                                    </tr>
                                </form>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="services" class="tabcontent">
                    <table border="1" width="100%" height="100%" style="text-align: center;">
                        <tr height="10%">
                            <td width="43%" colspan="2">Home</td>
                            <td></td>
                            <td width="20%">Account</td>
                        </tr>
                        <tr height="45%">
                            <td width="10%">Services</td>
                            <td width="33%"> Service 1 </td>
                            <td width=34%> service 2</td>
                            <td width="33%"> service 3 // more services </td>
                        </tr>
                        <tr>
                            <td width="10%">Inquiries</td>
                            <td> inquiry 1 // service 4 </td>
                            <td> inquiry 2 // service 5</td>
                            <td> inquiry 3 // more inquiries <br> service 6</td>
                        </tr>
                    </table>
                </div>

                <div id="events" class="tabcontent">
                </div>
                

                <div id="about" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <h4></h4>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

<script src="../source.js"></script>

</html>