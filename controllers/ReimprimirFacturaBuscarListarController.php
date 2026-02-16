<?php

namespace PHPMaker2021\mandrake;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * reimprimir_factura_buscar_listar controller
 */
class ReimprimirFacturaBuscarListarController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ReimprimirFacturaBuscarListar");
    }
}
