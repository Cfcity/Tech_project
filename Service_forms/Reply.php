<?php
include('../General/test.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .center {
            text-align: center;
        }

        .submit-btn {
            display: block;
            width: 100px;
            margin: 20px auto;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php
    // Retrieve the replyto value from the GET parameters
    if (isset($_GET['replyto'])) {
        $replyto = htmlspecialchars($_GET['replyto']);

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
    ?>
    <table>
        <tr>
            <th>From</th>
            <td><?php echo htmlspecialchars($studentName); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($email); ?></td>
        </tr>
        <tr>
            <th>Issue</th>
            <td><?php echo htmlspecialchars($issue); ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?php echo htmlspecialchars($description); ?></td>
        </tr>
        <tr>
            <th>Date</th>
            <td><?php echo htmlspecialchars($date); ?></td>
        </tr>
    </table>

    <form action="../Service_forms/data.php" method="post">
        <table>
            <tr>
                <th>Reply</th>
                <input type="hidden" name="replyto" id="replyto" value="<?php echo htmlspecialchars($replyto); ?>">
                <td><textarea name="reply" id="reply" cols="30" rows="10" style="width: 100%;"></textarea></td>
            </tr>
            <tr>
                <td colspan="2" class="center">
                    <button class="buttonc" type="submit" name="Send">Submit</button>
                </td>
            </tr>
        </table>
    </form>
    

    

</body>

</html>