@Component("xmlSupplierAdapter")
public class XmlSupplierAdapter implements SupplierAdapter {
    @Override
    public void processOrder(Order order) {
        // Assignment-specific XML handling
        System.out.println("Processing XML order: " + order.getId());
    }
}