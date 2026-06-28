<?php

// Implementa este método. Debe poder re-ejecutarse SIN duplicar.

final class CrmOrderImporter
{
    public function __construct(
        private CrmOrderSource $source,
        private OrderRepository $repository
    ) {
    }

    public function import(DateTimeImmutable $since): ImportResult
    {
        $result = new ImportResult();

        // TODO: Implementar la lógica de importación.

        return $result;
    }
}
