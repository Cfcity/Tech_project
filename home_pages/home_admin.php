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
        <button class="side-nav-button" onclick="opentab(event, 'reports')">
            <i class="fas fa-chart-bar"></i> Reports
        </button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Tab -->
        <div id="dashboard" class="content-card" style="display:block;">
            <table class="dashboard-table">
                <tr height="10%">
                    <th width="20%" colspan="3">Home</th>
                    <th width="60%">Welcome <?php echo htmlspecialchars(getUsername()) ?></th>
                    <th width="20%" colspan="2">Account</th>
                </tr>
                <tr>
                    <th colspan="5">Welcome to C.A.S.H</th>
                </tr>
            </table>
            <div class="stats-container">
                <div class="content-card">

                    <table class="dashboard-table">
                        <tr>
                            <th width="20%">
                                Total Users
                            </th>
                            <th colspan="2">
                                Breakdown
                            </th>
                        </tr>
                        <tr style="text-align: center;">
                            <td rowspan="2">
                                <div class="stat-value">
                                    <?php
                                    $query = "SELECT COUNT(*) as total_users FROM user";
                                    $result = mysqli_query($db, $query);
                                    $row = mysqli_fetch_assoc($result);
                                    echo htmlspecialchars($row['total_users']);
                                    ?>
                            </td>
                            <td>Students</td>
                            <td>Faculty</td>
                        </tr>


                        <tr style="text-align: center;">
                            <td>
                                <div class="stat-value">
                                    <?php
                                    $query = "SELECT COUNT(*) as total_students FROM students";
                                    $result = mysqli_query($db, $query);
                                    $row = mysqli_fetch_assoc($result);
                                    echo htmlspecialchars($row['total_students']);
                                    ?>
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
        </div>

        <!-- Queries Tab -->
        <div id="queries" class="tabcontent">
            <h2 style="text-align: center;">Recent Queries</h2>
            <table class="dashboard-table">
                <thead>
                    <tr>
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
                            echo "<tr>
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
                        echo "<tr><td colspan='6'>No queries found</td></tr>";
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
                echo '<a href="../Service_forms/' . htmlspecialchars($row['Ser_name']) . '.php">' . 
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
            <table class="dashboard-table">
                <tr height="10%">
                    <td width="43%" colspan="5">Home</td>
                    <td width="20%" colspan="2">Account</td>
                </tr>
                <tr>
                    <th colspan="7">Events</th>
                </tr>
                <tr text-align="center">
                    <td colspan="7" text-align="center">
                        <form method='POST' action=''>
                            <input class="primary-button" style="width: 20%;" type='submit' name='add_event' value='Add event'>
                    </td>
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
                    echo "</table>";
                } else {
                    echo "<tr><td colspan='6'>No events found.</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- About Tab -->
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