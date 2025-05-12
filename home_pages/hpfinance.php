<?php
include('../General/test.php');
include('../Staff view/staff.php');

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
    <title>Finance Dashboard</title>
    <link rel="stylesheet" href="../css/main.css">
    <?php
    $_SESSION['lastaccessed'] = "Home";
    $_SESSION['lastaccurl'] = "../home_pages/hpfinance.php";
    ?>
</head>

<body class="dashboard-container">
    <!-- Side Navigation -->
    <div class="side-nav">
        <button class="side-nav-button active" onclick="opentab(event, 'home')" id="defaultOpen">
            Dashboard
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'Meeting')">
            Meeting
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'inquiries')">
            Inquiries
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'events')">
            Events
        </button>
        <form action="" method="post" style="position: absolute; bottom: 30px; left: 3.5%">
            <input class="primary-button" type="submit" value="Log out" name="logout" style="width:100%;">
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Tab -->
        <div id="home" class="tabcontent" style="display:block;">
            <table class="dashboard-table">
                <tr height="10%">
                    <th width="20%" colspan="3">Home</th>
                    <th width="60%">Welcome <?php echo htmlspecialchars(getUsername()) ?></th>
                    <th width="20%" colspan="2">Account</th>
                </tr>
                <tr>
                    <th colspan="5">Finance Dashboard</th>
                </tr>
            </table>

            <!-- Add finance-specific content here -->
        </div>

        <!-- Queries Tab -->
        <div id="meeting" class="tabcontent">
            <h2 style="text-align: center;">Financial Queries</h2>
            <!-- Add meeting content here -->

        </div>

        <!-- Inquiries Tab -->
        <div id="inquiries" class="tabcontent">
            <h2 style="text-align: center;">Financial Inquiries</h2>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Time</th>
                        <th>Issue</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($db, "SELECT created_at, issue, Inq_ID, description, status 
                                               FROM inquiry WHERE inq_type='Finance'");
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>{$row['Inq_ID']}</td>
                                <td>{$row['created_at']}</td>
                                <td>{$row['issue']}</td>
                                <td>{$row['description']}</td>";

                            if ($row['status'] == 'Unread') {
                                echo "<td style='color: red;'>{$row['status']}</td>
                                    <td>
                                    <form action='' method='post'>
                                        <input type='hidden' name='replyto' value='{$row['Inq_ID']}'>
                                        <input type='submit' name='reply' value='Reply'>
                                    </form>
                                    </td>
                                    </tr>";
                            } else if ($row['status'] == 'Replied') {
                                echo "<td colspan='2' style='color: green;'>{$row['status']}</td>
                                    </tr>";
                            } else if ($row['status'] == 'Pending') {
                                echo "<td colspan='2' style='color: orange;'>{$row['status']}</td>
                                    </tr>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='5'>No inquiries found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Events Tab -->
        <div id="events" class="tabcontent">
            <h2 style="text-align: center;">Upcoming Events</h2>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Description</th>
                        <th>Date/Time</th>
                        <th>Countdown</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Main Events Section -->
                    <tr>
                        <th colspan="4" style="text-align:center;">Main Events</th>
                    </tr>
                    <?php
                    $result_main = mysqli_query($db, "SELECT event_name, event_time, event_desc
                                                      FROM events 
                                                      WHERE event_type='main'
                                                      ORDER BY event_time ASC");
                    if ($result_main && mysqli_num_rows($result_main) > 0) {
                        $index_main = 0;
                        while ($row = mysqli_fetch_assoc($result_main)) {
                            $event_name = htmlspecialchars($row['event_name']);
                            $event_desc = htmlspecialchars($row['event_desc']);
                            $event_time = htmlspecialchars($row['event_time']);
                            echo "<tr>
                                    <td>$event_name</td>
                                    <td>$event_desc</td>
                                    <td>$event_time</td>
                                    <td><span id='demo_main_$index_main'></span></td>
                                  </tr>";
                            echo "<script>
                                var countDownDate_main_$index_main = new Date('$event_time').getTime();
                                var now_main_$index_main = " . (time() * 1000) . ";
                                var x_main_$index_main = setInterval(function() {
                                    now_main_$index_main = now_main_$index_main + 1000;
                                    var distance_main_$index_main = countDownDate_main_$index_main - now_main_$index_main;
                                    var days_main_$index_main = Math.floor(distance_main_$index_main / (1000 * 60 * 60 * 24));
                                    var hours_main_$index_main = Math.floor((distance_main_$index_main % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    var minutes_main_$index_main = Math.floor((distance_main_$index_main % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds_main_$index_main = Math.floor((distance_main_$index_main % (1000 * 60)) / 1000);
                                    document.getElementById('demo_main_$index_main').innerHTML = days_main_$index_main + 'd ' + hours_main_$index_main + 'h ' + minutes_main_$index_main + 'm ' + seconds_main_$index_main + 's ';
                                    if (distance_main_$index_main < 0) {
                                        clearInterval(x_main_$index_main);
                                        document.getElementById('demo_main_$index_main').innerHTML = 'EXPIRED';
                                    }
                                }, 1000);
                            </script>";
                            $index_main++;
                        }
                    } else {
                        echo "<tr><td colspan='4'>No main events found.</td></tr>";
                    }
                    ?>

                    <!-- Upcoming Events Section -->
                    <tr>
                        <th colspan="4" style="text-align:center;">Upcoming Events</th>
                    </tr>
                    <?php
                    $result_upcoming = mysqli_query($db, "SELECT event_name, event_time, event_desc
                                                          FROM events 
                                                          WHERE event_type='upcoming'
                                                          ORDER BY event_time ASC");
                    if ($result_upcoming && mysqli_num_rows($result_upcoming) > 0) {
                        $index_upcoming = 0;
                        while ($index_upcoming < 3 && ($row = mysqli_fetch_assoc($result_upcoming))) {
                            $event_name = htmlspecialchars($row['event_name']);
                            $event_desc = htmlspecialchars($row['event_desc']);
                            $event_time = htmlspecialchars($row['event_time']);
                            echo "<tr>
                                    <td>$event_name</td>
                                    <td>$event_desc</td>
                                    <td>$event_time</td>
                                    <td><span id='demo_upcoming_$index_upcoming'></span></td>
                                  </tr>";
                            echo "<script>
                                var countDownDate_upcoming_$index_upcoming = new Date('$event_time').getTime();
                                var now_upcoming_$index_upcoming = " . (time() * 1000) . ";
                                var x_upcoming_$index_upcoming = setInterval(function() {
                                    now_upcoming_$index_upcoming = now_upcoming_$index_upcoming + 1000;
                                    var distance_upcoming_$index_upcoming = countDownDate_upcoming_$index_upcoming - now_upcoming_$index_upcoming;
                                    var days_upcoming_$index_upcoming = Math.floor(distance_upcoming_$index_upcoming / (1000 * 60 * 60 * 24));
                                    var hours_upcoming_$index_upcoming = Math.floor((distance_upcoming_$index_upcoming % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    var minutes_upcoming_$index_upcoming = Math.floor((distance_upcoming_$index_upcoming % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds_upcoming_$index_upcoming = Math.floor((distance_upcoming_$index_upcoming % (1000 * 60)) / 1000);
                                    document.getElementById('demo_upcoming_$index_upcoming').innerHTML = days_upcoming_$index_upcoming + 'd ' + hours_upcoming_$index_upcoming + 'h ' + minutes_upcoming_$index_upcoming + 'm ' + seconds_upcoming_$index_upcoming + 's ';
                                    if (distance_upcoming_$index_upcoming < 0) {
                                        clearInterval(x_upcoming_$index_upcoming);
                                        document.getElementById('demo_upcoming_$index_upcoming').innerHTML = 'EXPIRED';
                                    }
                                }, 1000);
                            </script>";
                            $index_upcoming++;
                        }
                        if ($index_upcoming === 0) {
                            echo "<tr><td colspan='4'>No upcoming events found.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No upcoming events found.</td></tr>";
                    }
                    ?>
                </tbody>
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
                <tr height="10%">
                    <td width="43%" colspan="3">Home</td>
                    <td width="20%" colspan="2">Account</td>
                </tr>
                <tr height="15%">
                    <th width="10%">Inquiry ID</th>
                    <th width="15%">Time</th>
                    <th width="15%">Issue</th>
                    <th>Description</th>
                    <th width="10%">Reply</th>
                </tr>
                <?php
                // Check if the database connection is established
                if ($db) {
                    $result = mysqli_query($db, "SELECT created_at, issue, Inq_ID, description FROM inquiry WHERE inq_type='Finance'");
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row["Inq_ID"] . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>";
                            echo "<td>" . $row["issue"] . "</td>";
                            echo "<td>" . $row["description"] . "</td>";
                            echo "<td><form action='../Service_forms/Reply.php' method='get'>
                                            <button name='reply' type='submit'>Reply</button> 
                                            <input type='hidden' name='replyto' value='" . $row["Inq_ID"] . "'>
                                            </form></td>";
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