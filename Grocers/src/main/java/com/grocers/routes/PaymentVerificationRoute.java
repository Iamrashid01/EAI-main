package com.grocers.routes;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class PaymentVerificationRoute extends RouteBuilder {

    @Override
    public void configure() throws Exception {
        from("jms:queue:paymentVerification")
            .routeId("payment-verification")
            .log("💳 Verifying payment for Order ID: ${body.id}");
    }
}