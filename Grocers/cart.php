<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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
        .cart-empty {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .cart-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 20px;
            padding: 20px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .cart-item-details h3 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .cart-item-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .cart-item-controls input {
            width: 60px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .remove-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .remove-btn:hover {
            background: #c82333;
        }
        .cart-total {
            text-align: right;
            padding: 20px;
            font-size: 1.2em;
            font-weight: bold;
            border-top: 2px solid #eee;
        }
        .checkout-btn {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 20px auto 0;
            padding: 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
        }
        .checkout-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="content">
            <h1>Shopping Cart</h1>
            
            <div id="cart-items">
                <!-- Cart items will be populated by JavaScript -->
            </div>
            
            <div class="cart-total">
                Total: <span id="cart-total">RM0.00</span>
            </div>
            
            <form id="checkout-form" action="place_order.php" method="POST" style="display: none;">
                <input type="hidden" name="cart_data" id="cart-data">
                <button type="submit" class="checkout-btn">Proceed to Checkout</button>
            </form>
        </div>
    </div>

    <script src="cart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            cart.updateUI();
            
            const checkoutForm = document.getElementById('checkout-form');
            if (cart.items.length > 0) {
                checkoutForm.style.display = 'block';
                document.getElementById('cart-data').value = JSON.stringify(cart.items);
            }
        });
    </script>
</body>
</html> 