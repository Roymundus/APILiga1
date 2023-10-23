<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    // get
    $app->get('/team', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT * FROM team');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/player', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL showPlayer()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });


    $app->get('/standings', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL ShowStandings()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/stadium', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL ShowStadium()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/pertandingan', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL ShowPertandingan()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });


    // POST
    $app->post('/stadium', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();

        $id = $parsedBody["id"]; // menambah dengan kolom baru
        $Nama_Stadion = $parsedBody["Nama_Stadion"];
        $Kota_Stadion = $parsedBody["Kota_Stadion"];
        $db = $this->get(PDO::class);

        $query = $db->prepare('CALL InsertStadium() values (?, ?)');

        // urutan harus sesuai dengan values
        $query->execute([$id, $Nama_Stadion, $Kota_Stadion]);

        $lastId = $db->lastInsertId();

        $response->getBody()->write(json_encode(
            [
                'message' => 'Stadion disimpan dengan id ' . $lastId
            ]
        ));

        return $response->withHeader("Content-Type", "application/json");
    });
};
