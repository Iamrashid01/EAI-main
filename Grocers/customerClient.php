<?php
$client = new SoapClient(null, [
    'location' => "http://localhost/grocers/customerService.php",
    'uri'      => "http://localhost/grocers/",
]);

// Example Usage
$products = $client->getProductList();
echo "<h3>Products:</h3><ul>";
foreach ($products as $product) {
    echo "<li>{$product['product_name']} - {$product['product_price']}</li>";
}
echo "</ul>";

$status = $client->getOrderStatus(5);
echo "<br>Order Status: $status";

$orderResponse = $client->placeOrder(1, 2, 1);
echo "<br>Order Response: $orderResponse";
?>
