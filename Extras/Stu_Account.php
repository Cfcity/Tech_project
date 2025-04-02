<?php

include ('../General/test.php');

$user = $_SESSION['Id'];

$db = mysqli_connect('localhost', 'root', '', 'test');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch existing data for the student
$query = "
        SELECT user.username, user.email, user.bio, students.Stu_fname, students.Stu_lname
        FROM user 
        JOIN students ON user.Id = students.Id 
        WHERE user.Id = ?";
$stmt = mysqli_prepare($db, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $fname = $row['Stu_fname'];
        $lname = $row['Stu_lname'];
        $bio = $row['bio'];
    } else {
        echo "No results found.";
        $fname = '';
        $lname = '';
        $bio = '';
    }
} else {
    die("Failed to prepare query: " . mysqli_error($db));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fname = mysqli_real_escape_string($db, $_POST['fname']);
    $new_lname = mysqli_real_escape_string($db, $_POST['lname']);
    $new_bio = mysqli_real_escape_string($db, $_POST['bio']);

    // Update only if values have changed
    if ($new_fname !== $fname || $new_lname !== $lname || $new_bio !== $bio) {
        $update_query = "
            UPDATE students 
            SET Stu_fname = ?, Stu_lname = ? 
            WHERE Id = ?";

        $update_query = "
            UPDATE user 
            SET Bio = ?, username = ? 
            WHERE Id = ?";

        $update_stmt = mysqli_prepare($db, $update_query);

        if ($update_stmt) {
            mysqli_stmt_bind_param($update_stmt, 'sssi', $new_fname, $new_lname, $new_bio, $user);
            if (mysqli_stmt_execute($update_stmt)) {
                echo "Profile updated successfully.";
                // Update the local variables to reflect the changes
                $fname = $new_fname;
                $lname = $new_lname;
                $bio = $new_bio;
            } else {
                echo "Failed to update profile: " . mysqli_error($db);
            }
        } else {
            echo "Failed to prepare update query: " . mysqli_error($db);
        }
    } else {
        echo "No changes detected.";
    }
}

mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Account</title>
</head>

<body>

    <form method="POST" action="">
        <table border="1" width="100%" height="100%" style="text-align: center;">
            <tr>
                <td colspan="2">
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" >
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($fname); ?> readonly">
                </td>
                <td>
                    <input type="text" name="lname" id="lname" value="<?php echo htmlspecialchars($lname); ?> readonly">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea name="bio" id="bio"><?php echo htmlspecialchars($bio); ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Update</button>
                </td>
            </tr>
        </table>
    </form>

</body>

</html>