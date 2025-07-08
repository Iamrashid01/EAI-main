package com.grocers.camel;

import org.apache.camel.builder.RouteBuilder;
import org.apache.camel.component.aws2.sqs.Sqs2Constants;
import org.apache.camel.model.dataformat.JsonLibrary;
import org.springframework.stereotype.Component;

@Component
public class ShipperRoute extends RouteBuilder {
    @Override
    public void configure() throws Exception {
        from("seda:shipping")
            .routeId("shipper-sqs-route")
            .log("📦 Received shipping request: ${body}")
            .marshal().json(JsonLibrary.Jackson) // Convert POJO to JSON
            .setHeader(Sqs2Constants.SQS_OPERATION, constant("sendMessage")) // AWS SQS operation
            .to("aws2-sqs://{{aws.sqs.queue}}"
                + "?accessKey={{aws.accessKey}}"
                + "&secretKey=RAW({{aws.secretKey}})"
                + "&region={{aws.region}}"
                + "&autoCreateQueue=true"
                + "&overrideEndpoint={{aws.sqs.overrideEndpoint}}"
                + "&uriEndpointOverride={{aws.sqs.uriEndpointOverride}}")
            .log("✅ Order shipped via SQS. Message ID: ${header.CamelAwsSqsMessageId}");
    }
}