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
        IdempotentRepository idempotentRepo = MemoryIdempotentRepository.memoryIdempotentRepository(1000);
        
        // Shared idempotent consumer
        from("jms:queue:customerOrders")
            .idempotentConsumer(
                header("OrderId"), 
                idempotentRepo
            )
            .to("direct:orderProcessing");
    }
}