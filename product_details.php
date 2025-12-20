<?php
// Include the database connection file
include 'db_connection.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];
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
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }

        .container {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            padding: 40px 20px;
            gap: 20px;
            justify-content: center;
            align-items: flex-start;
        }

        .product-image,
        .product-details {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            flex: 1 1 400px;
            max-width: 500px;
        }

        .product-image img {
            width: 100%;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
        }

        .product-header h1 {
            margin-top: 0;
            font-size: 28px;
            color: #333;
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

        .back-link:hover {
            background-color: #0056b3;
        }

        /* MOBILE RESPONSIVENESS */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 20px 10px;
            }

            .product-image,
            .product-details {
                max-width: 100%;
                flex: 1 1 100%;
                padding: 15px;
            }

            .product-header h1 {
                font-size: 22px;
            }

            .product-details p {
                font-size: 16px;
            }

            .price {
                font-size: 20px;
            }

            .back-link {
                width: 100%;
                text-align: center;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($product): ?>
            <div class="product-image">
                <?php if ($product['image']): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                <?php else: ?>
                    <p>No image available.</p>
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
            <div class="product-details">
                <p>Product not found.</p>
                <a class="back-link" href="product_wall.php">Back to Product Wall</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
