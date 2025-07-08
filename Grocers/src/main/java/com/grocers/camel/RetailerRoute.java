from("direct:supplierRouting")
    .process(exchange -> {
        Order order = exchange.getIn().getBody(Order.class);
        String supplierType = order.getProductType() + "Adapter";
        exchange.getIn().setHeader("supplierAdapter", supplierType);
    })
    .recipientList(header("supplierAdapter"))
    .log("Routed to ${header.supplierAdapter}");