package com.grocers.camel;

import org.apache.camel.LoggingLevel;
import org.apache.camel.builder.RouteBuilder;
import org.apache.camel.spi.IdempotentRepository;
import org.apache.camel.support.processor.idempotent.MemoryIdempotentRepository;
import org.springframework.context.annotation.Configuration;

@Configuration
public class GlobalRouteConfig extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        // Global error handler
        errorHandler(deadLetterChannel("jms:queue:deadLetters")
            .maximumRedeliveries(3)
            .redeliveryDelay(5000)
            .retryAttemptedLogLevel(LoggingLevel.WARN));

        // Idempotent repository
        //IdempotentRepository idempotentRepo = MemoryIdempotentRepository.memoryIdempotentRepository(1000);

        // ❌ Commented out to avoid duplicate consumption of customerOrders
        /*
        from("jms:queue:customerOrders")
            .routeId("customer-orders-jms")
            .idempotentConsumer(
                header("OrderId"),
                idempotentRepo
            )
            .log("Received JMS order with ID: ${header.OrderId}")
            .to("direct:orderProcessing");
        */

        // AWS SQS route: grocer-orders (LocalStack)
        from("aws2-sqs://grocer-orders"
            + "?autoCreateQueue=true"
            + "&region=us-east-1"
            + "&accessKey=test"
            + "&secretKey=test"
            + "&overrideEndpoint=true"
            + "&uriEndpointOverride=http://localhost:4566")
            .routeId("grocer-orders-sqs")
            .log("✅ Received SQS message from LocalStack: ${body}");
    }
}