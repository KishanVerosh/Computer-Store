<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
</head>
<body>
    <h1>Product List</h1>

    <table border="1">
        <tr>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Category</th>
            <th>Conditions</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($product = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $product['name']; ?></td>
                <td><?php echo $product['description']; ?></td>
                <td><?php echo $product['price']; ?></td>
                <td><?php echo $product['category']; ?></td>
                <td><?php echo $product['conditions']; ?></td>
                <td><img src="<?php echo $product['image']; ?>" alt="Image" style="max-width: 100px;"></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a> |
                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
