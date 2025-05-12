
<?php
  include('../General/test.php');
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Library</title>
    <link rel='stylesheet' href='../css/main.css'>
</head>
<body class='dashboard-container'>
    <table class='dashboard-table' style='max-width:700px;margin:2rem auto;'>
        <tr>
            <th colspan='2' style='font-size:1.5rem;'>Library</th>
        </tr>
        <tr>
            <td><strong>Details:</strong></td>
            <td>Explore a world of knowledge to no cost of you</td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td>2026-02-16T06:30</td>
        </tr>
        <tr>
            <td><strong>Lecturer:</strong></td>
            <td>Kimmberly Lockwood</td>
        </tr>
        <tr>
            <td><strong>Location:</strong></td>
            <td>CEH-0R-01</td>
        </tr>
        <tr>
            <td colspan='2' style='text-align:center;'>
                <img src='' alt='no available image' style='width: 100%; max-width: 600px;'>
            </td>
        </tr>
        <tr>
            <td colspan='2' style='text-align:center;'>
                <?php
                if ($role === 'admin' || $role == 1) {
                    echo "<a href='../home_pages/home_admin.php?role=$role'>Back to Home</a>";
                } else if ($role === 'faculty' || $role == 2) {
                    echo "<a href='../home_pages/hpfinance.php?role=$role'>Back to Home</a>";
                } else if ($role === 'student' || $role == 3) {
                    echo "<a href='../home_pages/hpstudent.php?role=$role'>Back to Home</a>";
                } else {
                    echo "<a href='../index.php'>Back to Home</a>";
                }
                ?>
            </td>
        </tr>
    </table>
</body>
</html>
