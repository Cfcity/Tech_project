<?php include('../General/test.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Sign up - Ezily</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        body {
            color: white;
            background: url('../images/nightsky.png') repeat center center fixed;
            min-height: 100vh;
        }
        .signup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3vw;
            margin: 4vw auto;
            max-width: 1200px;
        }
        .signup-card {
            background: rgba(30, 30, 60, 0.97);
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
            padding: 2.5rem 2rem 2rem 2rem;
            width: 370px;
            min-height: 520px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .signup-card h2 {
            margin-bottom: 1.5rem;
            color: #ffb366;
            font-size: 2rem;
            letter-spacing: 1px;
        }
        .signup-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .signup-form input,
        .signup-form select,
        .signup-form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #b0b3b8;
            background-color: rgba(255, 255, 255, 0.08);
            color: white;
            font-size: 1rem;
            transition: 0.3s;
        }
        .signup-form option {
            color: black;
        }
        .signup-form input:focus,
        .signup-form select:focus,
        .signup-form textarea:focus {
            border: 2px solid coral;
            outline: none;
            background-color: rgba(255, 255, 255, 0.18);
        }
        .signup-form textarea {
            min-height: 90px;
            resize: vertical;
        }
        .auth-button {
            background-color: coral;
            color: white;
            border: none;
            padding: 12px 0;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .auth-button:hover {
            background-color: #ff6b4a;
        }
        .auth-link {
            color: coral;
            text-decoration: none;
        }
        .auth-link:hover {
            text-decoration: underline;
        }
        .signup-image {
            width: 320px;
            border-radius: 12px;
            margin: 0 2vw;
            box-shadow: 0 2px 16px rgba(0,0,0,0.18);
            align-self: center;
        }
        @media (max-width: 1100px) {
            .signup-container {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }
            .signup-image {
                margin: 2vw auto 0 auto;
                display: block;
                width: 90vw;
                max-width: 340px;
                
            }
        }
    </style>
</head>

<body>
    <?php
    // Get user ID from various possible sources
    $user_id = getRoleSessionData('Id') ? getRoleSessionData('Id') : 
              (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 
              (isset($_GET['user_id']) ? $_GET['user_id'] : null));

    if (!$user_id) {
        echo "<p style='color: coral; text-align: center; margin-top: 2rem;'>Error: User not found. Please <a href='signup.php' class='auth-link'>register</a> first.</p>";
        exit();
    }

    // Handle faculty signup
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_faculty'])) {
        $f_name = mysqli_real_escape_string($db, $_POST['f_name']);
        $l_name = mysqli_real_escape_string($db, $_POST['l_name']);
        $bio = mysqli_real_escape_string($db, $_POST['bio']);
        $dept_id = mysqli_real_escape_string($db, $_POST['dept_id']);

        $qs = "INSERT INTO staff (Id, f_name, l_name, bio, dept_id) VALUES ('$user_id', '$f_name', '$l_name', '$bio', '$dept_id')";
        if (mysqli_query($db, $qs)) {
            echo "<p style='color: lightgreen; text-align: center;'>Faculty registration successful!</p>";
        } else {
            echo "<p style='color: coral; text-align: center;'>Error: " . mysqli_error($db) . "</p>";
        }
    }

    // Handle student signup
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_student'])) {
        $Stu_fname = mysqli_real_escape_string($db, $_POST['Stu_fname']);
        $Stu_lname = mysqli_real_escape_string($db, $_POST['Stu_lname']);
        $Stu_bio = mysqli_real_escape_string($db, $_POST['Stu_bio']);
        $Stu_dept_id = mysqli_real_escape_string($db, $_POST['Stu_dept_id']);

        // Update bio in user table
        $update_user = "UPDATE user SET bio = '$Stu_bio' WHERE Id = '$user_id'";
        mysqli_query($db, $update_user);

        // Insert into students table (no bio column here)
        $qs = "INSERT INTO students (Id, Stu_fname, Stu_lname, Stu_dept_id) VALUES ('$user_id', '$Stu_fname', '$Stu_lname', '$Stu_dept_id')";
        if (mysqli_query($db, $qs)) {
            echo "<p style='color: lightgreen; text-align: center;'>Student registration successful!</p>";
        } else {
            echo "<p style='color: coral; text-align: center;'>Error: " . mysqli_error($db) . "</p>";
        }
    }
    ?>

    <div class="signup-container">
        <!-- Faculty Signup Card -->
        <div class="signup-card">
            <h2>Faculty Registration</h2>
            <form method="post" action="" autocomplete="off" class="signup-form">
                <input type="hidden" name="role" value="2">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="text" name="f_name" placeholder="First Name" required>
                <input type="text" name="l_name" placeholder="Last Name" required>
                <textarea name="bio" placeholder="Bio..."></textarea>
                <select name="dept_id" required>
                    <option value="">Select Department</option>
                    <?php
                    $dept_result = mysqli_query($db, "SELECT dept_id, dept_name FROM department");
                    if ($dept_result) {
                        while ($dept_row = mysqli_fetch_assoc($dept_result)) {
                            echo '<option value="' . htmlspecialchars($dept_row['dept_id']) . '">' . htmlspecialchars($dept_row['dept_name']) . '</option>';
                        }
                    }
                    ?>
                </select>
                <button type="submit" name="signup_faculty" class="auth-button">Complete Faculty Registration</button>
            </form>
        </div>

        <!-- Signup Image in the Center -->
        <img src="../images/login.gif" alt="Relaxing image for login and signup" class="signup-image">

        <!-- Student Signup Card -->
        <div class="signup-card">
            <h2>Student Registration</h2>
            <form method="post" action="" autocomplete="off" class="signup-form">
                <input type="hidden" name="role" value="3">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="text" name="Stu_fname" placeholder="First Name" required>
                <input type="text" name="Stu_lname" placeholder="Last Name" required>
                <textarea name="Stu_bio" placeholder="Bio..."></textarea>
                <select name="Stu_dept_id" required>
                    <option value="">Select Department</option>
                    <?php
                    $dept_result = mysqli_query($db, "SELECT dept_id, dept_name FROM department");
                    if ($dept_result) {
                        while ($dept_row = mysqli_fetch_assoc($dept_result)) {
                            echo '<option value="' . htmlspecialchars($dept_row['dept_id']) . '">' . htmlspecialchars($dept_row['dept_name']) . '</option>';
                        }
                    }
                    ?>
                </select>
                <button type="submit" name="signup_student" class="auth-button">Complete Student Registration</button>
            </form>
        </div>
    </div>
</body>
</html>