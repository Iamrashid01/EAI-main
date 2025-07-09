package com.grocers.routes;

import com.grocers.domain.Order;
import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class CsvOrderTransformerRoute extends RouteBuilder {
    @Override
    public void configure() {
        from("file:input/csv?noop=true")
            .routeId("csv-transformer")
            .split(body().tokenize("\n"))
            .process(exchange -> {
                String[] fields = exchange.getIn().getBody(String.class).split(",");
                Order order = new Order(fields[0], fields[1], Integer.parseInt(fields[2]), fields[3]);
                exchange.getIn().setBody(order);
            })
            .to("jms:queue:customerOrders");
    }
}