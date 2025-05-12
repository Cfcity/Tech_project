<?php include('../General/test.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign up - C.A.S.H</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        body {
            color: white;
            background: url('../images/nightsky.png') repeat center center fixed;
            min-height: 100vh;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #b0b3b8;
            background-color: rgba(255,255,255,0.08);
            color: white;
            font-size: 1rem;
            transition: 0.3s;
            outline: none;
        }
        input:focus {
            border: 2px solid coral;
            background-color: rgba(255,255,255,0.18);
        }
        .signup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100vw;
            position: relative;
            z-index: 2;
        }
        .signup-card {
            background: rgba(30, 30, 60, 0.97);
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
            padding: 2.5rem 2rem 2rem 2rem;
            width: 370px;
            min-height: 420px;
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
        .signup-image-bottom {
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
            .signup-container {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }
            .signup-card {
                width: 95vw;
                min-width: unset;
                max-width: 400px;
            }
            .signup-image-bottom {
                width: 90vw;
                max-width: 340px;
            }
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <div class="signup-card">
            <h2>Sign Up</h2>
            <form method="post" action="" autocomplete="off" class="signup-form">
                <input type="text" id="user" name="username" placeholder="Username" value="<?php echo $username; ?>" required>
                <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required>
                <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required>
                <input type="password" id="conpassword" name="conpassword" placeholder="Confirm Password" value="<?php echo $conpassword; ?>" required>
                <button type="submit" name="reg_user" class="auth-button">Sign up</button>
                <p style="color: rgba(204, 183, 183, 0.267); margin-top: 1rem;">
                    Already a member? <a class="auth-link" href="login.php">Sign in</a>
                </p>
            </form>
        </div>
    </div>
    <!-- Signup Image at the Center Bottom -->
    <img src="../images/login.gif" alt="Relaxing image for login and signup" class="signup-image-bottom">
</body>
</html>