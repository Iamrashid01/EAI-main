package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class SupplierBRoute extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        from("jms:queue:supplierB")
            .routeId("supplier-b-route")
            .log("🏭 Supplier B processing order: ${body}")
            .to("jms:queue:shipper");
    }
}