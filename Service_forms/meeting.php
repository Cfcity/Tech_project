<?php
include('../General/test.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Meeting</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body class="dashboard-container">
    <div class="main-content">
        <div class="content-card" style="max-width: 500px; margin: 3rem auto;">
            <h2 class="center-text">Create Meeting</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <table class="dashboard-table">
                    <tr>
                        <td><label for="meeting_name">Meeting Name:</label></td>
                        <td><input type="text" id="meeting_name" name="meeting_name" class="input-field" required></td>
                    </tr>
                    <tr>
                        <td><label for="meeting_time">Meeting Date/Time:</label></td>
                        <td><input type="datetime-local" id="meeting_time" name="meeting_time" class="input-field" required></td>
                    </tr>
                    <tr>
                        <td><label for="meeting_location">Location:</label></td>
                        <td><input type="text" id="meeting_location" name="meeting_location" class="input-field" required></td>
                    </tr>
                    <tr>
                        <td><label for="meeting_desc">Description:</label></td>
                        <td><textarea id="meeting_desc" name="meeting_desc" class="input-field" rows="3"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="center-text">
                            <input type="submit" name="add_meeting" value="Create Meeting" class="primary-button">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="center-text">
                            <a href="../home_pages/home_admin.php?role=<?php echo $role ?>" class="primary-button">Home</a>
                        </td>
                    </tr>
                </table>
            </form>

</body>
</html>