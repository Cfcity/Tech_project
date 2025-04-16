<?php
include('../General/test.php'); // Ensure this file initializes the $db connection
include('../Staff view/staff.php');

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the database connection is valid
if (!isset($db) || !$db) {
    die("Database connection failed. Please check your configuration.");
}

if (!$db) {
    die("Database connection is not active.");
}

// Set session variables for tracking
$_SESSION['lastaccessed'] = "Home";
$_SESSION['lastaccurl'] = "../home_pages/hpstudent.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Student</title>
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
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'library')">Queries</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'services')">Services</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'events')">Events</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'about')">About</button>
                    </div>
                </div>
            </td>

            <!-- Content / Tabs -->
            <td>
                <div id="home" class="tabcontent">
                    <table border="1" width="100%" height="100%" style="text-align: center;">
                        <tr height="10%">
                            <td width="20%">Logo</td>
                            <td width="60%" colspan="2">Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></td>
                            <td width="20%">
                                <img src="<?php echo "../images/" . htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profile Image">
                                <a href="../Extras/Stu_Account.php">Account</a>
                            </td>
                        </tr>
                        <tr height="60%">
                            <td width="20%">Important aspect <br> Most used</td>
                            <td colspan="3" style="background-image: url(<?php echo "../images/" . htmlspecialchars($image); ?>); background-size: cover;">
                                Important information
                            </td>
                        </tr>
                        <tr height="30%">
                            <td width="20%">Last accessed <br> <?php echo htmlspecialchars($_SESSION['lastaccessed']); ?></td>
                            <td width="30%">More news</td>
                            <td width="30%">More news</td>
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
                                        <h2>Select Query Type</h2>
                                    </th>
                                </tr>
                                <tr style="height: 90%;">
                                    <td><button class="servicebubbles" onclick="financial()">Financial</button></td>
                                    <td><button class="servicebubbles" onclick="">Sonis / E-learning</button></td>
                                    <td><button class="servicebubbles" onclick="Subjects()">Subject Selection & Electives</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="services" class="tabcontent">
                    <table border="1" width="100%" height="100%" style="text-align: center;">
                        <tr height="10%">
                            <td width="43%" colspan="3">Home</td>
                            <td width="20%" colspan="2">Account</td>
                        </tr>
                        <tr height="45%">
                            <td width="10%" rowspan="2">Services</td>
                            <td width="33%">Service 1</td>
                            <td width="34%">Service 2</td>
                            <td width="33%">Service 3</td>
                        </tr>
                        <tr>
                            <td>Inquiry 1</td>
                            <td>Inquiry 2</td>
                            <td>Inquiry 3</td>
                        </tr>
                    </table>
                </div>

                <div id="events" class="tabcontent">
                    <table border="1" style="width: 100%; text-align: center; font-size: 14px; border-collapse: collapse;">
                        <tr >
                            <th style="width: 30%;">Event</th>
                            <th style="width: 40%;">Description</th>
                            <th style="width: 30%;">Countdown</th>
                        </tr>
                        <tr>
                            <th colspan="3">Main Events</th>
                        </tr>
                        <!-- Main event section -->
                        <?php
                        // Fetch and display "Main Events" ordered by time
                        $result_main = mysqli_query($db, "SELECT event_name, event_time, event_desc
                                                          FROM events 
                                                          WHERE event_type='main' 
                                                          AND priority = (SELECT MIN(priority) FROM events WHERE event_type='main')
                                                          ORDER BY event_time ASC");

                        if ($result_main) {
                            $index_main = 0;
                            while ($row = mysqli_fetch_assoc($result_main)) {
                                $event_name = $row['event_name'];
                                $event_desc = $row['event_desc'];
                                $event_time = $row['event_time'];

                                echo "<tr>
                                        <td>$event_name</td>
                                        <td>$event_desc</td>
                                        <td><span id='demo_main_$index_main'></span></td>
                                      </tr>";
                                echo "<script>
                                    var countDownDate_main_$index_main = new Date('$event_time').getTime();
                                    var now_main_$index_main = " . time() * 1000 . ";
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
                            echo "<tr><td colspan='3'>No main events found.</td></tr>";
                        }

                        // Fetch and display "Upcoming Events" ordered by time
                        $result_upcoming = mysqli_query($db, "SELECT event_name, event_time, event_desc
                                                              FROM events 
                                                              WHERE priority > (SELECT MIN(priority) FROM events WHERE event_type='main')
                                                              ORDER BY event_time ASC");

                        if ($result_upcoming) {
                            echo "<tr>
                                    <th colspan='3' >Upcoming Events</th>
                                  </tr>";
                            $index_upcoming = 0;
                            while ($row = mysqli_fetch_assoc($result_upcoming) AND $index_upcoming < 3) {
                                $event_name = $row['event_name'];
                                $event_desc = $row['event_desc'];
                                $event_time = $row['event_time'];

                                echo "<tr>
                                        <td>$event_name</td>
                                        <td>$event_desc</td>
                                        <td><span id='demo_upcoming_$index_upcoming'></span></td>
                                      </tr>";
                                echo "<script>
                                    var countDownDate_upcoming_$index_upcoming = new Date('$event_time').getTime();
                                    var now_upcoming_$index_upcoming = " . time() * 1000 . ";
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
                        } else {
                            echo "<tr><td colspan='3'>No upcoming events found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>

                <div id="about" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <h4>About Section</h4>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

<script src="../source.js"></script>

</html>