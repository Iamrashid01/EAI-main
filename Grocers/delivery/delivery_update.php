<?php
require_once '../db_config.php';

$updatedOrder = null;
$successMsg = null;

// Determine selected status tab
$statusTabs = ['Processing', 'Shipped', 'Delivered', 'Cancelled'];
$selectedStatus = isset($_GET['status']) && in_array($_GET['status'], $statusTabs) ? $_GET['status'] : 'Shipped';

// Handle status update action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['new_status'];
    $allowed = ['Shipped', 'Delivered', 'Cancelled'];
    if (in_array($newStatus, $allowed)) {
        $stmt = $conn->prepare("UPDATE `orders` SET order_status = ? WHERE order_id = ? AND order_status = 'Processing'");
        $stmt->bind_param('si', $newStatus, $orderId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $successMsg = "Order #$orderId status updated to $newStatus.";
        } else {
            $successMsg = "Order #$orderId could not be updated (maybe already updated).";
        }
        // Fetch the updated order
        $stmt2 = $conn->prepare("SELECT order_id, order_date, order_total_amount, order_status FROM `orders` WHERE order_id = ?");
        $stmt2->bind_param('i', $orderId);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $updatedOrder = $result2->fetch_assoc();
        // After update, redirect to the new status tab
        if ($updatedOrder && in_array($updatedOrder['order_status'], $statusTabs)) {
            header('Location: delivery_update.php?status=' . urlencode($updatedOrder['order_status']) . '&updated=' . $orderId);
            exit;
        }
    }
}

// If redirected after update, show the updated order
if (isset($_GET['updated'])) {
    $orderId = intval($_GET['updated']);
    $stmt2 = $conn->prepare("SELECT order_id, order_date, order_total_amount, order_status FROM `orders` WHERE order_id = ?");
    $stmt2->bind_param('i', $orderId);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $updatedOrder = $result2->fetch_assoc();
    $successMsg = "Order #$orderId status updated to " . htmlspecialchars($updatedOrder['order_status']) . ".";
}

// Fetch orders for the selected status
$stmt = $conn->prepare("SELECT order_id, order_date, order_total_amount, order_status FROM `orders` WHERE order_status = ? ORDER BY order_date ASC");
$stmt->bind_param('s', $selectedStatus);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Order Status</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .main-flex { display: flex; }
        .sidebar {
            width: 180px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            margin: 40px 20px 40px 40px;
            padding: 20px 0;
            height: fit-content;
        }
        .sidebar h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        .sidebar .tab-link {
            display: block;
            padding: 12px 24px;
            color: #333;
            text-decoration: none;
            border-left: 4px solid transparent;
            margin-bottom: 6px;
            transition: background 0.2s, border-color 0.2s;
        }
        .sidebar .tab-link.active {
            background: #e9ecef;
            border-left: 4px solid #007bff;
            color: #007bff;
            font-weight: bold;
        }
        .container { max-width: 900px; flex: 1; margin: 40px 40px 40px 0; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }
        th { background: #f0f0f0; }
        .btn { background: #28a745; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-right: 5px; }
        .btn-cancel { background: #dc3545; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="main-flex">
        <div class="sidebar">
            <h3>Order Status</h3>
            <?php foreach ($statusTabs as $tab): ?>
                <a href="?status=<?= urlencode($tab) ?>" class="tab-link<?= $selectedStatus === $tab ? ' active' : '' ?>">
                    <?= htmlspecialchars($tab) ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="container">
            <h1><?= htmlspecialchars($selectedStatus) ?> Orders</h1>
            <?php if ($successMsg): ?>
                <div style="background:#d4edda;color:#155724;padding:12px;border-radius:5px;margin-bottom:20px;border:1px solid #c3e6cb;text-align:center;">
                    <?= htmlspecialchars($successMsg) ?>
                </div>
            <?php endif; ?>
            <?php if ($updatedOrder): ?>
                <table style="margin-bottom:20px;">
                    <tr><th colspan="5">Recently Updated Order</th></tr>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Amount (RM)</th>
                        <th>Status</th>
                    </tr>
                    <tr>
                        <td><?= htmlspecialchars($updatedOrder['order_id']) ?></td>
                        <td><?= htmlspecialchars($updatedOrder['order_date']) ?></td>
                        <td><?= number_format($updatedOrder['order_total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($updatedOrder['order_status']) ?></td>
                    </tr>
                </table>
            <?php endif; ?>
            <?php if (empty($orders)): ?>
                <p style="text-align:center; color:#666;">No <?= htmlspecialchars($selectedStatus) ?> orders at the moment.</p>
            <?php else: ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total Amount (RM)</th>
                    <?php if ($selectedStatus === 'Processing' || $selectedStatus === 'Shipped'): ?>
                        <th>Update Status</th>
                    <?php else: ?>
                        <th>Status</th>
                    <?php endif; ?>
                </tr>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                    <td><?= number_format($order['order_total_amount'], 2) ?></td>
                    <?php if ($selectedStatus === 'Processing'): ?>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                            <select name="new_status">
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <button type="submit" class="btn">Update</button>
                        </form>
                    </td>
                    <?php elseif ($selectedStatus === 'Shipped'): ?>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                            <select name="new_status">
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <button type="submit" class="btn">Update</button>
                        </form>
                    </td>
                    <?php else: ?>
                    <td><?= htmlspecialchars($order['order_status']) ?></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 