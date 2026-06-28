<?php
    // Fuente de prueba: ejecuta el importador sin un CRM real
final class FakeCrmOrderSource implements CrmOrderSource {
    public function fetchOrders(DateTimeImmutable $since): iterable {
        $json = file_get_contents(__DIR__. '/sample_orders.json');
        return json_decode($json, true);
    }
}