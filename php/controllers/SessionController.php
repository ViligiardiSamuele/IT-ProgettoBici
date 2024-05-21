<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SessionController
{
    public function login(Request $request, Response $response, $args)
    {
        $credential = json_decode($request->getBody()->getContents(), true);
        $stm = Database::getInstance()->prepare("SELECT id FROM Utenti WHERE email = :email AND psw = SHA2(:psw,256)");
        $stm->bindParam(':email', $credential['email'], PDO::PARAM_STR);
        $stm->bindParam(':psw', $credential['password'], PDO::PARAM_STR);
        $stm->execute();
        $stm = $stm->fetch(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode("{'id':" . $stm['id'] . "}", JSON_PRETTY_PRINT));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function signin(Request $request, Response $response, $args)
    {
        $response->getBody()->write('{"route": "signin"}');
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function logout(Request $request, Response $response, $args)
    {
        $response->getBody()->write('{"route": "logout"}');
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }
}
