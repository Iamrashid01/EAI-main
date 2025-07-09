package com.grocers.routes;

import com.grocers.domain.Order; // ✅ Corrected import based on actual location

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class FileProcessingRoute extends RouteBuilder {
    @Override
    public void configure() {
        from("direct:processFile")
            .log("📄 Raw CSV content:\n${body}")
            .split(body().tokenize("\n")).streaming()
                .filter(body().startsWith("ORD-"))
                .process(exchange -> {
                    String line = exchange.getIn().getBody(String.class);
                    String[] parts = line.split(",");
                    Order order = new Order(
                        parts[0],              // orderId
                        parts[1],              // customerName
                        Integer.parseInt(parts[2]), // quantity
                        parts[3]               // product
                    );
                    exchange.getIn().setBody(order);
                })
                .log("📦 Sending Order to JMS: ${body}")
                .to("jms:queue:customerOrders")
            .end();
    }
}