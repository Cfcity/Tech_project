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
        $result_main = mysqli_query($db, "SELECT event_name, event_time FROM events WHERE event_type='main'");
        if ($result_main) {
            $index = 0;
            while ($row = mysqli_fetch_assoc($result_main)) {
                $event_name = $row['event_name'];
                $event_time = $row['event_time'];
                echo "<p>$event_name: <span id='demo_$index'></span></p>";
                echo "<script>
                    var countDownDate_$index = new Date('$event_time').getTime();
                    var now_$index = " . time() * 1000 . ";
                    var x_$index = setInterval(function() {
                        now_$index = now_$index + 1000;
                        var distance_$index = countDownDate_$index - now_$index;
                        var days_$index = Math.floor(distance_$index / (1000 * 60 * 60 * 24));
                        var hours_$index = Math.floor((distance_$index % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes_$index = Math.floor((distance_$index % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds_$index = Math.floor((distance_$index % (1000 * 60)) / 1000);
                        document.getElementById('demo_$index').innerHTML = days_$index + 'd ' + hours_$index + 'h ' + minutes_$index + 'm ' + seconds_$index + 's ';
                        if (distance_$index < 0) {
                            clearInterval(x_$index);
                            document.getElementById('demo_$index').innerHTML = 'EXPIRED';
                        }
                    }, 1000);
                </script>";
                $index++;
            }
        } else {
            echo "Error: " . mysqli_error($db);
        }
    } else {
        echo "Database connection failed.";
    }
    ?>
</body>

</html>

