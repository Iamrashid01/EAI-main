package com.grocers.domain;

import jakarta.xml.bind.annotation.XmlRootElement;
import jakarta.xml.bind.annotation.XmlElement;

import java.io.Serializable;

@XmlRootElement(name = "order")
public class Order implements Serializable {

    private static final long serialVersionUID = 1L;

    private String id;
    private String product;
    private int quantity;
    private String format;

    // ✅ Default constructor (required for frameworks like JAXB, Jackson, etc.)
    public Order() {}

    // ✅ Constructor used in your Camel route
    public Order(String id, String product, int quantity, String format) {
        this.id = id;
        this.product = product;
        this.quantity = quantity;
        this.format = format;
    }

    // ✅ Getters and setters with JAXB annotations
    @XmlElement
    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    @XmlElement
    public String getProduct() {
        return product;
    }

    public void setProduct(String product) {
        this.product = product;
    }

    @XmlElement
    public int getQuantity() {
        return quantity;
    }

    public void setQuantity(int quantity) {
        this.quantity = quantity;
    }

    @XmlElement
    public String getFormat() {
        return format;
    }

    public void setFormat(String format) {
        this.format = format;
    }

    @Override
    public String toString() {
        return "Order{" +
                "id='" + id + '\'' +
                ", product='" + product + '\'' +
                ", quantity=" + quantity +
                ", format='" + format + '\'' +
                '}';
    }
}