# Grocers EAI Integration

This repository contains:

* **Source Code** (Grocers Spring Boot & Apache Camel integration examples)
* **Assignment Deliverables** (Presentation, Design Document PDFs)
* **Scripts & Configurations** demonstrating: CSV/JSON/SOAP file upload, JMS routing, AWS integrations, database persistence.

---

## Project Structure

```
Grocers/                         # Main Spring Boot project
├── src/main/java/com/grocers/    # Java source code
│   ├── config/                   # Camel & Spring configurations
│   ├── controller/               # REST & file upload controllers
│   ├── model/                    # Domain models (Order, StatusUpdate)
│   ├── routes/                   # Camel route definitions
│   └── adapters/                 # Supplier & AWS adapters
├── src/main/resources/           # application.properties, Camel XML
├── pom.xml                       # Maven build file

Assignment_CaseStudies/          # Submission folder
├── Grocers_EAI_Complete_WithSummary.pptx
├── EAI_Final_Design_Document.pdf
└── README.md (this file)
```

---

## Prerequisites

1. **Java 11+**
2. **Maven 3.6+**
3. **Docker** (for LocalStack or other services)
4. **LocalStack** (optional, for AWS SQS/S3 simulation)

---

## Setup & Build

### 1. Start LocalStack (Optional)

```bash
docker pull localstack/localstack
docker run -d -p 4566:4566 -p 4571:4571 localstack/localstack
```

### 2. Build the Application

```bash
cd Grocers
mvn clean install
```

---

## Running the Application

```bash
cd Grocers
mvn spring-boot:run
```

---

## Command‑Line Examples

### CSV Upload

```bash
curl -X POST "https://localhost:8443/api/files/upload" \
  -H "Content-Type: multipart/form-data" \
  -F "file=@orders.csv" -k
```

### JSON Order Request

```bash
curl -X POST "https://localhost:8443/orders/jms" \
  -H "Content-Type: application/json" \
  -d '{"id":"ORD-701","product":"soap","quantity":4,"format":"json"}' -k
```

### SOAP/XML Order Request

```bash
curl -X POST "https://localhost:8443/ws/OrderSoapEndpoint" \
  -H "Content-Type: text/xml;charset=UTF-8" \
  -d '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">...'</soapenv:Envelope>' -k
```

---

## Project Overview

1. **Customer Interface**

   * REST JSON (`/orders/jms`)
   * CSV file upload (`/api/files/upload`)
   * SOAP endpoint (`/ws/OrderSoapEndpoint`)

2. **Retailer Router**

   * Camel `choice()` routes orders to supplier-specific JMS queues
   * Content-based routing & aggregation patterns

3. **Supplier Adapters**

   * REST/HTTP and SOAP-based external calls
   * Dynamic recipient lists for new suppliers

4. **Shipper Processor**

   * Consumes from `shipperQueue`
   * Sends status updates to `orderStatusUpdate` queue

---

## Non‑Functional Considerations

* **Scalability**: Horizontal scaling via JMS queues, AWS Lambda auto-scale
* **Fault Tolerance**: Durable queues (JMS), Dead Letter Channels, transaction support
* **Performance**: Asynchronous processing, connection pooling, streaming splitter

---

## Deliverables

* **Slide Deck**: `Grocers_EAI_Complete_WithSummary.pptx`
* **Design Document (PDF)**: `EAI_Final_Design_Document.pdf`

---

## Contributing

Feel free to update this README as you add:

* Diagrams into the `docs/` folder
* Screenshots in `docs/images/`
* Additional code examples in `routes/` or `adapters/`

---




**Commands to be used for test**
**CSV**

curl.exe -X POST "https://localhost:8443/api/files/upload" `
  -H "Content-Type: multipart/form-data" `
  -F "file=@orders.csv" `
  -k


**JSON**

curl -X POST "https://localhost:8443/orders/jms" `
  -H "Content-Type: application/json" `
  -d '{"id":"ORD-701","product":"soap","quantity":4,"format":"json"}' `
  -k


**SOAP - XML**

curl -X POST "https://localhost:8443/ws/OrderSoapEndpoint" `
  -H "Content-Type: text/xml;charset=UTF-8" `
  -d "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/'>
         <soapenv:Header/>
         <soapenv:Body>
            <ns2:placeOrder xmlns:ns2='http://soap.grocers.com/'>
               <arg0>
                  <id>ORD-801</id>
                  <product>shampoo</product>
                  <quantity>6</quantity>
                  <format>xml</format>
               </arg0>
            </ns2:placeOrder>
         </soapenv:Body>
      </soapenv:Envelope>" `
  -k


**ScreenShots**
**1 (CSV)**
Logging output confirming CSV file ingestion and multipart parsing of order.csv. Orders extracted from the uploaded file were routed via Apache Camel and dispatched through the supplier queue, successfully activating the delivery flow and status update mechanisms. This validates full support for file-based input → JMS → Supplier → SQS → Status Update
![alt text](image.png)

**2 (JSON)**

Logging output confirming JSON order submission for ORD-701. The durable status queue and SQS dispatch paths were successfully triggered, demonstrating full integration of REST → JMS → Supplier → SQS → Status Update.
![alt text](image-1.png)

**3 (SOAL – XML)**
Logs confirming successful SOAP-based order submission for ORD-801. The integration flow routed the request from CXF endpoint through JMS and SQS, with persistent status tracking
![alt text](image-2.png)


**Prepared by:** Rashid Ali Rabbani