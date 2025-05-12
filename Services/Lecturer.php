<?php
include('../General/test.php');

// Set session variables for tracking
$_SESSION['roles'][$_SESSION['current_role']]['lastaccessed'] = "Lecturers";
$_SESSION['roles'][$_SESSION['current_role']]['lastaccurl'] = "../home_pages/Lecturer.php";

// Initialize variables
$department = '';
$lecturers = [];
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['department'])) {
    $department = mysqli_real_escape_string($db, $_POST['department']);
    
    // Fetch lecturers from the selected department
    $query = "SELECT staff.Staffid, staff.f_name, staff.l_name, staff.department, 
                     user.email, user.bio 
              FROM staff 
              JOIN user ON staff.Id = user.Id 
              WHERE department = ?";
              
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $department);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $lecturers = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = "No lecturers found in the $department department.";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Lecturers</title>
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
    <div class="main-content">
        <h1>Department Lecturers</h1>
        
        <!-- Department Selection Form -->
        <form action="" method="post" class="department-form">
            <label for="department">Select Department:</label>
            <select name="department" id="department" required>
                <option value="" disabled <?= empty($department) ? 'selected' : '' ?>>-- Select Department --</option>
                <?php

                $query = "SELECT DISTINCT department FROM staff where department != ''";
                $result = mysqli_query($db, $query);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $dept = htmlspecialchars($row['department']);
                        echo "<option value='$dept' " . ($department == $dept ? 'selected' : '') . ">$dept</option>";
                    }
                } else {
                    echo "<option value=''>Error fetching departments</option>";
                }
?>
            </select>
            <button type="submit" name="department_submit" class="primary-button">View Lecturers</button>
        </form>

        <!-- Display Selected Department -->
        <?php if (!empty($department)): ?>
            <h2><?= htmlspecialchars($department) ?> Department Lecturers</h2>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Lecturers List -->
        <div class="lecturers-container">
            <?php foreach ($lecturers as $lecturer): ?>
                <div class="lecturer-card">
                    <div class="lecturer-name">
                        <?= htmlspecialchars($lecturer['f_name'] . ' ' . $lecturer['l_name']) ?>
                    </div>
                    <div class="lecturer-dept">
                        Department: <?= htmlspecialchars($lecturer['department']) ?>
                    </div>
                    <div class="lecturer-email">
                        Email: <a href="mailto:<?= htmlspecialchars($lecturer['email']) ?>">
                            <?= htmlspecialchars($lecturer['email']) ?>
                        </a>
                    </div>
                    <?php if (!empty($lecturer['bio'])): ?>
                        <div class="lecturer-bio">
                            <strong>About:</strong> <?= htmlspecialchars($lecturer['bio']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="../source.js"></script>
</body>
</html>