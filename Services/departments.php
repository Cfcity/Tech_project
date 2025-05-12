<?php

include('../General/test.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Information</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body class="dashboard-container">
    <!-- Side Navigation -->
    <div class="side-nav">
        <button class="side-nav-button" onclick="window.location.href='../home_pages/hpfinance.php'">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </button>
    </div>

    <!-- Main Content -->
    <div class="main-content" style="max-width: 800px; margin: 0 auto;">
        <h1 class="center-text">Department Information</h1>
        
        <!-- Department Selection Form -->
        <form action="" method="post" class="department-form" style="margin-bottom: 2rem; text-align: center;">
            <label for="department" style="font-weight: bold;">Select Department:</label>
            <select name="department" id="department" required class="input" style="margin: 0 10px;">
                <option value="" disabled <?= empty($department) ? 'selected' : '' ?>>-- Select Department --</option>
                <?php
                $query = "SELECT * FROM department";
                $result = mysqli_query($db, $query);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $dept = htmlspecialchars($row['dept_name']);
                        echo "<option value='$dept' " . ($department == $dept ? 'selected' : '') . ">$dept</option>";
                    }
                } else {
                    echo "<option value=''>Error fetching departments</option>";
                }
                ?>
            </select>
            <button type="submit" name="department_submit" class="primary-button">View Department</button>
        </form>

        <!-- Display Selected Department -->
        <?php if (!empty($department)): ?>
            <h2 class="center-text" style="color: #2a7ae4;"><?= htmlspecialchars($department) ?> Department Information</h2>
        <?php endif; ?>

        <!-- Error Message --> 
        <?php if (!empty($error)): ?>
            <div class="error-message center-text"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Department Information -->
        <div style="margin-top: 2rem;">
        <?php
        if (isset($_POST['department_submit'])) {
            $department = mysqli_real_escape_string($db, $_POST['department']);
            $query = "SELECT * FROM department WHERE dept_name = '$department'";
            $result = mysqli_query($db, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table class='data-table' style='margin: 0 auto;'>";
                echo "<tr>
                        <th>Department Name</th>
                        <th>Department Email</th>
                        <th>Department Office Number</th>
                        <th>Department Location</th>
                      </tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['dept_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dept_email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dept_phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dept_location']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='center-text'>No information found for the selected department.</p>";
            }
        }
        ?>
        </div>
    </div>
</body>
</html>