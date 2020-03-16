<?php

// CONNEXION A LA BD  -----------------------------------------------
// Paramètres de connexion au serveur de données
$host = 'mysql';
$user = 'root';
$pass = 'root';
$dbname = 'dbBCBB';

$dsn = "mysql:host=$host;dbname=$dbname";

$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4');

try {
    $pdo = new \PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}
