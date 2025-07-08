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

    // Sends order to the shipping route (SQS or internal processing)
    @PostMapping
    public String sendOrder(@RequestBody Order order) {
        producerTemplate.sendBody("seda:shipping", order);
        return "Order sent to shipping route.";
    }

    // Sends order to the customerOrders JMS queue
    @PostMapping("/jms")
    public String sendToCustomerOrders(@RequestBody Order order) {
        producerTemplate.sendBodyAndHeader("jms:queue:customerOrders", order, "OrderId", order.getId());
        return "Order sent to customerOrders JMS queue.";
    }
}