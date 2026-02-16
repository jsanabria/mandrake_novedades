<?php

namespace PHPMaker2021\mandrake;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * subir_costo_guardar controller
 */
class SubirCostoGuardarController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SubirCostoGuardar");
    }
}
