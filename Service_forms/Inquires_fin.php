<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiries - Finance</title>
    <link rel="stylesheet" href="../css/forms_styles.css">
</head>
<body class="staff"> 
    <table border="1">
        <tr>
            <th>Time</th>
            <th>Issue</th>
            <th>Description</th>
        </tr>
        <?php
            $db = mysqli_connect('localhost', 'root', '', 'test');

            if (!$db) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Check if the database connection is established
            if ($db) {
                $result = mysqli_query($db, "SELECT date, issue, description FROM inquiry WHERE inq_type='Finance'");
                if ($result) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["date"] . "</td>";
                        //echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["issue"] . "</td>";
                        echo "<td>" . $row["description"] . "</td>";
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
</body>
</html>