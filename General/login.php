<?php include ('../General/test.php') ?>

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
</head>


<body class="login_background">


<h1 style="color: coral; text-align: center; font-family:cursive;">Ezily</h1> 

<div class="login_center">
    <div class="containerlogin">
        <div class="center">
            <form action="" method="POST">
            <input type="text" id="user" name="username" placeholder="Username" value="<?php echo $username; ?>" required><br>
            <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required><br>
            <button class="buttonr" style="background-color: coral;" type="submit" name="login_user">Login</button>
            </form>
        </div>
    </div>
</div>

</body>