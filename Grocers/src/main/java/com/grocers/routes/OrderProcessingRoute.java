package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class OrderProcessingRoute extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        from("jms:queue:customerOrders")
            .routeId("customer-orders-jms")
            .log("📦 Received JMS order with ID: ${body.id}")
            .to("jms:queue:inventoryCheck")
            .to("jms:queue:paymentVerification")
            .to("jms:queue:orderStatusUpdate");
    }
}