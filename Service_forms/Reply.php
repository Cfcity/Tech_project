<?php
include('../General/test.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Inquiry</title>
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="dashboard-container">
    <div class="main-content">
        <div class="content-card" style="max-width: 600px; margin: 3rem auto;">
            <?php
            // Retrieve the replyto value from the GET parameters
            if (isset($_GET['replyto'])) {
                $replyto = htmlspecialchars($_GET['replyto']);

                // Ensure the database connection is open
                $db = mysqli_connect('localhost', 'root', '', 'tech_db');
                if (!$db) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Query to join the tables and fetch the required information
                $query = "
                    SELECT inquiry.Inq_ID, inquiry.created_at, inquiry.issue, inquiry.description, students.Stu_fname, students.Stu_lname, user.email
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
                    $created_at = $row['created_at'];
                } else {
                    echo "<div class='error-message center-text'>No results found.</div>";
                    $studentName = '';
                    $email = '';
                    $issue = '';
                    $description = '';
                    $created_at = '';
                }

                mysqli_close($db);
            } else {
                echo "<div class='error-message center-text'>No Inquiry ID provided.</div>";
                $studentName = '';
                $email = '';
                $issue = '';
                $description = '';
                $created_at = '';
            }
            ?>
            <table class="dashboard-table">
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
                    <td><?php echo htmlspecialchars($created_at); ?></td>
                </tr>
            </table>

            <form action="" method="post">
                <table class="dashboard-table">
                    <tr>
                        <th>Reply</th>
                        <td>
                            <input type="hidden" name="replyto" id="replyto" value="<?php echo htmlspecialchars($replyto); ?>" class="input-field">
                            <textarea name="reply" id="reply" cols="30" rows="6" class="input-field" required></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="center-text">
                            <button class="primary-button" type="submit" name="Send">Submit</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>