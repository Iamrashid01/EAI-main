<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = null;
$success = false;
$orderId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cart_data'])) {
    try {
        $cartData = json_decode($_POST['cart_data'], true);
        
        if (empty($cartData)) {
            throw new Exception("Cart is empty");
        }

        $client = new SoapClient(null, [
            'location' => "http://localhost/Grocers/customerService.php",
            'uri'      => "http://localhost/Grocers/",
        ]);
        
        // Format the order data
        $orderItems = array_map(function($item) {
            return [
                'product_id' => $item['id'],
                'item_quantity' => $item['quantity']
            ];
        }, $cartData);
        
        // Place the order
        $orderId = $client->placeOrder($orderItems);
        $success = true;
        
    } catch (Exception $e) {
        $error = "Error placing order: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .message {
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .order-id {
            font-size: 1.2em;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            background: #e9ecef;
            border-radius: 5px;
            display: inline-block;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Status</h1>
        
        <?php if ($error): ?>
            <div class="message error">
                <?php echo htmlspecialchars($error); ?>
                <p>
                    <a href="cart.php" class="btn btn-primary">Return to Cart</a>
                </p>
            </div>
        <?php elseif ($success): ?>
            <div class="message success">
                <h2>🎉 Order Placed Successfully!</h2>
                <p>Thank you for your order. Your order has been successfully placed.</p>
                <div class="order-id">
                    Order ID: <?php echo htmlspecialchars($orderId); ?>
                </div>
                <p>You can track your order status using this Order ID.</p>
                <div>
                    <a href="order_status.php?order_id=<?php echo urlencode($orderId); ?>" class="btn btn-primary">Track Order</a>
                    <a href="products.php" class="btn btn-success">Continue Shopping</a>
                </div>
            </div>
            <script>
                // Clear the cart after successful order
                if (typeof cart !== 'undefined') {
                    cart.clear();
                } else {
                    // Fallback: clear cart from localStorage directly
                    localStorage.removeItem('cart');
                    // Optionally, update cart count in navbar
                    var cartCount = document.getElementById('cart-count');
                    if (cartCount) cartCount.textContent = '0';
                }
            </script>
        <?php else: ?>
            <div class="message error">
                Invalid request. Please try again.
                <p>
                    <a href="cart.php" class="btn btn-primary">Return to Cart</a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 