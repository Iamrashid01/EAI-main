package com.grocers.soap;

import com.grocers.domain.Order;
import jakarta.jws.WebMethod;
import jakarta.jws.WebService;
import org.apache.camel.ProducerTemplate;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@WebService(serviceName = "OrderService", targetNamespace = "http://soap.grocers.com/")
@Component
public class OrderSoapEndpoint {

    @Autowired
    private ProducerTemplate producerTemplate;

    @WebMethod
    public String placeOrder(Order order) {
        producerTemplate.sendBodyAndHeader("jms:queue:customerOrders", order, "OrderId", order.getId());
        return "SOAP Order sent to customerOrders JMS queue.";
    }
}