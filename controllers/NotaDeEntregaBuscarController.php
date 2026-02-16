<?php

namespace PHPMaker2021\mandrake;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * nota_de_entrega_buscar controller
 */
class NotaDeEntregaBuscarController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaDeEntregaBuscar");
    }
}
