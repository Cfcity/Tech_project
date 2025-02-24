<?php $db = mysqli_connect('localhost', 'root', '', 'test'); ?>

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
    
    <script>
    // Set the date we're counting down to
    // 1. JavaScript
    // 2. PHP
    
    <?php 
    if ($db) {
        $result_main = mysqli_query($db, "SELECT event_time FROM events WHERE event_type='main'");
        if ($result_main) {
            $row = mysqli_fetch_assoc($result_main);
            $event_time = $row['event_time'];
            echo "var countDownDate = new Date('$event_time').getTime();";
        } else {
            echo "var countDownDate = new Date().getTime();"; // Default to current time if query fails
        }
    } else {
        echo "var countDownDate = new Date().getTime();"; // Default to current time if connection fails
    }
    ?>
    
    var now = <?php echo time() ?> * 1000;

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get todays date and time
        // 1. JavaScript
        // var now = new Date().getTime();
        // 2. PHP
        now = now + 1000;

        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        document.getElementById("demo").innerHTML = days + "d " + hours + "h " +
            minutes + "m " + seconds + "s ";

        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }
    }, 1000);
    </script>
</body>

</html>

