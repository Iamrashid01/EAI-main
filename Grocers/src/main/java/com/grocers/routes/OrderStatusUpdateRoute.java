package com.grocers.routes;

import com.grocers.domain.Order;
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
                    Object body = exchange.getIn().getBody();
                    if (body instanceof Order order) {
                        exchange.getMessage().setBody("✅ Order status updated for Order ID: " + order.getId());
                    } else if (body == null) {
                        exchange.getMessage().setBody("⚠️ Received null message body");
                    } else {
                        exchange.getMessage().setBody("⚠️ Unexpected message type: " + body.getClass().getName());
                    }
                }
            })
            .log("${body}");
    }
}