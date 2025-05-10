<?php include('../General/test.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - Ezily</title>
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="auth-page">
    <div class="auth-background left-bg"></div>
    <div class="auth-background right-bg"></div>

    <div class="auth-container">
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                <form class="auth-form" method="post" action="" autocomplete="off">
                    <h2 class="auth-title">Create Account</h2>
                    
                    <div class="form-group">
                        <input type="text" id="user" name="username" placeholder="Username" value="<?php echo $username; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="password" id="conpassword" name="conpassword" placeholder="Confirm Password" value="<?php echo $conpassword; ?>" required>
                    </div>
                    
                    <button type="submit" name="reg_user" class="btn btn-primary btn-block">Sign up</button>
                    
                    <div class="auth-links">
                        <p>Already a member? <a href="login.php" class="alt-link">Sign in</a></p>
                    </div>
                </form>
            </div>
            
            <div class="auth-graphic">
                <h1 class="welcome-title">Welcome to Ezily</h1>
                <img src="../images/login.gif" alt="Relaxing image for signup" class="welcome-image">
            </div>
        </div>
    </div>
</body>
</html>