<?php include('../General/test.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ezily</title>
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="auth-page">
    <div class="auth-background left-bg"></div>
    <div class="auth-background right-bg"></div>

    <div class="auth-container">
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                <form class="auth-form" action="" method="POST">
                    <h2 class="auth-title">Welcome Back</h2>
                    
                    <div class="form-group">
                        <input type="text" id="user" name="username" placeholder="Username" value="<?php echo $username; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required>
                    </div>
                    
                    <button type="submit" name="login_user" class="btn btn-primary btn-block">Login</button>
                    
                    <div class="auth-links">
                        <p>Not a member? <a href="../General/signup.php">Sign up</a></p>
                        <p>Forgot password? <a href="forgotpassword.php">Reset password</a></p>
                    </div>
                </form>
            </div>
            
            <div class="auth-graphic">
                <h1 class="welcome-title">Welcome to Ezily</h1>
                <img src="../images/login.gif" alt="Relaxing image for login" class="welcome-image">
            </div>       
        </div>
    </div>

    <script src="../source.js"></script>
</body>
</html>