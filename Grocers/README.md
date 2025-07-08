## Message Channels and Flow Design

| Interaction              | Channel Type       | Message Format     | Reason                                      |
|--------------------------|--------------------|--------------------|---------------------------------------------|
| Customer → Retailer      | JMS Queue          | XML/JSON/CSV       | Guaranteed delivery, async processing       |
| Retailer → Supplier      | Direct VM          | Canonical XML      | Low-latency in-app routing                  |
| Supplier → Shipper       | SEDA               | EDIFACT            | Async processing, load leveling             |
| Shipper → Logistics      | AWS SQS            | JSON               | Cloud scalability, persistent storage       |
| Error Channel            | Dead Letter Queue  | Error XML          | Failed message handling                     |

**Flow Explanation**:
1. **Customer Orders**: Multiple formats accepted (CSV/XML/JSON) via JMS queue for reliability
2. **Retailer Processing**: 
   - Transforms messages to canonical XML format
   - Uses Direct VM for high-speed in-process routing
3. **Supplier Routing**:
   - Dynamic recipient list based on product category
   - SEDA queues for parallel processing
4. **Shipping Integration**:
   - AWS SQS for cloud-based queueing
   - EDIFACT → JSON transformation for modern APIs