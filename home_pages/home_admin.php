<?php
include('../General/test.php');
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        
        /* Fixed sidebar styles - added to match student page */
        .side-nav {
            position: fixed;
            height: 100vh;
        }
        
        .main-content {
            margin-left: 22%; /* Adjust to match sidebar width */
            padding: 20px;
            width: 75%;
        }
    </style>
</head>

<body class="dashboard-container">
    <!-- Side Navigation -->
    <div class="side-nav">
        <button class="side-nav-button active" onclick="opentab(event, 'dashboard')" id="defaultOpen">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'queries')">
            <i class="fas fa-question-circle"></i> Queries
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'services')">
            <i class="fas fa-concierge-bell"></i> Services
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'events')">
            <i class="fas fa-calendar-alt"></i> Events
        </button>
        <form action="" method="post" style="position: absolute; bottom: 30px; left: 3.5%">
            <input class="primary-button" type="submit" value="Log out" name="logout" style="width:100%;">
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Tab -->
        <div id="dashboard" class="content-card" style="display:block;">
    <h2 class="center-text">Admin Dashboard Overview</h2>
    <div class="dashboard-cards" style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; margin-bottom: 2rem;">
        <!-- Services Card -->
        <div class="content-card" style="min-width: 320px; flex: 1 1 40%;">
            <h3 class="center-text">Total Services</h3>
            <div class="stat-value center-text" style="font-size: 2rem;">
                <?php
                $result = mysqli_query($db, "SELECT COUNT(*) as total_services FROM service");
                $row = mysqli_fetch_assoc($result);
                echo htmlspecialchars($row['total_services']);
                ?>
            </div>
        </div>
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
            <h3 class="center-text">Total Events</h3>
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
            <!-- User Breakdown Table -->
            <div class="content-card" style="margin-top: 2rem;">
                <table class="dashboard-table">
                    <tr>
                        <th width="20%">Total Users</th>
                        <th>Students</th>
                        <th>Faculty</th>
                    </tr>
                    <tr style="text-align: center;">
                        <td>
                            <form action="" method="post">
                                <input type="submit" class="primary-button" name="view_users" value="View User">
                            </form>
                        </td>
                        <td>
                            <div class="stat-value">
                                <?php
                                $query = "SELECT COUNT(*) as total_students FROM students";
                                $result = mysqli_query($db, $query);
                                $row = mysqli_fetch_assoc($result);
                                echo htmlspecialchars($row['total_students']);
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="stat-value">
                                <?php
                                $query = "SELECT COUNT(*) as total_faculty FROM staff";
                                $result = mysqli_query($db, $query);
                                $row = mysqli_fetch_assoc($result);
                                echo htmlspecialchars($row['total_faculty']);
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Queries Tab -->
        <div id="queries" class="tabcontent">
            <h2 style="text-align: center;">Recent Queries</h2>
            <table class="dashboard-table">
                <thead>
                    <tr height="20%">
                        <th>ID</th>
                        <th>Department</th>
                        <th>Time</th>
                        <th>Issue</th>
                        <th>Status</th>
                        <th>Student</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT inquiry.Inq_ID, inquiry.department, inquiry.created_at, inquiry.issue, inquiry.status, 
                             students.Stu_fname, students.Stu_lname  
                             FROM inquiry JOIN students ON inquiry.studentid = students.studentid
                             ORDER BY created_at DESC LIMIT 10";
                    $result = mysqli_query($db, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr >
                                <td>{$row['Inq_ID']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['created_at']}</td>
                                <td>{$row['issue']}</td>
                                <td>{$row['status']}</td>
                                <td>{$row['Stu_fname']} {$row['Stu_lname']}</td>
                                <td>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='Inq_ID' value='{$row['Inq_ID']}'>
                                        <input class='primary-button' type='submit' name='Review' value='Review'>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No queries found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Services Tab -->
        <div id="services" class="tabcontent">
            <h2 style="text-align: center;">Services Management</h2>
            <div style="display: flex; justify-content: center; gap: 1rem; margin-bottom: 1rem;">
                <form action="" method="post">
                    <input class="primary-button" type="submit" name="add_service" value="Add New Service">
                </form>
                <form action="" method="post">
                    <input class="primary-button" type="submit" name="delete_service" value="Manage Services">
                </form>
            </div>
            <div class="service-grid">
                <?php
                $Query = "SELECT * FROM service";
                $result = mysqli_query($db, $Query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="service-card">';
                        echo '<a href="../Services/' . htmlspecialchars($row['Ser_name']) . '.php?role=' . $role . '">' .
                            htmlspecialchars($row['Ser_name']) . '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="no-service" style="grid-column: 1 / -1; text-align: center;">No services available</div>';
                }
                ?>
            </div>
        </div>

        <!-- Events Tab -->
        <div id="events" class="tabcontent">
            <h2 style="text-align: center;">Events</h2>
            <div class="content-card" style="max-width: 900px; margin: 0 auto;">
                <form method='POST' action='' style="text-align: center; display: flex; justify-content: center; gap: 1rem;">
                <input class="primary-button" style="width: 20%;" type='submit' name='add_event' value='Add event'>
                <input class="primary-button" style="width: 20%; text-align: center;" type="submit" name="new_meeting" value="New Meeting">
                </form>
                <table class="dashboard-table" style="margin-top: 1rem;">
                    <tr>
                        <th>Event ID</th>
                        <th>Event Name</th>
                        <th>Description</th>
                        <th>Date and Time</th>
                        <th>Type</th>
                        <th colspan='2'>Action</th>
                    </tr>
                    <?php
                    $Query = "SELECT * FROM events";
                    $result = mysqli_query($db, $Query);
                    if ($result && mysqli_num_rows($result) > 0) {
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
                                        <input class='primary-button'  type='submit' name='Delete_event' value='Delete_event'>
                                    </form>
                                </td>
                                <td>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='event_id' value='" . htmlspecialchars($row['event_id']) . "'>
                                        <input class='primary-button' type='submit' name='Edit_event' value='Edit event'>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No events found.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>

        <!-- About Tab -->
        <div id="about" class="tabcontent">
            <div class="center">
                <div class="homecenter">
                    <!-- Content removed -->
                </div>
            </div>
        </div>
    </div>
</body>

<script src="../source.js"></script>

</html>