<?php

namespace Root\Html;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PDOException;
use Symfony\Component\VarDumper\VarDumper;

class Index
{
    private array $baseArray = [
        ["id" => 1, "create" => "14.04.2023", "title" => "array1"],
        ["id" => 4, "create" => "09.02.2023", "title" => "array4"],
        ["id" => 2, "create" => "03.07.2023", "title" => "array2"],
        ["id" => 1, "create" => "22.04.2023", "title" => "array1"],
        ["id" => 2, "create" => "12.12.2023", "title" => "array4"],
        ["id" => 3, "create" => "04.04.2023", "title" => "array3"]
    ];

    private MariaDBConnector $connector;

    public function __construct()
    {
        $this->connector = new MariaDBConnector("mariadb", "senior", "root", "root");
    }

    public function sortArrays(): array
    {
        $unique_ids = array_unique(array_column($this->baseArray, 'id'));

        $result = array_map(
            function ($id) {
                return array_values(array_filter($this->baseArray, function ($item) use ($id) {
                    return $item['id'] === $id;
                }))[0];
            },
            $unique_ids
        );

        return $result;
    }

    public function sortById(): array
    {
        usort($this->baseArray, function ($a, $b) {
            return $a['id'] - $b['id'];
        });

        return $this->baseArray;
    }

    public function filterByTitle(string $title): array
    {
        return array_filter($this->baseArray, function ($item) use ($title) {
            return $item['title'] === $title;
        });
    }

    public function transformArray(): array
    {
        $names = array_column($this->baseArray, 'title');
        $ids = array_column($this->baseArray, 'id');
        $result = array_combine($names, $ids);

        return $result;
    }

    public function getDepartments(): array
    {
        try {
            $result = $this->connector->executeQuery("SELECT department_id
                FROM evaluations
                WHERE gender = TRUE
                GROUP BY department_id
                HAVING COUNT(*) > 0 AND COUNT(CASE WHEN value > 5 THEN 1 ELSE NULL END) = COUNT(*)");

            return $result;
        } catch (PDOException $e) {
            // Обработка ошибок
            echo "Ошибка: " . $e->getMessage();
            return [];
        }
    }

    public function searchByBio($searchTerm): array
    {
        try {
            // Подготавливаем запрос с использованием полнотекстового индекса
            return $this->connector->executeQuery("
                SELECT *
                FROM evaluations
                WHERE MATCH(bio) AGAINST (:searchTerm IN BOOLEAN MODE)",
                ['searchTerm' => "'\"" . $searchTerm . "\"'"]);
        } catch (PDOException $e) {
            // Обработка ошибок
            echo "Ошибка: " . $e->getMessage();
            return [];
        }
    }
}

$index = new Index();
VarDumper::dump($index->sortArrays());
VarDumper::dump($index->sortById());
VarDumper::dump($index->filterByTitle('array1'));
VarDumper::dump($index->transformArray());

// Mysql Tasks

VarDumper::dump($index->getDepartments());
VarDumper::dump($index->searchByBio('Bio John'));
