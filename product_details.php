<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Product Details</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      height: 100%;
      background: #f8f9fa;
    }
    .container {
      display: flex;
      height: 100vh;
      width: 100%;
      padding: 40px;
      box-sizing: border-box;
      gap: 40px;
    }
    .box {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 20px;
      box-sizing: border-box;
      flex: 1;
      overflow: hidden;
    }
    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 10px;
    }
    .product-header h1 {
      margin: 0 0 20px;
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
      <div class="box product-image">
        <?php if ($product['image']): ?>
          <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
        <?php else: ?>
          <p>No image available.</p>
        <?php endif; ?>
      </div>
      <div class="box product-details">
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
