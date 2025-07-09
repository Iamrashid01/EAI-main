package com.grocers.api;

import com.grocers.domain.Order;
import org.apache.camel.ProducerTemplate;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/orders")
public class OrderController {

    @Autowired
    private ProducerTemplate producerTemplate;

    // JSON input
    @PostMapping(value = "/jms", consumes = "application/json")
    public String sendJsonOrder(@RequestBody Order order) {
        producerTemplate.sendBodyAndHeader("jms:queue:customerOrders", order, "OrderId", order.getId());
        return "JSON Order sent to customerOrders JMS queue.";
    }

    // CSV input
    @PostMapping(value = "/csv", consumes = "text/plain")
    public String sendCsvOrder(@RequestBody String csv) {
        String[] parts = csv.split(",");
        Order order = new Order();
        order.setId(parts[0]);
        order.setProduct(parts[1]);
        order.setQuantity(Integer.parseInt(parts[2]));
        order.setFormat(parts[3]);

        producerTemplate.sendBodyAndHeader("jms:queue:customerOrders", order, "OrderId", order.getId());
        return "CSV Order sent to customerOrders JMS queue.";
    }

    // XML input
    @PostMapping(value = "/xml", consumes = "application/xml")
    public String sendXmlOrder(@RequestBody Order order) {
        producerTemplate.sendBodyAndHeader("jms:queue:customerOrders", order, "OrderId", order.getId());
        return "XML Order sent to customerOrders JMS queue.";
    }
}