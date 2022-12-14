<?php

namespace App\Modules\Subcategories;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Subcategories
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getSubcategories(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT
            subcategories.id,
            subcategories.category_id,
            categories.slug AS 'category_slug',
            subcategories.title,
            subcategories.slug,
            subcategories.description,
            subcategories.sort_weight,
            subcategories.image
            FROM
            subcategories
            JOIN
            categories
            ON
            categories.id = subcategories.category_id";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
