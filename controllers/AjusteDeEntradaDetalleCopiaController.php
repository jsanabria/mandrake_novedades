<?php

namespace PHPMaker2021\mandrake;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * ajuste_de_entrada_detalle_copia controller
 */
class AjusteDeEntradaDetalleCopiaController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AjusteDeEntradaDetalleCopia");
    }
}
