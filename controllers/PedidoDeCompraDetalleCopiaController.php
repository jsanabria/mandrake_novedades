<?php

namespace PHPMaker2021\mandrake;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * pedido_de_compra_detalle_copia controller
 */
class PedidoDeCompraDetalleCopiaController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidoDeCompraDetalleCopia");
    }
}
