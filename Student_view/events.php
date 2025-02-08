<?php include('../General/test.php');
  include('../Staff view/staff.php');
  include('../trial.php');
  session_start();
$db = mysqli_connect('localhost', 'root', '', 'test'); ?>


<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/styles.css">
</head>

<body style="background-color: rgb(63, 63, 63);">
  <div class="centertop header">
    <a class="centerleft" href="home.php"><button style="height: 7vh; width: 10vw;" class="buttonr tablinks">Home</button></a>
    <div class="center">
      <a href="#Urgent"><button style="height: 7vh; width: 10vw;" class="buttonc tablinks">Urgent !!</button></a>
      <a href="#Upcoming"><button style="height: 7vh; width: 10vw;" class="buttonc tablinks">Upcoming</button></a>
      <a href="#News"><button style="height: 7vh; width: 10vw;" class="buttonc tablinks">News</button></a>
    </div>
  </div>

  <table border="1" style="position: absolute; top: 10vh; width: 99%;" align="center">
    <!-- Urgent section -->
    <?php


    if ($db) {
      $result_main = mysqli_query($db, "SELECT event_name, event_desc, event_time  FROM events WHERE event_type='main'");
      if ($result_main) {
        while ($row = mysqli_fetch_assoc($result_main)) {
          echo "<tr>";
          echo "<td>" . $row["event_name"] . "</td>";
          echo "<td>" . $row["event_desc"] . "</td>";
          echo "</tr>";
        }
      } else {
        echo "Error: " . mysqli_error($db);
      }
    } else {
      echo "Database connection failed.";
    }
    ?>
    <!-- Upcoming section -->
    <tr>
      <td colspan="3" align="center">
        <section id="Upcoming">
          <h4>Upcoming</h4>
        </section>
      </td>
    </tr>
    <tr>
      <th width="25%">Event</th>
      <th width="50%">Description</th>
      <th>Countdown</th>
    </tr>
    <tr>
        <?php
        $result_main = mysqli_query($db, "SELECT event_name, event_desc, event_time FROM events  WHERE event_type='upcoming'");
        if ($result_main) {
          while ($row = mysqli_fetch_assoc($result_main)) {
            echo "<td>" . $row["event_name"] . "</td>";
            echo "<td>" . $row["event_desc"] . "</td>";
            echo "<td>" . $row["event_time"] . "</td>";
          }
        } else {
          echo "Error: " . mysqli_error($db);
        }
        ?>
    </tr>

    <tr>
      <td colspan="3">
        <hr size="3" color="black">
      </td>
    </tr>

    <!-- News Section -->
    <tr>
      <td colspan="3"></td>
    </tr>
  </table>
  <?php mysqli_close($db); ?>
</body>
<script src="source.js"></script>

</html>