<?php include('data.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanncial inquirys</title>
    <link rel="stylesheet" href="../css/forms_styles.css">
</head>

<body class="query_r">

    <table border="1">
        <tr>
            <td colspan="2">
                <h2 style="text-align: center;">Financial Queries</h2>
            </td>
        </tr>
        <form action="R_subjects.php" method="post">
            <input type="hidden" name="inq_type" value="Finance">
            <tr>
                <td id="label">
                    <label for="issue">Topic of issue:</label>
                </td>
                <td>
                    <select name="issue" id="issue" required>
                        <option value="">Select</option>
                        <option value="Request for transcript">Transcript request</option>
                        <option value="Tuition Fees">Tuition Fees</option>
                        <option value="Overdues">Overdue Fees</option>
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
                    <button class="buttonr" type="submit" name="submit_query" >Submit</button>
                </td>
            </tr>
        </form>
    </table>
</body>

</html>