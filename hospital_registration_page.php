<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Registration - Blood Bank Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #b34141;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .registration-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            border: 1px solid #c5535f;
            margin: 50px 0;
        }

        .registration-container form {
            width: 100%;
            display: flex;
            flex-direction: column; 
            align-items: center;
        }

        .registration-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #721c24;
        }
        .registration-container input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #c5535f;
            border-radius: 4px;
            outline: none;
            box-sizing: border-box;
        }
        .registration-container button {
            width: 100%;
            padding: 10px;
            background-color: #b30000;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .registration-container button:hover {
            background-color: #a71d2a;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
        .success {
            color: green;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Hospital Registration</h2>
        <?php
            require_once("conn.php");

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $hospital_username = trim($_POST["hospital_username"]);
                $hospital_email = trim($_POST["hospital_email"]);
                $hospital_contact_no = trim($_POST["hospital_contact_no"]);
                $hospital_name = trim($_POST["hospital_name"]);
                $hospital_address = trim($_POST["hospital_address"]);
                $hospital_city = trim($_POST["hospital_city"]);
                $hospital_pin_code = trim($_POST["hospital_pin_code"]);
                $hospital_password = trim($_POST["hospital_password"]);
                $hospital_confirm_password = trim($_POST["hospital_confirm_password"]);

                $errors = [];

                if (empty($hospital_username)) {
                    $errors[] = "Username is required.";
                } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $hospital_username)) {
                    $errors[] = "Username must be 3-20 characters and contain only letters, numbers, and underscores.";
                }

                if (empty($hospital_email)) {
                    $errors[] = "Email is required.";
                } elseif (!filter_var($hospital_email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Invalid email format.";
                }

                if (empty($hospital_contact_no)) {
                    $errors[] = "Contact number is required.";
                } elseif (!preg_match("/^\d{10}$/", $hospital_contact_no)) {
                    $errors[] = "Contact number must be a valid 10-digit number.";
                }

                if (empty($hospital_password)) {
                    $errors[] = "Password is required.";
                } elseif (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/", $hospital_password)) {
                    $errors[] = "Password must be 8-16 characters long, include at least one letter, one number, and one special character.";
                }

                if ($hospital_password !== $hospital_confirm_password) {
                    $errors[] = "Passwords do not match.";
                }

                if (empty($hospital_name)) {
                    $errors[] = "Hospital name is required.";
                }

                if (empty($hospital_address)) {
                    $errors[] = "Hospital address is required.";
                }
                
                if (empty($hospital_city)) {
                    $errors[] = "City is required.";
                } elseif (!preg_match("/^[a-zA-Z\s]{2,50}$/", $hospital_city)) {
                    $errors[] = "City must only contain letters and spaces, and be 2-50 characters long.";
                }
            
                // Validate pincode
                if (empty($hospital_pin_code)) {
                    $errors[] = "Pincode is required.";
                } elseif (!preg_match("/^\d{6}$/", $hospital_pin_code)) {
                    $errors[] = "Pincode must be a valid 6-digit number.";
                }
                // If no errors, insert data into the database
                if (empty($errors)) {
                    // Hash the password before storing it
                    $hashed_password = password_hash($hospital_password, PASSWORD_DEFAULT);

                    // Prepare SQL query
                    $query = "INSERT INTO hospital (hospital_username, hospital_email, hospital_password, hospital_contact_no, hospital_name, hospital_address, hospital_city, hospital_pin_code) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $query);

                    if ($stmt) {
                        // Bind parameters
                        mysqli_stmt_bind_param($stmt, "ssssssss", $hospital_username, $hospital_email, $hashed_password, $hospital_contact_no, $hospital_name, $hospital_address, $hospital_city, $hospital_pin_code);
                        // Execute the query
                        if (mysqli_stmt_execute($stmt)) {
                            echo "<p class='success'>Registration successful.</p>";
                            header("Location:hospital_list.php");
                        } else {
                            echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
                        }

                        // Close the statement
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<p class='error'>Error preparing the query: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    // Display validation errors
                    foreach ($errors as $error) {
                        echo "<p class='error'>$error</p>";
                    }
                }

                // Close the database connection
                mysqli_close($conn);
            }
?>
        <form action="" method="post">
            <input type="email" name="hospital_email" placeholder="Email" required>
            <input type="text" name="hospital_username" placeholder="Username" required>
            <!-- <input type="text" name="hospital_contact_no" placeholder="Contact Number" required> -->
            <input type="text" id="hospital_contact_no" name="hospital_contact_no" placeholder="Contact No." pattern="[0-9]+"  maxlength="10" 
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" value="<?php echo $formData["hospital_contact_no"] ?? ""; ?>" required>
            <input type="text" name="hospital_name" placeholder="Hospital's Name" required>
            <!-- <input type="text" name="hospital_address" placeholder="hospital address" required> -->
            <input type="text" name="hospital_address" placeholder="Hospital's Address" required>
            <input type="text" name="hospital_city" placeholder="City" required>
            <input type="text" id="hospital_pin_code" name="hospital_pin_code" placeholder="6-digit postal code" maxlength="6" pattern="[0-9]{6}" value="<?php echo $formData["hospital_pin_code"] ?? ""; ?>" required>
            <input type="password" name="hospital_password" placeholder="Password" required>
            <input type="password" name="hospital_confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
