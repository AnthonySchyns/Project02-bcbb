<?php

// CONNEXION A LA BD  -----------------------------------------------
// Paramètres de connexion au serveur de données
$host = 'g4yltwdo6z0izlm6.chr7pe7iynqr.eu-west-1.rds.amazonaws.com';
$user = 'jq40hf89z7u7twye';
$pass = 'm9jkd42kgd42d587';
$dbname = 'y1kfbq0yhsa094o5';


$dsn = "mysql:host=$host;dbname=$dbname";

$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4');

try {
    $pdo = new \PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}
