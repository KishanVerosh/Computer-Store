<?php
// Include the database connection file
include 'db_connection.php';

// Initialize filter variables
$searchQuery = "";
$categoryFilter = "";
$conditionsFilter = "";

// Check if filters are submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchQuery = $_POST['search'] ?? "";
    $categoryFilter = $_POST['category'] ?? "";
    $conditionsFilter = $_POST['conditions'] ?? "";
}

// Build the SQL query with optional filters
$sql = "SELECT * FROM products WHERE name LIKE ?";
$filters = ["%" . $searchQuery . "%"];

if (!empty($categoryFilter)) {
    $sql .= " AND category = ?";
    $filters[] = $categoryFilter;
}

if (!empty($conditionsFilter)) {
    $sql .= " AND conditions = ?";
    $filters[] = $conditionsFilter;
}

// Prepare the SQL query with dynamic parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($filters)), ...$filters);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        header img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .search-bar {
            margin: 20px auto;
            text-align: center;
        }
        .search-bar input, .search-bar select {
            padding: 10px;
            width: 80%;
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .search-bar button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .search-bar button:hover {
            background: #0056b3;
        }
        .product-wall {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }
        .product-box {
            border: 1px solid #ddd;
            padding: 20px;
            width: 200px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .product-box img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .product-box h3 a {
    color: #000; /* Ensures the link is black */
    text-decoration: none; /* Removes the underline */
    font-weight: bold; /* Make the text bold */
}


        .product-box p {
            color: #555;
            font-size: 14px;
        }
        .product-box .price {
            font-weight: bold;
            color: #28a745;
        }
        .product-box a {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background: #fff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <header>
        <img src="header-image.jpg" alt="Header Photo">
    </header>
    
    <!-- Search Bar Form -->
    <div class="search-bar">
        <form method="POST">
            <input type="text" name="search" placeholder="Search for products" value="<?php echo htmlspecialchars($searchQuery); ?>">
            
            <select name="category">
                <option value="">Select Category</option>
                <option value="laptop" <?php echo $categoryFilter == 'laptop' ? 'selected' : ''; ?>>Laptop</option>
                <option value="desktops" <?php echo $categoryFilter == 'desktops' ? 'selected' : ''; ?>>Desktops</option>
                <option value="storages" <?php echo $categoryFilter == 'storages' ? 'selected' : ''; ?>>Storages</option>
                <option value="printer_and_equipment" <?php echo $categoryFilter == 'printer_and_equipment' ? 'selected' : ''; ?>>Printer and Equipments</option>
                <option value="accessories" <?php echo $categoryFilter == 'accessories' ? 'selected' : ''; ?>>Accessories</option>
                <option value="other" <?php echo $categoryFilter == 'other' ? 'selected' : ''; ?>>Other</option>
            </select>

            <select name="conditions">
                <option value="">Select Condition</option>
                <option value="brand_new" <?php echo $conditionsFilter == 'brand_new' ? 'selected' : ''; ?>>Brand New</option>
                <option value="used" <?php echo $conditionsFilter == 'used' ? 'selected' : ''; ?>>Used</option>
            </select>
            
            <button type="submit">Search</button>
        </form>
    </div>
    
    <div class="product-wall">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-box">';
            // Clickable image
            if ($row['image']) {
                echo '<a href="product_details.php?id=' . $row['id'] . '">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Product Image">';
                echo '</a>';
            }
            // Clickable product name
            echo '<h3><a href="product_details.php?id=' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</a></h3>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '<div class="price">$' . htmlspecialchars($row['price']) . '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found</p>';
    }
    ?>
</div>


    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> MicroLinks. All rights reserved.</p>
    </div>
</body>
</html>

<?php
$conn->close();
?>
