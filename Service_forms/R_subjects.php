<?php include('data.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject inquirys</title>
    <link rel="stylesheet" href="../css/forms_styles.css">
</head>

<body class="query_r">

    <table border="1">
        <tr>
            <td colspan="2">
                <h2 style="text-align: center;">Subject Query</h2>
            </td>
        </tr>
        <form action="R_subjects.php" method="post">
            <input type="hidden" name="inq_type" value="Subject">
            <tr>
                <td id="label">
                    <label for="issue">Topic of issue:</label>
                </td>
                <td>
                    <select name="issue" id="issue" required>
                        <option value="">Select</option>
                        <option value="Elective selection">Elective selection</option>
                        <option value="Change of subject">Change of subject</option>
                        <option value="Timetable errors">Timetable errors</option>
                        <option value="Other">Other</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td id="label">
                    <label for="department">Department:</label>
                </td>
                <td>
                    <select name="department" id="department" required>
                        <option value="">Select</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Nursing">Nursing</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td id="label">
                    <label for="description">Description of issue:</label>
                </td>
                <td>
                    <textarea name="description" id="description" required></textarea>
                </td>
            </tr>

            <tr>
                <td id="label">
                    <label for="img">Insert any images:</label>
                </td>
                <td>
                    <input type="file" name="img" id="img">
                </td>
            </tr>

            <tr>
                <td colspan="2" align="center">
                    <button class="buttonr" type="submit" name="submit_query">Submit</button>
                </td>
            </tr>
        </form>
    </table>
</body>

</html>