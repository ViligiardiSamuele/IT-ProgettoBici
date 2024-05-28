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
        if ($stm->rowCount() > 0) {
            $stm = $stm->fetch(PDO::FETCH_ASSOC);
            $_SESSION["id_utente"] = $stm['id_utente'];
            $response->getBody()->write(json_encode(array("id_utente" => $stm['id_utente']), JSON_PRETTY_PRINT));
            $response->withHeader("Content-type", "application/json");
            return $response->withStatus(200);
        }
        return $response->withStatus(400);
    }

    public function signin(Request $request, Response $response, $args)
    {
        $credential = json_decode($request->getBody()->getContents(), true);
        $stm = Database::getInstance()->prepare("SELECT id_utente FROM Utenti WHERE email = :email");
        $stm->bindParam(':email', $credential['email'], PDO::PARAM_STR);
        $stm->execute();
        if ($stm->rowCount() == 0) {
            $stm = Database::getInstance()->prepare("
            INSERT INTO Utenti (nome, cognome, nascita, email, psw)
            values (:nome, :cognome, :nascita, :email, SHA2(:password, 256))");
            $stm->bindParam(':nome', $credential['nome'], PDO::PARAM_STR);
            $stm->bindParam(':cognome', $credential['cognome'], PDO::PARAM_STR);
            $stm->bindParam(':nascita', $credential['nascita'], PDO::PARAM_STR);
            $stm->bindParam(':email', $credential['email'], PDO::PARAM_STR);
            $stm->bindParam(':password', $credential['password'], PDO::PARAM_STR);
            $stm->execute();
            if ($stm) {
                $stm = Database::getInstance()->prepare("SELECT id_utente FROM Utenti WHERE email = :email");
                $stm->bindParam(':email', $credential['email'], PDO::PARAM_STR);
                $stm->execute();
                $stm = $stm->fetch(PDO::FETCH_ASSOC);
                $_SESSION['id_utente'] = $stm["id_utente"];
                $response->getBody()->write('{"msg": "Registrazione avvenuta con successo"}');
                return $response->withHeader("Content-type", "application/json")->withStatus(200);
            } else {
                $response->getBody()->write('{"msg": "Errore nella comunicazione con il database"}');
                return $response->withHeader("Content-type", "application/json")->withStatus(500);
            }
        }
        $response->getBody()->write('{"msg": "L\'email è già stata utilizzata"}');
        return $response->withHeader("Content-type", "application/json")->withStatus(400);
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
