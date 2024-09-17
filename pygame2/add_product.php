<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ürün ekleme işlemi
    if (isset($_POST['name'])) {
        $name = $_POST['name'];

        $sql = "INSERT INTO products (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name]);

        echo "Ürün başarıyla eklendi!";
    }

    // Ürün silme işlemi
    if (isset($_POST['delete_product_id'])) {
        $product_id = $_POST['delete_product_id'];

        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_id]);

        echo "Ürün başarıyla silindi!";
    }
}

// Veritabanından ürünleri çekme
$sql = "SELECT * FROM products";
$stmt = $conn->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Ekle</title>
    <style>
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .shopping-list-container {
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #ccc;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .shopping-list-container h2 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 24px;
            color: #4CAF50;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        ul li {
            background-color: #f9f9f9;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        input[type="text"], input[type="submit"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #333;
            text-decoration: none;
        }

        .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: darkred;
        }

        /* Eklenebilir Ürünler Kutusu */
        .expandable-box {
            display: none; /* Başlangıçta gizli */
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 20px auto;
        }

        .expandable-box ul {
            list-style-type: none;
            padding: 0;
        }

        .expandable-box ul li {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .toggle-button {
            display: block;
            margin: 20px auto;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .toggle-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Yeni Ürün Ekle</h1>

    <!-- Eklenebilir Ürünler Butonu -->
    <button class="toggle-button" onclick="toggleBox()">Eklenebilir Ürünler</button>

    <!-- Eklenebilir Ürünler Kutusu -->
    <div id="expandableBox" class="expandable-box">
        <h2>Eklenebilir Ürünler</h2>
        <ul>
            <li>Armut</li>
            <li>Elma</li>
            <li>Fıstık</li>
            <li>Hindistan Cevizi İçi</li>
            <li>Kiraz</li>
            <li>Mercimek</li>
            <li>Muz</li>
            <li>Nohut</li>
            <li>Pirinç</li>
            <li>Un</li>
        </ul>
    </div>

    <form method="POST" action="add_product.php">
        Ürün Adı: <input type="text" name="name" required><br>
        <input type="submit" value="Ekle">
    </form>

    <!-- Alışveriş Listesi Kutusu -->
    <div class="shopping-list-container">
        <h2>Alışveriş Listesi</h2>
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <?= htmlspecialchars($product['name']) ?>
                    <!-- Ürünü silmek için form -->
                    <form method="POST" action="add_product.php" style="display:inline;">
                        <input type="hidden" name="delete_product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="delete-btn">X</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <a href="index.php">Alışveriş Sepetine Dön</a>

    <script>
        function toggleBox() {
            var box = document.getElementById('expandableBox');
            if (box.style.display === 'none' || box.style.display === '') {
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        }
    </script>
</body>
</html>
