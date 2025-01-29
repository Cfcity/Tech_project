<?php include('../General/test.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign up - Ezily</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Styling for the form */
        input, select {
            width: 100%;
            padding: 3px 10px;
            margin: 3px 0;
            border-radius: 6px;
            border: 2px solid;
            transition: 0.7s;
            outline: none;
        }

        .input {
            width: 100%;
            padding: 3px 10px;
            margin: 3px 0;
            border-radius: 6px;
            border: 1px solid;
        }

        input:focus {
            border: 3px solid coral;
        }
    </style>
</head>

<body style="height: 100vh; background-image: linear-gradient(to bottom, rgba(110,110,110), rgb(63, 63, 63));">

    <h1 style="color: coral; text-align: center; font-family: cursive;">Ezily</h1>

    <div class="centerbottom">
        <div class="containerlogin">
            <h1 class="centertop">Sign up</h1>
            <div class="center">
                <form method="post" action="" autocomplete="on">
                    <input type="text" id="user" name="username" placeholder="Username" value="<?php echo $username; ?>" required><br>
                    <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required><br>
                    <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required><br>
                    <input type="password" id="conpassword" name="conpassword" placeholder="Confirm Password" value="<?php echo $conpassword; ?>" required><br>
                    <select name="role" id="role" required>
                        <option value="<?php echo $role = 3 ?>">Select position</option>
                        <option value="<?php echo $role = 0 ?>">Admin</option>
                        <option value="<?php echo $role = 1 ?>">Faculty member</option>
                        <option value="<?php echo $role = 2 ?>">Student</option>
                    </select>
                    <button style="background-color:coral; " class="input" type="submit" name="reg_user" class="button">Submit</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>