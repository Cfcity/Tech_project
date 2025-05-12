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
    $query = "SELECT staff.Staffid, staff.f_name, staff.l_name, department.dept_name, 
                     user.email, user.bio 
              FROM staff 
              JOIN department ON staff.dept_id = department.dept_id
              JOIN user ON staff.Id = user.Id 
              WHERE dept_name = ?";
              
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
    <div class="main-content">
        <h1 class="center-text">Department Lecturers</h1>
        
        <!-- Department Selection Form in a table -->
        <form action="" method="post" class="department-form" style="margin-bottom: 2rem;">
            <table class="dashboard-table">
                <tr>
                    <td colspan="2" class="center-text" style="font-weight: bold;">
                        <label for="department">Select Department</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <select name="department" id="department" required class="input-field">
                            <option value="" disabled <?= empty($department) ? 'selected' : '' ?>>-- Select Department --</option>
                            <?php
                            $query = "SELECT DISTINCT dept_name FROM department";
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
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center-text">
                        <button type="submit" name="department_submit" class="primary-button">View Lecturers</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center-text">
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
        </form>

        <!-- Display Selected Department -->
        <?php if (!empty($department)): ?>
            <h2 class="center-text" style="color: #2a7ae4;"><?= htmlspecialchars($department) ?> Department Lecturers</h2>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="error-message center-text"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Lecturers List in a table -->
        <?php if (!empty($lecturers)): ?>
            <table class="dashboard-table" style="margin-top:2rem;">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>About</th>
                </tr>
                <?php foreach ($lecturers as $lecturer): ?>
                    <tr>
                        <td><?= htmlspecialchars($lecturer['f_name'] . ' ' . $lecturer['l_name']) ?></td>
                        <td>
                            <a href="mailto:<?= htmlspecialchars($lecturer['email']) ?>">
                                <?= htmlspecialchars($lecturer['email']) ?>
                            </a>
                        </td>
                        <td>
                            <?php if (!empty($lecturer['bio'])): ?>
                                <?= htmlspecialchars($lecturer['bio']) ?>
                            <?php else: ?>
                                <span style="color:#aaa;">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
    <script src="../source.js"></script>
</body>
</html>