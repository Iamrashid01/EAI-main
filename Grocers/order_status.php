<?php
$status = null;
$error = null;
$orderId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    
    try {
        $client = new SoapClient(null, [
            'location' => "http://localhost/Grocers/customerService.php",
            'uri'      => "http://localhost/Grocers/",
        ]);
        
        $status = $client->getOrderStatus($orderId);
    } catch (Exception $e) {
        $error = "Error checking order status: " . $e->getMessage();
    }
}

// Auto-fill order ID from URL parameter
if (empty($orderId) && !empty($_GET['order_id'])) {
    $orderId = $_GET['order_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status - Grocery Store</title>
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
        .form-group {
            margin-bottom: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #0056b3;
        }
        .result {
            margin-top: 30px;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .result.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .result.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            margin-top: 10px;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background: #cce5ff;
            color: #004085;
        }
        .status-shipped {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-delivered {
            background: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="content">
            <h1>Track Your Order</h1>
            
            <form method="POST">
                <div class="form-group">
                    <label for="order_id">Order ID:</label>
                    <input type="number" id="order_id" name="order_id" value="<?php echo htmlspecialchars($orderId); ?>" required placeholder="Enter order ID">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Check Status</button>
                </div>
            </form>
            
            <?php if ($error): ?>
                <div class="result error">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php elseif ($status !== null): ?>
                <div class="result success">
                    <h3>Order #<?php echo htmlspecialchars($orderId); ?></h3>
                    <?php
                    $statusText = is_array($status) && isset($status['order_status'])
                        ? $status['order_status']
                        : (is_string($status) ? $status : 'Pending');
                
                // Determine class
                $statusClass = 'status-' . strtolower($statusText);
                if (!in_array($statusClass, ['status-pending', 'status-processing', 'status-shipped', 'status-delivered', 'status-cancelled'])) {
                    $statusClass = 'status-pending';
                }
                ?>
                <div class="status-badge <?php echo $statusClass; ?>">
                    <?php echo htmlspecialchars($statusText); ?>
                </div>
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