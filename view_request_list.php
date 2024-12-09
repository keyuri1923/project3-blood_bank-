<?php
require_once("conn.php");
include "header_footer_admin.html";

// Check if Accept or Deny button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action']; // 'Accept' or 'Deny'

    // Determine the new status
    $new_status = $action === 'Accept' ? 'Accepted' : 'Denied';

    // Update the status of the request
    $query = "UPDATE blood_request SET status = '$new_status' WHERE id = $request_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Request status updated successfully.');</script>";
    } else {
        echo "<script>alert('Error: Could not update request status.');</script>";
    }
}

// Fetch all blood requests
$query = "SELECT request_id, hospital_id, blood_group, quantity_require, requested_date, status FROM blood_request";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Blood Requests</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
        }
        button[name='action'][value='Accept'] {
            background-color: green;
            color: white;
            border: none;
        }
        button[name='action'][value='Deny'] {
            background-color: red;
            color: white;
            border: none;
        }
        button:hover {
            opacity: 0.8;
        }
        a{
            text-decoration:none;
            color:black;
        }
        /* Add some space below the table */
        /* table {
            margin-bottom: 30px; 
        } */
        /* Adding space between content and footer */
        /* Adjust space between table and footer */
        /* body {
            margin-bottom: 50px; 
        } */
    </style>
</head>
<body>
    <h1>Requests</h1>

    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Hospital ID</th>
                <th>Blood Group</th>
                <th>Quantity (Units)</th>
                <th>Requested Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $req_id=$row["request_id"];
                    echo "<tr>
                        <td>{$row['request_id']}</td>
                        <td>{$row['hospital_id']}</td>
                        <td>{$row['blood_group']}</td>
                        <td>{$row['quantity_require']}</td>
                        <td>{$row['requested_date']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <form action='handle_request.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='request_id' value='{$row['request_id']}'>
                                <button type='submit' name='action' value='Accept'><a href='handle_request.php?rid=$req_id'>Accept</a></button>
                                <button type='submit' name='action' value='Deny'><a href='handle_request.php?rid=$req_id&status=deny'>Deny</a></button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No blood requests found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <!-- <br><br><br><br> -->
</body>
</html>
