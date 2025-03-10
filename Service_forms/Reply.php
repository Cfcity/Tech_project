<?php
include('../General/test.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    // Retrieve the replyto value from the GET parameters
    if (isset($_GET['replyto'])) {
        $replyto = htmlspecialchars($_GET['replyto']);
        echo "<h1>Reply to Inquiry ID: $replyto</h1>";

        // Ensure the database connection is open
        $db = mysqli_connect('localhost', 'root', '', 'test');
        if (!$db) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Query to join the tables and fetch the required information
        $query = "
            SELECT inquiry.Inq_ID, inquiry.date, inquiry.issue, inquiry.description, students.Stu_fname, students.Stu_lname, user.email
            FROM inquiry
            JOIN students ON inquiry.StudentId = students.StudentId
            JOIN user ON students.Id = user.Id
            WHERE inquiry.Inq_ID = $replyto;
        ";
        

        $result = mysqli_query($db, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $studentName = $row['Stu_fname'] . ' ' . $row['Stu_lname'];
            $email = $row['email'];
            $issue = $row['issue'];
            $description = $row['description'];
            $date = $row['date'];
        } else {
            echo "No results found.";
            $studentName = '';
            $email = '';
            $issue = '';
            $description = '';
            $date = '';
        }

        mysqli_close($db);
    } else {
        echo "No Inquiry ID provided.";
        $studentName = '';
        $email = '';
        $issue = '';
        $description = '';
        $date = '';
    }
    // Close the database connection
    
    ?>
    <h1>Reply</h1>
    <h3>From: <?php echo htmlspecialchars($studentName); ?></h3>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Issue: <?php echo htmlspecialchars($issue); ?></p>
    <p>Description: <?php echo htmlspecialchars($description); ?></p>
    <p>Date: <?php echo htmlspecialchars($date); ?></p>

    <form action="mailto:<?php echo htmlspecialchars($email); ?>" method="post">
        <label for="reply">Reply:</label><br>
        <textarea id="reply" name="reply" rows="4" cols="50"></textarea><br><br>
        <input type="submit" value="Submit">
    </form>

</body>

</html>