<?php
// Include the database connection file
include 'db_connection.php';

// Check if an ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
} else {
    $product = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .container {
            display: flex;
            height: 100vh;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .product-image {
            flex: 1;
            max-width: 50%;
            padding-right: 20px;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
        .product-details {
            flex: 2;
            max-width: 50%;
            padding-left: 20px;
            overflow-y: auto;
        }
        .product-header h1 {
            margin: 20px 0;
            font-size: 28px;
        }
        .product-details p {
            margin: 10px 0;
            font-size: 18px;
            color: #555;
        }
        .price {
            font-size: 24px;
            color: #28a745;
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($product): ?>
            <div class="product-image">
                <?php if ($product['image']): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                <?php endif; ?>
            </div>
            <div class="product-details">
                <div class="product-header">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                </div>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                <p><strong>Condition:</strong> <?php echo htmlspecialchars($product['conditions']); ?></p>
                <p class="price">$<?php echo htmlspecialchars($product['price']); ?></p>
                <a class="back-link" href="product_wall.php">Back to Product Wall</a>
            </div>
        <?php else: ?>
            <p>Product not found.</p>
            <a class="back-link" href="product_wall.php">Back to Product Wall</a>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
