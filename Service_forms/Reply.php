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
    // Ensure the StudentId is passed as a GET parameter
    if (isset($_GET['StudentId'])) {
        $studentId = $_GET['StudentId'];

        // Query to join the tables and fetch the required information
        $query = "
            SELECT students.Stu_fname, students.Stu_lname, users.email, inquiries.inq_type, inquiries.issue, inquiries.description, inquiries.date 
            FROM students 
            JOIN emails ON students.StudentId = emails.StudentId
            JOIN users ON emails.UserId = users.UserId
            JOIN inquiries ON students.StudentId = inquiries.StudentId
            WHERE students.StudentId = $studentId
        ";

        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $studentName = $row['Stu_fname'] . ' ' . $row['Stu_lname'];
            $email = $row['email'];
            $inq_type = $row['inq_type'];
            $issue = $row['issue'];
            $description = $row['description'];
            $date = $row['date'];
        } else {
            echo "No results found.";
            $studentName = '';
            $email = '';
            $inq_type = '';
            $issue = '';
            $description = '';
            $date = '';
        }
    } else {
        echo "StudentId not provided.";
        $studentName = '';
        $email = '';
        $inq_type = '';
        $issue = '';
        $description = '';
        $date = '';
    }
    ?>
    <h1>Reply</h1>
    <table border="1" width="100%">
        <tr>
            <td width="40%">
                <h3>From: <?php echo htmlspecialchars($studentName); ?></h3>
            </td>
            <td>
                <p>Email: <?php echo htmlspecialchars($email); ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Inquiry Type: <?php echo htmlspecialchars($inq_type); ?></p>
            </td>
            <td>
                <p>Issue: <?php echo htmlspecialchars($issue); ?></p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>Description: <?php echo htmlspecialchars($description); ?></p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>Date: <?php echo htmlspecialchars($date); ?></p>
            </td>
        </tr>


        <form action="mailto:<?php echo htmlspecialchars($email); ?>" method="post">
            <tr>
                <td colspan="2"><label for="reply">Reply:</label><br>
                    <textarea style="width:fit-content;" id="reply" name="reply" rows="4" cols="50"></textarea><br><br>
                </td>
                </tr>
                <tr>
                <td colspan="2"><input type="submit" value="Submit"></td>
            </tr>
        </form>

    </table>
</body>

</html>