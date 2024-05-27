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

        

        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }
}
