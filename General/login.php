<?php include('../General/test.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Ezily</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        body {
            color: white;
            background: url('../images/nightsky.png') repeat center center fixed;
            min-height: 100vh;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3vw;
            min-height: 100vh;
            width: 100vw;
            position: relative;
        }
        .login-card {
            background: rgba(30, 30, 60, 0.97);
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
            padding: 2.5rem 2rem 2rem 2rem;
            width: 370px;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }
        .login-card h2 {
            margin-bottom: 1.5rem;
            color: #ffb366;
            font-size: 2rem;
            letter-spacing: 1px;
        }
        .login-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #b0b3b8;
            background-color: rgba(255, 255, 255, 0.08);
            color: white;
            font-size: 1rem;
            transition: 0.3s;
        }
        .login-form input:focus {
            border: 2px solid coral;
            outline: none;
            background-color: rgba(255, 255, 255, 0.18);
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
        .login-image-bottom {
            width: 320px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.18);
            display: block;
            position: fixed;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            z-index: 1;
            pointer-events: none;
        }
        @media (max-width: 900px) {
            .login-container {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }
            .login-card {
                width: 95vw;
                min-width: unset;
                max-width: 400px;
            }
            .login-image-bottom {
                width: 90vw;
                max-width: 340px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Login</h2>
            <form action="" method="POST" class="login-form">
                <input type="text" id="user" name="username" placeholder="Username" value="<?php echo $username; ?>" required>
                <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required>
                <button type="submit" name="login_user" class="auth-button">Login</button>
                <p style="color: rgba(204, 183, 183, 0.7); margin-top: 1rem;">
                    Not a member? <a href="signup.php" class="auth-link">Sign up</a>
                </p>
                <p style="margin-top: 0.5rem;">
                    Forgot password? <a href="forgotpassword.php" class="auth-link">Reset password</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Login Image at the Center Bottom -->
    <img src="../images/login.gif" alt="Relaxing image for login and signup" class="login-image-bottom">

    <script src="../source.js"></script>
</body>
</html>