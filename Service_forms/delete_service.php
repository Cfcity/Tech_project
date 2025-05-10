<?php

include("../General/test.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete or Update Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        table {
            width: 50%;
            margin: 5% auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            font-size: 18px;
        }

        td {
            padding: 15px;
            text-align: left;
        }

        a,
        select,
        input[type="text"],
        input[type="datetime-local"],
        input[type="submit"] {
            width: 90%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"], a {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .center {
            text-align: center;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <th class="center" colspan="2">Select Service</th>
        </tr>
        <form action="delete_service.php" method="POST">
            <tr>
                <td colspan="2" class="center">
                    <select name="service_id" id="service_id">
                        <?php
                        $query = "SELECT * FROM service";
                        $result = mysqli_query($db, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['ser_id'] . "'>" . htmlspecialchars($row['Ser_name']) . "</option>";
                        }
                        ?>
                    </select>
                </td>
                    </tr>
            <tr>
                <td colspan="2" class="center">
                    <input type="submit" value="Select Service">
                </td>
            </tr>
            <tr>
                <td colspan="2" class="center">
                    <a href="../home_pages/home_admin.php">Return to home</a>
                </td>
            </tr>
        </form>
        <tr>
            <?php
            if (isset($_POST['service_id'])) {
                $service_id = $_POST['service_id'];
                $query = "SELECT * FROM service WHERE ser_id = $service_id";
                $result = mysqli_query($db, $query);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);

                    echo "<td colspan='2'>
                        <form action='' method='POST'>
                            <table style='width: 100%;'>
                                <tr>
                                    <td><label for='ser_name'>Service Name:</label></td>
                                    <td><input type='text' id='ser_name' name='ser_name' value='" . htmlspecialchars($row['Ser_name']) . "'></td>
                                </tr>
                                <tr>
                                    <td><label for='ser_details'>Service Details:</label></td>
                                    <td><input type='text' id='ser_details' name='ser_details' value='" . htmlspecialchars($row['ser_details']) . "'></td>
                                </tr>
                                <tr>
                                    <td><label for='ser_lecturer'>Lecturer Name:</label></td>
                                    <td><input type='text' id='ser_lecturer' name='ser_lecturer' value='" . htmlspecialchars($row['Ser_lecturer']) . "'></td>
                                </tr>
                                <tr>
                                    <td><label for='ser_location'>Location:</label></td>
                                    <td><input type='text' id='ser_location' name='ser_location' value='" . htmlspecialchars($row['Ser_location']) . "'></td>
                                </tr>
                                <tr>
                                    <td><label for='ser_date'>Service Date:</label></td>
                                    <td><input type='datetime-local' id='ser_date' name='ser_date' value='" . htmlspecialchars($row['Ser_date']) . "'></td>
                                </tr>
                                
                                <tr>
                                    <td colspan='2' class='center'>
                                        <input type='hidden' name='service_id' value='" . htmlspecialchars($row['ser_id']) . "'>
                                        <input type='submit' name='service_delete' value='Delete Service'>
                                        <input type='submit' name='update_service' value='Update Service'>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td>";
                } else {
                    echo "<td colspan='2' class='error'>Error fetching service details.</td>";
                }
            } else {
                echo "<td colspan='2' class='center'>Please select a service.</td>";
            }
            ?>
        </tr>
    </table>
</body>

</html>