package com.grocers.routes;

import com.grocers.domain.Order;
import org.apache.camel.Exchange;
import org.apache.camel.Processor;
import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class SupplierAndShipperRoute extends RouteBuilder {
    @Override
    public void configure() {

        // 🔁 Supplier protocol registry simulation
        from("direct:routeToSupplier")
            .routeId("dynamic-supplier-router")
            .choice()
                .when(simple("${body.product} == 'milk'"))
                    .log("🥛 Routing to milk supplier via HTTP")
                    .setHeader("originalOrder", simple("${body}"))
                    .to("http://localhost:8081/api/milkSupplier")
                    .setBody(header("originalOrder"))
                    .to("jms:queue:shipperQueue")
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
            .to("jms:queue:shipperQueue");

        // 📦 Generic supplier fallback
        from("jms:queue:genericSupplierQueue")
            .routeId("generic-supplier")
            .log("📦 Generic supplier processing order: ${body}")
            .to("jms:queue:shipperQueue");

        // ✅ Supplier routes for dynamically routed products
        from("jms:queue:soapSupplierQueue")
            .routeId("soap-supplier")
            .log("🧼 Soap supplier processing order: ${body}")
            .to("jms:queue:shipperQueue");

        from("jms:queue:shampooSupplierQueue")
            .routeId("shampoo-supplier")
            .log("🧴 Shampoo supplier processing order: ${body}")
            .to("jms:queue:shipperQueue");

        from("jms:queue:toothpasteSupplierQueue")
            .routeId("toothpaste-supplier")
            .log("🪥 Toothpaste supplier processing order: ${body}")
            .to("jms:queue:shipperQueue");

        // 🚚 Shipper processing
        from("jms:queue:shipperQueue")
            .routeId("shipper-route")
            .log("🚚 Shipper received order for delivery: ${body} (${body.class.name})")
            .to("jms:queue:orderStatusUpdate");
    }
}