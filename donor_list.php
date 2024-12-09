<?php
require_once("conn.php");
include "header_footer_admin.html";

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page - 1) * $limit : 0;

$search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';
                $search_locality = isset($_GET['search_locality']) ? $_GET['search_locality'] : '';
                $search_city = isset($_GET['search_city']) ? $_GET['search_city'] : '';
                $search_pincode = isset($_GET['search_pincode']) ? $_GET['search_pincode'] : '';
                $search_blood_type = isset($_GET['search_blood_type']) ? $_GET['search_blood_type'] : '';

                // Build the WHERE clause dynamically
                $conditions = [];
                if (!empty($search_name)) {
                    $conditions[] = "(donor_first_name LIKE '%$search_name%' OR donor_last_name LIKE '%$search_name%')";
                }
                if (!empty($search_locality)) {
                    $conditions[] = "locality LIKE '%$search_locality%'";
                }
                if (!empty($search_city)) {
                    $conditions[] = "city LIKE '%$search_city%'";
                }
                if (!empty($search_pincode)) {
                    $conditions[] = "pin_code LIKE '%$search_pincode%'";
                }
                if (!empty($search_blood_type)) {
                    $conditions[] = "donor_blood_group = '$search_blood_type'";
                }

                $where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";


$result = $conn->query("SELECT COUNT(*) AS total FROM donor");
$totalRecords = $result->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

$sql = "SELECT donor_id, donor_first_name, donor_last_name, dob, weight, gender, donor_blood_group,
         donor_email, donor_phone_no, house_number, locality, city, pin_code, donor_status, status_remark 
        FROM donor $where_clause
        LIMIT $start, $limit";
$donors = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor List</title>
    <link rel="stylesheet" href="show_list_style.css">
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
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #e6e6e6;
            color: black;
            border-radius: 4px;
            border: 1px solid #ddd;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a.active {
            background-color: #cccccc;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #cccccc;
        }

        a.edit-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        a.edit-button:hover {
            background-color: #45a049;
        }


    </style>
</head>
<body>
     <!-- <div class="container"> -->
        <h2>Donor List</h2>

        <div class="search-bar">
            <center>
            <form method="GET" action="">
                <input type="text" name="search_name" placeholder="Search by Name">
                <input type="text" name="search_locality" placeholder="Search by Locality">
                <input type="text" name="search_city" placeholder="Search by City">
                <input type="text" name="search_pincode" placeholder="Search by Pincode">
                <select name="search_blood_type">
                    <option value="">Select Blood Type</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
                <button type="submit">Search</button>
            </form>
                </form>
            </center>
        </div>

        <table>
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>DOB</th>
                    <th>Weight</th>
                    <th>Gender</th>
                    <th>Blood Group</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <!-- <th>Address</th> -->
                    <th>House No.</th>
                    <th>Locality</th>
                    <th>City</th>
                    <th>Pincode</th>
                    <th>Status</th>
                    <th>Status Remark</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($donors->num_rows > 0): ?>
                    <?php while ($row = $donors->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['donor_first_name']; ?></td>
                            <td><?php echo $row['donor_last_name']; ?></td>
                            <td><?php echo $row['dob']; ?></td>
                            <td><?php echo $row['weight']; ?></td>
                            <td><?php echo ucfirst($row['gender']); ?></td>
                            <td><?php echo $row['donor_blood_group']; ?></td>
                            <td><?php echo $row['donor_email']; ?></td>
                            <td><?php echo $row['donor_phone_no']; ?></td>
                            <td><?php echo $row['house_number']; ?></td>
                            <td><?php echo $row['locality']; ?></td>
                            <td><?php echo $row['city']; ?></td>
                            <td><?php echo $row['pin_code']; ?></td>    
                            <td><?php echo ucfirst($row['donor_status']); ?></td>
                            <td><?php echo $row['status_remark'] ? $row['status_remark'] : 'N/A'; ?></td>
                            <td><a href="update_donor.php?donor_id=<?php echo $row['donor_id']; ?>" class="edit-button">Edit</a></td>
                            <?php /*$num = $num+1;*/ ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12">No donors found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <center><br>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            </center>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
