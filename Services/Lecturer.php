<?php

include('../General/test.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <title>Document</title>
</head>

<body>
    <table class="dashboard-table">
        <tr height="10%">
            <td width="75%">Home</td>
            <td width="20%">Account</td>
        </tr>
        <tr>
           <th colspan="2">Lecture information</th> 
        </tr>
        <tr>
            <form action="" method="post">
                <label for="department">Select Department</label>
                <select name="department" id="department">
                    <option value="CSE">CSE</option>
                    <option value="EEE">EEE</option>
                    <option value="BBA">BBA</option>
                    <option value="ENG">ENG</option>
                    <option value="MATH">MATH</option>
                </select>
                <br>
                <input type="submit" name="department" value="Submit">
            </form>
        </tr>
        <tr>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $department = $_POST['department'];
                echo "<h2>Selected Department: $department</h2>";
                // Here you can add code to fetch and display the lecture information based on the selected department
            
                $query = "SELECT * FROM staff WHERE department = '$department'";
                $result = mysqli_query($db, $query);

                
            }
            ?>
        </tr>
    </table>
</body>

</html>