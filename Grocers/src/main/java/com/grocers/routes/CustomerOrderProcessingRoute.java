package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.apache.camel.support.processor.idempotent.MemoryIdempotentRepository;
import org.springframework.stereotype.Component;

@Component
public class CustomerOrderProcessingRoute extends RouteBuilder {
    @Override
    public void configure() {
        from("jms:queue:customerOrders")
            .routeId("customer-order-processing")
            .idempotentConsumer(header("OrderId"), MemoryIdempotentRepository.memoryIdempotentRepository(2000))
            .log("🛒 Received order: ${body}")
            .multicast().parallelProcessing()
                .to("jms:queue:inventoryCheck")
                .to("jms:queue:paymentVerification")
                .to("direct:routeToSupplier")
            .end();
    }
}