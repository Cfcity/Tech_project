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
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'services')">Inquiries</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="events()">Events</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'about')">About</button>
                    </div>
                </div>
            </td>

            <!-- Content / Tabs ------------------------------------------------------------------------------------------------ -->
            <td>
                <div id="create" class="tabcontent">

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
                    <table border="1" style="text-align: center;">
                        <tr>
                            <th>Time</th>
                            <th>Issue</th>
                            <th>Description</th>
                        </tr>
                        <?php
                        $db = mysqli_connect('localhost', 'root', '', 'test');

                        if (!$db) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        // Check if the database connection is established
                        if ($db) {
                            $result = mysqli_query($db, "SELECT date, issue, description FROM inquiry WHERE inq_type='Finance'");
                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row["date"] . "</td>";
                                    //echo "<td>" . $row["name"] . "</td>";
                                    echo "<td>" . $row["issue"] . "</td>";
                                    echo "<td>" . $row["description"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "Error: " . mysqli_error($db);
                            }
                        } else {
                            echo "Database connection failed.";
                        }
                        ?>
                    </table>
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