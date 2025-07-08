package com.grocers.routes;

import org.apache.camel.Exchange;
import org.apache.camel.Processor;
import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class OrderStatusUpdateRoute extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        from("jms:queue:orderStatusUpdate")
            .routeId("order-status-update")
            .process(new Processor() {
                @Override
                public void process(Exchange exchange) throws Exception {
                    String orderId = exchange.getIn().getHeader("OrderId", String.class);
                    exchange.getMessage().setBody("Order status updated for Order ID: " + (orderId != null ? orderId : "UNKNOWN"));
                }
            })
            .log("${body}");
    }
}