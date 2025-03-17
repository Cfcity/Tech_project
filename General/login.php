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
    <title>Login - Ezily</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/General.css">
</head>


<body>
    <div class="login_background_left"></div>
    <div class="login_background_right"></div>


    <div class="login_center">
        <div class="containerlogin">
            <div class="centerleft" style=" left:0%; border-top-left-radius:12px; border-bottom-left-radius:12px;  background-color: purple; height: 90vh; width: 44.5%; margin:0%; padding: 0%; border: 0%; transform:translate(0%, -50.07%);">
                <div class="center">
                    <form action="" method="POST">
                        <input type="text" id="user" name="username" placeholder="Username" value="<?php echo $username; ?>" required><br>
                        <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required><br><br>
                        <button type="submit" name="login_user">Login</button><br>
                        <p style="color: rgba(204, 183, 183, 0.267) ">Not a member? <button id="altbutton" onclick="signup()">sign up</button></p>
                        <p>Forgot password? <a href="forgotpassword.php">Reset password</a></p>
                    </form>
                </div>
            </div>
            <div class="login_right">
                <h1 class="centertop" style="top:15%; color:white; ">
                    welcome to Ezily
                </h1>
                <img src="../images/login.gif" alt="Relaxing image for login and signup" style="width: 18vw;  top: 37.3%; left:64%; position: relative; border-radius: 12px;">
            </div>       
         </div>
    </div>

<script src="../source.js"></script>
</body>