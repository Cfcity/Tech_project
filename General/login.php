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
    <link rel="stylesheet" href="../css/containers.css">
</head>


<body class="login_background">


    <h1 style="color: coral; text-align: center; font-family:cursive;">Ezily</h1>

    <div class="signup">
        <div class="login_center">
            <div class="containerlogin">
                <div class="centerleft" style=" left:0%; border-top-left-radius:12px; border-bottom-left-radius:12px;  background-color: purple; height: 90vh; width: 44.5%; margin:0%; padding: 0%; border: 0%; transform:translate(0%, -50.07%);">
                    <div class="center">
                        <form action="" method="POST">
                            <input type="text" id="user" name="username" placeholder="Username" value="<?php echo $username; ?>" required><br>
                            <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required><br>
                            <button class="buttonr" style="background-color: coral;" type="submit" name="login_user">Login</button><br>
                            <p style="display:block">Not a member? <a href="signup.php">Sign up</a></p>
                            <p>Forgot password? <a href="forgotpassword.php">Reset password</a></p>
                        </form>
                    </div>
                </div>
                <div class="centerright" style="right:0%;  border-top-right-radius:12px; border-bottom-right-radius:12px; background-color: coral; height: 90vh; width: 55.5%; margin:0%; padding: 0%; border: 0%; transform:translate(-97.3%, -50.07%);">
                    <div class="center">
                        <h1 style="color: purple; font-family:cursive;">Welcome to Ezily</h1>
                        <p style="color: purple; font-family:cursive;">Ezily is a platform that helps you to manage your tasks and time efficiently. It is a simple and easy to use platform that helps you to keep track of your tasks and time. It is a platform that helps you to manage your tasks and time efficiently. It is a simple and easy to use platform that helps you to keep track of your tasks and time.</p>
                        <img src="" alt="">
                    </div>
            </div>
        </div>

</body>