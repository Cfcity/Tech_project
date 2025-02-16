<?php include('../General/test.php') ?>

<!DOCTYPE html>
<html lang="en">
<style>
    input {
        width: 100%;
        padding: 3px 10px;
        margin: 3px 0;
        border-radius: 6px;
        border: 2px solid;
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
                        <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required><br>
                        <button class="buttonr" style="background-color: coral;" type="submit" name="login_user">Login</button><br>
                        <p style="display:block">Not a member? <a href="signup.php"> Sign up</a></p>
                        <p>Forgot password? <a href="forgotpassword.php">Reset password</a></p>
                    </form>
                </div>
            </div>
            <h1 class="login_right" style="color: purple; top:15%;  ">
                welcome to Ezily
            </h1>
            <img src="../images/login.gif" alt="Relaxing image for login and signup" class="login_right" style="width: 18vw;  top:61.8vh; left:83.5vw; ">
        </div>
    </div>

</body>