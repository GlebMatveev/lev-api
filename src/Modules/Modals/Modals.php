<?php

namespace App\Modules\Modals;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Modals
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getModalByCode(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $code = $request->getAttribute('code');

        $sql = "SELECT
                    modals.id,
                    modals.code,
                    modals.content,
                    modals.activity_from,
                    modals.activity_to,
                    modals.activity,
                    modals.created_at,
                    modals.modified_at
                FROM
                    modals
                WHERE
                    modals.code = '$code'";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
