<?php
session_start();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GaraController
{
    public function gara(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $response->getBody()->write(json_encode(new Gara($args['id'])));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function gare(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $stm = Database::getInstance()->prepare("
            select id_gara
            from Gare
        ");
        $stm->execute();
        $id_gare = $stm->fetchAll(PDO::FETCH_ASSOC);

        $gare = [];
        foreach ($id_gare as $key => $value) {
            array_push($gare, new Gara($value['id_gara']));
        }

        $response->getBody()->write(json_encode($gare), JSON_PRETTY_PRINT);
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function gareAperte(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $stm = Database::getInstance()->prepare("
            SELECT g.id_gara
            FROM Gare g
            LEFT JOIN Concorrenti c ON g.id_gara = c.id_gara AND c.id_utente = :id_utente
            WHERE g.chiusa = false AND c.id_utente IS NULL;
        ");
        $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
        $stm->execute();
        $id_gare = $stm->fetchAll(PDO::FETCH_ASSOC);

        $gare = [];
        foreach ($id_gare as $key => $value) {
            array_push($gare, new Gara($value['id_gara']));
        }

        $response->getBody()->write(json_encode($gare), JSON_PRETTY_PRINT);
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function gareDellUtente(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $stm = Database::getInstance()->prepare("
            select id_gara
            from Concorrenti
            where id_utente = :id_utente
        ");
        $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
        $stm->execute();
        $id_gare = $stm->fetchAll(PDO::FETCH_ASSOC);

        $gare = [];
        foreach ($id_gare as $key => $value) {
            array_push($gare, new Gara($value['id_gara']));
        }

        $response->getBody()->write(json_encode($gare), JSON_PRETTY_PRINT);
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function utenteNonIscritto(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $stm = Database::getInstance()->prepare("
            SELECT 
                g.id_gara
            FROM 
                Gare g
            LEFT JOIN 
                Concorrenti c ON g.id_gara = c.id_gara AND c.id_utente = :id_utente
            WHERE 
                c.id_utente IS NULL
        ");
        $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
        $stm->execute();
        $id_gare = $stm->fetchAll(PDO::FETCH_ASSOC);

        $gare = [];
        foreach ($id_gare as $key => $value) {
            array_push($gare, new Gara($value['id_gara']));
        }

        $response->getBody()->write(json_encode($gare), JSON_PRETTY_PRINT);
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function iscriviUtente(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        //Controllo esistenza gara
        $stm = Database::getInstance()->prepare("
                select nome from Gare where id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
        $stm->execute();
        if ($stm->rowCount() > 0) {
            //Controllo iscrizione già avvenuta
            $stm = Database::getInstance()->prepare("
                select *
                from Concorrenti
                where id_gara = :id_gara and id_utente = :id_utente
            ");
            $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
            $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
            $stm->execute();
            if ($stm->rowCount() == 0) {
                $gara = new Gara($args["id"]);
                $oggi = new DateTime();
                $utente = new Utente($_SESSION['id_utente']);
                if ($oggi->diff($utente->getNascita())->y >= $gara->getMinEta()) {
                    $stm = Database::getInstance()->prepare("
                        INSERT INTO Concorrenti (id_gara, id_utente)
                        VALUES (:id_gara, :id_utente)
                    ");
                    $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
                    $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
                    $stm->execute();
                    if ($stm) {
                        $response->getBody()->write(json_encode([
                            "msg" => "Iscrizione avvenuta con successo"
                        ], JSON_PRETTY_PRINT));
                        return $response->withHeader("Content-type", "application/json")->withStatus(200);
                    } else {
                        $response->getBody()->write(json_encode([
                            "msg" => "Errore nell'inserimento della registrazione"
                        ], JSON_PRETTY_PRINT));
                        return $response->withHeader("Content-type", "application/json")->withStatus(500);
                    }
                }
                $response->getBody()->write(json_encode([
                    "msg" => "La gara scelta richiede un' età minima di " . $gara->getMinEta()
                ], JSON_PRETTY_PRINT));
                return $response->withHeader("Content-type", "application/json")->withStatus(400);
            }
            return $response->withHeader("Content-type", "application/json")->withStatus(200);
        }
        $response->getBody()->write(json_encode([
            "msg" => "La gara (ID: " . $args['id'] . ") non esiste: Controlla di aver inserito correttamente l'ID"
        ], JSON_PRETTY_PRINT));
        return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
}
