<?php
session_start();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    public function userInfo(Request $request, Response $response, $args)
    {

        if (isset($_SESSION['User'])) {
            $_SESSION['Utente']->updateInfo();
            $response->getBody()->write(json_encode($_SESSION['Utente']));
        } else {

            $response->getBody()->write(
                json_encode(
                    [
                        'Error' => '500',
                        'msg' => 'No id found'
                    ]
                )
            );
            return $response->withHeader("Content-type", "application/json")->withStatus(500);
        }

        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }
}
