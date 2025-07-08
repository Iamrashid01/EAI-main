package com.grocers.camel;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class CustomerRoute extends RouteBuilder {
    @Override
    public void configure() throws Exception {
        from("direct:customer")
            .log("📥 Received order from customer: ${body}")
            .to("seda:retailer");
    }
}