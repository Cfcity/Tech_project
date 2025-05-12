<?php

include('../General/test.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <title>View Users</title>
</head>

<body class="dashboard-container" style="text-align: center;">
    <div class="main-content">
        <div class="content-card">
            <div class="center-text">
                <h1>Users</h1>
            </div>
            <div class="user-section">
                <h2>Staff</h2>
                <table class="dashboard-table">
                    <tr>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Bio</th>
                        <th>Department</th>
                        <th>Options</th>
                    </tr>
                    <?php
                    // Staff: JOIN user and staff tables
                    $query = "SELECT user.Id, user.username, staff.f_name, staff.l_name, user.email, user.bio, department.dept_name
                        FROM user
                        INNER JOIN staff ON user.Id = staff.Id
                        INNER JOIN department ON staff.dept_id = department.dept_id
                        WHERE user.role = 2 AND user.username != 'Admin'";
                    $result = mysqli_query($db, $query);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['f_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['l_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['bio']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['dept_name']) . "</td>";
                            echo "<td>
                                <a href='edit_user.php?id=" . urlencode($row['Id']) . "&role=staff' class='primary-button' style='padding:4px 10px;font-size:0.95rem;'>Edit</a>
                                <a href='delete_user.php?id=" . urlencode($row['Id']) . "&role=staff' class='primary-button' style='background:#ef4444;padding:4px 10px;font-size:0.95rem;' onclick=\"return confirm('Are you sure you want to delete this staff user?');\">Delete</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Error fetching staff details.</td></tr>";
                    }
                    ?>
                </table>
            </div>
            <div class="user-section">
                <h2>Students</h2>
                <table class="dashboard-table">
                    <tr>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Bio</th>
                        <th>Department</th>
                        <th>Options</th>
                    </tr>
                    <?php
                    // Students: JOIN user, students, and department tables
                    $query = "SELECT user.Id, user.username, students.Stu_fname, students.Stu_lname, user.email, user.bio, department.dept_name
                        FROM user
                        INNER JOIN students ON user.Id = students.Id
                        INNER JOIN department ON students.Stu_dept_id = department.dept_id
                        WHERE user.role = 3 AND user.username != 'Admin'";
                    $result = mysqli_query($db, $query);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Stu_fname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Stu_lname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['bio']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['dept_name']) . "</td>";
                            echo "<td>
                                <a href='edit_user.php?id=" . urlencode($row['Id']) . "&role=student' class='primary-button' style='padding:4px 10px;font-size:0.95rem;'>Edit</a>
                                <a href='delete_user.php?id=" . urlencode($row['Id']) . "&role=student' class='primary-button' style='background:#ef4444;padding:4px 10px;font-size:0.95rem;' onclick=\"return confirm('Are you sure you want to delete this student user?');\">Delete</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Error fetching student details.</td></tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="7">
                            <a href="../home_pages/home_admin.php?role=<?php echo $role ?>" class="primary-button">Home</a>

                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>