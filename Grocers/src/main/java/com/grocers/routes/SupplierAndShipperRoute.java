package com.grocers.routes;

import com.grocers.adapters.JsonSupplierAdapter;
import com.grocers.adapters.XmlSupplierAdapter;
import com.grocers.domain.Order;
import org.apache.camel.builder.RouteBuilder;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
public class SupplierAndShipperRoute extends RouteBuilder {

    @Autowired
    private JsonSupplierAdapter jsonSupplierAdapter;

    @Autowired
    private XmlSupplierAdapter xmlSupplierAdapter;

    @Override
    public void configure() {

        // 🔁 Supplier protocol registry simulation
        from("direct:routeToSupplier")
            .routeId("dynamic-supplier-router")
            .process(exchange -> {
                Order order = exchange.getIn().getBody(Order.class);
                if ("json".equalsIgnoreCase(order.getFormat())) {
                    jsonSupplierAdapter.processOrder(order);
                } else if ("xml".equalsIgnoreCase(order.getFormat())) {
                    xmlSupplierAdapter.processOrder(order);
                }
            })
            .choice()
                .when(simple("${body.product} == 'milk'"))
                    .log("🥛 Routing to milk supplier via HTTP")
                    .setHeader("originalOrder", simple("${body}"))
                    .to("http://localhost:8081/api/milkSupplier")
                    .setBody(header("originalOrder"))
                    .to("jms:queue:shipperQueue")
                    .to("seda:shipping")
                .when(simple("${body.product} == 'oil'"))
                    .log("🛢️ Routing to oil supplier via SOAP adapter")
                    .to("direct:oilSupplierSoapAdapter")
                .otherwise()
                    .log("📦 Routing to ${body.product}SupplierQueue via JMS")
                    .recipientList(simple("jms:queue:${body.product}SupplierQueue"));

        // 🧼 Example SOAP adapter (mocked)
        from("direct:oilSupplierSoapAdapter")
            .routeId("oil-soap-adapter")
            .log("🧼 Sending order to oil supplier via SOAP: ${body}")
            .to("jms:queue:shipperQueue")
            .to("seda:shipping");

        // 📦 Generic supplier fallback
        from("jms:queue:genericSupplierQueue")
            .routeId("generic-supplier")
            .log("📦 Generic supplier processing order: ${body}")
            .to("jms:queue:shipperQueue")
            .to("seda:shipping");

        // ✅ Supplier routes for dynamically routed products
        from("jms:queue:soapSupplierQueue")
            .routeId("soap-supplier")
            .log("🧼 Soap supplier processing order: ${body}")
            .to("jms:queue:shipperQueue")
            .to("seda:shipping");

        from("jms:queue:shampooSupplierQueue")
            .routeId("shampoo-supplier")
            .log("🧴 Shampoo supplier processing order: ${body}")
            .to("jms:queue:shipperQueue")
            .to("seda:shipping");

        from("jms:queue:toothpasteSupplierQueue")
            .routeId("toothpaste-supplier")
            .log("🪥 Toothpaste supplier processing order: ${body}")
            .to("jms:queue:shipperQueue")
            .to("seda:shipping");

        // 🚚 Shipper processing
        from("jms:queue:shipperQueue")
            .routeId("shipper-route")
            .log("🚚 Shipper received order for delivery: ${body} (${body.class.name})")
            .to("jms:queue:orderStatusUpdate");
    }
}