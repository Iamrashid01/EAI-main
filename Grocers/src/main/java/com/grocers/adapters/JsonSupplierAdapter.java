package com.grocers.adapters;

import com.grocers.SupplierAdapter;
import com.grocers.domain.Order;
import org.springframework.stereotype.Component;

@Component("jsonSupplierAdapter")
public class JsonSupplierAdapter implements SupplierAdapter {
    @Override
    public void processOrder(Order order) {
        System.out.println("🛒 Processing JSON order for: " + order.getProduct());
    }

    @Override
    public String getProtocol() {
        return "JSON";
    }
}