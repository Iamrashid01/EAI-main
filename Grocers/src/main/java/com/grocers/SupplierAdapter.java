package com.grocers;

import com.grocers.domain.Order;

public interface SupplierAdapter {
    void processOrder(Order order);
    String getProtocol();
}