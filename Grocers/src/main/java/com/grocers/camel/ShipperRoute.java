import org.apache.camel.component.aws.sqs.SqsConstants;

public class ShipperRoute extends RouteBuilder {
    @Override
    public void configure() throws Exception {
        from("seda:shipping")
            .log("Received shipping request: ${body}")
            // Transform to cloud-friendly JSON
            .marshal().json(JsonLibrary.Jackson)
            // AWS SQS integration
            .setHeader(SqsConstants.SQS_OPERATION, constant("sendMessage"))
            // Add to existing route
        .to("aws-sqs://{{aws.sqs.queue}}?accessKey={{aws.accessKey}}"
            + "&secretKey=RAW({{aws.secretKey}})"
            + "®ion={{aws.region}}"
            + "&autoCreateQueue=true") // Critical for assignment demo
            .log("Order shipped via SQS: ${header.CamelAwsSqsMessageId}");
    }
}