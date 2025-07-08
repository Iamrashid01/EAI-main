<?php
require_once('db_config.php');

function getProductList() {
    global $conn;
    $result = $conn->query("SELECT * FROM products WHERE product_stock > 0");
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

function getOrderStatus($orderID) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT 
            o.order_status,
            o.order_date,
            o.order_total_amount,
            GROUP_CONCAT(
                CONCAT(p.product_name, ' (', oi.item_quantity, ' x RM', oi.item_price, ')')
                SEPARATOR ', '
            ) as items
        FROM `orders` o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        WHERE o.order_id = ?
        GROUP BY o.order_id
    ");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    
    if (!$order) {
        return "Order Not Found";
    }
    
    return [
        'order_status' => $order['order_status'],
        'date' => $order['order_date'],
        'total' => $order['order_total_amount'],
        'items' => $order['items']
    ];
}

function placeOrder($orderItems) {
    global $conn;
    
    if (empty($orderItems)) {
        throw new Exception("Cart is empty");
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Create the main order
        $stmt = $conn->prepare("INSERT INTO `orders` (order_status, order_date) VALUES ('Pending', NOW())");
        $stmt->execute();
        $orderId = $stmt->insert_id;
        
        // Prepare statement for order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, item_quantity, item_price) VALUES (?, ?, ?, ?)");
        
        $totalAmount = 0;
        
        // Insert each order item
        foreach ($orderItems as $item) {
            // Get current product price and stock
            $productStmt = $conn->prepare("SELECT product_price, product_stock FROM products WHERE product_id = ? FOR UPDATE");
            $productStmt->bind_param("i", $item['product_id']);
            $productStmt->execute();
            $product = $productStmt->get_result()->fetch_assoc();
            
            if (!$product) {
                throw new Exception("Product not found: " . $item['product_id']);
            }
            
            if ($product['product_stock'] < $item['item_quantity']) {
                throw new Exception("Insufficient stock for product ID: " . $item['product_id']);
            }
            
            // Insert order item
            $stmt->bind_param("iiid", $orderId, $item['product_id'], $item['item_quantity'], $product['product_price']);
            $stmt->execute();
            
            // Update stock
            $newStock = $product['product_stock'] - $item['item_quantity'];
            $updateStmt = $conn->prepare("UPDATE products SET product_stock = ? WHERE product_id = ?");
            $updateStmt->bind_param("ii", $newStock, $item['product_id']);
            $updateStmt->execute();
            
            // Add to total
            $totalAmount += $product['product_price'] * $item['item_quantity'];
        }
        
        // Update order total
        $updateTotal = $conn->prepare("UPDATE `orders` SET order_total_amount = ? WHERE order_id = ?");
        $updateTotal->bind_param("di", $totalAmount, $orderId);
        $updateTotal->execute();
        
        // Commit transaction
        $conn->commit();
        return $orderId;
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        throw $e;
    }
}

$server = new SoapServer(null, ['uri' => "http://localhost/grocers/"]);
$server->addFunction("getProductList");
$server->addFunction("getOrderStatus");
$server->addFunction("placeOrder");
$server->handle();
?>
