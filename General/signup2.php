<?php include('../General/test.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - Ezily</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/General.css">

    <!-- Inline Styles -->
    <style>
        /* Button styles for signup selection */
        .signupselect {
            position: relative;
            z-index: 1;
            transition: opacity 0.5s ease;
        }

        /* Form styles for signup */
        .signupform {
            position: relative;
            left: 0%;
            top: 50%;
            text-align: center;
            width: 50%;
            height: 70%;
            background-color: inherit;
            backdrop-filter: blur(10px);
            z-index: 0;
            opacity: 0;
            transition: opacity 0.5s ease, z-index 0.5s ease;
            border-radius: 12px;
        }

        /* Active form styles */
        .signupform.active {
            z-index: 2;
            opacity: 1;
        }
    </style>
</head>

<body style="color: white;">

    <!-- Background Sections -->
    <div class="login_background_left"></div>
    <div class="login_background_right"></div>

    <!-- Main Signup Section -->
    <div class="login_center">
        <div class="containerlogin">

            <!-- Faculty Signup Section -->
            <div class="centerleft" style="left:0%; border-top-left-radius:12px; border-bottom-left-radius:12px; background-color: purple; height: 90vh; width: 44.5%; margin:0%; padding: 0%; border: 0%; transform:translate(0%, -50.07%);">
                <h1 class="centertop" style="top:7%;">Faculty</h1>
                <button class="center signupselect" style="background-color: rgba(99, 32, 99, 0.8);" onclick="showForm('facultyForm')">Sign up</button>
                <div id="facultyForm" class="center signupform">

                    <!-- Faculty Signup Form -->
                    <form method="post" action="../General/test.php" style="position: center;">
                        <input type="hidden" name="role" value="2">

                        <input type="text" name="f_name" placeholder="First Name" required>

                        <input type="text" name="l_name" placeholder="Last Name" required>

                        <textarea name="bio" placeholder="Bio..."></textarea>

                        <select name="department" required>
                            <option value="">Select Department</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Nursing">Nursing</option>
                            <option value="Finance">Finance</option>
                        </select>
                        <button type="submit" name="signup_faculty" class="btn">Sign up</button>
                    </form>
                </div>
            </div>

            <!-- Student Signup Section -->
            <div class="login_right">
                <h1 class="centertop" style="top: 7%;">Student</h1>
                <button class="center signupselect" style="background-color: rgba(32, 32, 32, 0.8);" onclick="showForm('studentForm')">
                    <img class="center" src="../images/pixil-frame-0 (2).png" alt="" style="height: 100%;">
                </button>
                <div id="studentForm" class="center signupform">

                    <!-- Student Signup Form -->
                    <form method="post" action="../General/test.php" style="position: center;">
                        <input type="hidden" name="role" value="3">
                        <input type="text" name="Stu_fname" placeholder="First Name" required>
                        <input type="text" name="Stu_lname" placeholder="Last Name" required>
                        <textarea name="Stu_bio" placeholder="Bio..."></textarea>
                        <select name="Stu_department" required>
                            <option value="0">Select Department</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Nursing">Nursing</option>
                            <option value="Finance">Finance</option>
                        </select>
                        <button type="submit" name="signup_student">Sign up</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript Section -->
    <script>
        // Function to show the selected form
        function showForm(formId) {
            document.getElementById(formId).classList.add('active');
        }
    </script>
</body>

</html>