<?php
require_once('../db_config.php');

function getDeliveryStatus($orderID) {
    global $conn;
    $sql = "SELECT status FROM deliveries WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    return $status ?? "Not Found";
}

function updateDeliveryStatus($orderID, $status) {
    global $conn;
    $sql = "UPDATE deliveries SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $orderID);
    $stmt->execute();
    return $stmt->affected_rows > 0 ? "Status Updated" : "Failed to Update";
}

$server = new SoapServer(null, ['uri' => "http://localhost/grocers/delivery/"]);
$server->addFunction("getDeliveryStatus");
$server->addFunction("updateDeliveryStatus");
$server->handle();
?>
