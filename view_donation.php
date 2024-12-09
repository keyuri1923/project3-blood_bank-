<?php
require_once("conn.php");
// include "header_footer_admin.html";
// include "blood_request_style.css";

// Fetch all donation records from the database
$query = "SELECT d.donor_id, d.quantity, d.donation_date, don.donor_blood_group
          FROM donation d
          JOIN donor don ON d.donor_id = don.donor_id
          ORDER BY d.donation_date DESC";

$result = $conn->query($query);

if ($result === false) {
    echo "Error: Could not fetch donation records. " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Donations</title>
    <link rel="stylesheet" href="show_list_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
            font-size: 24px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px 20px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: grey;
            color: white;
            font-weight: bold;
        }

        table td {
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        button {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: grey;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        button a {
            color: white;
            text-decoration: none;
        }

        button:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
<?php include 'header_footer_admin.html';?>
    <h2>View Donations</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Donor ID</th>
                <th>Blood Group</th>
                <th>Donation Quantity (Units)</th>
                <th>Donation Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['donor_id']; ?></td>
                    <td><?php echo $row['donor_blood_group']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['donation_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No donation records found.</p>
    <?php endif; ?>

    <br><br>
    <button><a href="add_donation.php">Add Donation</a></button>
</body>
</html>