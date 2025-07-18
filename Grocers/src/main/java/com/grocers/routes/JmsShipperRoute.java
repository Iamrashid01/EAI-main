package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component("jmsShipperRoute")
public class JmsShipperRoute extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        from("jms:queue:shipper")
            .routeId("shipper-route")
            .log("🚚 Shipper received order for delivery: ${body}");
    }
}