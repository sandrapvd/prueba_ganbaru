<?php

// Fuente de prueba: ejecuta el importador sin un CRM real

final class FakeCrmOrderSource implements CrmOrderSource {
    public function fetchOrders(DateTimeImmutable $since): iterable {
        $json = file_get_contents(__DIR__. '/sample_orders.json');
        return json_decode($json, true);
    }
}

//Creamos una clase para implementar la interfaz de OrderRepository y poder usarla en el init

class FakeOrderRepository implements OrderRepository
{
    public function findByExternalId(string $externalId): ?Order
    {
        return null;
    }

    public function save(Order $order): void
    {
        // No hace nada
    }
}