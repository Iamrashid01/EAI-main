package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class SupplierCRoute extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        from("jms:queue:supplierC")
            .routeId("supplier-c-route")
            .log("🏭 Supplier C processing order: ${body}")
            .to("jms:queue:shipper");
    }
}