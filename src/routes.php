<?php

namespace PHPMaker2021\mandrake;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Handle Routes
return function (App $app) {
    // abono
    $app->any('/AbonoList[/{id}]', AbonoController::class . ':list')->add(PermissionMiddleware::class)->setName('AbonoList-abono-list'); // list
    $app->any('/AbonoAdd[/{id}]', AbonoController::class . ':add')->add(PermissionMiddleware::class)->setName('AbonoAdd-abono-add'); // add
    $app->any('/AbonoView[/{id}]', AbonoController::class . ':view')->add(PermissionMiddleware::class)->setName('AbonoView-abono-view'); // view
    $app->group(
        '/abono',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', AbonoController::class . ':list')->add(PermissionMiddleware::class)->setName('abono/list-abono-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', AbonoController::class . ':add')->add(PermissionMiddleware::class)->setName('abono/add-abono-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', AbonoController::class . ':view')->add(PermissionMiddleware::class)->setName('abono/view-abono-view-2'); // view
        }
    );

    // abono2
    $app->any('/Abono2List[/{id}]', Abono2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('Abono2List-abono2-list'); // list
    $app->any('/Abono2Add[/{id}]', Abono2Controller::class . ':add')->add(PermissionMiddleware::class)->setName('Abono2Add-abono2-add'); // add
    $app->any('/Abono2View[/{id}]', Abono2Controller::class . ':view')->add(PermissionMiddleware::class)->setName('Abono2View-abono2-view'); // view
    $app->group(
        '/abono2',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', Abono2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('abono2/list-abono2-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', Abono2Controller::class . ':add')->add(PermissionMiddleware::class)->setName('abono2/add-abono2-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', Abono2Controller::class . ':view')->add(PermissionMiddleware::class)->setName('abono2/view-abono2-view-2'); // view
        }
    );

    // actualizar_nota_entrega
    $app->any('/ActualizarNotaEntrega[/{params:.*}]', ActualizarNotaEntregaController::class)->add(PermissionMiddleware::class)->setName('ActualizarNotaEntrega-actualizar_nota_entrega-custom'); // custom

    // Actualizar_tarifa_patron
    $app->any('/ActualizarTarifaPatron[/{params:.*}]', ActualizarTarifaPatronController::class)->add(PermissionMiddleware::class)->setName('ActualizarTarifaPatron-Actualizar_tarifa_patron-custom'); // custom

    // ActualizarExiste
    $app->any('/ActualizarExiste[/{params:.*}]', ActualizarExisteController::class)->add(PermissionMiddleware::class)->setName('ActualizarExiste-ActualizarExiste-custom'); // custom

    // adjunto
    $app->any('/AdjuntoList[/{id}]', AdjuntoController::class . ':list')->add(PermissionMiddleware::class)->setName('AdjuntoList-adjunto-list'); // list
    $app->any('/AdjuntoAdd[/{id}]', AdjuntoController::class . ':add')->add(PermissionMiddleware::class)->setName('AdjuntoAdd-adjunto-add'); // add
    $app->any('/AdjuntoPreview', AdjuntoController::class . ':preview')->add(PermissionMiddleware::class)->setName('AdjuntoPreview-adjunto-preview'); // preview
    $app->group(
        '/adjunto',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', AdjuntoController::class . ':list')->add(PermissionMiddleware::class)->setName('adjunto/list-adjunto-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', AdjuntoController::class . ':add')->add(PermissionMiddleware::class)->setName('adjunto/add-adjunto-add-2'); // add
            $group->any('/' . Config("PREVIEW_ACTION") . '', AdjuntoController::class . ':preview')->add(PermissionMiddleware::class)->setName('adjunto/preview-adjunto-preview-2'); // preview
        }
    );

    // ajustar_existencia_lotes
    $app->any('/AjustarExistenciaLotes[/{params:.*}]', AjustarExistenciaLotesController::class)->add(PermissionMiddleware::class)->setName('AjustarExistenciaLotes-ajustar_existencia_lotes-custom'); // custom

    // ajuste_de_entrada_detalle_copia
    $app->any('/AjusteDeEntradaDetalleCopia[/{params:.*}]', AjusteDeEntradaDetalleCopiaController::class)->add(PermissionMiddleware::class)->setName('AjusteDeEntradaDetalleCopia-ajuste_de_entrada_detalle_copia-custom'); // custom

    // ajuste_salida
    $app->any('/AjusteSalida[/{params:.*}]', AjusteSalidaController::class)->add(PermissionMiddleware::class)->setName('AjusteSalida-ajuste_salida-custom'); // custom

    // alicuota
    $app->any('/AlicuotaList[/{id}]', AlicuotaController::class . ':list')->add(PermissionMiddleware::class)->setName('AlicuotaList-alicuota-list'); // list
    $app->any('/AlicuotaAdd[/{id}]', AlicuotaController::class . ':add')->add(PermissionMiddleware::class)->setName('AlicuotaAdd-alicuota-add'); // add
    $app->any('/AlicuotaView[/{id}]', AlicuotaController::class . ':view')->add(PermissionMiddleware::class)->setName('AlicuotaView-alicuota-view'); // view
    $app->any('/AlicuotaEdit[/{id}]', AlicuotaController::class . ':edit')->add(PermissionMiddleware::class)->setName('AlicuotaEdit-alicuota-edit'); // edit
    $app->any('/AlicuotaDelete[/{id}]', AlicuotaController::class . ':delete')->add(PermissionMiddleware::class)->setName('AlicuotaDelete-alicuota-delete'); // delete
    $app->group(
        '/alicuota',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', AlicuotaController::class . ':list')->add(PermissionMiddleware::class)->setName('alicuota/list-alicuota-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', AlicuotaController::class . ':add')->add(PermissionMiddleware::class)->setName('alicuota/add-alicuota-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', AlicuotaController::class . ':view')->add(PermissionMiddleware::class)->setName('alicuota/view-alicuota-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', AlicuotaController::class . ':edit')->add(PermissionMiddleware::class)->setName('alicuota/edit-alicuota-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', AlicuotaController::class . ':delete')->add(PermissionMiddleware::class)->setName('alicuota/delete-alicuota-delete-2'); // delete
        }
    );

    // almacen
    $app->any('/AlmacenList[/{id}]', AlmacenController::class . ':list')->add(PermissionMiddleware::class)->setName('AlmacenList-almacen-list'); // list
    $app->any('/AlmacenAdd[/{id}]', AlmacenController::class . ':add')->add(PermissionMiddleware::class)->setName('AlmacenAdd-almacen-add'); // add
    $app->any('/AlmacenView[/{id}]', AlmacenController::class . ':view')->add(PermissionMiddleware::class)->setName('AlmacenView-almacen-view'); // view
    $app->group(
        '/almacen',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', AlmacenController::class . ':list')->add(PermissionMiddleware::class)->setName('almacen/list-almacen-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', AlmacenController::class . ':add')->add(PermissionMiddleware::class)->setName('almacen/add-almacen-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', AlmacenController::class . ':view')->add(PermissionMiddleware::class)->setName('almacen/view-almacen-view-2'); // view
        }
    );

    // anular_venta
    $app->any('/AnularVenta[/{params:.*}]', AnularVentaController::class)->add(PermissionMiddleware::class)->setName('AnularVenta-anular_venta-custom'); // custom

    // articulo
    $app->any('/ArticuloList[/{id}]', ArticuloController::class . ':list')->add(PermissionMiddleware::class)->setName('ArticuloList-articulo-list'); // list
    $app->any('/ArticuloAdd[/{id}]', ArticuloController::class . ':add')->add(PermissionMiddleware::class)->setName('ArticuloAdd-articulo-add'); // add
    $app->any('/ArticuloView[/{id}]', ArticuloController::class . ':view')->add(PermissionMiddleware::class)->setName('ArticuloView-articulo-view'); // view
    $app->any('/ArticuloEdit[/{id}]', ArticuloController::class . ':edit')->add(PermissionMiddleware::class)->setName('ArticuloEdit-articulo-edit'); // edit
    $app->any('/ArticuloDelete[/{id}]', ArticuloController::class . ':delete')->add(PermissionMiddleware::class)->setName('ArticuloDelete-articulo-delete'); // delete
    $app->group(
        '/articulo',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ArticuloController::class . ':list')->add(PermissionMiddleware::class)->setName('articulo/list-articulo-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ArticuloController::class . ':add')->add(PermissionMiddleware::class)->setName('articulo/add-articulo-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ArticuloController::class . ':view')->add(PermissionMiddleware::class)->setName('articulo/view-articulo-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ArticuloController::class . ':edit')->add(PermissionMiddleware::class)->setName('articulo/edit-articulo-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ArticuloController::class . ':delete')->add(PermissionMiddleware::class)->setName('articulo/delete-articulo-delete-2'); // delete
        }
    );

    // articulo_anterior
    $app->any('/ArticuloAnteriorList[/{articulo}]', ArticuloAnteriorController::class . ':list')->add(PermissionMiddleware::class)->setName('ArticuloAnteriorList-articulo_anterior-list'); // list
    $app->group(
        '/articulo_anterior',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{articulo}]', ArticuloAnteriorController::class . ':list')->add(PermissionMiddleware::class)->setName('articulo_anterior/list-articulo_anterior-list-2'); // list
        }
    );

    // articulo_porcentaje_descuento_temp
    $app->any('/ArticuloPorcentajeDescuentoTempList[/{codigo}]', ArticuloPorcentajeDescuentoTempController::class . ':list')->add(PermissionMiddleware::class)->setName('ArticuloPorcentajeDescuentoTempList-articulo_porcentaje_descuento_temp-list'); // list
    $app->group(
        '/articulo_porcentaje_descuento_temp',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{codigo}]', ArticuloPorcentajeDescuentoTempController::class . ':list')->add(PermissionMiddleware::class)->setName('articulo_porcentaje_descuento_temp/list-articulo_porcentaje_descuento_temp-list-2'); // list
        }
    );

    // articulo_stock
    $app->any('/ArticuloStockList', ArticuloStockController::class . ':list')->add(PermissionMiddleware::class)->setName('ArticuloStockList-articulo_stock-list'); // list
    $app->group(
        '/articulo_stock',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', ArticuloStockController::class . ':list')->add(PermissionMiddleware::class)->setName('articulo_stock/list-articulo_stock-list-2'); // list
        }
    );

    // articulo_unidad_medida
    $app->any('/ArticuloUnidadMedidaList[/{id}]', ArticuloUnidadMedidaController::class . ':list')->add(PermissionMiddleware::class)->setName('ArticuloUnidadMedidaList-articulo_unidad_medida-list'); // list
    $app->any('/ArticuloUnidadMedidaAdd[/{id}]', ArticuloUnidadMedidaController::class . ':add')->add(PermissionMiddleware::class)->setName('ArticuloUnidadMedidaAdd-articulo_unidad_medida-add'); // add
    $app->any('/ArticuloUnidadMedidaView[/{id}]', ArticuloUnidadMedidaController::class . ':view')->add(PermissionMiddleware::class)->setName('ArticuloUnidadMedidaView-articulo_unidad_medida-view'); // view
    $app->any('/ArticuloUnidadMedidaEdit[/{id}]', ArticuloUnidadMedidaController::class . ':edit')->add(PermissionMiddleware::class)->setName('ArticuloUnidadMedidaEdit-articulo_unidad_medida-edit'); // edit
    $app->any('/ArticuloUnidadMedidaDelete[/{id}]', ArticuloUnidadMedidaController::class . ':delete')->add(PermissionMiddleware::class)->setName('ArticuloUnidadMedidaDelete-articulo_unidad_medida-delete'); // delete
    $app->any('/ArticuloUnidadMedidaPreview', ArticuloUnidadMedidaController::class . ':preview')->add(PermissionMiddleware::class)->setName('ArticuloUnidadMedidaPreview-articulo_unidad_medida-preview'); // preview
    $app->group(
        '/articulo_unidad_medida',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ArticuloUnidadMedidaController::class . ':list')->add(PermissionMiddleware::class)->setName('articulo_unidad_medida/list-articulo_unidad_medida-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ArticuloUnidadMedidaController::class . ':add')->add(PermissionMiddleware::class)->setName('articulo_unidad_medida/add-articulo_unidad_medida-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ArticuloUnidadMedidaController::class . ':view')->add(PermissionMiddleware::class)->setName('articulo_unidad_medida/view-articulo_unidad_medida-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ArticuloUnidadMedidaController::class . ':edit')->add(PermissionMiddleware::class)->setName('articulo_unidad_medida/edit-articulo_unidad_medida-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ArticuloUnidadMedidaController::class . ':delete')->add(PermissionMiddleware::class)->setName('articulo_unidad_medida/delete-articulo_unidad_medida-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', ArticuloUnidadMedidaController::class . ':preview')->add(PermissionMiddleware::class)->setName('articulo_unidad_medida/preview-articulo_unidad_medida-preview-2'); // preview
        }
    );

    // asesor
    $app->any('/AsesorList[/{id}]', AsesorController::class . ':list')->add(PermissionMiddleware::class)->setName('AsesorList-asesor-list'); // list
    $app->any('/AsesorAdd[/{id}]', AsesorController::class . ':add')->add(PermissionMiddleware::class)->setName('AsesorAdd-asesor-add'); // add
    $app->any('/AsesorView[/{id}]', AsesorController::class . ':view')->add(PermissionMiddleware::class)->setName('AsesorView-asesor-view'); // view
    $app->any('/AsesorEdit[/{id}]', AsesorController::class . ':edit')->add(PermissionMiddleware::class)->setName('AsesorEdit-asesor-edit'); // edit
    $app->any('/AsesorDelete[/{id}]', AsesorController::class . ':delete')->add(PermissionMiddleware::class)->setName('AsesorDelete-asesor-delete'); // delete
    $app->group(
        '/asesor',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', AsesorController::class . ':list')->add(PermissionMiddleware::class)->setName('asesor/list-asesor-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', AsesorController::class . ':add')->add(PermissionMiddleware::class)->setName('asesor/add-asesor-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', AsesorController::class . ':view')->add(PermissionMiddleware::class)->setName('asesor/view-asesor-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', AsesorController::class . ':edit')->add(PermissionMiddleware::class)->setName('asesor/edit-asesor-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', AsesorController::class . ':delete')->add(PermissionMiddleware::class)->setName('asesor/delete-asesor-delete-2'); // delete
        }
    );

    // asesor_cliente
    $app->any('/AsesorClienteList[/{id}]', AsesorClienteController::class . ':list')->add(PermissionMiddleware::class)->setName('AsesorClienteList-asesor_cliente-list'); // list
    $app->any('/AsesorClienteAdd[/{id}]', AsesorClienteController::class . ':add')->add(PermissionMiddleware::class)->setName('AsesorClienteAdd-asesor_cliente-add'); // add
    $app->any('/AsesorClienteView[/{id}]', AsesorClienteController::class . ':view')->add(PermissionMiddleware::class)->setName('AsesorClienteView-asesor_cliente-view'); // view
    $app->any('/AsesorClienteEdit[/{id}]', AsesorClienteController::class . ':edit')->add(PermissionMiddleware::class)->setName('AsesorClienteEdit-asesor_cliente-edit'); // edit
    $app->any('/AsesorClienteDelete[/{id}]', AsesorClienteController::class . ':delete')->add(PermissionMiddleware::class)->setName('AsesorClienteDelete-asesor_cliente-delete'); // delete
    $app->any('/AsesorClientePreview', AsesorClienteController::class . ':preview')->add(PermissionMiddleware::class)->setName('AsesorClientePreview-asesor_cliente-preview'); // preview
    $app->group(
        '/asesor_cliente',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', AsesorClienteController::class . ':list')->add(PermissionMiddleware::class)->setName('asesor_cliente/list-asesor_cliente-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', AsesorClienteController::class . ':add')->add(PermissionMiddleware::class)->setName('asesor_cliente/add-asesor_cliente-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', AsesorClienteController::class . ':view')->add(PermissionMiddleware::class)->setName('asesor_cliente/view-asesor_cliente-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', AsesorClienteController::class . ':edit')->add(PermissionMiddleware::class)->setName('asesor_cliente/edit-asesor_cliente-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', AsesorClienteController::class . ':delete')->add(PermissionMiddleware::class)->setName('asesor_cliente/delete-asesor_cliente-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', AsesorClienteController::class . ':preview')->add(PermissionMiddleware::class)->setName('asesor_cliente/preview-asesor_cliente-preview-2'); // preview
        }
    );

    // asesor_fabricante
    $app->any('/AsesorFabricanteList[/{id}]', AsesorFabricanteController::class . ':list')->add(PermissionMiddleware::class)->setName('AsesorFabricanteList-asesor_fabricante-list'); // list
    $app->any('/AsesorFabricanteAdd[/{id}]', AsesorFabricanteController::class . ':add')->add(PermissionMiddleware::class)->setName('AsesorFabricanteAdd-asesor_fabricante-add'); // add
    $app->any('/AsesorFabricanteView[/{id}]', AsesorFabricanteController::class . ':view')->add(PermissionMiddleware::class)->setName('AsesorFabricanteView-asesor_fabricante-view'); // view
    $app->any('/AsesorFabricanteEdit[/{id}]', AsesorFabricanteController::class . ':edit')->add(PermissionMiddleware::class)->setName('AsesorFabricanteEdit-asesor_fabricante-edit'); // edit
    $app->any('/AsesorFabricanteDelete[/{id}]', AsesorFabricanteController::class . ':delete')->add(PermissionMiddleware::class)->setName('AsesorFabricanteDelete-asesor_fabricante-delete'); // delete
    $app->any('/AsesorFabricantePreview', AsesorFabricanteController::class . ':preview')->add(PermissionMiddleware::class)->setName('AsesorFabricantePreview-asesor_fabricante-preview'); // preview
    $app->group(
        '/asesor_fabricante',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', AsesorFabricanteController::class . ':list')->add(PermissionMiddleware::class)->setName('asesor_fabricante/list-asesor_fabricante-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', AsesorFabricanteController::class . ':add')->add(PermissionMiddleware::class)->setName('asesor_fabricante/add-asesor_fabricante-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', AsesorFabricanteController::class . ':view')->add(PermissionMiddleware::class)->setName('asesor_fabricante/view-asesor_fabricante-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', AsesorFabricanteController::class . ':edit')->add(PermissionMiddleware::class)->setName('asesor_fabricante/edit-asesor_fabricante-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', AsesorFabricanteController::class . ':delete')->add(PermissionMiddleware::class)->setName('asesor_fabricante/delete-asesor_fabricante-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', AsesorFabricanteController::class . ':preview')->add(PermissionMiddleware::class)->setName('asesor_fabricante/preview-asesor_fabricante-preview-2'); // preview
        }
    );

    // audittrail
    $app->any('/AudittrailList[/{id}]', AudittrailController::class . ':list')->add(PermissionMiddleware::class)->setName('AudittrailList-audittrail-list'); // list
    $app->any('/AudittrailAdd[/{id}]', AudittrailController::class . ':add')->add(PermissionMiddleware::class)->setName('AudittrailAdd-audittrail-add'); // add
    $app->any('/AudittrailView[/{id}]', AudittrailController::class . ':view')->add(PermissionMiddleware::class)->setName('AudittrailView-audittrail-view'); // view
    $app->group(
        '/audittrail',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', AudittrailController::class . ':list')->add(PermissionMiddleware::class)->setName('audittrail/list-audittrail-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', AudittrailController::class . ':add')->add(PermissionMiddleware::class)->setName('audittrail/add-audittrail-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', AudittrailController::class . ':view')->add(PermissionMiddleware::class)->setName('audittrail/view-audittrail-view-2'); // view
        }
    );

    // balance_de_comprobacion
    $app->any('/BalanceDeComprobacion[/{params:.*}]', BalanceDeComprobacionController::class)->add(PermissionMiddleware::class)->setName('BalanceDeComprobacion-balance_de_comprobacion-custom'); // custom

    // balance_general
    $app->any('/BalanceGeneral[/{params:.*}]', BalanceGeneralController::class)->add(PermissionMiddleware::class)->setName('BalanceGeneral-balance_general-custom'); // custom

    // cierre_de_caja
    $app->any('/CierreDeCajaList[/{id}]', CierreDeCajaController::class . ':list')->add(PermissionMiddleware::class)->setName('CierreDeCajaList-cierre_de_caja-list'); // list
    $app->any('/CierreDeCajaAdd[/{id}]', CierreDeCajaController::class . ':add')->add(PermissionMiddleware::class)->setName('CierreDeCajaAdd-cierre_de_caja-add'); // add
    $app->any('/CierreDeCajaView[/{id}]', CierreDeCajaController::class . ':view')->add(PermissionMiddleware::class)->setName('CierreDeCajaView-cierre_de_caja-view'); // view
    $app->any('/CierreDeCajaEdit[/{id}]', CierreDeCajaController::class . ':edit')->add(PermissionMiddleware::class)->setName('CierreDeCajaEdit-cierre_de_caja-edit'); // edit
    $app->any('/CierreDeCajaDelete[/{id}]', CierreDeCajaController::class . ':delete')->add(PermissionMiddleware::class)->setName('CierreDeCajaDelete-cierre_de_caja-delete'); // delete
    $app->group(
        '/cierre_de_caja',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', CierreDeCajaController::class . ':list')->add(PermissionMiddleware::class)->setName('cierre_de_caja/list-cierre_de_caja-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', CierreDeCajaController::class . ':add')->add(PermissionMiddleware::class)->setName('cierre_de_caja/add-cierre_de_caja-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', CierreDeCajaController::class . ':view')->add(PermissionMiddleware::class)->setName('cierre_de_caja/view-cierre_de_caja-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', CierreDeCajaController::class . ':edit')->add(PermissionMiddleware::class)->setName('cierre_de_caja/edit-cierre_de_caja-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', CierreDeCajaController::class . ':delete')->add(PermissionMiddleware::class)->setName('cierre_de_caja/delete-cierre_de_caja-delete-2'); // delete
        }
    );

    // cliente
    $app->any('/ClienteList[/{id}]', ClienteController::class . ':list')->add(PermissionMiddleware::class)->setName('ClienteList-cliente-list'); // list
    $app->any('/ClienteAdd[/{id}]', ClienteController::class . ':add')->add(PermissionMiddleware::class)->setName('ClienteAdd-cliente-add'); // add
    $app->any('/ClienteAddopt', ClienteController::class . ':addopt')->add(PermissionMiddleware::class)->setName('ClienteAddopt-cliente-addopt'); // addopt
    $app->any('/ClienteView[/{id}]', ClienteController::class . ':view')->add(PermissionMiddleware::class)->setName('ClienteView-cliente-view'); // view
    $app->any('/ClienteEdit[/{id}]', ClienteController::class . ':edit')->add(PermissionMiddleware::class)->setName('ClienteEdit-cliente-edit'); // edit
    $app->any('/ClienteDelete[/{id}]', ClienteController::class . ':delete')->add(PermissionMiddleware::class)->setName('ClienteDelete-cliente-delete'); // delete
    $app->group(
        '/cliente',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ClienteController::class . ':list')->add(PermissionMiddleware::class)->setName('cliente/list-cliente-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ClienteController::class . ':add')->add(PermissionMiddleware::class)->setName('cliente/add-cliente-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', ClienteController::class . ':addopt')->add(PermissionMiddleware::class)->setName('cliente/addopt-cliente-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ClienteController::class . ':view')->add(PermissionMiddleware::class)->setName('cliente/view-cliente-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ClienteController::class . ':edit')->add(PermissionMiddleware::class)->setName('cliente/edit-cliente-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ClienteController::class . ':delete')->add(PermissionMiddleware::class)->setName('cliente/delete-cliente-delete-2'); // delete
        }
    );

    // cobros_cliente
    $app->any('/CobrosClienteList[/{id}]', CobrosClienteController::class . ':list')->add(PermissionMiddleware::class)->setName('CobrosClienteList-cobros_cliente-list'); // list
    $app->any('/CobrosClienteAdd[/{id}]', CobrosClienteController::class . ':add')->add(PermissionMiddleware::class)->setName('CobrosClienteAdd-cobros_cliente-add'); // add
    $app->any('/CobrosClienteView[/{id}]', CobrosClienteController::class . ':view')->add(PermissionMiddleware::class)->setName('CobrosClienteView-cobros_cliente-view'); // view
    $app->any('/CobrosClienteDelete[/{id}]', CobrosClienteController::class . ':delete')->add(PermissionMiddleware::class)->setName('CobrosClienteDelete-cobros_cliente-delete'); // delete
    $app->group(
        '/cobros_cliente',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', CobrosClienteController::class . ':list')->add(PermissionMiddleware::class)->setName('cobros_cliente/list-cobros_cliente-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', CobrosClienteController::class . ':add')->add(PermissionMiddleware::class)->setName('cobros_cliente/add-cobros_cliente-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', CobrosClienteController::class . ':view')->add(PermissionMiddleware::class)->setName('cobros_cliente/view-cobros_cliente-view-2'); // view
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', CobrosClienteController::class . ':delete')->add(PermissionMiddleware::class)->setName('cobros_cliente/delete-cobros_cliente-delete-2'); // delete
        }
    );

    // cobros_cliente_detalle
    $app->any('/CobrosClienteDetalleList[/{id}]', CobrosClienteDetalleController::class . ':list')->add(PermissionMiddleware::class)->setName('CobrosClienteDetalleList-cobros_cliente_detalle-list'); // list
    $app->any('/CobrosClienteDetalleAdd[/{id}]', CobrosClienteDetalleController::class . ':add')->add(PermissionMiddleware::class)->setName('CobrosClienteDetalleAdd-cobros_cliente_detalle-add'); // add
    $app->any('/CobrosClienteDetalleView[/{id}]', CobrosClienteDetalleController::class . ':view')->add(PermissionMiddleware::class)->setName('CobrosClienteDetalleView-cobros_cliente_detalle-view'); // view
    $app->any('/CobrosClienteDetallePreview', CobrosClienteDetalleController::class . ':preview')->add(PermissionMiddleware::class)->setName('CobrosClienteDetallePreview-cobros_cliente_detalle-preview'); // preview
    $app->group(
        '/cobros_cliente_detalle',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', CobrosClienteDetalleController::class . ':list')->add(PermissionMiddleware::class)->setName('cobros_cliente_detalle/list-cobros_cliente_detalle-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', CobrosClienteDetalleController::class . ':add')->add(PermissionMiddleware::class)->setName('cobros_cliente_detalle/add-cobros_cliente_detalle-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', CobrosClienteDetalleController::class . ':view')->add(PermissionMiddleware::class)->setName('cobros_cliente_detalle/view-cobros_cliente_detalle-view-2'); // view
            $group->any('/' . Config("PREVIEW_ACTION") . '', CobrosClienteDetalleController::class . ':preview')->add(PermissionMiddleware::class)->setName('cobros_cliente_detalle/preview-cobros_cliente_detalle-preview-2'); // preview
        }
    );

    // cobros_cliente_factura
    $app->any('/CobrosClienteFacturaList[/{id}]', CobrosClienteFacturaController::class . ':list')->add(PermissionMiddleware::class)->setName('CobrosClienteFacturaList-cobros_cliente_factura-list'); // list
    $app->any('/CobrosClienteFacturaView[/{id}]', CobrosClienteFacturaController::class . ':view')->add(PermissionMiddleware::class)->setName('CobrosClienteFacturaView-cobros_cliente_factura-view'); // view
    $app->any('/CobrosClienteFacturaEdit[/{id}]', CobrosClienteFacturaController::class . ':edit')->add(PermissionMiddleware::class)->setName('CobrosClienteFacturaEdit-cobros_cliente_factura-edit'); // edit
    $app->any('/CobrosClienteFacturaDelete[/{id}]', CobrosClienteFacturaController::class . ':delete')->add(PermissionMiddleware::class)->setName('CobrosClienteFacturaDelete-cobros_cliente_factura-delete'); // delete
    $app->group(
        '/cobros_cliente_factura',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', CobrosClienteFacturaController::class . ':list')->add(PermissionMiddleware::class)->setName('cobros_cliente_factura/list-cobros_cliente_factura-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', CobrosClienteFacturaController::class . ':view')->add(PermissionMiddleware::class)->setName('cobros_cliente_factura/view-cobros_cliente_factura-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', CobrosClienteFacturaController::class . ':edit')->add(PermissionMiddleware::class)->setName('cobros_cliente_factura/edit-cobros_cliente_factura-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', CobrosClienteFacturaController::class . ':delete')->add(PermissionMiddleware::class)->setName('cobros_cliente_factura/delete-cobros_cliente_factura-delete-2'); // delete
        }
    );

    // codigo_buscar
    $app->any('/CodigoBuscar[/{params:.*}]', CodigoBuscarController::class)->add(PermissionMiddleware::class)->setName('CodigoBuscar-codigo_buscar-custom'); // custom

    // codigo_proveedor_buscar
    $app->any('/CodigoProveedorBuscar[/{params:.*}]', CodigoProveedorBuscarController::class)->add(PermissionMiddleware::class)->setName('CodigoProveedorBuscar-codigo_proveedor_buscar-custom'); // custom

    // compania
    $app->any('/CompaniaList[/{id}]', CompaniaController::class . ':list')->add(PermissionMiddleware::class)->setName('CompaniaList-compania-list'); // list
    $app->any('/CompaniaView[/{id}]', CompaniaController::class . ':view')->add(PermissionMiddleware::class)->setName('CompaniaView-compania-view'); // view
    $app->any('/CompaniaEdit[/{id}]', CompaniaController::class . ':edit')->add(PermissionMiddleware::class)->setName('CompaniaEdit-compania-edit'); // edit
    $app->group(
        '/compania',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', CompaniaController::class . ':list')->add(PermissionMiddleware::class)->setName('compania/list-compania-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', CompaniaController::class . ':view')->add(PermissionMiddleware::class)->setName('compania/view-compania-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', CompaniaController::class . ':edit')->add(PermissionMiddleware::class)->setName('compania/edit-compania-edit-2'); // edit
        }
    );

    // compania_cuenta
    $app->any('/CompaniaCuentaList[/{id}]', CompaniaCuentaController::class . ':list')->add(PermissionMiddleware::class)->setName('CompaniaCuentaList-compania_cuenta-list'); // list
    $app->any('/CompaniaCuentaAdd[/{id}]', CompaniaCuentaController::class . ':add')->add(PermissionMiddleware::class)->setName('CompaniaCuentaAdd-compania_cuenta-add'); // add
    $app->any('/CompaniaCuentaView[/{id}]', CompaniaCuentaController::class . ':view')->add(PermissionMiddleware::class)->setName('CompaniaCuentaView-compania_cuenta-view'); // view
    $app->any('/CompaniaCuentaEdit[/{id}]', CompaniaCuentaController::class . ':edit')->add(PermissionMiddleware::class)->setName('CompaniaCuentaEdit-compania_cuenta-edit'); // edit
    $app->any('/CompaniaCuentaDelete[/{id}]', CompaniaCuentaController::class . ':delete')->add(PermissionMiddleware::class)->setName('CompaniaCuentaDelete-compania_cuenta-delete'); // delete
    $app->any('/CompaniaCuentaPreview', CompaniaCuentaController::class . ':preview')->add(PermissionMiddleware::class)->setName('CompaniaCuentaPreview-compania_cuenta-preview'); // preview
    $app->group(
        '/compania_cuenta',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', CompaniaCuentaController::class . ':list')->add(PermissionMiddleware::class)->setName('compania_cuenta/list-compania_cuenta-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', CompaniaCuentaController::class . ':add')->add(PermissionMiddleware::class)->setName('compania_cuenta/add-compania_cuenta-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', CompaniaCuentaController::class . ':view')->add(PermissionMiddleware::class)->setName('compania_cuenta/view-compania_cuenta-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', CompaniaCuentaController::class . ':edit')->add(PermissionMiddleware::class)->setName('compania_cuenta/edit-compania_cuenta-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', CompaniaCuentaController::class . ':delete')->add(PermissionMiddleware::class)->setName('compania_cuenta/delete-compania_cuenta-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', CompaniaCuentaController::class . ':preview')->add(PermissionMiddleware::class)->setName('compania_cuenta/preview-compania_cuenta-preview-2'); // preview
        }
    );

    // compra
    $app->any('/CompraList[/{id}]', CompraController::class . ':list')->add(PermissionMiddleware::class)->setName('CompraList-compra-list'); // list
    $app->any('/CompraAdd[/{id}]', CompraController::class . ':add')->add(PermissionMiddleware::class)->setName('CompraAdd-compra-add'); // add
    $app->any('/CompraView[/{id}]', CompraController::class . ':view')->add(PermissionMiddleware::class)->setName('CompraView-compra-view'); // view
    $app->any('/CompraEdit[/{id}]', CompraController::class . ':edit')->add(PermissionMiddleware::class)->setName('CompraEdit-compra-edit'); // edit
    $app->any('/CompraDelete[/{id}]', CompraController::class . ':delete')->add(PermissionMiddleware::class)->setName('CompraDelete-compra-delete'); // delete
    $app->group(
        '/compra',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', CompraController::class . ':list')->add(PermissionMiddleware::class)->setName('compra/list-compra-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', CompraController::class . ':add')->add(PermissionMiddleware::class)->setName('compra/add-compra-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', CompraController::class . ':view')->add(PermissionMiddleware::class)->setName('compra/view-compra-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', CompraController::class . ':edit')->add(PermissionMiddleware::class)->setName('compra/edit-compra-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', CompraController::class . ':delete')->add(PermissionMiddleware::class)->setName('compra/delete-compra-delete-2'); // delete
        }
    );

    // Confirm_Page
    $app->any('/ConfirmPage[/{params:.*}]', ConfirmPageController::class)->add(PermissionMiddleware::class)->setName('ConfirmPage-Confirm_Page-custom'); // custom

    // cont_asiento
    $app->any('/ContAsientoList[/{id}]', ContAsientoController::class . ':list')->add(PermissionMiddleware::class)->setName('ContAsientoList-cont_asiento-list'); // list
    $app->any('/ContAsientoAdd[/{id}]', ContAsientoController::class . ':add')->add(PermissionMiddleware::class)->setName('ContAsientoAdd-cont_asiento-add'); // add
    $app->any('/ContAsientoEdit[/{id}]', ContAsientoController::class . ':edit')->add(PermissionMiddleware::class)->setName('ContAsientoEdit-cont_asiento-edit'); // edit
    $app->any('/ContAsientoDelete[/{id}]', ContAsientoController::class . ':delete')->add(PermissionMiddleware::class)->setName('ContAsientoDelete-cont_asiento-delete'); // delete
    $app->any('/ContAsientoPreview', ContAsientoController::class . ':preview')->add(PermissionMiddleware::class)->setName('ContAsientoPreview-cont_asiento-preview'); // preview
    $app->group(
        '/cont_asiento',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ContAsientoController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_asiento/list-cont_asiento-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ContAsientoController::class . ':add')->add(PermissionMiddleware::class)->setName('cont_asiento/add-cont_asiento-add-2'); // add
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ContAsientoController::class . ':edit')->add(PermissionMiddleware::class)->setName('cont_asiento/edit-cont_asiento-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ContAsientoController::class . ':delete')->add(PermissionMiddleware::class)->setName('cont_asiento/delete-cont_asiento-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', ContAsientoController::class . ':preview')->add(PermissionMiddleware::class)->setName('cont_asiento/preview-cont_asiento-preview-2'); // preview
        }
    );

    // cont_comprobante
    $app->any('/ContComprobanteList[/{id}]', ContComprobanteController::class . ':list')->add(PermissionMiddleware::class)->setName('ContComprobanteList-cont_comprobante-list'); // list
    $app->any('/ContComprobanteAdd[/{id}]', ContComprobanteController::class . ':add')->add(PermissionMiddleware::class)->setName('ContComprobanteAdd-cont_comprobante-add'); // add
    $app->any('/ContComprobanteView[/{id}]', ContComprobanteController::class . ':view')->add(PermissionMiddleware::class)->setName('ContComprobanteView-cont_comprobante-view'); // view
    $app->any('/ContComprobanteEdit[/{id}]', ContComprobanteController::class . ':edit')->add(PermissionMiddleware::class)->setName('ContComprobanteEdit-cont_comprobante-edit'); // edit
    $app->any('/ContComprobanteDelete[/{id}]', ContComprobanteController::class . ':delete')->add(PermissionMiddleware::class)->setName('ContComprobanteDelete-cont_comprobante-delete'); // delete
    $app->group(
        '/cont_comprobante',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ContComprobanteController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_comprobante/list-cont_comprobante-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ContComprobanteController::class . ':add')->add(PermissionMiddleware::class)->setName('cont_comprobante/add-cont_comprobante-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ContComprobanteController::class . ':view')->add(PermissionMiddleware::class)->setName('cont_comprobante/view-cont_comprobante-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ContComprobanteController::class . ':edit')->add(PermissionMiddleware::class)->setName('cont_comprobante/edit-cont_comprobante-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ContComprobanteController::class . ':delete')->add(PermissionMiddleware::class)->setName('cont_comprobante/delete-cont_comprobante-delete-2'); // delete
        }
    );

    // cont_lotes
    $app->any('/ContLotesList[/{id}]', ContLotesController::class . ':list')->add(PermissionMiddleware::class)->setName('ContLotesList-cont_lotes-list'); // list
    $app->any('/ContLotesAdd[/{id}]', ContLotesController::class . ':add')->add(PermissionMiddleware::class)->setName('ContLotesAdd-cont_lotes-add'); // add
    $app->any('/ContLotesView[/{id}]', ContLotesController::class . ':view')->add(PermissionMiddleware::class)->setName('ContLotesView-cont_lotes-view'); // view
    $app->group(
        '/cont_lotes',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ContLotesController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_lotes/list-cont_lotes-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ContLotesController::class . ':add')->add(PermissionMiddleware::class)->setName('cont_lotes/add-cont_lotes-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ContLotesController::class . ':view')->add(PermissionMiddleware::class)->setName('cont_lotes/view-cont_lotes-view-2'); // view
        }
    );

    // cont_lotes_pagos
    $app->any('/ContLotesPagosList[/{id}]', ContLotesPagosController::class . ':list')->add(PermissionMiddleware::class)->setName('ContLotesPagosList-cont_lotes_pagos-list'); // list
    $app->any('/ContLotesPagosAdd[/{id}]', ContLotesPagosController::class . ':add')->add(PermissionMiddleware::class)->setName('ContLotesPagosAdd-cont_lotes_pagos-add'); // add
    $app->any('/ContLotesPagosView[/{id}]', ContLotesPagosController::class . ':view')->add(PermissionMiddleware::class)->setName('ContLotesPagosView-cont_lotes_pagos-view'); // view
    $app->any('/ContLotesPagosEdit[/{id}]', ContLotesPagosController::class . ':edit')->add(PermissionMiddleware::class)->setName('ContLotesPagosEdit-cont_lotes_pagos-edit'); // edit
    $app->any('/ContLotesPagosDelete[/{id}]', ContLotesPagosController::class . ':delete')->add(PermissionMiddleware::class)->setName('ContLotesPagosDelete-cont_lotes_pagos-delete'); // delete
    $app->group(
        '/cont_lotes_pagos',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ContLotesPagosController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_lotes_pagos/list-cont_lotes_pagos-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ContLotesPagosController::class . ':add')->add(PermissionMiddleware::class)->setName('cont_lotes_pagos/add-cont_lotes_pagos-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ContLotesPagosController::class . ':view')->add(PermissionMiddleware::class)->setName('cont_lotes_pagos/view-cont_lotes_pagos-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ContLotesPagosController::class . ':edit')->add(PermissionMiddleware::class)->setName('cont_lotes_pagos/edit-cont_lotes_pagos-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ContLotesPagosController::class . ':delete')->add(PermissionMiddleware::class)->setName('cont_lotes_pagos/delete-cont_lotes_pagos-delete-2'); // delete
        }
    );

    // cont_lotes_pagos_detalle
    $app->any('/ContLotesPagosDetalleList[/{Id}]', ContLotesPagosDetalleController::class . ':list')->add(PermissionMiddleware::class)->setName('ContLotesPagosDetalleList-cont_lotes_pagos_detalle-list'); // list
    $app->any('/ContLotesPagosDetalleDelete[/{Id}]', ContLotesPagosDetalleController::class . ':delete')->add(PermissionMiddleware::class)->setName('ContLotesPagosDetalleDelete-cont_lotes_pagos_detalle-delete'); // delete
    $app->any('/ContLotesPagosDetallePreview', ContLotesPagosDetalleController::class . ':preview')->add(PermissionMiddleware::class)->setName('ContLotesPagosDetallePreview-cont_lotes_pagos_detalle-preview'); // preview
    $app->group(
        '/cont_lotes_pagos_detalle',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{Id}]', ContLotesPagosDetalleController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_lotes_pagos_detalle/list-cont_lotes_pagos_detalle-list-2'); // list
            $group->any('/' . Config("DELETE_ACTION") . '[/{Id}]', ContLotesPagosDetalleController::class . ':delete')->add(PermissionMiddleware::class)->setName('cont_lotes_pagos_detalle/delete-cont_lotes_pagos_detalle-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', ContLotesPagosDetalleController::class . ':preview')->add(PermissionMiddleware::class)->setName('cont_lotes_pagos_detalle/preview-cont_lotes_pagos_detalle-preview-2'); // preview
        }
    );

    // cont_mes_contable
    $app->any('/ContMesContableList[/{id}]', ContMesContableController::class . ':list')->add(PermissionMiddleware::class)->setName('ContMesContableList-cont_mes_contable-list'); // list
    $app->any('/ContMesContableView[/{id}]', ContMesContableController::class . ':view')->add(PermissionMiddleware::class)->setName('ContMesContableView-cont_mes_contable-view'); // view
    $app->any('/ContMesContableEdit[/{id}]', ContMesContableController::class . ':edit')->add(PermissionMiddleware::class)->setName('ContMesContableEdit-cont_mes_contable-edit'); // edit
    $app->group(
        '/cont_mes_contable',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ContMesContableController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_mes_contable/list-cont_mes_contable-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ContMesContableController::class . ':view')->add(PermissionMiddleware::class)->setName('cont_mes_contable/view-cont_mes_contable-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ContMesContableController::class . ':edit')->add(PermissionMiddleware::class)->setName('cont_mes_contable/edit-cont_mes_contable-edit-2'); // edit
        }
    );

    // cont_periodo_contable
    $app->any('/ContPeriodoContableList[/{id}]', ContPeriodoContableController::class . ':list')->add(PermissionMiddleware::class)->setName('ContPeriodoContableList-cont_periodo_contable-list'); // list
    $app->any('/ContPeriodoContableAdd[/{id}]', ContPeriodoContableController::class . ':add')->add(PermissionMiddleware::class)->setName('ContPeriodoContableAdd-cont_periodo_contable-add'); // add
    $app->any('/ContPeriodoContableView[/{id}]', ContPeriodoContableController::class . ':view')->add(PermissionMiddleware::class)->setName('ContPeriodoContableView-cont_periodo_contable-view'); // view
    $app->any('/ContPeriodoContableEdit[/{id}]', ContPeriodoContableController::class . ':edit')->add(PermissionMiddleware::class)->setName('ContPeriodoContableEdit-cont_periodo_contable-edit'); // edit
    $app->any('/ContPeriodoContableDelete[/{id}]', ContPeriodoContableController::class . ':delete')->add(PermissionMiddleware::class)->setName('ContPeriodoContableDelete-cont_periodo_contable-delete'); // delete
    $app->group(
        '/cont_periodo_contable',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ContPeriodoContableController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_periodo_contable/list-cont_periodo_contable-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ContPeriodoContableController::class . ':add')->add(PermissionMiddleware::class)->setName('cont_periodo_contable/add-cont_periodo_contable-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ContPeriodoContableController::class . ':view')->add(PermissionMiddleware::class)->setName('cont_periodo_contable/view-cont_periodo_contable-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ContPeriodoContableController::class . ':edit')->add(PermissionMiddleware::class)->setName('cont_periodo_contable/edit-cont_periodo_contable-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ContPeriodoContableController::class . ':delete')->add(PermissionMiddleware::class)->setName('cont_periodo_contable/delete-cont_periodo_contable-delete-2'); // delete
        }
    );

    // cont_plancta
    $app->any('/ContPlanctaList[/{id}]', ContPlanctaController::class . ':list')->add(PermissionMiddleware::class)->setName('ContPlanctaList-cont_plancta-list'); // list
    $app->any('/ContPlanctaAdd[/{id}]', ContPlanctaController::class . ':add')->add(PermissionMiddleware::class)->setName('ContPlanctaAdd-cont_plancta-add'); // add
    $app->any('/ContPlanctaView[/{id}]', ContPlanctaController::class . ':view')->add(PermissionMiddleware::class)->setName('ContPlanctaView-cont_plancta-view'); // view
    $app->any('/ContPlanctaEdit[/{id}]', ContPlanctaController::class . ':edit')->add(PermissionMiddleware::class)->setName('ContPlanctaEdit-cont_plancta-edit'); // edit
    $app->any('/ContPlanctaDelete[/{id}]', ContPlanctaController::class . ':delete')->add(PermissionMiddleware::class)->setName('ContPlanctaDelete-cont_plancta-delete'); // delete
    $app->group(
        '/cont_plancta',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ContPlanctaController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_plancta/list-cont_plancta-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ContPlanctaController::class . ':add')->add(PermissionMiddleware::class)->setName('cont_plancta/add-cont_plancta-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ContPlanctaController::class . ':view')->add(PermissionMiddleware::class)->setName('cont_plancta/view-cont_plancta-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ContPlanctaController::class . ':edit')->add(PermissionMiddleware::class)->setName('cont_plancta/edit-cont_plancta-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ContPlanctaController::class . ':delete')->add(PermissionMiddleware::class)->setName('cont_plancta/delete-cont_plancta-delete-2'); // delete
        }
    );

    // cont_reglas
    $app->any('/ContReglasList[/{id}]', ContReglasController::class . ':list')->add(PermissionMiddleware::class)->setName('ContReglasList-cont_reglas-list'); // list
    $app->any('/ContReglasView[/{id}]', ContReglasController::class . ':view')->add(PermissionMiddleware::class)->setName('ContReglasView-cont_reglas-view'); // view
    $app->any('/ContReglasEdit[/{id}]', ContReglasController::class . ':edit')->add(PermissionMiddleware::class)->setName('ContReglasEdit-cont_reglas-edit'); // edit
    $app->any('/ContReglasPreview', ContReglasController::class . ':preview')->add(PermissionMiddleware::class)->setName('ContReglasPreview-cont_reglas-preview'); // preview
    $app->group(
        '/cont_reglas',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ContReglasController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_reglas/list-cont_reglas-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ContReglasController::class . ':view')->add(PermissionMiddleware::class)->setName('cont_reglas/view-cont_reglas-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ContReglasController::class . ':edit')->add(PermissionMiddleware::class)->setName('cont_reglas/edit-cont_reglas-edit-2'); // edit
            $group->any('/' . Config("PREVIEW_ACTION") . '', ContReglasController::class . ':preview')->add(PermissionMiddleware::class)->setName('cont_reglas/preview-cont_reglas-preview-2'); // preview
        }
    );

    // cont_reglas_hr
    $app->any('/ContReglasHrList[/{id}]', ContReglasHrController::class . ':list')->add(PermissionMiddleware::class)->setName('ContReglasHrList-cont_reglas_hr-list'); // list
    $app->any('/ContReglasHrView[/{id}]', ContReglasHrController::class . ':view')->add(PermissionMiddleware::class)->setName('ContReglasHrView-cont_reglas_hr-view'); // view
    $app->any('/ContReglasHrEdit[/{id}]', ContReglasHrController::class . ':edit')->add(PermissionMiddleware::class)->setName('ContReglasHrEdit-cont_reglas_hr-edit'); // edit
    $app->group(
        '/cont_reglas_hr',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ContReglasHrController::class . ':list')->add(PermissionMiddleware::class)->setName('cont_reglas_hr/list-cont_reglas_hr-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ContReglasHrController::class . ':view')->add(PermissionMiddleware::class)->setName('cont_reglas_hr/view-cont_reglas_hr-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ContReglasHrController::class . ':edit')->add(PermissionMiddleware::class)->setName('cont_reglas_hr/edit-cont_reglas_hr-edit-2'); // edit
        }
    );

    // convertir_a_factura
    $app->any('/ConvertirAFactura[/{params:.*}]', ConvertirAFacturaController::class)->add(PermissionMiddleware::class)->setName('ConvertirAFactura-convertir_a_factura-custom'); // custom

    // crear_factura_compra
    $app->any('/CrearFacturaCompra[/{params:.*}]', CrearFacturaCompraController::class)->add(PermissionMiddleware::class)->setName('CrearFacturaCompra-crear_factura_compra-custom'); // custom

    // crear_factura_venta
    $app->any('/CrearFacturaVenta[/{params:.*}]', CrearFacturaVentaController::class)->add(PermissionMiddleware::class)->setName('CrearFacturaVenta-crear_factura_venta-custom'); // custom

    // crear_nota_entrada_update
    $app->any('/CrearNotaEntradaUpdate[/{params:.*}]', CrearNotaEntradaUpdateController::class)->add(PermissionMiddleware::class)->setName('CrearNotaEntradaUpdate-crear_nota_entrada_update-custom'); // custom

    // crear_nota_entrega
    $app->any('/CrearNotaEntrega[/{params:.*}]', CrearNotaEntregaController::class)->add(PermissionMiddleware::class)->setName('CrearNotaEntrega-crear_nota_entrega-custom'); // custom

    // crear_nota_entrega_guardar
    $app->any('/CrearNotaEntregaGuardar[/{params:.*}]', CrearNotaEntregaGuardarController::class)->add(PermissionMiddleware::class)->setName('CrearNotaEntregaGuardar-crear_nota_entrega_guardar-custom'); // custom

    // crear_nota_recepcion
    $app->any('/CrearNotaRecepcion[/{params:.*}]', CrearNotaRecepcionController::class)->add(PermissionMiddleware::class)->setName('CrearNotaRecepcion-crear_nota_recepcion-custom'); // custom

    // devoluciones
    $app->any('/Devoluciones[/{params:.*}]', DevolucionesController::class)->add(PermissionMiddleware::class)->setName('Devoluciones-devoluciones-custom'); // custom

    // devoluciones_buscar
    $app->any('/DevolucionesBuscar[/{params:.*}]', DevolucionesBuscarController::class)->add(PermissionMiddleware::class)->setName('DevolucionesBuscar-devoluciones_buscar-custom'); // custom

    // devoluciones_guardar
    $app->any('/DevolucionesGuardar[/{params:.*}]', DevolucionesGuardarController::class)->add(PermissionMiddleware::class)->setName('DevolucionesGuardar-devoluciones_guardar-custom'); // custom

    // devoluciones_ver
    $app->any('/DevolucionesVer[/{params:.*}]', DevolucionesVerController::class)->add(PermissionMiddleware::class)->setName('DevolucionesVer-devoluciones_ver-custom'); // custom

    // eliminar_linea
    $app->any('/EliminarLinea[/{params:.*}]', EliminarLineaController::class)->add(PermissionMiddleware::class)->setName('EliminarLinea-eliminar_linea-custom'); // custom

    // entradas
    $app->any('/EntradasList[/{id}]', EntradasController::class . ':list')->add(PermissionMiddleware::class)->setName('EntradasList-entradas-list'); // list
    $app->any('/EntradasAdd[/{id}]', EntradasController::class . ':add')->add(PermissionMiddleware::class)->setName('EntradasAdd-entradas-add'); // add
    $app->any('/EntradasView[/{id}]', EntradasController::class . ':view')->add(PermissionMiddleware::class)->setName('EntradasView-entradas-view'); // view
    $app->any('/EntradasEdit[/{id}]', EntradasController::class . ':edit')->add(PermissionMiddleware::class)->setName('EntradasEdit-entradas-edit'); // edit
    $app->any('/EntradasDelete[/{id}]', EntradasController::class . ':delete')->add(PermissionMiddleware::class)->setName('EntradasDelete-entradas-delete'); // delete
    $app->group(
        '/entradas',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', EntradasController::class . ':list')->add(PermissionMiddleware::class)->setName('entradas/list-entradas-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', EntradasController::class . ':add')->add(PermissionMiddleware::class)->setName('entradas/add-entradas-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', EntradasController::class . ':view')->add(PermissionMiddleware::class)->setName('entradas/view-entradas-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', EntradasController::class . ':edit')->add(PermissionMiddleware::class)->setName('entradas/edit-entradas-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', EntradasController::class . ':delete')->add(PermissionMiddleware::class)->setName('entradas/delete-entradas-delete-2'); // delete
        }
    );

    // entradas_salidas
    $app->any('/EntradasSalidasList[/{id}]', EntradasSalidasController::class . ':list')->add(PermissionMiddleware::class)->setName('EntradasSalidasList-entradas_salidas-list'); // list
    $app->any('/EntradasSalidasAdd[/{id}]', EntradasSalidasController::class . ':add')->add(PermissionMiddleware::class)->setName('EntradasSalidasAdd-entradas_salidas-add'); // add
    $app->any('/EntradasSalidasView[/{id}]', EntradasSalidasController::class . ':view')->add(PermissionMiddleware::class)->setName('EntradasSalidasView-entradas_salidas-view'); // view
    $app->any('/EntradasSalidasDelete[/{id}]', EntradasSalidasController::class . ':delete')->add(PermissionMiddleware::class)->setName('EntradasSalidasDelete-entradas_salidas-delete'); // delete
    $app->any('/EntradasSalidasPreview', EntradasSalidasController::class . ':preview')->add(PermissionMiddleware::class)->setName('EntradasSalidasPreview-entradas_salidas-preview'); // preview
    $app->group(
        '/entradas_salidas',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', EntradasSalidasController::class . ':list')->add(PermissionMiddleware::class)->setName('entradas_salidas/list-entradas_salidas-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', EntradasSalidasController::class . ':add')->add(PermissionMiddleware::class)->setName('entradas_salidas/add-entradas_salidas-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', EntradasSalidasController::class . ':view')->add(PermissionMiddleware::class)->setName('entradas_salidas/view-entradas_salidas-view-2'); // view
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', EntradasSalidasController::class . ':delete')->add(PermissionMiddleware::class)->setName('entradas_salidas/delete-entradas_salidas-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', EntradasSalidasController::class . ':preview')->add(PermissionMiddleware::class)->setName('entradas_salidas/preview-entradas_salidas-preview-2'); // preview
        }
    );

    // error_page
    $app->any('/ErrorPage[/{params:.*}]', ErrorPageController::class)->add(PermissionMiddleware::class)->setName('ErrorPage-error_page-custom'); // custom

    // estado_resultados
    $app->any('/EstadoResultados[/{params:.*}]', EstadoResultadosController::class)->add(PermissionMiddleware::class)->setName('EstadoResultados-estado_resultados-custom'); // custom

    // exportar_data
    $app->any('/ExportarData[/{params:.*}]', ExportarDataController::class)->add(PermissionMiddleware::class)->setName('ExportarData-exportar_data-custom'); // custom

    // fabricante
    $app->any('/FabricanteList[/{Id}]', FabricanteController::class . ':list')->add(PermissionMiddleware::class)->setName('FabricanteList-fabricante-list'); // list
    $app->any('/FabricanteAdd[/{Id}]', FabricanteController::class . ':add')->add(PermissionMiddleware::class)->setName('FabricanteAdd-fabricante-add'); // add
    $app->group(
        '/fabricante',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{Id}]', FabricanteController::class . ':list')->add(PermissionMiddleware::class)->setName('fabricante/list-fabricante-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{Id}]', FabricanteController::class . ':add')->add(PermissionMiddleware::class)->setName('fabricante/add-fabricante-add-2'); // add
        }
    );

    // factura_consignacion
    $app->any('/FacturaConsignacion[/{params:.*}]', FacturaConsignacionController::class)->add(PermissionMiddleware::class)->setName('FacturaConsignacion-factura_consignacion-custom'); // custom

    // factura_consignacion_guardar
    $app->any('/FacturaConsignacionGuardar[/{params:.*}]', FacturaConsignacionGuardarController::class)->add(PermissionMiddleware::class)->setName('FacturaConsignacionGuardar-factura_consignacion_guardar-custom'); // custom

    // factura_de_compra_detalle_copia
    $app->any('/FacturaDeCompraDetalleCopia[/{params:.*}]', FacturaDeCompraDetalleCopiaController::class)->add(PermissionMiddleware::class)->setName('FacturaDeCompraDetalleCopia-factura_de_compra_detalle_copia-custom'); // custom

    // factura_de_venta_copiar_como
    $app->any('/FacturaDeVentaCopiarComo[/{params:.*}]', FacturaDeVentaCopiarComoController::class)->add(PermissionMiddleware::class)->setName('FacturaDeVentaCopiarComo-factura_de_venta_copiar_como-custom'); // custom

    // factura_de_venta_detalle_copia
    $app->any('/FacturaDeVentaDetalleCopia[/{params:.*}]', FacturaDeVentaDetalleCopiaController::class)->add(PermissionMiddleware::class)->setName('FacturaDeVentaDetalleCopia-factura_de_venta_detalle_copia-custom'); // custom

    // grupo_funciones
    $app->any('/GrupoFuncionesList[/{id}]', GrupoFuncionesController::class . ':list')->add(PermissionMiddleware::class)->setName('GrupoFuncionesList-grupo_funciones-list'); // list
    $app->any('/GrupoFuncionesAdd[/{id}]', GrupoFuncionesController::class . ':add')->add(PermissionMiddleware::class)->setName('GrupoFuncionesAdd-grupo_funciones-add'); // add
    $app->any('/GrupoFuncionesView[/{id}]', GrupoFuncionesController::class . ':view')->add(PermissionMiddleware::class)->setName('GrupoFuncionesView-grupo_funciones-view'); // view
    $app->any('/GrupoFuncionesDelete[/{id}]', GrupoFuncionesController::class . ':delete')->add(PermissionMiddleware::class)->setName('GrupoFuncionesDelete-grupo_funciones-delete'); // delete
    $app->any('/GrupoFuncionesPreview', GrupoFuncionesController::class . ':preview')->add(PermissionMiddleware::class)->setName('GrupoFuncionesPreview-grupo_funciones-preview'); // preview
    $app->group(
        '/grupo_funciones',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', GrupoFuncionesController::class . ':list')->add(PermissionMiddleware::class)->setName('grupo_funciones/list-grupo_funciones-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', GrupoFuncionesController::class . ':add')->add(PermissionMiddleware::class)->setName('grupo_funciones/add-grupo_funciones-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', GrupoFuncionesController::class . ':view')->add(PermissionMiddleware::class)->setName('grupo_funciones/view-grupo_funciones-view-2'); // view
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', GrupoFuncionesController::class . ':delete')->add(PermissionMiddleware::class)->setName('grupo_funciones/delete-grupo_funciones-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', GrupoFuncionesController::class . ':preview')->add(PermissionMiddleware::class)->setName('grupo_funciones/preview-grupo_funciones-preview-2'); // preview
        }
    );

    // home
    $app->any('/Home[/{params:.*}]', HomeController::class)->add(PermissionMiddleware::class)->setName('Home-home-custom'); // custom

    // indicadores
    $app->any('/Indicadores[/{params:.*}]', IndicadoresController::class)->add(PermissionMiddleware::class)->setName('Indicadores-indicadores-custom'); // custom

    // libro_diario
    $app->any('/LibroDiario[/{params:.*}]', LibroDiarioController::class)->add(PermissionMiddleware::class)->setName('LibroDiario-libro_diario-custom'); // custom

    // libro_mayor
    $app->any('/LibroMayor[/{params:.*}]', LibroMayorController::class)->add(PermissionMiddleware::class)->setName('LibroMayor-libro_mayor-custom'); // custom

    // listado_master
    $app->any('/ListadoMaster[/{params:.*}]', ListadoMasterController::class)->add(PermissionMiddleware::class)->setName('ListadoMaster-listado_master-custom'); // custom

    // listado_master_general
    $app->any('/ListadoMasterGeneral[/{params:.*}]', ListadoMasterGeneralController::class)->add(PermissionMiddleware::class)->setName('ListadoMasterGeneral-listado_master_general-custom'); // custom

    // main_report
    $app->any('/MainReport[/{params:.*}]', MainReportController::class)->add(PermissionMiddleware::class)->setName('MainReport-main_report-custom'); // custom

    // masivo_ajuste_entrada
    $app->any('/MasivoAjusteEntrada[/{params:.*}]', MasivoAjusteEntradaController::class)->add(PermissionMiddleware::class)->setName('MasivoAjusteEntrada-masivo_ajuste_entrada-custom'); // custom

    // masivo_ajuste_salida
    $app->any('/MasivoAjusteSalida[/{params:.*}]', MasivoAjusteSalidaController::class)->add(PermissionMiddleware::class)->setName('MasivoAjusteSalida-masivo_ajuste_salida-custom'); // custom

    // nota_de_entrega_buscar
    $app->any('/NotaDeEntregaBuscar[/{params:.*}]', NotaDeEntregaBuscarController::class)->add(PermissionMiddleware::class)->setName('NotaDeEntregaBuscar-nota_de_entrega_buscar-custom'); // custom

    // nota_de_entrega_buscar_listar
    $app->any('/NotaDeEntregaBuscarListar[/{params:.*}]', NotaDeEntregaBuscarListarController::class)->add(PermissionMiddleware::class)->setName('NotaDeEntregaBuscarListar-nota_de_entrega_buscar_listar-custom'); // custom

    // nota_de_entrega_ver
    $app->any('/NotaDeEntregaVer[/{params:.*}]', NotaDeEntregaVerController::class)->add(PermissionMiddleware::class)->setName('NotaDeEntregaVer-nota_de_entrega_ver-custom'); // custom

    // notificaciones
    $app->any('/NotificacionesList[/{Nnotificaciones}]', NotificacionesController::class . ':list')->add(PermissionMiddleware::class)->setName('NotificacionesList-notificaciones-list'); // list
    $app->any('/NotificacionesAdd[/{Nnotificaciones}]', NotificacionesController::class . ':add')->add(PermissionMiddleware::class)->setName('NotificacionesAdd-notificaciones-add'); // add
    $app->any('/NotificacionesView[/{Nnotificaciones}]', NotificacionesController::class . ':view')->add(PermissionMiddleware::class)->setName('NotificacionesView-notificaciones-view'); // view
    $app->any('/NotificacionesEdit[/{Nnotificaciones}]', NotificacionesController::class . ':edit')->add(PermissionMiddleware::class)->setName('NotificacionesEdit-notificaciones-edit'); // edit
    $app->any('/NotificacionesDelete[/{Nnotificaciones}]', NotificacionesController::class . ':delete')->add(PermissionMiddleware::class)->setName('NotificacionesDelete-notificaciones-delete'); // delete
    $app->group(
        '/notificaciones',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{Nnotificaciones}]', NotificacionesController::class . ':list')->add(PermissionMiddleware::class)->setName('notificaciones/list-notificaciones-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{Nnotificaciones}]', NotificacionesController::class . ':add')->add(PermissionMiddleware::class)->setName('notificaciones/add-notificaciones-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{Nnotificaciones}]', NotificacionesController::class . ':view')->add(PermissionMiddleware::class)->setName('notificaciones/view-notificaciones-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{Nnotificaciones}]', NotificacionesController::class . ':edit')->add(PermissionMiddleware::class)->setName('notificaciones/edit-notificaciones-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{Nnotificaciones}]', NotificacionesController::class . ':delete')->add(PermissionMiddleware::class)->setName('notificaciones/delete-notificaciones-delete-2'); // delete
        }
    );

    // pagos
    $app->any('/PagosList[/{id}]', PagosController::class . ':list')->add(PermissionMiddleware::class)->setName('PagosList-pagos-list'); // list
    $app->any('/PagosAdd[/{id}]', PagosController::class . ':add')->add(PermissionMiddleware::class)->setName('PagosAdd-pagos-add'); // add
    $app->any('/PagosView[/{id}]', PagosController::class . ':view')->add(PermissionMiddleware::class)->setName('PagosView-pagos-view'); // view
    $app->any('/PagosEdit[/{id}]', PagosController::class . ':edit')->add(PermissionMiddleware::class)->setName('PagosEdit-pagos-edit'); // edit
    $app->any('/PagosDelete[/{id}]', PagosController::class . ':delete')->add(PermissionMiddleware::class)->setName('PagosDelete-pagos-delete'); // delete
    $app->any('/PagosPreview', PagosController::class . ':preview')->add(PermissionMiddleware::class)->setName('PagosPreview-pagos-preview'); // preview
    $app->group(
        '/pagos',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', PagosController::class . ':list')->add(PermissionMiddleware::class)->setName('pagos/list-pagos-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', PagosController::class . ':add')->add(PermissionMiddleware::class)->setName('pagos/add-pagos-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', PagosController::class . ':view')->add(PermissionMiddleware::class)->setName('pagos/view-pagos-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', PagosController::class . ':edit')->add(PermissionMiddleware::class)->setName('pagos/edit-pagos-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', PagosController::class . ':delete')->add(PermissionMiddleware::class)->setName('pagos/delete-pagos-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', PagosController::class . ':preview')->add(PermissionMiddleware::class)->setName('pagos/preview-pagos-preview-2'); // preview
        }
    );

    // pagos_proveedor
    $app->any('/PagosProveedorList[/{id}]', PagosProveedorController::class . ':list')->add(PermissionMiddleware::class)->setName('PagosProveedorList-pagos_proveedor-list'); // list
    $app->any('/PagosProveedorAdd[/{id}]', PagosProveedorController::class . ':add')->add(PermissionMiddleware::class)->setName('PagosProveedorAdd-pagos_proveedor-add'); // add
    $app->any('/PagosProveedorView[/{id}]', PagosProveedorController::class . ':view')->add(PermissionMiddleware::class)->setName('PagosProveedorView-pagos_proveedor-view'); // view
    $app->any('/PagosProveedorEdit[/{id}]', PagosProveedorController::class . ':edit')->add(PermissionMiddleware::class)->setName('PagosProveedorEdit-pagos_proveedor-edit'); // edit
    $app->any('/PagosProveedorDelete[/{id}]', PagosProveedorController::class . ':delete')->add(PermissionMiddleware::class)->setName('PagosProveedorDelete-pagos_proveedor-delete'); // delete
    $app->group(
        '/pagos_proveedor',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', PagosProveedorController::class . ':list')->add(PermissionMiddleware::class)->setName('pagos_proveedor/list-pagos_proveedor-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', PagosProveedorController::class . ':add')->add(PermissionMiddleware::class)->setName('pagos_proveedor/add-pagos_proveedor-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', PagosProveedorController::class . ':view')->add(PermissionMiddleware::class)->setName('pagos_proveedor/view-pagos_proveedor-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', PagosProveedorController::class . ':edit')->add(PermissionMiddleware::class)->setName('pagos_proveedor/edit-pagos_proveedor-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', PagosProveedorController::class . ':delete')->add(PermissionMiddleware::class)->setName('pagos_proveedor/delete-pagos_proveedor-delete-2'); // delete
        }
    );

    // pagos_proveedor_factura
    $app->any('/PagosProveedorFacturaList[/{id}]', PagosProveedorFacturaController::class . ':list')->add(PermissionMiddleware::class)->setName('PagosProveedorFacturaList-pagos_proveedor_factura-list'); // list
    $app->any('/PagosProveedorFacturaView[/{id}]', PagosProveedorFacturaController::class . ':view')->add(PermissionMiddleware::class)->setName('PagosProveedorFacturaView-pagos_proveedor_factura-view'); // view
    $app->any('/PagosProveedorFacturaDelete[/{id}]', PagosProveedorFacturaController::class . ':delete')->add(PermissionMiddleware::class)->setName('PagosProveedorFacturaDelete-pagos_proveedor_factura-delete'); // delete
    $app->any('/PagosProveedorFacturaPreview', PagosProveedorFacturaController::class . ':preview')->add(PermissionMiddleware::class)->setName('PagosProveedorFacturaPreview-pagos_proveedor_factura-preview'); // preview
    $app->group(
        '/pagos_proveedor_factura',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', PagosProveedorFacturaController::class . ':list')->add(PermissionMiddleware::class)->setName('pagos_proveedor_factura/list-pagos_proveedor_factura-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', PagosProveedorFacturaController::class . ':view')->add(PermissionMiddleware::class)->setName('pagos_proveedor_factura/view-pagos_proveedor_factura-view-2'); // view
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', PagosProveedorFacturaController::class . ':delete')->add(PermissionMiddleware::class)->setName('pagos_proveedor_factura/delete-pagos_proveedor_factura-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', PagosProveedorFacturaController::class . ':preview')->add(PermissionMiddleware::class)->setName('pagos_proveedor_factura/preview-pagos_proveedor_factura-preview-2'); // preview
        }
    );

    // parametro
    $app->any('/ParametroList[/{id}]', ParametroController::class . ':list')->add(PermissionMiddleware::class)->setName('ParametroList-parametro-list'); // list
    $app->any('/ParametroAdd[/{id}]', ParametroController::class . ':add')->add(PermissionMiddleware::class)->setName('ParametroAdd-parametro-add'); // add
    $app->any('/ParametroView[/{id}]', ParametroController::class . ':view')->add(PermissionMiddleware::class)->setName('ParametroView-parametro-view'); // view
    $app->any('/ParametroEdit[/{id}]', ParametroController::class . ':edit')->add(PermissionMiddleware::class)->setName('ParametroEdit-parametro-edit'); // edit
    $app->any('/ParametroDelete[/{id}]', ParametroController::class . ':delete')->add(PermissionMiddleware::class)->setName('ParametroDelete-parametro-delete'); // delete
    $app->group(
        '/parametro',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ParametroController::class . ':list')->add(PermissionMiddleware::class)->setName('parametro/list-parametro-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ParametroController::class . ':add')->add(PermissionMiddleware::class)->setName('parametro/add-parametro-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ParametroController::class . ':view')->add(PermissionMiddleware::class)->setName('parametro/view-parametro-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ParametroController::class . ':edit')->add(PermissionMiddleware::class)->setName('parametro/edit-parametro-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ParametroController::class . ':delete')->add(PermissionMiddleware::class)->setName('parametro/delete-parametro-delete-2'); // delete
        }
    );

    // pedidio_detalle_online
    $app->any('/PedidioDetalleOnlineList[/{id}]', PedidioDetalleOnlineController::class . ':list')->add(PermissionMiddleware::class)->setName('PedidioDetalleOnlineList-pedidio_detalle_online-list'); // list
    $app->any('/PedidioDetalleOnlineView[/{id}]', PedidioDetalleOnlineController::class . ':view')->add(PermissionMiddleware::class)->setName('PedidioDetalleOnlineView-pedidio_detalle_online-view'); // view
    $app->any('/PedidioDetalleOnlineDelete[/{id}]', PedidioDetalleOnlineController::class . ':delete')->add(PermissionMiddleware::class)->setName('PedidioDetalleOnlineDelete-pedidio_detalle_online-delete'); // delete
    $app->any('/PedidioDetalleOnlinePreview', PedidioDetalleOnlineController::class . ':preview')->add(PermissionMiddleware::class)->setName('PedidioDetalleOnlinePreview-pedidio_detalle_online-preview'); // preview
    $app->group(
        '/pedidio_detalle_online',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', PedidioDetalleOnlineController::class . ':list')->add(PermissionMiddleware::class)->setName('pedidio_detalle_online/list-pedidio_detalle_online-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', PedidioDetalleOnlineController::class . ':view')->add(PermissionMiddleware::class)->setName('pedidio_detalle_online/view-pedidio_detalle_online-view-2'); // view
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', PedidioDetalleOnlineController::class . ':delete')->add(PermissionMiddleware::class)->setName('pedidio_detalle_online/delete-pedidio_detalle_online-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', PedidioDetalleOnlineController::class . ':preview')->add(PermissionMiddleware::class)->setName('pedidio_detalle_online/preview-pedidio_detalle_online-preview-2'); // preview
        }
    );

    // pedido_de_compra_detalle_copia
    $app->any('/PedidoDeCompraDetalleCopia[/{params:.*}]', PedidoDeCompraDetalleCopiaController::class)->add(PermissionMiddleware::class)->setName('PedidoDeCompraDetalleCopia-pedido_de_compra_detalle_copia-custom'); // custom

    // pedido_de_venta_detalle
    $app->any('/PedidoDeVentaDetalle[/{params:.*}]', PedidoDeVentaDetalleController::class)->add(PermissionMiddleware::class)->setName('PedidoDeVentaDetalle-pedido_de_venta_detalle-custom'); // custom

    // pedido_de_venta_detalle_agregar
    $app->any('/PedidoDeVentaDetalleAgregar[/{params:.*}]', PedidoDeVentaDetalleAgregarController::class)->add(PermissionMiddleware::class)->setName('PedidoDeVentaDetalleAgregar-pedido_de_venta_detalle_agregar-custom'); // custom

    // pedido_de_venta_detalle_copia
    $app->any('/PedidoDeVentaDetalleCopia[/{params:.*}]', PedidoDeVentaDetalleCopiaController::class)->add(PermissionMiddleware::class)->setName('PedidoDeVentaDetalleCopia-pedido_de_venta_detalle_copia-custom'); // custom

    // pedido_de_venta_detalle_guardar
    $app->any('/PedidoDeVentaDetalleGuardar[/{params:.*}]', PedidoDeVentaDetalleGuardarController::class)->add(PermissionMiddleware::class)->setName('PedidoDeVentaDetalleGuardar-pedido_de_venta_detalle_guardar-custom'); // custom

    // pedido_online
    $app->any('/PedidoOnlineList[/{id}]', PedidoOnlineController::class . ':list')->add(PermissionMiddleware::class)->setName('PedidoOnlineList-pedido_online-list'); // list
    $app->any('/PedidoOnlineView[/{id}]', PedidoOnlineController::class . ':view')->add(PermissionMiddleware::class)->setName('PedidoOnlineView-pedido_online-view'); // view
    $app->any('/PedidoOnlineDelete[/{id}]', PedidoOnlineController::class . ':delete')->add(PermissionMiddleware::class)->setName('PedidoOnlineDelete-pedido_online-delete'); // delete
    $app->group(
        '/pedido_online',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', PedidoOnlineController::class . ':list')->add(PermissionMiddleware::class)->setName('pedido_online/list-pedido_online-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', PedidoOnlineController::class . ':view')->add(PermissionMiddleware::class)->setName('pedido_online/view-pedido_online-view-2'); // view
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', PedidoOnlineController::class . ':delete')->add(PermissionMiddleware::class)->setName('pedido_online/delete-pedido_online-delete-2'); // delete
        }
    );

    // proveedor
    $app->any('/ProveedorList[/{id}]', ProveedorController::class . ':list')->add(PermissionMiddleware::class)->setName('ProveedorList-proveedor-list'); // list
    $app->any('/ProveedorAdd[/{id}]', ProveedorController::class . ':add')->add(PermissionMiddleware::class)->setName('ProveedorAdd-proveedor-add'); // add
    $app->any('/ProveedorAddopt', ProveedorController::class . ':addopt')->add(PermissionMiddleware::class)->setName('ProveedorAddopt-proveedor-addopt'); // addopt
    $app->any('/ProveedorView[/{id}]', ProveedorController::class . ':view')->add(PermissionMiddleware::class)->setName('ProveedorView-proveedor-view'); // view
    $app->any('/ProveedorEdit[/{id}]', ProveedorController::class . ':edit')->add(PermissionMiddleware::class)->setName('ProveedorEdit-proveedor-edit'); // edit
    $app->any('/ProveedorDelete[/{id}]', ProveedorController::class . ':delete')->add(PermissionMiddleware::class)->setName('ProveedorDelete-proveedor-delete'); // delete
    $app->group(
        '/proveedor',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ProveedorController::class . ':list')->add(PermissionMiddleware::class)->setName('proveedor/list-proveedor-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ProveedorController::class . ':add')->add(PermissionMiddleware::class)->setName('proveedor/add-proveedor-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', ProveedorController::class . ':addopt')->add(PermissionMiddleware::class)->setName('proveedor/addopt-proveedor-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ProveedorController::class . ':view')->add(PermissionMiddleware::class)->setName('proveedor/view-proveedor-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ProveedorController::class . ':edit')->add(PermissionMiddleware::class)->setName('proveedor/edit-proveedor-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ProveedorController::class . ':delete')->add(PermissionMiddleware::class)->setName('proveedor/delete-proveedor-delete-2'); // delete
        }
    );

    // proveedor_articulo
    $app->any('/ProveedorArticuloList[/{id}]', ProveedorArticuloController::class . ':list')->add(PermissionMiddleware::class)->setName('ProveedorArticuloList-proveedor_articulo-list'); // list
    $app->any('/ProveedorArticuloAdd[/{id}]', ProveedorArticuloController::class . ':add')->add(PermissionMiddleware::class)->setName('ProveedorArticuloAdd-proveedor_articulo-add'); // add
    $app->any('/ProveedorArticuloView[/{id}]', ProveedorArticuloController::class . ':view')->add(PermissionMiddleware::class)->setName('ProveedorArticuloView-proveedor_articulo-view'); // view
    $app->any('/ProveedorArticuloEdit[/{id}]', ProveedorArticuloController::class . ':edit')->add(PermissionMiddleware::class)->setName('ProveedorArticuloEdit-proveedor_articulo-edit'); // edit
    $app->any('/ProveedorArticuloDelete[/{id}]', ProveedorArticuloController::class . ':delete')->add(PermissionMiddleware::class)->setName('ProveedorArticuloDelete-proveedor_articulo-delete'); // delete
    $app->any('/ProveedorArticuloPreview', ProveedorArticuloController::class . ':preview')->add(PermissionMiddleware::class)->setName('ProveedorArticuloPreview-proveedor_articulo-preview'); // preview
    $app->group(
        '/proveedor_articulo',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ProveedorArticuloController::class . ':list')->add(PermissionMiddleware::class)->setName('proveedor_articulo/list-proveedor_articulo-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', ProveedorArticuloController::class . ':add')->add(PermissionMiddleware::class)->setName('proveedor_articulo/add-proveedor_articulo-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', ProveedorArticuloController::class . ':view')->add(PermissionMiddleware::class)->setName('proveedor_articulo/view-proveedor_articulo-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ProveedorArticuloController::class . ':edit')->add(PermissionMiddleware::class)->setName('proveedor_articulo/edit-proveedor_articulo-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', ProveedorArticuloController::class . ':delete')->add(PermissionMiddleware::class)->setName('proveedor_articulo/delete-proveedor_articulo-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', ProveedorArticuloController::class . ':preview')->add(PermissionMiddleware::class)->setName('proveedor_articulo/preview-proveedor_articulo-preview-2'); // preview
        }
    );

    // recarga
    $app->any('/RecargaList[/{id}]', RecargaController::class . ':list')->add(PermissionMiddleware::class)->setName('RecargaList-recarga-list'); // list
    $app->any('/RecargaView[/{id}]', RecargaController::class . ':view')->add(PermissionMiddleware::class)->setName('RecargaView-recarga-view'); // view
    $app->any('/RecargaPreview', RecargaController::class . ':preview')->add(PermissionMiddleware::class)->setName('RecargaPreview-recarga-preview'); // preview
    $app->group(
        '/recarga',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', RecargaController::class . ':list')->add(PermissionMiddleware::class)->setName('recarga/list-recarga-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', RecargaController::class . ':view')->add(PermissionMiddleware::class)->setName('recarga/view-recarga-view-2'); // view
            $group->any('/' . Config("PREVIEW_ACTION") . '', RecargaController::class . ':preview')->add(PermissionMiddleware::class)->setName('recarga/preview-recarga-preview-2'); // preview
        }
    );

    // recarga2
    $app->any('/Recarga2List[/{id}]', Recarga2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('Recarga2List-recarga2-list'); // list
    $app->any('/Recarga2View[/{id}]', Recarga2Controller::class . ':view')->add(PermissionMiddleware::class)->setName('Recarga2View-recarga2-view'); // view
    $app->any('/Recarga2Preview', Recarga2Controller::class . ':preview')->add(PermissionMiddleware::class)->setName('Recarga2Preview-recarga2-preview'); // preview
    $app->group(
        '/recarga2',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', Recarga2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('recarga2/list-recarga2-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', Recarga2Controller::class . ':view')->add(PermissionMiddleware::class)->setName('recarga2/view-recarga2-view-2'); // view
            $group->any('/' . Config("PREVIEW_ACTION") . '', Recarga2Controller::class . ':preview')->add(PermissionMiddleware::class)->setName('recarga2/preview-recarga2-preview-2'); // preview
        }
    );

    // reimprimir_factura
    $app->any('/ReimprimirFactura[/{params:.*}]', ReimprimirFacturaController::class)->add(PermissionMiddleware::class)->setName('ReimprimirFactura-reimprimir_factura-custom'); // custom

    // reimprimir_factura_buscar_listar
    $app->any('/ReimprimirFacturaBuscarListar[/{params:.*}]', ReimprimirFacturaBuscarListarController::class)->add(PermissionMiddleware::class)->setName('ReimprimirFacturaBuscarListar-reimprimir_factura_buscar_listar-custom'); // custom

    // reimprimir_factura_ver
    $app->any('/ReimprimirFacturaVer[/{params:.*}]', ReimprimirFacturaVerController::class)->add(PermissionMiddleware::class)->setName('ReimprimirFacturaVer-reimprimir_factura_ver-custom'); // custom

    // rif_buscar
    $app->any('/RifBuscar[/{params:.*}]', RifBuscarController::class)->add(PermissionMiddleware::class)->setName('RifBuscar-rif_buscar-custom'); // custom

    // salidas
    $app->any('/SalidasList[/{id}]', SalidasController::class . ':list')->add(PermissionMiddleware::class)->setName('SalidasList-salidas-list'); // list
    $app->any('/SalidasAdd[/{id}]', SalidasController::class . ':add')->add(PermissionMiddleware::class)->setName('SalidasAdd-salidas-add'); // add
    $app->any('/SalidasView[/{id}]', SalidasController::class . ':view')->add(PermissionMiddleware::class)->setName('SalidasView-salidas-view'); // view
    $app->any('/SalidasEdit[/{id}]', SalidasController::class . ':edit')->add(PermissionMiddleware::class)->setName('SalidasEdit-salidas-edit'); // edit
    $app->any('/SalidasDelete[/{id}]', SalidasController::class . ':delete')->add(PermissionMiddleware::class)->setName('SalidasDelete-salidas-delete'); // delete
    $app->group(
        '/salidas',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', SalidasController::class . ':list')->add(PermissionMiddleware::class)->setName('salidas/list-salidas-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', SalidasController::class . ':add')->add(PermissionMiddleware::class)->setName('salidas/add-salidas-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', SalidasController::class . ':view')->add(PermissionMiddleware::class)->setName('salidas/view-salidas-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', SalidasController::class . ':edit')->add(PermissionMiddleware::class)->setName('salidas/edit-salidas-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', SalidasController::class . ':delete')->add(PermissionMiddleware::class)->setName('salidas/delete-salidas-delete-2'); // delete
        }
    );

    // sesiones
    $app->any('/Sesiones[/{params:.*}]', SesionesController::class)->add(PermissionMiddleware::class)->setName('Sesiones-sesiones-custom'); // custom

    // sincronizar
    $app->any('/Sincronizar[/{params:.*}]', SincronizarController::class)->add(PermissionMiddleware::class)->setName('Sincronizar-sincronizar-custom'); // custom

    // subir_costo
    $app->any('/SubirCosto[/{params:.*}]', SubirCostoController::class)->add(PermissionMiddleware::class)->setName('SubirCosto-subir_costo-custom'); // custom

    // subir_costo_guardar
    $app->any('/SubirCostoGuardar[/{params:.*}]', SubirCostoGuardarController::class)->add(PermissionMiddleware::class)->setName('SubirCostoGuardar-subir_costo_guardar-custom'); // custom

    // subir_por_desc_articulo
    $app->any('/SubirPorDescArticulo[/{params:.*}]', SubirPorDescArticuloController::class)->add(PermissionMiddleware::class)->setName('SubirPorDescArticulo-subir_por_desc_articulo-custom'); // custom

    // subir_por_desc_articulo_guardar
    $app->any('/SubirPorDescArticuloGuardar[/{params:.*}]', SubirPorDescArticuloGuardarController::class)->add(PermissionMiddleware::class)->setName('SubirPorDescArticuloGuardar-subir_por_desc_articulo_guardar-custom'); // custom

    // subir_tarifa
    $app->any('/SubirTarifa[/{params:.*}]', SubirTarifaController::class)->add(PermissionMiddleware::class)->setName('SubirTarifa-subir_tarifa-custom'); // custom

    // subir_tarifa_guardar
    $app->any('/SubirTarifaGuardar[/{params:.*}]', SubirTarifaGuardarController::class)->add(PermissionMiddleware::class)->setName('SubirTarifaGuardar-subir_tarifa_guardar-custom'); // custom

    // SyncItem
    $app->any('/SyncItem[/{params:.*}]', SyncItemController::class)->add(PermissionMiddleware::class)->setName('SyncItem-SyncItem-custom'); // custom

    // tabla
    $app->any('/TablaList[/{id}]', TablaController::class . ':list')->add(PermissionMiddleware::class)->setName('TablaList-tabla-list'); // list
    $app->any('/TablaAdd[/{id}]', TablaController::class . ':add')->add(PermissionMiddleware::class)->setName('TablaAdd-tabla-add'); // add
    $app->any('/TablaView[/{id}]', TablaController::class . ':view')->add(PermissionMiddleware::class)->setName('TablaView-tabla-view'); // view
    $app->any('/TablaEdit[/{id}]', TablaController::class . ':edit')->add(PermissionMiddleware::class)->setName('TablaEdit-tabla-edit'); // edit
    $app->group(
        '/tabla',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', TablaController::class . ':list')->add(PermissionMiddleware::class)->setName('tabla/list-tabla-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', TablaController::class . ':add')->add(PermissionMiddleware::class)->setName('tabla/add-tabla-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', TablaController::class . ':view')->add(PermissionMiddleware::class)->setName('tabla/view-tabla-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', TablaController::class . ':edit')->add(PermissionMiddleware::class)->setName('tabla/edit-tabla-edit-2'); // edit
        }
    );

    // tabla_retenciones
    $app->any('/TablaRetencionesList[/{id}]', TablaRetencionesController::class . ':list')->add(PermissionMiddleware::class)->setName('TablaRetencionesList-tabla_retenciones-list'); // list
    $app->any('/TablaRetencionesAdd[/{id}]', TablaRetencionesController::class . ':add')->add(PermissionMiddleware::class)->setName('TablaRetencionesAdd-tabla_retenciones-add'); // add
    $app->any('/TablaRetencionesView[/{id}]', TablaRetencionesController::class . ':view')->add(PermissionMiddleware::class)->setName('TablaRetencionesView-tabla_retenciones-view'); // view
    $app->any('/TablaRetencionesEdit[/{id}]', TablaRetencionesController::class . ':edit')->add(PermissionMiddleware::class)->setName('TablaRetencionesEdit-tabla_retenciones-edit'); // edit
    $app->group(
        '/tabla_retenciones',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', TablaRetencionesController::class . ':list')->add(PermissionMiddleware::class)->setName('tabla_retenciones/list-tabla_retenciones-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', TablaRetencionesController::class . ':add')->add(PermissionMiddleware::class)->setName('tabla_retenciones/add-tabla_retenciones-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', TablaRetencionesController::class . ':view')->add(PermissionMiddleware::class)->setName('tabla_retenciones/view-tabla_retenciones-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', TablaRetencionesController::class . ':edit')->add(PermissionMiddleware::class)->setName('tabla_retenciones/edit-tabla_retenciones-edit-2'); // edit
        }
    );

    // tarifa
    $app->any('/TarifaList[/{id}]', TarifaController::class . ':list')->add(PermissionMiddleware::class)->setName('TarifaList-tarifa-list'); // list
    $app->any('/TarifaAdd[/{id}]', TarifaController::class . ':add')->add(PermissionMiddleware::class)->setName('TarifaAdd-tarifa-add'); // add
    $app->any('/TarifaView[/{id}]', TarifaController::class . ':view')->add(PermissionMiddleware::class)->setName('TarifaView-tarifa-view'); // view
    $app->any('/TarifaEdit[/{id}]', TarifaController::class . ':edit')->add(PermissionMiddleware::class)->setName('TarifaEdit-tarifa-edit'); // edit
    $app->any('/TarifaDelete[/{id}]', TarifaController::class . ':delete')->add(PermissionMiddleware::class)->setName('TarifaDelete-tarifa-delete'); // delete
    $app->group(
        '/tarifa',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', TarifaController::class . ':list')->add(PermissionMiddleware::class)->setName('tarifa/list-tarifa-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', TarifaController::class . ':add')->add(PermissionMiddleware::class)->setName('tarifa/add-tarifa-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', TarifaController::class . ':view')->add(PermissionMiddleware::class)->setName('tarifa/view-tarifa-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', TarifaController::class . ':edit')->add(PermissionMiddleware::class)->setName('tarifa/edit-tarifa-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', TarifaController::class . ':delete')->add(PermissionMiddleware::class)->setName('tarifa/delete-tarifa-delete-2'); // delete
        }
    );

    // tarifa_anterior
    $app->any('/TarifaAnteriorList', TarifaAnteriorController::class . ':list')->add(PermissionMiddleware::class)->setName('TarifaAnteriorList-tarifa_anterior-list'); // list
    $app->group(
        '/tarifa_anterior',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', TarifaAnteriorController::class . ':list')->add(PermissionMiddleware::class)->setName('tarifa_anterior/list-tarifa_anterior-list-2'); // list
        }
    );

    // tarifa_articulo
    $app->any('/TarifaArticuloList[/{id}]', TarifaArticuloController::class . ':list')->add(PermissionMiddleware::class)->setName('TarifaArticuloList-tarifa_articulo-list'); // list
    $app->any('/TarifaArticuloAdd[/{id}]', TarifaArticuloController::class . ':add')->add(PermissionMiddleware::class)->setName('TarifaArticuloAdd-tarifa_articulo-add'); // add
    $app->any('/TarifaArticuloView[/{id}]', TarifaArticuloController::class . ':view')->add(PermissionMiddleware::class)->setName('TarifaArticuloView-tarifa_articulo-view'); // view
    $app->any('/TarifaArticuloEdit[/{id}]', TarifaArticuloController::class . ':edit')->add(PermissionMiddleware::class)->setName('TarifaArticuloEdit-tarifa_articulo-edit'); // edit
    $app->any('/TarifaArticuloDelete[/{id}]', TarifaArticuloController::class . ':delete')->add(PermissionMiddleware::class)->setName('TarifaArticuloDelete-tarifa_articulo-delete'); // delete
    $app->any('/TarifaArticuloPreview', TarifaArticuloController::class . ':preview')->add(PermissionMiddleware::class)->setName('TarifaArticuloPreview-tarifa_articulo-preview'); // preview
    $app->group(
        '/tarifa_articulo',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', TarifaArticuloController::class . ':list')->add(PermissionMiddleware::class)->setName('tarifa_articulo/list-tarifa_articulo-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', TarifaArticuloController::class . ':add')->add(PermissionMiddleware::class)->setName('tarifa_articulo/add-tarifa_articulo-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', TarifaArticuloController::class . ':view')->add(PermissionMiddleware::class)->setName('tarifa_articulo/view-tarifa_articulo-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', TarifaArticuloController::class . ':edit')->add(PermissionMiddleware::class)->setName('tarifa_articulo/edit-tarifa_articulo-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', TarifaArticuloController::class . ':delete')->add(PermissionMiddleware::class)->setName('tarifa_articulo/delete-tarifa_articulo-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', TarifaArticuloController::class . ':preview')->add(PermissionMiddleware::class)->setName('tarifa_articulo/preview-tarifa_articulo-preview-2'); // preview
        }
    );

    // tasa_usd
    $app->any('/TasaUsdList[/{id}]', TasaUsdController::class . ':list')->add(PermissionMiddleware::class)->setName('TasaUsdList-tasa_usd-list'); // list
    $app->any('/TasaUsdAdd[/{id}]', TasaUsdController::class . ':add')->add(PermissionMiddleware::class)->setName('TasaUsdAdd-tasa_usd-add'); // add
    $app->any('/TasaUsdView[/{id}]', TasaUsdController::class . ':view')->add(PermissionMiddleware::class)->setName('TasaUsdView-tasa_usd-view'); // view
    $app->any('/TasaUsdEdit[/{id}]', TasaUsdController::class . ':edit')->add(PermissionMiddleware::class)->setName('TasaUsdEdit-tasa_usd-edit'); // edit
    $app->group(
        '/tasa_usd',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', TasaUsdController::class . ':list')->add(PermissionMiddleware::class)->setName('tasa_usd/list-tasa_usd-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', TasaUsdController::class . ':add')->add(PermissionMiddleware::class)->setName('tasa_usd/add-tasa_usd-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', TasaUsdController::class . ':view')->add(PermissionMiddleware::class)->setName('tasa_usd/view-tasa_usd-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', TasaUsdController::class . ':edit')->add(PermissionMiddleware::class)->setName('tasa_usd/edit-tasa_usd-edit-2'); // edit
        }
    );

    // temp_consignacion
    $app->any('/TempConsignacionList[/{id}]', TempConsignacionController::class . ':list')->add(PermissionMiddleware::class)->setName('TempConsignacionList-temp_consignacion-list'); // list
    $app->any('/TempConsignacionAdd[/{id}]', TempConsignacionController::class . ':add')->add(PermissionMiddleware::class)->setName('TempConsignacionAdd-temp_consignacion-add'); // add
    $app->any('/TempConsignacionView[/{id}]', TempConsignacionController::class . ':view')->add(PermissionMiddleware::class)->setName('TempConsignacionView-temp_consignacion-view'); // view
    $app->any('/TempConsignacionEdit[/{id}]', TempConsignacionController::class . ':edit')->add(PermissionMiddleware::class)->setName('TempConsignacionEdit-temp_consignacion-edit'); // edit
    $app->any('/TempConsignacionDelete[/{id}]', TempConsignacionController::class . ':delete')->add(PermissionMiddleware::class)->setName('TempConsignacionDelete-temp_consignacion-delete'); // delete
    $app->group(
        '/temp_consignacion',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', TempConsignacionController::class . ':list')->add(PermissionMiddleware::class)->setName('temp_consignacion/list-temp_consignacion-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', TempConsignacionController::class . ':add')->add(PermissionMiddleware::class)->setName('temp_consignacion/add-temp_consignacion-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', TempConsignacionController::class . ':view')->add(PermissionMiddleware::class)->setName('temp_consignacion/view-temp_consignacion-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', TempConsignacionController::class . ':edit')->add(PermissionMiddleware::class)->setName('temp_consignacion/edit-temp_consignacion-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', TempConsignacionController::class . ':delete')->add(PermissionMiddleware::class)->setName('temp_consignacion/delete-temp_consignacion-delete-2'); // delete
        }
    );

    // tipo_documento
    $app->any('/TipoDocumentoList[/{id}]', TipoDocumentoController::class . ':list')->add(PermissionMiddleware::class)->setName('TipoDocumentoList-tipo_documento-list'); // list
    $app->any('/TipoDocumentoView[/{id}]', TipoDocumentoController::class . ':view')->add(PermissionMiddleware::class)->setName('TipoDocumentoView-tipo_documento-view'); // view
    $app->any('/TipoDocumentoEdit[/{id}]', TipoDocumentoController::class . ':edit')->add(PermissionMiddleware::class)->setName('TipoDocumentoEdit-tipo_documento-edit'); // edit
    $app->group(
        '/tipo_documento',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', TipoDocumentoController::class . ':list')->add(PermissionMiddleware::class)->setName('tipo_documento/list-tipo_documento-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', TipoDocumentoController::class . ':view')->add(PermissionMiddleware::class)->setName('tipo_documento/view-tipo_documento-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', TipoDocumentoController::class . ':edit')->add(PermissionMiddleware::class)->setName('tipo_documento/edit-tipo_documento-edit-2'); // edit
        }
    );

    // unidad_medida
    $app->any('/UnidadMedidaList[/{id}]', UnidadMedidaController::class . ':list')->add(PermissionMiddleware::class)->setName('UnidadMedidaList-unidad_medida-list'); // list
    $app->any('/UnidadMedidaAdd[/{id}]', UnidadMedidaController::class . ':add')->add(PermissionMiddleware::class)->setName('UnidadMedidaAdd-unidad_medida-add'); // add
    $app->any('/UnidadMedidaView[/{id}]', UnidadMedidaController::class . ':view')->add(PermissionMiddleware::class)->setName('UnidadMedidaView-unidad_medida-view'); // view
    $app->any('/UnidadMedidaEdit[/{id}]', UnidadMedidaController::class . ':edit')->add(PermissionMiddleware::class)->setName('UnidadMedidaEdit-unidad_medida-edit'); // edit
    $app->any('/UnidadMedidaDelete[/{id}]', UnidadMedidaController::class . ':delete')->add(PermissionMiddleware::class)->setName('UnidadMedidaDelete-unidad_medida-delete'); // delete
    $app->group(
        '/unidad_medida',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', UnidadMedidaController::class . ':list')->add(PermissionMiddleware::class)->setName('unidad_medida/list-unidad_medida-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', UnidadMedidaController::class . ':add')->add(PermissionMiddleware::class)->setName('unidad_medida/add-unidad_medida-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', UnidadMedidaController::class . ':view')->add(PermissionMiddleware::class)->setName('unidad_medida/view-unidad_medida-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', UnidadMedidaController::class . ':edit')->add(PermissionMiddleware::class)->setName('unidad_medida/edit-unidad_medida-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', UnidadMedidaController::class . ':delete')->add(PermissionMiddleware::class)->setName('unidad_medida/delete-unidad_medida-delete-2'); // delete
        }
    );

    // userlevelpermissions
    $app->any('/UserlevelpermissionsList[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsList-userlevelpermissions-list'); // list
    $app->any('/UserlevelpermissionsAdd[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsAdd-userlevelpermissions-add'); // add
    $app->any('/UserlevelpermissionsView[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsView-userlevelpermissions-view'); // view
    $app->any('/UserlevelpermissionsEdit[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsEdit-userlevelpermissions-edit'); // edit
    $app->any('/UserlevelpermissionsDelete[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsDelete-userlevelpermissions-delete'); // delete
    $app->group(
        '/userlevelpermissions',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelpermissions/list-userlevelpermissions-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelpermissions/add-userlevelpermissions-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelpermissions/view-userlevelpermissions-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelpermissions/edit-userlevelpermissions-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelpermissions/delete-userlevelpermissions-delete-2'); // delete
        }
    );

    // userlevels
    $app->any('/UserlevelsList[/{userlevelid}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('UserlevelsList-userlevels-list'); // list
    $app->any('/UserlevelsAdd[/{userlevelid}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('UserlevelsAdd-userlevels-add'); // add
    $app->any('/UserlevelsView[/{userlevelid}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('UserlevelsView-userlevels-view'); // view
    $app->any('/UserlevelsEdit[/{userlevelid}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('UserlevelsEdit-userlevels-edit'); // edit
    $app->any('/UserlevelsDelete[/{userlevelid}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('UserlevelsDelete-userlevels-delete'); // delete
    $app->group(
        '/userlevels',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevels/list-userlevels-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevels/add-userlevels-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevels/view-userlevels-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevels/edit-userlevels-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevels/delete-userlevels-delete-2'); // delete
        }
    );

    // usuario
    $app->any('/UsuarioList[/{id}]', UsuarioController::class . ':list')->add(PermissionMiddleware::class)->setName('UsuarioList-usuario-list'); // list
    $app->any('/UsuarioAdd[/{id}]', UsuarioController::class . ':add')->add(PermissionMiddleware::class)->setName('UsuarioAdd-usuario-add'); // add
    $app->any('/UsuarioView[/{id}]', UsuarioController::class . ':view')->add(PermissionMiddleware::class)->setName('UsuarioView-usuario-view'); // view
    $app->any('/UsuarioEdit[/{id}]', UsuarioController::class . ':edit')->add(PermissionMiddleware::class)->setName('UsuarioEdit-usuario-edit'); // edit
    $app->any('/UsuarioDelete[/{id}]', UsuarioController::class . ':delete')->add(PermissionMiddleware::class)->setName('UsuarioDelete-usuario-delete'); // delete
    $app->group(
        '/usuario',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', UsuarioController::class . ':list')->add(PermissionMiddleware::class)->setName('usuario/list-usuario-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', UsuarioController::class . ':add')->add(PermissionMiddleware::class)->setName('usuario/add-usuario-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', UsuarioController::class . ':view')->add(PermissionMiddleware::class)->setName('usuario/view-usuario-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', UsuarioController::class . ':edit')->add(PermissionMiddleware::class)->setName('usuario/edit-usuario-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id}]', UsuarioController::class . ':delete')->add(PermissionMiddleware::class)->setName('usuario/delete-usuario-delete-2'); // delete
        }
    );

    // ventas_por_laboratorio
    $app->any('/VentasPorLaboratorio[/{params:.*}]', VentasPorLaboratorioController::class)->add(PermissionMiddleware::class)->setName('VentasPorLaboratorio-ventas_por_laboratorio-custom'); // custom

    // verificar_existencia
    $app->any('/VerificarExistencia[/{params:.*}]', VerificarExistenciaController::class)->add(PermissionMiddleware::class)->setName('VerificarExistencia-verificar_existencia-custom'); // custom

    // verificar_existencia_update2
    $app->any('/VerificarExistenciaUpdate2[/{params:.*}]', VerificarExistenciaUpdate2Controller::class)->add(PermissionMiddleware::class)->setName('VerificarExistenciaUpdate2-verificar_existencia_update2-custom'); // custom

    // verificar_venta
    $app->any('/VerificarVenta[/{params:.*}]', VerificarVentaController::class)->add(PermissionMiddleware::class)->setName('VerificarVenta-verificar_venta-custom'); // custom

    // view_articulos
    $app->any('/ViewArticulosList[/{id}]', ViewArticulosController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewArticulosList-view_articulos-list'); // list
    $app->any('/ViewArticulosEdit[/{id}]', ViewArticulosController::class . ':edit')->add(PermissionMiddleware::class)->setName('ViewArticulosEdit-view_articulos-edit'); // edit
    $app->group(
        '/view_articulos',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewArticulosController::class . ':list')->add(PermissionMiddleware::class)->setName('view_articulos/list-view_articulos-list-2'); // list
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ViewArticulosController::class . ':edit')->add(PermissionMiddleware::class)->setName('view_articulos/edit-view_articulos-edit-2'); // edit
        }
    );

    // view_banco
    $app->any('/ViewBancoList[/{id}]', ViewBancoController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewBancoList-view_banco-list'); // list
    $app->group(
        '/view_banco',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewBancoController::class . ':list')->add(PermissionMiddleware::class)->setName('view_banco/list-view_banco-list-2'); // list
        }
    );

    // view_bultos
    $app->any('/ViewBultosList[/{id}]', ViewBultosController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewBultosList-view_bultos-list'); // list
    $app->group(
        '/view_bultos',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewBultosController::class . ':list')->add(PermissionMiddleware::class)->setName('view_bultos/list-view_bultos-list-2'); // list
        }
    );

    // view_costo_articulos_no_encontrados
    $app->any('/ViewCostoArticulosNoEncontradosList', ViewCostoArticulosNoEncontradosController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewCostoArticulosNoEncontradosList-view_costo_articulos_no_encontrados-list'); // list
    $app->group(
        '/view_costo_articulos_no_encontrados',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', ViewCostoArticulosNoEncontradosController::class . ':list')->add(PermissionMiddleware::class)->setName('view_costo_articulos_no_encontrados/list-view_costo_articulos_no_encontrados-list-2'); // list
        }
    );

    // view_entradas
    $app->any('/ViewEntradasList[/{id}]', ViewEntradasController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewEntradasList-view_entradas-list'); // list
    $app->group(
        '/view_entradas',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewEntradasController::class . ':list')->add(PermissionMiddleware::class)->setName('view_entradas/list-view_entradas-list-2'); // list
        }
    );

    // view_entradas_salidas
    $app->any('/ViewEntradasSalidasList[/{id}]', ViewEntradasSalidasController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewEntradasSalidasList-view_entradas_salidas-list'); // list
    $app->any('/ViewEntradasSalidasPreview', ViewEntradasSalidasController::class . ':preview')->add(PermissionMiddleware::class)->setName('ViewEntradasSalidasPreview-view_entradas_salidas-preview'); // preview
    $app->group(
        '/view_entradas_salidas',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewEntradasSalidasController::class . ':list')->add(PermissionMiddleware::class)->setName('view_entradas_salidas/list-view_entradas_salidas-list-2'); // list
            $group->any('/' . Config("PREVIEW_ACTION") . '', ViewEntradasSalidasController::class . ':preview')->add(PermissionMiddleware::class)->setName('view_entradas_salidas/preview-view_entradas_salidas-preview-2'); // preview
        }
    );

    // view_factura_asesor
    $app->any('/ViewFacturaAsesorList[/{id}]', ViewFacturaAsesorController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewFacturaAsesorList-view_factura_asesor-list'); // list
    $app->any('/ViewFacturaAsesorEdit[/{id}]', ViewFacturaAsesorController::class . ':edit')->add(PermissionMiddleware::class)->setName('ViewFacturaAsesorEdit-view_factura_asesor-edit'); // edit
    $app->group(
        '/view_factura_asesor',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewFacturaAsesorController::class . ':list')->add(PermissionMiddleware::class)->setName('view_factura_asesor/list-view_factura_asesor-list-2'); // list
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ViewFacturaAsesorController::class . ':edit')->add(PermissionMiddleware::class)->setName('view_factura_asesor/edit-view_factura_asesor-edit-2'); // edit
        }
    );

    // view_facturas_a_entregar
    $app->any('/ViewFacturasAEntregarList[/{id}]', ViewFacturasAEntregarController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewFacturasAEntregarList-view_facturas_a_entregar-list'); // list
    $app->any('/ViewFacturasAEntregarEdit[/{id}]', ViewFacturasAEntregarController::class . ':edit')->add(PermissionMiddleware::class)->setName('ViewFacturasAEntregarEdit-view_facturas_a_entregar-edit'); // edit
    $app->group(
        '/view_facturas_a_entregar',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewFacturasAEntregarController::class . ':list')->add(PermissionMiddleware::class)->setName('view_facturas_a_entregar/list-view_facturas_a_entregar-list-2'); // list
            $group->any('/' . Config("EDIT_ACTION") . '[/{id}]', ViewFacturasAEntregarController::class . ':edit')->add(PermissionMiddleware::class)->setName('view_facturas_a_entregar/edit-view_facturas_a_entregar-edit-2'); // edit
        }
    );

    // view_facturas_vencidas
    $app->any('/ViewFacturasVencidasList[/{id}]', ViewFacturasVencidasController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewFacturasVencidasList-view_facturas_vencidas-list'); // list
    $app->group(
        '/view_facturas_vencidas',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewFacturasVencidasController::class . ':list')->add(PermissionMiddleware::class)->setName('view_facturas_vencidas/list-view_facturas_vencidas-list-2'); // list
        }
    );

    // view_plancta
    $app->any('/ViewPlanctaList[/{id}]', ViewPlanctaController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewPlanctaList-view_plancta-list'); // list
    $app->group(
        '/view_plancta',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewPlanctaController::class . ':list')->add(PermissionMiddleware::class)->setName('view_plancta/list-view_plancta-list-2'); // list
        }
    );

    // view_saldos
    $app->any('/ViewSaldosList', ViewSaldosController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewSaldosList-view_saldos-list'); // list
    $app->group(
        '/view_saldos',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', ViewSaldosController::class . ':list')->add(PermissionMiddleware::class)->setName('view_saldos/list-view_saldos-list-2'); // list
        }
    );

    // view_salidas
    $app->any('/ViewSalidasList[/{id}]', ViewSalidasController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewSalidasList-view_salidas-list'); // list
    $app->group(
        '/view_salidas',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', ViewSalidasController::class . ':list')->add(PermissionMiddleware::class)->setName('view_salidas/list-view_salidas-list-2'); // list
        }
    );

    // view_tarifa
    $app->any('/ViewTarifaList', ViewTarifaController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewTarifaList-view_tarifa-list'); // list
    $app->group(
        '/view_tarifa',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', ViewTarifaController::class . ':list')->add(PermissionMiddleware::class)->setName('view_tarifa/list-view_tarifa-list-2'); // list
        }
    );

    // view_tarifa_articulos_no_encontrados
    $app->any('/ViewTarifaArticulosNoEncontradosList', ViewTarifaArticulosNoEncontradosController::class . ':list')->add(PermissionMiddleware::class)->setName('ViewTarifaArticulosNoEncontradosList-view_tarifa_articulos_no_encontrados-list'); // list
    $app->group(
        '/view_tarifa_articulos_no_encontrados',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', ViewTarifaArticulosNoEncontradosController::class . ':list')->add(PermissionMiddleware::class)->setName('view_tarifa_articulos_no_encontrados/list-view_tarifa_articulos_no_encontrados-list-2'); // list
        }
    );

    // ya_fue_procesado
    $app->any('/YaFueProcesado[/{params:.*}]', YaFueProcesadoController::class)->add(PermissionMiddleware::class)->setName('YaFueProcesado-ya_fue_procesado-custom'); // custom

    // view_saldos2
    $app->any('/ViewSaldos2List', ViewSaldos2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('ViewSaldos2List-view_saldos2-list'); // list
    $app->group(
        '/view_saldos2',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', ViewSaldos2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('view_saldos2/list-view_saldos2-list-2'); // list
        }
    );

    // puntos
    $app->any('/PuntosList[/{id}]', PuntosController::class . ':list')->add(PermissionMiddleware::class)->setName('PuntosList-puntos-list'); // list
    $app->any('/PuntosAdd[/{id}]', PuntosController::class . ':add')->add(PermissionMiddleware::class)->setName('PuntosAdd-puntos-add'); // add
    $app->any('/PuntosView[/{id}]', PuntosController::class . ':view')->add(PermissionMiddleware::class)->setName('PuntosView-puntos-view'); // view
    $app->group(
        '/puntos',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id}]', PuntosController::class . ':list')->add(PermissionMiddleware::class)->setName('puntos/list-puntos-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id}]', PuntosController::class . ':add')->add(PermissionMiddleware::class)->setName('puntos/add-puntos-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id}]', PuntosController::class . ':view')->add(PermissionMiddleware::class)->setName('puntos/view-puntos-view-2'); // view
        }
    );

    // error
    $app->any('/error', OthersController::class . ':error')->add(PermissionMiddleware::class)->setName('error');

    // personal_data
    $app->any('/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->any('/login', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // change_password
    $app->any('/changepassword', OthersController::class . ':changepassword')->add(PermissionMiddleware::class)->setName('changepassword');

    // userpriv
    $app->any('/userpriv', OthersController::class . ':userpriv')->add(PermissionMiddleware::class)->setName('userpriv');

    // logout
    $app->any('/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // captcha
    $app->any('/captcha[/{page}]', OthersController::class . ':captcha')->add(PermissionMiddleware::class)->setName('captcha');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->any('/[index]', OthersController::class . ':index')->add(PermissionMiddleware::class)->setName('index');

    // Route Action event
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        Route_Action($app);
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            $error = [
                "statusCode" => "404",
                "error" => [
                    "class" => "text-warning",
                    "type" => Container("language")->phrase("Error"),
                    "description" => str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")),
                ],
            ];
            Container("flash")->addMessage("error", $error);
            return $response->withStatus(302)->withHeader("Location", GetUrl("error")); // Redirect to error page
        }
    );
};
