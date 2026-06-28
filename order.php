<?php

//importamos los ficheros 

require_once 'classes.php';
require_once 'interfaces.php';
require_once 'importer.php';

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
        //Variables globales:

        $errors = 0;
        $result = new ImportResult();

        //Traemos los pedidos actuales del json:
        
        $orders = $this->source->fetchOrders($since);
        
        //var_dump($orders);

        //Recorremos los pedidos y vamos creando objetos con la información de los mismos.
        //La función fetchOrders devuelve el json como array asociativo:

        foreach ($orders as $orderJson) {
            try {

                //Extraemos los datos de lines ya que suponen un array independiente.
                $lines = [];

                //Validamos que todos los campos existan:


                if ($orderJson['lines'] !== null) {
                    foreach ($orderJson['lines'] as $line) {
                        if (
                            empty($line['sku']) ||
                            empty($line['qty']) ||
                            empty($line['unit_price'])
                        ) {
                            throw new Exception('Invalid order line');
                        }

                        $lines[] = new OrderLine(
                            $line['sku'],
                            $line['qty'],
                            $line['unit_price']
                        );
                    }
                }
                else { //Contolamos que si hay elementos vacíos en lines salte una excepción
                    throw new Exception('Order without lines');
                }

                //Validamos que no haya campos vacios, si no los evaluamos como skipped:

                if (
                    empty($orderJson['order_ref']) ||
                    empty($orderJson['customer']['email']) ||
                    empty($orderJson['customer']['name']) ||
                    empty($orderJson['created_at']) ||
                    empty($orderJson['status'])
                ) {
                    $result->skipped++;
                    continue;
                }

                //Creamos un objeto Order con los elementos vacíos del array:
                $order = new Order( 
                    $orderJson['order_ref'],
                    $orderJson['customer']['email'],
                    $orderJson['customer']['name'],
                    new DateTimeImmutable($orderJson['created_at']),
                    $orderJson['status'],
                    $lines
                );

                //Evaluamos si ya hay elementos en el repositorio con el mismo id, para evitar duplicados.
                $existing = $this->repository->findByExternalId($order->externalId);

                if ($existing === null) {
                    //Si no hay elementos duplicados se guarda.
                    $this->repository->save($order);
                    $result->imported++;
                } else {
                    $result->updated++;
                }

            }
            catch(Throwable $e){
                $errors++;
                $result->errors = $errors;
                //Aquí tendría la duda sobre si tratar el elemento no guardado como skipped o como error.
                //En este caso lo trato como error.
            }

        }                        
        
        return $result;

    }
}

