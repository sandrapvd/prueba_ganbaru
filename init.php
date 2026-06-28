<?php

//importamos los ficheros 

require_once 'classes.php';
require_once 'interfaces.php';
require_once 'importer.php';
require_once 'order.php';

$source = new FakeCrmOrderSource();
$repository = new FakeOrderRepository();

$orderImporter = new CrmOrderImporter($source, $repository);

$result = $orderImporter->import(new DateTimeImmutable());

var_dump($result);