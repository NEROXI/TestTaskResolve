<?php

namespace Root\Html;

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PDO;
use PDOException;

class MariaDBConnector
{
    private PDO $pdo;

    public function __construct(string $host, string $database, string $username, string $password)
    {
        $dsn = "mysql:host=$host;dbname=$database";
        $this->pdo = new PDO($dsn, $username, $password);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getPdoConnection(): PDO
    {
        return $this->pdo;
    }

    public function executeQuery(string $query, array $params = [])
    {
        try {
            $statement = $this->pdo->prepare($query);
            foreach ($params as $key => $value) {
                $statement->bindValue($key, $value);
            }

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Обработка ошибок
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
