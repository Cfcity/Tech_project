<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ezily</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body style="background-color: rgb(63,63,63); height: 100vh;">
    <table>
        <tr>
            <td>
                <div class="sideheader">
                    <div class="center">
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'finhome')" id="defaultOpen">Home</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'finqueries')">Inquries</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'finlibrary')">Querys</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'finservices')">Services</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="events()">Events</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'finabout')">About</button>
                    </div>
                </div>
            </td>

            <!-- Content / Tabs ------------------------------------------------------------------------------------------------ -->
            <td>
                <div id="finqueries" class="tabcontent">
                    <?php include '../Service_forms/Inquires_fin.php'; ?>
                </div>
                <div id="finhome" class="tabcontent"></div>
                <div id="finlibrary" class="tabcontent"></div>
                <div id="finservices" class="tabcontent"></div>
                <div id="finabout" class="tabcontent"></div>
            </td>
        </tr>
    </table>
</body>

<script src="../source.js"></script>

</html>