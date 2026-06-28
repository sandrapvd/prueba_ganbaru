<?php

// Dominio (puedes ampliarlo si lo necesitas)

final class Order
{
    public function __construct(
        public string $externalId,
        public string $customerEmail,
        public string $customerName,
        public DateTimeImmutable $createdAt,
        public string $status,
        public array $lines // OrderLine[]
    ) {
    }
}

final class OrderLine
{
    public function __construct(
        public string $sku,
        public int $qty,
        public float $unitPrice
    ) {
    }
}

final class ImportResult
{
    public int $imported = 0;
    public int $updated = 0;
    public int $skipped = 0;
    public int $errors = 0;
}