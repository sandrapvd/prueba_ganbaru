<?php

// Interfaces — NO las modifiques

interface CrmOrderSource
{
    /**
     * Pedidos creados o modificados desde $since.
     * Puede ser MUCHO volumen.
     *
     * @return iterable<array> Cada elemento es un pedido en crudo del CRM.
     */
    public function fetchOrders(DateTimeImmutable $since): iterable;
}

interface OrderRepository
{
    public function findByExternalId(string $externalId): ?Order;

    public function save(Order $order): void; // Crea o actualiza
}