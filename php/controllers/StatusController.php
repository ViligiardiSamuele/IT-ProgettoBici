<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StatusController
{
  public function status(Request $request, Response $response, $args)
  {
    $response->getBody()->write('{"status": "online"}');
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }
}
