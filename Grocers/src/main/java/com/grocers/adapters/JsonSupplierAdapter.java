@Component("jsonSupplierAdapter")
public class JsonSupplierAdapter implements SupplierAdapter {
    @Override
    public void processOrder(Order order) {
        // JSON-specific processing logic
    }

    @Override
    public String getProtocol() {
        return "JSON";
    }
}