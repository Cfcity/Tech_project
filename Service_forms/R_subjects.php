<?php include('data.php'); 
    include('../General/test.php')

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject inquirys</title>
    <link rel="stylesheet" href="../css/forms_styles.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="query_r">
<div class="centertop header">
    <a class="centerleft" href="../home_pages/hpstudent.php"><button style="height: 7vh; width: 10vw;" class="buttonr tablinks">Home</button></a>
    <div class="center">
    <h2 style="text-align: center;">Subject Queries</h2>
    </div>
  </div>

    <table style="position: absolute; left:0%; top:15%;" border="1">
        <form action="" method="post">
            <input type="hidden" name="studentId" value="<?php echo htmlspecialchars(getRoleSessionData('studentid', '')) ?>">
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
                    <button class="buttonr" type="submit" name="s_submit_query" >Submit</button>
                </td>
            </tr>
        </form>
    </table>
</body>

</html>