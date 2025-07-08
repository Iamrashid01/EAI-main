package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class SupplierARoute extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        from("jms:queue:supplierA")
            .routeId("supplier-a-route")
            .log("🏭 Supplier A processing order: ${body}")
            .to("jms:queue:shipper");
    }
}