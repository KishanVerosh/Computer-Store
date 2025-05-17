<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Fetch the product data
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Update product data
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $category = $conn->real_escape_string($_POST['category']);
        $conditions = $conn->real_escape_string($_POST['conditions']);
        $image = $conn->real_escape_string($_POST['image']);

        $update_stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, category = ?, conditions = ? WHERE id = ?");
        $update_stmt->bind_param("ssdsssi", $name, $description, $price, $image, $category, $conditions, $id);

        if ($update_stmt->execute()) {
            $success_message = "Product updated successfully!";
        } else {
            $error_message = "Error: " . $update_stmt->error;
        }

        $update_stmt->close();
    }
} else {
    header('Location: product_list.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            background: #fff;
            padding: 20px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .success {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background: #218838;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>

        <?php if (isset($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="edit_product.php?id=<?php echo $id; ?>">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="laptop" <?php echo ($product['category'] == 'laptop') ? 'selected' : ''; ?>>Laptop</option>
                <option value="desktops" <?php echo ($product['category'] == 'desktops') ? 'selected' : ''; ?>>Desktops</option>
                <option value="storages" <?php echo ($product['category'] == 'storages') ? 'selected' : ''; ?>>Storages</option>
                <option value="printer_and_equipment" <?php echo ($product['category'] == 'printer_and_equipment') ? 'selected' : ''; ?>>Printer and Equipment</option>
                <option value="accessories" <?php echo ($product['category'] == 'accessories') ? 'selected' : ''; ?>>Accessories</option>
                <option value="other" <?php echo ($product['category'] == 'other') ? 'selected' : ''; ?>>Other</option>
            </select>

            <label for="conditions">Conditions</label>
            <select id="conditions" name="conditions" required>
                <option value="brand_new" <?php echo ($product['conditions'] == 'brand_new') ? 'selected' : ''; ?>>Brand New</option>
                <option value="used" <?php echo ($product['conditions'] == 'used') ? 'selected' : ''; ?>>Used</option>
            </select>

            <label for="image">Image URL</label>
            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required>

            <button type="submit">Update Product</button>
        </form>

        <a class="back-link" href="product_list.php">Back to Product List</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
