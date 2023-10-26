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

        $query = $db->query('CALL  ShowTim()');
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

        try {
            $parseBody = $request->getParsedBody();
        if(
            empty($parseBody["id"]) ||
            empty($parseBody["Nama_Stadion"]) ||
            empty($parseBody["Kota_Stadion"])
        ) {
            throw new Exception ("Input tidak boleh kosong");
        }

        $id = $parseBody["id"];
        $Nama_Stadion = $parseBody["Nama_Stadion"];
        $Kota_Stadion = $parseBody["Kota_Stadion"];
        $db = $this->get(PDO::class);
        $query = $db->prepare('CALL InsertStadium (?, ?, ?)');

        // urutan harus sesuai dengan values
        $query->execute([$id, $Nama_Stadion, $Kota_Stadion]);
        $lastIdQuery = $db->query("SELECT @lastId as last_id");
        $lastId = $lastIdQuery->fetch(PDO::FETCH_ASSOC)['last_id'];

        $response->getBody()->write(json_encode(
            [
                'message' => 'Stadion disimpan dengan id ' . $lastId
            ]
        ));

        return $response->withHeader("Content-Type", "application/json");
    } 
    catch (Exception $exception) {
        $errorResponse = ['error' => $exception->getMessage()];
        $response = $response
        ->withStatus(400)
        ->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode($errorResponse));
        return $response;
    }
    });

    // =====================================================================
    $app->post('/player', function (Request $request, Response $response) {

        try {
            $parseBody = $request->getParsedBody();
        if(
            empty($parseBody["Pemain_ID"]) ||
            empty($parseBody["Nama_Pemain"]) ||
            empty($parseBody["Tanggal_Lahir"]) ||
            empty($parseBody["Tinggi"]) ||
            empty($parseBody["Posisi_Pemain"]) ||
            empty($parseBody["Nomor_Punggung"]) ||
            empty($parseBody["Tim_ID"])
        ) {
            throw new Exception ("Input tidak boleh kosong");
        }

        $Pemain_ID = $parseBody["Pemain_ID"];
        $Nama_Pemain = $parseBody["Nama_Pemain"];
        $Tanggal_Lahir = $parseBody["Tanggal_Lahir"];
        $Tinggi = $parseBody["Tinggi"];
        $Posisi_Pemain = $parseBody["Posisi_Pemain"];
        $Nomor_Punggung = $parseBody["Nomor_Punggung"];
        $Tim_ID = $parseBody["Tim_ID"];
        $db = $this->get(PDO::class);
        $query = $db->prepare('CALL InsertPlayer (?, ?, ?, ?, ?, ?, ?)');

        // urutan harus sesuai dengan values
        $query->execute([$Pemain_ID,$Nama_Pemain, $Tanggal_Lahir, $Tinggi, $Posisi_Pemain, $Nomor_Punggung, $Tim_ID]);
        $lastIdQuery = $db->query("SELECT @lastId as last_id");
        $lastId = $lastIdQuery->fetch(PDO::FETCH_ASSOC)['last_id'];

        $response->getBody()->write(json_encode(
            [
                'message' => 'Nama Pemain disimpan dengan id ' . $lastId
            ]
        ));

        return $response->withHeader("Content-Type", "application/json");
    } 
    catch (Exception $exception) {
        $errorResponse = ['error' => $exception->getMessage()];
        $response = $response
        ->withStatus(400)
        ->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode($errorResponse));
        return $response;
    }
    });

    // =====================================================================
    $app->post('/team', function (Request $request, Response $response) {

        try {
            $parseBody = $request->getParsedBody();
        if(
            empty($parseBody["Tim_ID"]) ||
            empty($parseBody["Nama_Tim"]) ||
            empty($parseBody["Kota_Asal"])
        ) {
            throw new Exception ("Input tidak boleh kosong");
        }

        $Tim_ID = $parseBody["Tim_ID"];
        $Nama_Tim = $parseBody["Nama_Tim"];
        $Kota_Asal = $parseBody["Kota_Asal"];
        $db = $this->get(PDO::class);
        $query = $db->prepare('CALL InsertTim (?, ?, ?)');

        // urutan harus sesuai dengan values
        $query->execute([$Tim_ID,$Nama_Tim, $Kota_Asal]);
        $lastIdQuery = $db->query("SELECT @lastId as last_id");
        $lastId = $lastIdQuery->fetch(PDO::FETCH_ASSOC)['last_id'];

        $response->getBody()->write(json_encode(
            [
                'message' => 'Nama Tim disimpan dengan id ' . $lastId
            ]
        ));

        return $response->withHeader("Content-Type", "application/json");
    } 
    catch (Exception $exception) {
        $errorResponse = ['error' => $exception->getMessage()];
        $response = $response
        ->withStatus(400)
        ->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode($errorResponse));
        return $response;
    }
    });

    // ========================================================================
    $app->post('/pertandingan', function (Request $request, Response $response) {

        try {
            $parseBody = $request->getParsedBody();
        if(
            empty($parseBody["Pertandingan_ID"]) ||
            empty($parseBody["Tanggal_Pertandingan"]) ||
            empty($parseBody["Skor_Tuan_Rumah"]) ||
            empty($parseBody["Skor_Tamu"]) ||
            empty($parseBody["Tim_Tuan_Rumah"]) ||
            empty($parseBody["Tim_Tamu_ID"]) ||
            empty($parseBody["Stadion_ID"])
        ) {
            // throw new Exception ("Input tidak boleh kosong");
        }

        $Pertandingan_ID = $parseBody["Pertandingan_ID"];
        $Tanggal_Pertandingan = $parseBody["Tanggal_Pertandingan"];
        $Skor_Tuan_Rumah = $parseBody["Skor_Tuan_Rumah"];
        $Skor_Tamu= $parseBody["Skor_Tamu"];
        $Tim_Tuan_Rumah_ID = $parseBody["Tim_Tuan_Rumah_ID"];
        $Tim_Tamu_ID = $parseBody["Tim_Tamu_ID"];
        $Stadion_ID = $parseBody["Stadion_ID"];

        $db = $this->get(PDO::class);
        $query = $db->prepare('CALL InsertPertandingan(?, ?, ?, ?, ?, ?, ?)');

        // urutan harus sesuai dengan values
        $query->execute([$Pertandingan_ID, $Tanggal_Pertandingan, $Skor_Tuan_Rumah, $Skor_Tamu, $Tim_Tuan_Rumah_ID, $Tim_Tamu_ID, $Stadion_ID]);
        $lastIdQuery = $db->query("SELECT @lastId as last_id");
        $lastId = $lastIdQuery->fetch(PDO::FETCH_ASSOC)['last_id'];

        $response->getBody()->write(json_encode(
            [
                'message' => 'Pertandingan disimpan dengan id ' . $lastId
            ]
        ));

        return $response->withHeader("Content-Type", "application/json");
    } 
    catch (Exception $exception) {
        $errorResponse = ['error' => $exception->getMessage()];
        $response = $response
        ->withStatus(400)
        ->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode($errorResponse));
        return $response;
    }
    });


     // put data
    $app->put('/countries/{id}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();

        $currentId = $args['id'];
        $Nama_Stadion = $parsedBody["Nama_Stadion"];
        $Kota_Stadion = $parsedBody["Kota_Stadion"];

        $db = $this->get(PDO::class);

        $query = $db->prepare('CALL UpdateStadium (?, ?, ?)');
        $query->bindrParam([1, $currentId, PDO::PARAM_INT]);
        $query->bindrParam([2, $Nama_Stadion, PDO::PARAM_STR]);
        $query->bindrParam([3, $Kota_Stadion, PDO::PARAM_STR]);

        $query->execute();

        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Stadium dengan id ' . $currentId . ' telah diupdate dengan nama ' . $Nama_Stadion
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Stadium dengan id ' . $currentId . ' gagal diupdate'
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });





    // ========================================================================
     // delete data
    $app->delete('/player/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('CALL DeletePlayerByID (?)');
            $query->execute([$currentId]);

            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Player dengan ID ' . $currentId . ' dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });


    $app->delete('/stadium/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('CALL DeleteStadiumByID (?)');
            $query->execute([$currentId]);

            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Stadium dengan ID ' . $currentId . ' dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });


    $app->delete('/pertandingan/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('CALL DeletePertandingan (?)');
            $query->execute([$currentId]);

            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Pertadingan dengan ID ' . $currentId . ' dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->delete('/Standings/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('CALL DeleteStandings (?)');
            $query->execute([$currentId]);

            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Standings dengan ID ' . $currentId . ' dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });


    $app->delete('/team/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('CALL DeleteTim (?)');
            $query->execute([$currentId]);

            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Tim dengan ID ' . $currentId . ' dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });

};
