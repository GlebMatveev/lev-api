<?php

namespace App\Modules\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Example
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getExample(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT
            example.id,
            example.say,
            example.created_at,
            example.modified_at
            FROM
            example";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function postExample(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $sql = "INSERT INTO
        example(
            example.say
        ) VALUES (
            '$data[say]'
        );";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putExample(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        $sql = "UPDATE
            example
            SET 
            example.say = '" . $data["say"] . "' WHERE
            example.id = '" . $id . "';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteExample(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM example WHERE example.id = '" . $id . "';";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
