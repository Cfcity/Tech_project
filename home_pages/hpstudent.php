<?php
include('../General/test.php'); // Include the modified test.php with namespace support

// Set session variables for tracking
$_SESSION['roles'][$_SESSION['current_role']]['lastaccessed'] = "Home";
$_SESSION['roles'][$_SESSION['current_role']]['lastaccurl'] = "../home_pages/hpstudent.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Student</title>
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="dashboard-container">
    <!-- Side Navigation -->
    <div class="side-nav">
        <button class="side-nav-button active" onclick="opentab(event, 'home')" id="defaultOpen">
            <i class="fas fa-home"></i> Dashboard
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'services')">
            <i class="fas fa-concierge-bell"></i> Services
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'N_queries')">
            <i class="fas fa-file-alt"></i> New Query
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'queries')">
            <i class="fas fa-question-circle"></i> Submitted Queries
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'events')">
            <i class="fas fa-calendar-alt"></i> Events
        </button>
        <button class="side-nav-button" onclick="opentab(event, 'resources')">
            <i class="fas fa-book"></i> Resources
        </button>
    </div>

    <!-- Content / Tabs -->
    <td>
        <div id="home" class="tabcontent">
            <table border="1" width="100%" height="100%" style="text-align: center;">
                <tr height="10%">
                    <td width="20%">Logo</td>
                    <td width="60%" colspan="2">Welcome <?php echo htmlspecialchars(getUsername()); ?></td>
                    <td width="20%">
                        <img src="<?php echo "../images/" . htmlspecialchars(getRoleSessionData('profile_image', 'default.jpg')); ?>" alt="Profile Image">
                        <a href="../Extras/Stu_Account.php?role=<?php echo $_SESSION['current_role']; ?>">Account</a>
                    </td>
                </tr>
                <tr height="60%">
                    <td width="20%">Important aspect <br> Most used</td>
                    <td colspan="3" style="background-image: url(<?php echo "../images/" . htmlspecialchars($image); ?>); background-size: cover;">
                        Important information
                    </td>
                </tr>
                <tr height="30%">
                    <td width="20%">Last accessed <br> <?php echo htmlspecialchars(getRoleSessionData('lastaccessed', 'None')); ?></td>
                    <td width="30%">More news</td>
                    <td width="30%">More news</td>
                    <td></td>
                </tr>
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

        <!-- Services Tab -->
        <div id="services" class="tabcontent">
            <h2 style="text-align: center;">Available Services</h2>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result_services = mysqli_query($db, "SELECT Ser_name, ser_details FROM service ORDER BY Ser_name ASC");
                    if ($result_services && mysqli_num_rows($result_services) > 0) {
                        while ($row = mysqli_fetch_assoc($result_services)) {
                            $ser_name = htmlspecialchars($row['Ser_name']);
                            $ser_details = htmlspecialchars($row['ser_details']);
                            echo "<tr>
                                    <td>$ser_name</td>
                                    <td>$ser_details</td>
                                    <td><a class='primary-button' href='../Services/$ser_name.php'>Go</a></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No services available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- New Queries Tab -->
        <div id="N_queries" class="tabcontent">
            <h2 style="text-align: center;">Submit a New Inquiry</h2>
            <div style="text-align:center; margin-bottom: 1.5rem;">
                <label for="inquiry-type" style="font-weight:600;">Select Inquiry Type:</label>
                <select id="inquiry-type" style="margin-left:1rem; padding:0.5rem;">
                    <option value="">-- Choose --</option>
                    <option value="subject">Subject</option>
                    <option value="hostel">Hostel</option>
                    <option value="finance">Finance</option>
                    <!-- Add more types as needed -->
                </select>
            </div>

            <?php
            // Fetch department name for this student using Stu_dept_id
            $studentId = getRoleSessionData('studentid', '');
            $department = '';
            if ($studentId) {
                $deptResult = mysqli_query($db, "SELECT d.dept_name FROM students s JOIN department d ON s.Stu_dept_id = d.dept_id WHERE s.studentid = '$studentId' LIMIT 1");
                if ($deptResult && $deptRow = mysqli_fetch_assoc($deptResult)) {
                    $department = $deptRow['dept_name'];
                }
            }
            ?>

            <!-- Subject Inquiry Form -->
            <div id="form-subject" class="inquiry-form" style="display:none;">
                <form action="" method="post" enctype="multipart/form-data" class="dashboard-table">
                    <input type="hidden" name="studentId" value="<?php echo htmlspecialchars(getRoleSessionData('studentid', '')); ?>">
                    <input type="hidden" name="inq_type" value="Subject">
                    <input type="hidden" name="department" value="<?php echo htmlspecialchars($department); ?>">
                    <table style="width:100%;">
                        <tr>
                            <td><label for="issue">Topic of issue:</label></td>
                            <td>
                                <select name="issue" id="issue" required>
                                    <option value="">Select</option>
                                    <option value="Elective selection">Elective selection</option>
                                    <option value="Change of subject">Change of subject</option>
                                    <option value="Timetable errors">Timetable errors</option>
                                    <option value="Other">Other</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="description">Description of issue:</label></td>
                            <td><textarea name="description" id="description" required></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="img">Insert any images:</label></td>
                            <td><input type="file" name="img" id="img"></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <button class="primary-button" type="submit" name="s_submit_query">Submit</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <!-- Hostel Inquiry Form -->
            <div id="form-hostel" class="inquiry-form" style="display:none;">
                <form action="" method="post" enctype="multipart/form-data" class="dashboard-table">
                    <input type="hidden" name="studentId" value="<?php echo htmlspecialchars(getRoleSessionData('studentid', '')); ?>">
                    <input type="hidden" name="inq_type" value="Hostel">
                    <input type="hidden" name="department" value="<?php echo htmlspecialchars($department); ?>">
                    <table style="width:100%;">
                        <tr>
                            <td><label for="hostel_issue">Hostel Issue:</label></td>
                            <td><input type="text" name="hostel_issue" id="hostel_issue" required></td>
                        </tr>
                        <tr>
                            <td><label for="description">Description:</label></td>
                            <td><textarea name="hostel_description" id="hostel_description" required></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <button class="primary-button" type="submit" name="h_submit_query">Submit</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <!-- Finance Inquiry Form -->
            <div id="form-finance" class="inquiry-form" style="display:none;">
                <form action="" method="post" enctype="multipart/form-data" class="dashboard-table">
                    <input type="hidden" name="studentId" value="<?php echo htmlspecialchars(getRoleSessionData('studentid', '')); ?>">
                    <input type="hidden" name="inq_type" value="Finance">
                    <input type="hidden" name="department" value="<?php echo htmlspecialchars($department); ?>">
                    <table style="width:100%;">
                        <tr>
                            <td><label for="issue">Finance Issue:</label></td>
                            <td><input type="text" name="issue" id="issue" required></td>
                        </tr>
                        <tr>
                            <td><label for="description">Description:</label></td>
                            <td><textarea name="description" id="description" required></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <button class="primary-button" type="submit" name="f_submit_query">Submit</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <!-- Queries Tab -->
        <div id="queries" class="tabcontent">
            <h2 style="text-align: center;">Your Queries</h2>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Issue</th>
                        <th>Description</th>
                        <th>Date created</th>
                        <th colspan="2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $student_id = getRoleSessionData('studentid', '');
                    $result_queries = mysqli_query($db, "SELECT Inq_ID, department, issue, description, status, created_at FROM inquiry WHERE studentid='$student_id' ORDER BY created_at DESC");
                    if ($result_queries && mysqli_num_rows($result_queries) > 0) {
                        while ($row = mysqli_fetch_assoc($result_queries)) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['issue']) . "</td>
                                    <td>" . htmlspecialchars($row['description']) . "</td>
                                    <td>" . htmlspecialchars($row['created_at']) . "</td>";

                                    if ($row['status'] == 'Pending') {
                                        echo "<td colspan='2' style='color: orange;'>" . htmlspecialchars($row['status']) . "</td>";
                                    } elseif ($row['status'] == 'Replied') {
                                        echo "<td style='color: green;'>" . htmlspecialchars($row['status']) . "</td>";
                                        echo "<td><a class='primary-button' href='../Service_forms/view_reply.php?Inq_ID=" . htmlspecialchars($row['Inq_ID']) . "'>View Reply</a></td>";
                                    } elseif ($row['status'] == 'Unread') {
                                        echo "<td colspan='2' style='color: red;'>" . htmlspecialchars($row['status']) . "</td>";
                                    }


                                  "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No queries found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>



    </td>
    </tr>
    </table>
</body>

<script src="../source.js"></script>

</html>