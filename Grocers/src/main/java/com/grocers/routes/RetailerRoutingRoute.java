package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class RetailerRoutingRoute extends RouteBuilder {
    @Override
    public void configure() {
        from("jms:queue:customerOrders")
            .routeId("retailer-routing")
            .log("🏬 Retailer received order: ${body}")
            .choice()
                .when(simple("${body.product} != null"))
                    .to("direct:routeToSupplier")
                .otherwise()
                    .log("⚠️ Product type missing, routing to generic supplier")
                    .to("jms:queue:genericSupplierQueue");
    }
}