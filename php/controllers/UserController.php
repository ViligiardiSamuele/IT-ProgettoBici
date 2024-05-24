<?php
session_start();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    public function userInfo(Request $request, Response $response, $args)
    {
        $Utente = new Utente($_SESSION["id_utente"]);
        $response->getBody()->write(json_encode($Utente));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function userInfoByID(Request $request, Response $response, $args)
    {
        $response->getBody()->write(json_encode(new Utente($args['id'])));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }
}
