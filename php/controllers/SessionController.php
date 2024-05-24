<?php
session_start();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SessionController
{
    public function login(Request $request, Response $response, $args)
    {
        $credential = json_decode($request->getBody()->getContents(), true);
        $stm = Database::getInstance()->prepare("SELECT id_utente FROM Utenti WHERE email = :email AND psw = SHA2(:psw,256)");
        $stm->bindParam(':email', $credential['email'], PDO::PARAM_STR);
        $stm->bindParam(':psw', $credential['password'], PDO::PARAM_STR);
        $stm->execute();
        $stm = $stm->fetch(PDO::FETCH_ASSOC);
        if ($stm['id_utente'] != null) {
            $_SESSION['Utente'] = new Utente($stm['id_utente']);
            $response->getBody()->write(json_encode(array("id_utente" => $stm['id_utente']), JSON_PRETTY_PRINT));
            $response->withHeader("Content-type", "application/json");
        }

        return $response->withStatus(200);
    }

    public function signin(Request $request, Response $response, $args)
    {
        $response->getBody()->write('{"route": "signin"}');
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function logout(Request $request, Response $response, $args)
    {
        session_destroy();
        if (isset($_COOKIE['PHPSESSID'])) {
            unset($_COOKIE['PHPSESSID']);
            setcookie('PHPSESSID', '', time() - 3600, '/');
        }
        return $response->withStatus(200);
    }
}
