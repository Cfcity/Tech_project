<?php

include('../General/test.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <title>Add Service</title>
</head>

<body class="dashboard-container">
    <div class="main-content">
        <div class="content-card" style="max-width: 500px; margin: 3rem auto;">
            <h2 class="center-text">Add Service</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <table class="dashboard-table">
                    <tr>
                        <td><label for="ser_name">Service Name:</label></td>
                        <td><input type="text" id="ser_name" name="ser_name" class="input-field" placeholder="Service Name" required></td>
                    </tr>
                    <tr>
                        <td><label for="ser_details">Service Description:</label></td>
                        <td><input type="text" id="ser_details" name="ser_details" class="input-field" placeholder="Service Description" required></td>
                    </tr>
                    <tr>
                        <td><label for="ser_date">Service Date:</label></td>
                        <td><input type="datetime-local" name="ser_date" id="ser_date" class="input-field" required></td>
                    </tr>
                    <tr>
                        <td><label for="ser_lecturer">Lecturer Name:</label></td>
                        <td><input type="text" name="ser_lecturer" id="ser_lecturer" class="input-field" placeholder="Lecturer Name" required></td>
                    </tr>
                    <tr>
                        <td><label for="ser_location">Location:</label></td>
                        <td><input type="text" name="ser_location" id="ser_location" class="input-field" placeholder="Location" required></td>
                    </tr>
                    <tr>
                        <td><label for="ser_image">Service Image:</label></td>
                        <td><input type="file" id="ser_image" name="service_image" class="input-field"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="center-text">
                            <input type="submit" name="Service_add" value="Add Service" class="primary-button">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="center-text">
                            <a href="../home_pages/home_admin.php?role=<?php echo $role ?>" class="primary-button">Home</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>

</html>