<?php
require_once('vendor\autoload.php');

use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\ClientBuilder;

try {
    $client = ClientBuilder::create()
        ->withDriver('RS Projet', 'bolt://localhost:7687')
        ->build();
} catch (Exception $e) {
    echo ("error connection");
}
