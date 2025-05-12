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
    <script src="../source.js"></script>
    <?php
    $_SESSION['lastaccessed'] = "Home";
    $_SESSION['lastaccurl'] = "../home_pages/hpfinance.php";
    ?>
    <style>
        .tabcontent { display: none; }
        .tabcontent.active { display: block; }
    </style>
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
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Tab -->
        <div id="home" class="tabcontent active">
            <div id="dashboard" class="content-card" style="display:block;">
                <h2 class="center-text">Finance Dashboard Overview</h2>
                <div class="dashboard-cards" style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; margin-bottom: 2rem;">
                    <!-- Inquiries Card -->
                    <div class="content-card" style="min-width: 320px; flex: 1 1 40%;">
                        <h3 class="center-text">Total Inquiries</h3>
                        <div class="stat-value center-text" style="font-size: 2rem;">
                            <?php
                            $result = mysqli_query($db, "SELECT COUNT(*) as total_inquiries FROM inquiry");
                            $row = mysqli_fetch_assoc($result);
                            echo htmlspecialchars($row['total_inquiries']);
                            ?>
                        </div>
                        <div class="center-text" style="margin-top: 1rem;">
                            <?php
                            $pending = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as cnt FROM inquiry WHERE status='pending'"))['cnt'];
                            $unread = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as cnt FROM inquiry WHERE status='unread'"))['cnt'];
                            $resolved = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as cnt FROM inquiry WHERE status='Replied'"))['cnt'];
                            ?>
                            <span style="color: #e43a2a;">Pending: <?= $pending ?></span> |
                            <span style="color: #2a7ae4;">Unread: <?= $unread ?></span> |
                            <span style="color: #2ae47a;">Resolved: <?= $resolved ?></span>
                        </div>
                    </div>

                    <!-- Events Card -->
                    <div class="content-card" style="min-width: 320px; flex: 1 1 40%;">
                        <h3 class="center-text">Events</h3>
                        <div class="stat-value center-text" style="font-size: 2rem;">
                            <?php
                            $result = mysqli_query($db, "SELECT COUNT(*) as total_events FROM events");
                            $row = mysqli_fetch_assoc($result);
                            echo htmlspecialchars($row['total_events']);
                            ?>
                        </div>
                        <div class="center-text" style="margin-top: 1rem;">
                            <?php
                            $main = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as cnt FROM events WHERE event_type='main'"))['cnt'];
                            $upcoming = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as cnt FROM events WHERE event_type='upcoming'"))['cnt'];
                            ?>
                            <span style="color: #2a7ae4;">Main: <?= $main ?></span> |
                            <span style="color: #e4a22a;">Upcoming: <?= $upcoming ?></span>
                        </div>
                    </div>

                    <!-- Meetings Card -->
                    <div class="content-card" style="min-width: 320px; flex: 1 1 40%;">
                        <h3 class="center-text">Meetings</h3>
                        <div class="stat-value center-text" style="font-size: 2rem;">
                            <?php
                            $result = mysqli_query($db, "SELECT COUNT(*) as total_meetings FROM meetings");
                            $row = mysqli_fetch_assoc($result);
                            echo htmlspecialchars($row['total_meetings']);
                            ?>
                        </div>
                        <div class="center-text" style="margin-top: 1rem;">
                            <?php
                            $next_meeting = mysqli_query($db, "SELECT meeting_name, meeting_time FROM meetings WHERE meeting_time >= NOW() ORDER BY meeting_time ASC LIMIT 1");
                            if ($next_meeting && $row = mysqli_fetch_assoc($next_meeting)) {
                                echo "<span style='color:#2a7ae4;'>Next: " . htmlspecialchars($row['meeting_name']) . " (" . htmlspecialchars($row['meeting_time']) . ")</span>";
                            } else {
                                echo "<span style='color:#aaa;'>No upcoming meetings</span>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meeting Tab -->
        <div id="Meeting" class="tabcontent">
            <h2 style="text-align: center;">Meetings</h2>
            <div class="content-card" style="max-width: 900px; margin: 0 auto;">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Meeting Name</th>
                            <th>Description</th>
                            <th>Date/Time</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($db, "SELECT meeting_name, meeting_desc, meeting_time, meeting_location FROM meetings ORDER BY meeting_time ASC");
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['meeting_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['meeting_desc']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['meeting_time']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['meeting_location']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No meetings found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inquiries Tab -->
        <div id="inquiries" class="tabcontent">
            <h2 class="center-text">Financial Inquiries</h2>
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
                        echo "<tr><td colspan='6'>No inquiries found</td></tr>";
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
    </div>
</body>
</html>