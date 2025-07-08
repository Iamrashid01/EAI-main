package com.grocers.camel;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class RetailerRoute extends RouteBuilder {
    @Override
    public void configure() throws Exception {
        from("seda:retailer")
            .log("🏪 Retailer processing order: ${body}")
            .toD("seda:${body.format}-supplier");
    }
}