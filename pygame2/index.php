<?php
include 'db.php';

$sql = "SELECT * FROM products";
$stmt = $conn->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $sql = "UPDATE products SET is_checked = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_id]);
    header("Location: index.php");
    exit;
}

// Fonksiyon: Ürün adını resim dosya yoluna çevir
function getImagePath($productName) {
    // Küçük harfe çevir
    $productName = strtolower($productName);
    // Türkçe karakterleri dönüştür
    $search = ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü'];
    $replace = ['c', 'g', 'i', 'o', 's', 'u'];
    $productName = str_replace($search, $replace, $productName);
    // Boşlukları tire ile değiştir
    $productName = str_replace(' ', '-', $productName);
    // Resim yolunu oluştur
    return "img/{$productName}.png";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alışveriş Listesi</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
            padding: 0;
            background-size: cover; /* Arka planın tüm sayfayı kaplaması */
            background-repeat: no-repeat; /* Tekrar etmemesi */
            background-position: center; /* Ortalanması */
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-top: 20px;
            color: #333;
        }

        .container {
            width: 80%;
            max-width: 800px;
            background-color: rgba(255, 255, 255, 0.9); /* Hafif saydam arka plan */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .shopping-cart {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
        }

        ul {
            list-style-type: none; /* Madde işaretlerini kaldır */
            padding: 0;
            margin: 0;
        }

        .product {
            width: 95%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        .product img {
            max-width: 50px;
            max-height: 50px;
            margin-right: 10px;
        }

        .checked {
            text-decoration: line-through;
        }

        .checkmark {
            color: green;
            font-weight: bold;
        }

        /* Form görünümü */
        form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="hidden"] {
            display: none;
        }

        button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Geri dön linki */
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Alışveriş Sepeti</h1>
        <div class="shopping-cart">
            <ul>
                <?php foreach ($products as $product): ?>
                <li class="product <?= $product['is_checked'] ? 'checked' : '' ?>">
                    <?php
                    // Resim yolunu dinamik olarak al
                    $imagePath = getImagePath($product['name']);
                    ?>
                    <img src="<?= $imagePath ?>" alt="<?= $product['name'] ?>">
                    <?= htmlspecialchars($product['name']) ?>
                    <?php if ($product['is_checked']): ?>
                        <span class="checkmark">✔</span>
                    <?php else: ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit">Sepete ekleyin</button>
                        </form>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <a href="add_product.php">Alışveriş Listesine git</a>
    </div>
</body>
</html>
