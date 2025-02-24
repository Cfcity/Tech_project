<?php
include('../General/test.php');
include('../Staff view/staff.php');
include('../Extras/trial.php');
include('../Extras/arrays.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ezily</title>
    <link rel="stylesheet" href="../css/styles.css">
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
                        <!-- Urgent section -->
                        <?php


                        if ($db) {
                            $result_main = mysqli_query($db, "SELECT event_name, event_desc  FROM events WHERE event_type='main'");
                            if ($result_main) {
                                while ($row = mysqli_fetch_assoc($result_main)) {
                                    echo "<tr >";
                                    echo "<td>" . $row["event_name"] . "</td>";
                                    echo "<td>" . $row["event_desc"] . "</td>";
                                    echo "<td>" . "<p id='demo'></p>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "Error: " . mysqli_error($db);
                            }
                        } else {
                            echo "Database connection failed.";
                        }
                        ?>
                        <!-- Upcoming section -->
                        <tr>
                            <td colspan="3" align="center">
                                <section id="Upcoming">
                                    <h4>Upcoming</h4>
                                </section>
                            </td>
                        </tr>
                        <tr>
                            <th width="25%">Event</th>
                            <th width="50%">Description</th>
                            <th>Countdown</th>
                        </tr>
                        <?php /*
                        echo '<tr>';
                        echo '<td>' . $main[$i][0] . '</td>';
                        echo '<td>' . $main[$i][1] . '</td>';
                        echo '<td>' . $main[$i][2] . '</td>';
                        echo '</tr>';*/
                        ?>
                    </table>
                </div>

                <div id="home" class="tabcontent">
                    <table border="1" width="100%" height="100%" style="text-align: center;">
                        <tr height="10%">
                            <td width="20%">Unknown</td>
                            <td width="60%">Welcome</td>
                            <td width="20%">Account</td>
                        </tr>
                        <tr>
                            <td width="20%"> Important aspect <br> Most used</td>
                            <td rowspan="2" colspan="2"> Important information</td>
                        </tr>
                        <tr>
                            <td width="20%"> Last accesed</td>
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
                                        <td><button class="servicebubbles" onclick="">Financial</button></td>
                                        <td><button class="servicebubbles" onclick="">Sonis / E-learning</button></td>
                                        <td><button class="servicebubbles" onclick="">Subject selction & Electives</button></td>
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
                            <td width="10%">inquiries</td>
                            <td> inquiry 1 // service 4 </td>
                            <td> inquiry 2 // service 5</td>
                            <td> inquiry 3 // more inquiries <br> service 6</td>
                        </tr>
                    </table>
                </div>



                <div id="events" class="tabcontent">


                </div>



                </div>
                </div>
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