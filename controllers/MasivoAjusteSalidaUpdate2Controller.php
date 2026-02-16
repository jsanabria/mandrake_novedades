<?php

namespace PHPMaker2021\mandrake;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * masivo_ajuste_salida_update2 controller
 */
class MasivoAjusteSalidaUpdate2Controller extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MasivoAjusteSalidaUpdate2");
    }
}
