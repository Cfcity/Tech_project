<?php
include('../General/test.php');
$db = mysqli_connect('localhost', 'root', '', 'test');
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        p {
            text-align: center;
            font-size: 60px;
            margin-top: 0px;
        }
    </style>
</head>

<body>
    <?php
    if ($db) {
        // Fetch and display "Main Events" ordered by time
        $result_main = mysqli_query($db, "SELECT event_name, event_time, event_desc 
                                          FROM events 
                                          WHERE event_type='main' 
                                          AND priority = (SELECT MIN(priority) FROM events WHERE event_type='main')
                                          ORDER BY event_time ASC");

        if ($result_main) {
            $index_main = 0;
            echo "<table width='100%' border='1'>
                    <tr>
                        <td colspan='3' align='center'>Main Events</td>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result_main)) {
                $event_name = $row['event_name'];
                $event_desc = $row['event_desc'];
                $event_time = $row['event_time'];

                echo "<tr align='center'>
                        <td width='30%'>$event_name:</td>
                        <td width='40%'>$event_desc </td>
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
            echo "</table>";
        } else {
            echo "Error fetching main events: " . mysqli_error($db);
        }

        // Fetch and display "Upcoming Events" ordered by time
        $result_upcoming = mysqli_query($db, "SELECT event_name, event_time, event_desc 
                                              FROM events 
                                              WHERE priority > (SELECT MIN(priority) FROM events WHERE event_type='main')
                                              ORDER BY event_time ASC");

        if ($result_upcoming) {
            $index_upcoming = 0;
            echo "<table width='100%' border='1'>
                    <tr>
                        <td colspan='3' align='center'>Upcoming Events</td>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result_upcoming)) {
                $event_name = $row['event_name'];
                $event_desc = $row['event_desc'];
                $event_time = $row['event_time'];

                echo "<tr align='center'>
                        <td width='30%'>$event_name:</td>
                        <td width='40%'>$event_desc</td>
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
            echo "</table>";
        } else {
            echo "Error fetching upcoming events: " . mysqli_error($db);
        }
    } else {
        echo "Database connection failed.";
    }
    ?>
</body>

</html>