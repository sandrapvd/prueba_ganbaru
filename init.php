<?php

require_once 'classes.php';
require_once 'interfaces.php';
require_once 'importer.php';
require_once 'order.php';

//Creamos dos clases auxiliares para implementar las interfaces:

class FakeCrmSource implements CrmOrderSource
{
    public function fetchOrders(DateTimeImmutable $since): iterable
    {
        return [];
    }
}

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

$source = new FakeCrmSource();
$repository = new FakeOrderRepository();

$importer = new CrmOrderImporter($source, $repository);

$result = $importer->import(new DateTimeImmutable());
