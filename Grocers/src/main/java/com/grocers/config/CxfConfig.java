package com.grocers.config;

import jakarta.xml.ws.Endpoint;
import org.apache.cxf.Bus;
import org.apache.cxf.jaxws.EndpointImpl;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import com.grocers.soap.OrderSoapEndpoint;

@Configuration
public class CxfConfig {

    private final Bus bus;
    private final OrderSoapEndpoint orderSoapEndpoint;

    public CxfConfig(Bus bus, OrderSoapEndpoint orderSoapEndpoint) {
        this.bus = bus;
        this.orderSoapEndpoint = orderSoapEndpoint;
    }

    @Bean
    public Endpoint endpoint() {
        EndpointImpl endpoint = new EndpointImpl(bus, orderSoapEndpoint);
        endpoint.publish("/OrderSoapEndpoint");
        return endpoint;
    }
}
