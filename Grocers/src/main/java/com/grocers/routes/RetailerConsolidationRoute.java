package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component("retailerConsolidationRoute")
public class RetailerConsolidationRoute extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        from("jms:queue:customerOrders")
            .routeId("retailer-consolidation")
            .log("🛒 Retailer received order: ${body}")
            .choice()
                .when(simple("${body.product} == 'soap'"))
                    .to("jms:queue:supplierA")
                .when(simple("${body.product} == 'oil'"))
                    .to("jms:queue:supplierB")
                .when(simple("${body.product} == 'milk'"))
                    .to("jms:queue:supplierC")
                .otherwise()
                    .to("jms:queue:unknownSupplier");
    }
}