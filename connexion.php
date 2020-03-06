<?php

// CONNEXION A LA BD  -----------------------------------------------
// Paramètres de connexion au serveur de données
$host = 'eu-cdbr-west-02.cleardb.net';
$user = 'b78a5d5ddfc094';
$pass = 'ed31129b';
$dbname = 'heroku_4fbec00e63bf317';

$dsn = "mysql:host=$host;dbname=$dbname";

$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4');

try {
    $pdo = new \PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}