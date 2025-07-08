<?php
$client = new SoapClient(null, [
    'location' => "http://localhost/grocers/delivery/deliveryService.php",
    'uri'      => "http://localhost/grocers/delivery/",
]);

// Example Usage:
$status = $client->getDeliveryStatus(101);
echo "Order Status: " . $status;

$update = $client->updateDeliveryStatus(101, "Delivered");
echo "<br>Update Response: " . $update;
?>
