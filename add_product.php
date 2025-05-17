<?php
// Include the database connection
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use the already established connection from db_connection.php
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $image = $conn->real_escape_string($_POST['image']);
    $category = $conn->real_escape_string($_POST['category']);
    $conditions = $conn->real_escape_string($_POST['conditions']);

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category, conditions) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsss", $name, $description, $price, $image, $category, $conditions);

    if ($stmt->execute()) {
        $success_message = "Product added successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
        .drop-zone {
            width: 100%;
            height: 150px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 14px;
            color: #aaa;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .drop-zone.dragover {
            border-color: #28a745;
            color: #28a745;
        }
        button {
            padding: 10px 20px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
            background: #218838;
        }
        img.preview {
            max-width: 100%;
            max-height: 150px;
            margin-top: 10px;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Add Product</h1>

    <!-- Display success or error message -->
    <?php if (isset($success_message)): ?>
        <div class="message success">
            <?php echo $success_message; ?>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="message error">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="add_product.php">
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="category">Category</label>
        <select id="category" name="category" required>
            <option value="laptop">Laptop</option>
            <option value="desktops">Desktops</option>
            <option value="storages">Storages</option>
            <option value="printer_and_equipment">Printer and Equipments</option>
            <option value="accessories">Accessories</option>
            <option value="other">Other</option>
        </select>

        <label for="conditions">Conditions</label>
        <select id="conditions" name="conditions" required>
            <option value="brand_new">Brand New</option>
            <option value="used">Used</option>
        </select>

        <label for="image">Image</label>
        <div class="drop-zone" id="drop-zone">
            Drag & Drop an image or click to upload
        </div>
        <img id="preview" class="preview" alt="Preview" style="display:none;">
        <input type="hidden" id="image" name="image">

        <button type="submit">Add Product</button>
    </form>

    <script>
        const dropZone = document.getElementById('drop-zone');
        const preview = document.getElementById('preview');
        const imageInput = document.getElementById('image');

        dropZone.addEventListener('click', () => {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.click();

            fileInput.onchange = () => {
                const file = fileInput.files[0];
                uploadFile(file);
            };
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            uploadFile(file);
        });

        function uploadFile(file) {
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);

            fetch('upload_image.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    imageInput.value = data.file_path;
                    preview.src = data.file_path;
                    preview.style.display = 'block';
                } else {
                    alert(data.message || 'File upload failed.');
                }
            })
            .catch(error => {
                console.error('Error uploading file:', error);
                alert('File upload failed.');
            });
        }
    </script>
</body>
</html>