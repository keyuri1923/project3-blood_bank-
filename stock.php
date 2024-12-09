<?php
require_once("conn.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $blood_group = $_POST['blood_group'];
    $quantity = (int)$_POST['quantity'];

    // Validate input
    if (!empty($blood_group) && $quantity >= 0) {
        $update_sql = "UPDATE stock SET quantity = ? WHERE blood_group = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("is", $quantity, $blood_group);

        if ($stmt->execute()) {
            echo "<script>alert('Stock updated successfully!');</script>";
        } else {
            echo "<script>alert('Failed to update stock. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid input! Please provide valid data.');</script>";
    }
}

// Fetch all stock data
$sql = "SELECT blood_group, quantity FROM stock";
$stock_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Stock Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .stock-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .stock-table th, .stock-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        .stock-table th {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }
        .stock-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .stock-table tr:hover {
            background-color: #f1f1f1;
        }
        input[type="number"] {
            width: 80px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }
        button {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #b21f2d;
        }
        @media (max-width: 768px) {
            .stock-table th, .stock-table td {
                font-size: 14px;
                padding: 8px;
            }
            input[type="number"] {
                width: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Blood Stock Management</h2>
        <table class="stock-table">
            <thead>
                <tr>
                    <th>Blood Group</th>
                    <th>Quantity (Units)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($stock_result->num_rows > 0): ?>
                    <?php while ($row = $stock_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['blood_group']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="blood_group" value="<?php echo $row['blood_group']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="0" required>
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No stock data available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
