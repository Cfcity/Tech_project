<?php

include('../General/test.php');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <title>Inquiry Review</title>
    <style>
        .edit-btn, .save-btn, .cancel-btn {
            background-color: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 14px;
            margin: 2px;
            cursor: pointer;
            font-size: 1rem;
        }
        .save-btn { background-color: #059669; }
        .cancel-btn { background-color: #ef4444; }
        .edit-btn:disabled, .save-btn:disabled, .cancel-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        input[readonly], textarea[readonly] {
            background: #e5e7eb;
            color: #22223b;
            border: 1px solid #d1d5db;
        }
    </style>
</head>

<body class="dashboard-container">

<div class="main-content">
    <table class="dashboard-table">
        <tr>
            <td colspan="8" class="center-text" style="font-size:1.3rem; font-weight:600;">
                Query Information
            </td>
        </tr>
        <tr>
            <td colspan="8" class="center-text">
                <a href="../home_pages/home_admin.php?role=<?php echo$role?>" class="primary-button">Home</a>
            </td>
        </tr>
        <?php
        if (isset($_GET['Inq_ID'])) {
            $service_id = intval($_GET['Inq_ID']);
        } elseif (isset($_POST['Inq_ID'])) {
            $service_id = intval($_POST['Inq_ID']);
        } else {
            die("<tr><td colspan='8'>No Inquiry ID provided.</td></tr>");
        }

        // Handle reply update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_reply'])) {
            $edit_reply_id = intval($_POST['edit_reply_id']);
            $edit_reply_text = mysqli_real_escape_string($db, $_POST['edit_reply_text']);
            $update = mysqli_query($db, "UPDATE reply SET reply = '$edit_reply_text' WHERE Reply_ID = '$edit_reply_id'");
            if ($update) {
                echo "<script>alert('Reply updated successfully!');window.location.href='review.php?Inq_ID=$service_id';</script>";
                exit;
            } else {
                echo "<tr><td colspan='8'>Error updating reply: " . mysqli_error($db) . "</td></tr>";
            }
        }

        $query = "
        SELECT reply.Reply_ID, reply.reply, reply.StaffID, reply.created_at, 
               staff.Staffid, staff.f_name, staff.l_name, department.dept_name AS department,   
               inquiry.Inq_ID, inquiry.issue, inquiry.inq_type, inquiry.status, inquiry.description, inquiry.studentid, 
               students.Stu_fname, students.Stu_lname 
        FROM inquiry
        LEFT JOIN reply ON reply.Inq_ID = inquiry.Inq_ID
        LEFT JOIN staff ON reply.StaffID = staff.Staffid
        LEFT JOIN department ON staff.dept_id = department.dept_id
        JOIN students ON inquiry.studentid = students.studentid
        WHERE inquiry.Inq_ID = $service_id
        ";

        $result = mysqli_query($db, $query);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            // Inquiry Information
            echo "<tr>
                    <th>Inquiry ID</th>
                    <th>Issue</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Student Name</th>
                    <th colspan='2'>Admin Options</th>
                  </tr>";
            echo "<tr>
                    <td>" . htmlspecialchars($row['Inq_ID']) . "</td>
                    <td>" . htmlspecialchars($row['issue']) . "</td>
                    <td>" . htmlspecialchars($row['inq_type']) . "</td>
                    <td>" . htmlspecialchars($row['status']) . "</td>
                    <td>" . htmlspecialchars($row['description']) . "</td>
                    <td>" . htmlspecialchars($row['Stu_fname']) . " " . htmlspecialchars($row['Stu_lname']) . "</td>
                    <td colspan='2'>
                        <form method='POST' action=''>
                            <input type='hidden' name='Inq_ID' value='" . htmlspecialchars($row['Inq_ID']) . "'>
                            <input class='primary-button' type='submit' value='Delete Inquiry' name='del_inquiry'>
                        </form>
                    </td>
                  </tr>";

            // Reply Information
            echo "<tr>
                    <th>Reply ID</th>
                    <th colspan='2'>Reply</th>
                    <th>Date</th>
                    <th colspan='2'>Staff Name</th>
                    <th>Staff Department</th>
                    <th>Admin Options</th>
                  </tr>";

            if (!empty($row['Reply_ID'])) {
                $edit_mode = (isset($_POST['edit_reply']) && $_POST['edit_reply_id'] == $row['Reply_ID']);
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Reply_ID']) . "</td>";
                echo "<form method='POST' action=''>";
                echo "<td colspan='2'>";
                if ($edit_mode) {
                    echo "<textarea name='edit_reply_text' class='input-field' required>" . htmlspecialchars($row['reply']) . "</textarea>";
                } else {
                    echo "<textarea readonly class='input-field'>" . htmlspecialchars($row['reply']) . "</textarea>";
                }
                echo "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "<td>" . htmlspecialchars($row['f_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['l_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                echo "<td>";
                if ($edit_mode) {
                    echo "<input type='hidden' name='edit_reply_id' value='" . htmlspecialchars($row['Reply_ID']) . "'>";
                    echo "<button type='submit' name='save_reply' class='save-btn'>Save</button>";
                    echo "<button type='button' class='cancel-btn' onclick='window.location.href=\"review.php?Inq_ID=$service_id\"'>Cancel</button>";
                } else {
                    echo "<input type='hidden' name='edit_reply_id' value='" . htmlspecialchars($row['Reply_ID']) . "'>";
                    echo "<button type='submit' name='edit_reply' class='edit-btn'>Edit</button>";
                }
                echo "</td>";
                echo "</form>";
                echo "</tr>";
                // Delete button (separate form to avoid conflict with edit)
                echo "<tr><td colspan='8' class='right-text'>
                        <form method='POST' action='' style='display:inline;'>
                            <input type='hidden' name='Reply_ID' value='" . htmlspecialchars($row['Reply_ID']) . "'>
                            <input type='submit' name='del_reply' value='Delete Reply' class='primary-button' style='background:#ef4444;'>
                        </form>
                      </td></tr>";
            } else {
                echo "<tr><td colspan='8'>No reply available for this inquiry.</td></tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Error fetching inquiry details or no data found.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>