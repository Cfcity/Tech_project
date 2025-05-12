<?php
include("../General/test.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete or Update Service</title>
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="dashboard-container">
    <div class="main-content">
        <div class="content-card" style="max-width: 500px; margin: 3rem auto;">
            <h2 class="center-text">Delete or Update Service</h2>
            <form action="delete_service.php" method="POST">
                <table class="dashboard-table">
                    <tr>
                        <th colspan="2" class="center-text">Select Service</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <select name="service_id" id="service_id" class="input-field" style="height:50%;" required>
                                <option value="">Select a service</option>
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
                        <td colspan="2" class="center-text">
                            <input type="submit" value="Select Service" class="primary-button">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="center-text">
                            <a href="../home_pages/home_admin.php?role=<?php echo $role ?>" class="primary-button">Home</a>
                        </td>
                    </tr>
                </table>
            </form>
            <div style="margin-top:2rem;">
                <?php
                if (isset($_POST['service_id'])) {
                    $service_id = $_POST['service_id'];
                    $query = "SELECT * FROM service WHERE ser_id = $service_id";
                    $result = mysqli_query($db, $query);

                    if ($result && $row = mysqli_fetch_assoc($result)) {
                        echo "<form action='' method='POST'>
                            <input type='hidden' name='service_id' value='" . htmlspecialchars($row['ser_id']) . "'>
                            <table class='dashboard-table'>
                                <tr>
                                    <th colspan='2' class='center-text'>Edit Service</th>
                                </tr>
                                <tr>
                                    <td><label for='ser_name'>Service Name:</label></td>
                                    <td><input type='text' id='ser_name' name='ser_name' class='input-field' value='" . htmlspecialchars($row['Ser_name']) . "'></td>
                                </tr>
                                <tr>
                                    <td><label for='ser_details'>Service Details:</label></td>
                                    <td><input type='text' id='ser_details' name='ser_details' class='input-field' value='" . htmlspecialchars($row['ser_details']) . "'></td>
                                </tr>
                                <tr>
                                    <td><label for='ser_lecturer'>Lecturer Name:</label></td>
                                    <td><input type='text' id='ser_lecturer' name='ser_lecturer' class='input-field' value='" . htmlspecialchars($row['Ser_lecturer']) . "'></td>
                                </tr>
                                <tr>
                                    <td><label for='ser_location'>Location:</label></td>
                                    <td><input type='text' id='ser_location' name='ser_location' class='input-field' value='" . htmlspecialchars($row['Ser_location']) . "'></td>
                                </tr>
                                <tr>
                                    <td><label for='ser_date'>Service Date:</label></td>
                                    <td><input type='datetime-local' id='ser_date' name='ser_date' class='input-field' value='" . htmlspecialchars($row['Ser_date']) . "'></td>
                                </tr>
                                <tr>
                                    <td colspan='2' class='center-text'>
                                        <input type='submit' name='service_delete' value='Delete Service' class='primary-button'>
                                        <input type='submit' name='update_service' value='Update Service' class='primary-button'>
                                    </td>
                                </tr>
                            </table>
                        </form>";
                    } else {
                        echo "<div class='error-message center-text'>Error fetching service details.</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>