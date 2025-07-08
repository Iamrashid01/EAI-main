<?php
require_once '../db_config.php';

// Handle order receive action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $stmt = $conn->prepare("UPDATE `orders` SET order_status = 'Processing' WHERE order_id = ? AND order_status = 'Pending'");
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
}

// Fetch all pending orders
$result = $conn->query("SELECT order_id, order_date, order_total_amount FROM `orders` WHERE order_status = 'Pending' ORDER BY order_date ASC");
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery - Receive Orders</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 800px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }
        th { background: #f0f0f0; }
        .btn { background: #007bff; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
        .btn:disabled { background: #ccc; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1>Pending Orders to Receive</h1>
        <?php if (empty($orders)): ?>
            <p style="text-align:center; color:#666;">No pending orders at the moment.</p>
        <?php else: ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total Amount (RM)</th>
                <th>Action</th>
            </tr>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars($order['order_date']) ?></td>
                <td><?= number_format($order['order_total_amount'], 2) ?></td>
                <td>
                    <form method="POST" style="margin:0;">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                        <button type="submit" class="btn">Mark as Received</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</body>
</html> 