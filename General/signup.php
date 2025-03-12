<?php include('../General/test.php') ?>

<!DOCTYPE html>
<html lang="en">
<style>
    input {
        width: 100%;
        padding: 3px 10px;
        margin: 3px 0;
        border-radius: 6px;
        -webkit-transition: 0.7s;
        transition: 0.7s;
        outline: none;
        
    }

    input:focus {
        border: 3px solid coral;
    }
</style>

<head>
    <title>Sign up - Ezily</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/General.css">
</head>


<body style="color: white;">
    <div class="login_background_left"></div>
    <div class="login_background_right"></div>


    <div class="login_center">
        <div class="containerlogin">
            <div class="centerleft" style=" left:0%; border-top-left-radius:12px; border-bottom-left-radius:12px;  background-color: purple; height: 90vh; width: 44.5%; margin:0%; padding: 0%; border: 0%; transform:translate(0%, -50.07%);">
                <div class="center">
                    <form method="post" action="" autocomplete="off">
                        <input type="text" id="user" name="username" placeholder="Username" value="<?php echo $username; ?>" required><br>
                        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required><br>
                        <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required><br>
                        <input type="password" id="conpassword" name="conpassword" placeholder="Confirm Password" value="<?php echo $conpassword; ?>" required><br>

                        <br><br><button type="submit" name="reg_user">Sign up</button><br>
                        <!-- 
                        <button style="background-color:coral; " class="input" type="submit" name="Student" class="button">Student</button>
                        <button style="background-color:coral; " class="input" type="submit" name="Staff" class="button">Staff</button>
-->
                        <p style="color: rgba(204, 183, 183, 0.267)">Already a member? <a id="altbutton" href="login.php">Sign in</a></p>
                    </form>
                </div>
            </div>
            <div class="login_right">
                <h1 class="centertop" style="top:15%; ">
                    welcome to Ezily
                </h1>
                <img src="../images/login.gif" alt="Relaxing image for login and signup" style="width: 18vw;  top: 37.3%; left:64%; position: relative; border-radius: 12px;">
            </div>
        </div>
    </div>

</body>