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

    @PostMapping
    public String sendOrder(@RequestBody Order order) {
        producerTemplate.sendBody("seda:shipping", order);
        return "Order sent to shipping route.";
    }
}