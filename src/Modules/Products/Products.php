<?php

namespace App\Modules\Products;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Products
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getProducts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT
            products.id,
            products.category_id,
            categories.slug AS 'category_slug',
            products.subcategory_id,
            subcategories.slug AS 'subcategory_slug',
            products.title,
            products.slug,
            products.description,
            products.ingredients,
            products.quantity,
            products.quantity_unit,
            products.weight,
            products.weight_unit,
            products.volume,
            products.volume_unit,
            products.price,
            products.price_unit,
            products.old_price,
            products.old_price_unit,
            products.sort_weight,
            products.image,
            products.activity
            FROM
            products
            JOIN
            categories
            ON
            categories.id = products.category_id
            JOIN
            subcategories
            ON
            subcategories.id = products.subcategory_id";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
