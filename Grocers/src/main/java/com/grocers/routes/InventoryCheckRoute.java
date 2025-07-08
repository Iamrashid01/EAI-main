package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class InventoryCheckRoute extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        from("jms:queue:inventoryCheck")
            .routeId("inventory-check")
            .log("📦 Checking inventory for product: ${body.product}");
    }
}