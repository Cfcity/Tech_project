<?php

include('../General/test.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/form_styles.css">
    <title>Add Service</title>
    <style>
        body {
            background-color: rgb(63, 63, 63);
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            margin: 5% auto;
            width: 60%;
            border-collapse: collapse;
            background-color: rgb(48, 48, 48);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            margin: 0;
            padding: 20px;
            font-size: 24px;
            color: coral;
        }

        td {
            padding: 15px;
        }

        td:first-child {
            text-align: left;
            font-weight: bold;
            width: 25%;
        }

        td:last-child {
            text-align: left;
        }

        input[type="text"],
        input[type="datetime-local"],
        input[type="file"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: rgb(63, 63, 63);
            color: white;
        }

        input[type="submit"] {
            background-color: coral;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: rgb(255, 127, 80);
        }
    </style>
</head>
<body>
    
<table>
    <tr>
        <td colspan="2" style="text-align: center;">
            <h1>Add Service</h1>
        </td>
    </tr>
    <form action="" method="post" enctype="multipart/form-data">
        <tr>
            <td>Service Name:</td>
            <td><input type="text" id="ser_name" name="ser_name" placeholder="Service Name" required></td>
        </tr>
        <tr>
            <td>Service Description:</td>
            <td><input type="text" id="ser_details" name="ser_details" placeholder="Service Description" required></td>
        </tr>
        <tr>
            <td>Service Date:</td>
            <td><input type="datetime-local" name="ser_date" id="" placeholder="Service Date" required></td>
        </tr>
        <tr>
            <td>Lecturer Name:</td>
            <td><input type="text" name="ser_lecturer" id="" placeholder="Lecturer Name" required></td>
        </tr>
        <tr>
            <td>Location:</td>
            <td><input type="text" name="ser_location" id="" placeholder="Location" required></td>
        </tr>
        <tr>
            <td>Service Image:</td>
            <td><input type="file" id="ser_image" name="service_image"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="Service_add" value="Add Service">
            </td>
        </tr>
    </form>
</table>

</body>
</html>