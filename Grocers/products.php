<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $client = new SoapClient(null, [
        'location' => "http://localhost/grocers/customerService.php",
        'uri'      => "http://localhost/grocers/",
    ]);
    
    $products = $client->getProductList();
} catch (Exception $e) {
    $error = "Error connecting to SOAP service: " . $e->getMessage();
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background: white;
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }
        .product-name {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .product-price {
            font-size: 1.1em;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .product-description {
            color: #666;
            margin: 10px 0;
            flex-grow: 1;
        }
        .product-stock {
            color: #28a745;
            margin-bottom: 15px;
            font-size: 0.9em;
        }
        .add-to-cart {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.2s;
        }
        .add-to-cart:hover {
            background: #218838;
        }
        .add-to-cart:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .no-products {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="content">
            <h1>Products</h1>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (empty($products)): ?>
                <div class="no-products">No products available at the moment.</div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <?php
                        // Ensure product has all required fields
                        $productData = [
                            'id' => $product['id'] ?? $product['product_id'] ?? null,
                            'name' => $product['name'] ?? $product['product_name'] ?? 'Unknown Product',
                            'price' => floatval($product['product_price'] ?? 0),
                            'stock' => intval($product['product_stock'] ?? 0),
                            'image' => $product['product_image'] ?? null,
                        ];
                        
                        // Skip products without ID
                        if (!$productData['id']) continue;
                        ?>
                        <div class="product-card">
                            <?php if ($productData['image']): ?>
                                <img src="<?php echo htmlspecialchars($productData['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($productData['name']); ?>" 
                                     class="product-image">
                            <?php else: ?>
                                <div class="product-image"></div>
                            <?php endif; ?>
                            
                            <div class="product-name"><?php echo htmlspecialchars($productData['name']); ?></div>
                            <div class="product-price">RM<?php echo number_format($productData['price'], 2); ?></div>
                            
                            <div class="product-stock">
                                <?php if ($productData['stock'] > 0): ?>
                                    In Stock
                                <?php else: ?>
                                    Out of Stock
                                <?php endif; ?>
                            </div>
                            
                            <button 
                                class="add-to-cart"
                                onclick='cart.addItem(<?php echo json_encode($productData, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'
                                <?php echo ($productData['stock'] <= 0) ? 'disabled' : ''; ?>
                            >
                                Add to Cart
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="cart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            cart.updateUI();
        });
    </script>
</body>
</html> 