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

        $query = $db->query('CALL  ShowTim()')->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($query));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/team/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $teamId = $args['id'];
    
        $query = $db->prepare('CALL GetTeamNameById(:teamId)');
        $query->bindParam(':teamId', $teamId, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
        } else {
            // Tim dengan ID tertentu tidak ditemukan
            return $response->withStatus(404);
        }
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

    $app->get('/standings/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $Klasemen_ID = $args['id'];
    
        $query = $db->prepare('CALL GetStandingsById(:Klasemen_ID)');
        $query->bindParam(':Klasemen_ID', $Klasemen_ID, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
        } else {
            // Tim dengan ID tertentu tidak ditemukan
            return $response->withStatus(404);
        }
    });


    $app->get('/player/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $Pemain_ID = $args['id'];
    
        $query = $db->prepare('CALL GetPlayerbyID(:Pemain_ID)');
        $query->bindParam(':Pemain_ID', $Pemain_ID, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
        } else {
            // Pemain dengan ID tertentu tidak ditemukan
            return $response->withStatus(404);
        }
    });


    $app->get('/stadium/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $Stadion_ID = $args['id'];
    
        $query = $db->prepare('CALL GetStadiumbyID(:Stadium_ID)');
        $query->bindParam(':Stadium_ID', $Stadion_ID, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
        } else {
            // Pemain dengan ID tertentu tidak ditemukan
            return $response->withStatus(404);
        }
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

    $app->get('/pertandingan/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $Pertandingan_ID = $args['id'];
    
        $query = $db->prepare('CALL GetMatchById(:Pertandingan_ID)');
        $query->bindParam(':Pertandingan_ID', $Pertandingan_ID, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result));
            return $response->withHeader("Content-Type", "application/json");
        } else {
            // Tim dengan ID tertentu tidak ditemukan
            return $response->withStatus(404);
        }
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
            throw new Exception ("Input tidak boleh kosong");
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
    $app->put('/stadium/{Stadion_ID}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
    
        $Stadion_ID = $args['Stadion_ID'];
        $Nama_Stadion = $parsedBody["Nama_Stadion"];
        $Kota_Stadion = $parsedBody["Kota_Stadion"];
    
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('CALL UpdateStadion(?, ?, ?)');
        $query->bindParam(1, $Stadion_ID, PDO::PARAM_INT);
        $query->bindParam(2, $Nama_Stadion, PDO::PARAM_STR);
        $query->bindParam(3, $Kota_Stadion, PDO::PARAM_STR);
    
        $query->execute();
    
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Stadium dengan id ' . $Stadion_ID . ' telah diupdate dengan nama ' . $Nama_Stadion
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Stadium dengan id ' . $Stadion_ID . ' gagal diupdate'
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->put('/team/{Tim_ID}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
    
        $Tim_ID = $args['Tim_ID'];
        $Nama_Tim = $parsedBody["Nama_Tim"];
        $Kota_Asal = $parsedBody["Kota_Asal"];
    
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('CALL UpdateTim(?, ?, ?)');
        $query->bindParam(1, $Tim_ID, PDO::PARAM_INT);
        $query->bindParam(2, $Nama_Tim, PDO::PARAM_STR);
        $query->bindParam(3, $Kota_Asal, PDO::PARAM_STR);
    
        $query->execute();
    
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Tim dengan id ' . $Tim_ID . ' telah diupdate dengan nama ' . $Nama_Tim
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Tim dengan id ' . $Tim_ID . ' gagal diupdate'
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });


    $app->put('/pertandingan/{Pertandingan_ID}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
    
        $Pertandingan_ID = $args['Pertandingan_ID'];
        $Tanggal_Pertandingan = $parsedBody["Tanggal_Pertandingan"];
        $Skor_Tuan_Rumah = $parsedBody["Skor_Tuan_Rumah"];
        $Skor_Tamu = $parsedBody["Skor_Tamu"];
        $Tim_Tuan_Rumah_ID = $parsedBody["Tim_Tuan_Rumah_ID"];
        $Tim_Tamu_ID = $parsedBody["Tim_Tamu_ID"];
        $Stadion_ID = $parsedBody["Stadion_ID"];
    
    
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('CALL UpdatePertandingan(?, ?, ?, ?, ?, ?, ?)');
        $query->bindParam(1, $Pertandingan_ID, PDO::PARAM_INT);
        $query->bindParam(2, $Tanggal_Pertandingan, PDO::PARAM_STR);
        $query->bindParam(3, $Skor_Tuan_Rumah, PDO::PARAM_STR);
        $query->bindParam(4, $Skor_Tamu, PDO::PARAM_STR);
        $query->bindParam(5, $Tim_Tuan_Rumah_ID, PDO::PARAM_STR);
        $query->bindParam(6, $Tim_Tamu_ID, PDO::PARAM_STR);
        $query->bindParam(7, $Stadion_ID, PDO::PARAM_STR);
        
    
        $query->execute();
    
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pertandingan dengan ID ' . $Pertandingan_ID . ' telah diupdate '
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pertandingan dengan id ' . $Pertandingan_ID . ' gagal diupdate'
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });


    $app->put('/player/{Pemain_ID}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
    
        $Pemain_ID = $args['Pemain_ID'];
        $Nama_Pemain = $parsedBody["Nama_Pemain"];
        $Tanggal_Lahir = $parsedBody["Tanggal_Lahir"];
        $Tinggi = $parsedBody["Tinggi"];
        $Posisi_Pemain = $parsedBody["Posisi_Pemain"];
        $Nomor_Punggung = $parsedBody["Nomor_Punggung"];
        $Tim_ID = $parsedBody["Tim_ID"];
    
    
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('CALL UpdatePlayerByID(?, ?, ?, ?, ?, ?, ?)');
        $query->bindParam(1, $Pemain_ID, PDO::PARAM_INT);
        $query->bindParam(2, $Nama_Pemain, PDO::PARAM_STR);
        $query->bindParam(3, $Tanggal_Lahir, PDO::PARAM_STR);
        $query->bindParam(4, $Tinggi, PDO::PARAM_STR);
        $query->bindParam(5, $Posisi_Pemain, PDO::PARAM_STR);
        $query->bindParam(6, $Nomor_Punggung, PDO::PARAM_STR);
        $query->bindParam(7, $Tim_ID, PDO::PARAM_STR);
        
    
        $query->execute();
    
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Player dengan ID ' . $Pemain_ID . ' telah diupdate'
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Player dengan id ' . $Pemain_ID . ' gagal diupdate'
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });


    $app->put('/standings/{Klasemen_ID}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
    
        $Klasemen_ID = $args['Klasemen_ID'];
        $Jumlah_Pertandingan = $parsedBody["Jumlah_Pertandingan"];
        $Jumlah_Kemenangan = $parsedBody["Jumlah_Kemenangan"];
        $Jumlah_Seri = $parsedBody["Jumlah_Seri"];
        $Jumlah_Kekalahan = $parsedBody["Jumlah_Kekalahan"];
        $Jumlah_Gol = $parsedBody["Jumlah_Gol"];
        $Jumlah_Gol_Kebobolan = $parsedBody["Jumlah_Gol_Kebobolan"];
        $Poin = $parsedBody["Poin"];
        $Tim_ID = $parsedBody["Tim_ID"];
    
    
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('CALL UpdateStandings(?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $query->bindParam(1, $Klasemen_ID, PDO::PARAM_INT);
        $query->bindParam(2, $Jumlah_Pertandingan, PDO::PARAM_STR);
        $query->bindParam(3, $Jumlah_Kemenangan, PDO::PARAM_STR);
        $query->bindParam(4, $Jumlah_Seri, PDO::PARAM_STR);
        $query->bindParam(5, $Jumlah_Kekalahan, PDO::PARAM_STR);
        $query->bindParam(6, $Jumlah_Gol, PDO::PARAM_STR);
        $query->bindParam(7, $Jumlah_Gol_Kebobolan, PDO::PARAM_STR);
        $query->bindParam(8, $Poin, PDO::PARAM_STR);
        $query->bindParam(9, $Tim_ID, PDO::PARAM_STR);
        
    
        $query->execute();
    
        if ($query) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Klasemen dengan ID ' . $Klasemen_ID . ' telah diupdate'
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Klasemen dengan id ' . $Klasemen_ID . ' gagal diupdate'
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });


    // DELETE

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
