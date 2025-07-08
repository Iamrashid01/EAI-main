package com.grocers.adapters;

import com.grocers.SupplierAdapter;
import com.grocers.domain.Order;
import org.springframework.stereotype.Component;

@Component("xmlSupplierAdapter")
public class XmlSupplierAdapter implements SupplierAdapter {

    @Override
    public void processOrder(Order order) {
        // Assignment-specific XML handling
        System.out.println("📦 Processing XML order: " + order.getId());
    }

    @Override
    public String getProtocol() {
        return "xml";
    }
}