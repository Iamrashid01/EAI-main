from("jetty:https://0.0.0.0:8443/customer/order?sslContextParameters=#sslContext")
    .log("Secure order received: ${body}")
    .to("jms:queue:customerOrders");