<?php

namespace PHPMaker2021\mandrake;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * ya_fue_procesado controller
 */
class YaFueProcesadoController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "YaFueProcesado");
    }
}
