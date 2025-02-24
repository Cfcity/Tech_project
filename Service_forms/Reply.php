<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form action="mailto: <?php echo $_GET['email']; ?>" method="post"> 
    <label for="reply">Reply:</label><br>
    <textarea id="reply" name="reply" rows="4" cols="50"></textarea><br><br>
    <input type="submit" value="Submit">
</form>

</body>
</html>