<?php

namespace PHPMaker2021\mandrake;

use Slim\Views\PhpRenderer;
use Slim\Csrf\Guard;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\DebugStack;

return [
    "cache" => function (ContainerInterface $c) {
        return new \Slim\HttpCache\CacheProvider();
    },
    "view" => function (ContainerInterface $c) {
        return new PhpRenderer("views/");
    },
    "flash" => function (ContainerInterface $c) {
        return new \Slim\Flash\Messages();
    },
    "audit" => function (ContainerInterface $c) {
        $logger = new Logger("audit"); // For audit trail
        $logger->pushHandler(new AuditTrailHandler("audit.log"));
        return $logger;
    },
    "log" => function (ContainerInterface $c) {
        global $RELATIVE_PATH;
        $logger = new Logger("log");
        $logger->pushHandler(new RotatingFileHandler($RELATIVE_PATH . "log.log"));
        return $logger;
    },
    "sqllogger" => function (ContainerInterface $c) {
        $loggers = [];
        if (Config("DEBUG")) {
            $loggers[] = $c->get("debugstack");
        }
        return (count($loggers) > 0) ? new LoggerChain($loggers) : null;
    },
    "csrf" => function (ContainerInterface $c) {
        global $ResponseFactory;
        return new Guard($ResponseFactory, Config("CSRF_PREFIX"));
    },
    "debugstack" => \DI\create(DebugStack::class),
    "debugsqllogger" => \DI\create(DebugSqlLogger::class),
    "security" => \DI\create(AdvancedSecurity::class),
    "profile" => \DI\create(UserProfile::class),
    "language" => \DI\create(Language::class),
    "timer" => \DI\create(Timer::class),
    "session" => \DI\create(HttpSession::class),

    // Tables
    "abono" => \DI\create(Abono::class),
    "abono2" => \DI\create(Abono2::class),
    "actualizar_nota_entrega" => \DI\create(ActualizarNotaEntrega::class),
    "Actualizar_tarifa_patron" => \DI\create(ActualizarTarifaPatron::class),
    "ActualizarExiste" => \DI\create(ActualizarExiste::class),
    "adjunto" => \DI\create(Adjunto::class),
    "ajustar_existencia_lotes" => \DI\create(AjustarExistenciaLotes::class),
    "ajuste_de_entrada_detalle_copia" => \DI\create(AjusteDeEntradaDetalleCopia::class),
    "ajuste_salida" => \DI\create(AjusteSalida::class),
    "alicuota" => \DI\create(Alicuota::class),
    "almacen" => \DI\create(Almacen::class),
    "anular_venta" => \DI\create(AnularVenta::class),
    "articulo" => \DI\create(Articulo::class),
    "articulo_anterior" => \DI\create(ArticuloAnterior::class),
    "articulo_porcentaje_descuento_temp" => \DI\create(ArticuloPorcentajeDescuentoTemp::class),
    "articulo_stock" => \DI\create(ArticuloStock::class),
    "articulo_unidad_medida" => \DI\create(ArticuloUnidadMedida::class),
    "asesor" => \DI\create(Asesor::class),
    "asesor_cliente" => \DI\create(AsesorCliente::class),
    "asesor_fabricante" => \DI\create(AsesorFabricante::class),
    "audittrail" => \DI\create(Audittrail::class),
    "balance_de_comprobacion" => \DI\create(BalanceDeComprobacion::class),
    "balance_general" => \DI\create(BalanceGeneral::class),
    "cierre_de_caja" => \DI\create(CierreDeCaja::class),
    "cliente" => \DI\create(Cliente::class),
    "cobros_cliente" => \DI\create(CobrosCliente::class),
    "cobros_cliente_detalle" => \DI\create(CobrosClienteDetalle::class),
    "cobros_cliente_factura" => \DI\create(CobrosClienteFactura::class),
    "codigo_buscar" => \DI\create(CodigoBuscar::class),
    "codigo_proveedor_buscar" => \DI\create(CodigoProveedorBuscar::class),
    "compania" => \DI\create(Compania::class),
    "compania_cuenta" => \DI\create(CompaniaCuenta::class),
    "compra" => \DI\create(Compra::class),
    "Confirm_Page" => \DI\create(ConfirmPage::class),
    "cont_asiento" => \DI\create(ContAsiento::class),
    "cont_comprobante" => \DI\create(ContComprobante::class),
    "cont_lotes" => \DI\create(ContLotes::class),
    "cont_lotes_pagos" => \DI\create(ContLotesPagos::class),
    "cont_lotes_pagos_detalle" => \DI\create(ContLotesPagosDetalle::class),
    "cont_mes_contable" => \DI\create(ContMesContable::class),
    "cont_periodo_contable" => \DI\create(ContPeriodoContable::class),
    "cont_plancta" => \DI\create(ContPlancta::class),
    "cont_reglas" => \DI\create(ContReglas::class),
    "cont_reglas_hr" => \DI\create(ContReglasHr::class),
    "convertir_a_factura" => \DI\create(ConvertirAFactura::class),
    "costo_temp" => \DI\create(CostoTemp::class),
    "crear_factura_compra" => \DI\create(CrearFacturaCompra::class),
    "crear_factura_venta" => \DI\create(CrearFacturaVenta::class),
    "crear_nota_entrada_update" => \DI\create(CrearNotaEntradaUpdate::class),
    "crear_nota_entrega" => \DI\create(CrearNotaEntrega::class),
    "crear_nota_entrega_guardar" => \DI\create(CrearNotaEntregaGuardar::class),
    "crear_nota_recepcion" => \DI\create(CrearNotaRecepcion::class),
    "devoluciones" => \DI\create(Devoluciones::class),
    "devoluciones_buscar" => \DI\create(DevolucionesBuscar::class),
    "devoluciones_guardar" => \DI\create(DevolucionesGuardar::class),
    "devoluciones_ver" => \DI\create(DevolucionesVer::class),
    "eliminar_linea" => \DI\create(EliminarLinea::class),
    "entradas" => \DI\create(Entradas::class),
    "entradas_salidas" => \DI\create(EntradasSalidas::class),
    "error_page" => \DI\create(ErrorPage::class),
    "estado_resultados" => \DI\create(EstadoResultados::class),
    "exportar_data" => \DI\create(ExportarData::class),
    "fabricante" => \DI\create(Fabricante::class),
    "factura_consignacion" => \DI\create(FacturaConsignacion::class),
    "factura_consignacion_guardar" => \DI\create(FacturaConsignacionGuardar::class),
    "factura_de_compra_detalle_copia" => \DI\create(FacturaDeCompraDetalleCopia::class),
    "factura_de_venta_copiar_como" => \DI\create(FacturaDeVentaCopiarComo::class),
    "factura_de_venta_detalle_copia" => \DI\create(FacturaDeVentaDetalleCopia::class),
    "funciones" => \DI\create(Funciones::class),
    "grupo_funciones" => \DI\create(GrupoFunciones::class),
    "home" => \DI\create(Home::class),
    "indicadores" => \DI\create(Indicadores::class),
    "libro_diario" => \DI\create(LibroDiario::class),
    "libro_mayor" => \DI\create(LibroMayor::class),
    "listado_master" => \DI\create(ListadoMaster::class),
    "listado_master_general" => \DI\create(ListadoMasterGeneral::class),
    "main_report" => \DI\create(MainReport::class),
    "masivo_ajuste_entrada" => \DI\create(MasivoAjusteEntrada::class),
    "masivo_ajuste_salida" => \DI\create(MasivoAjusteSalida::class),
    "medicamentos" => \DI\create(Medicamentos::class),
    "nota_de_entrega_buscar" => \DI\create(NotaDeEntregaBuscar::class),
    "nota_de_entrega_buscar_listar" => \DI\create(NotaDeEntregaBuscarListar::class),
    "nota_de_entrega_ver" => \DI\create(NotaDeEntregaVer::class),
    "notificaciones" => \DI\create(Notificaciones::class),
    "pagos" => \DI\create(Pagos::class),
    "pagos_proveedor" => \DI\create(PagosProveedor::class),
    "pagos_proveedor_factura" => \DI\create(PagosProveedorFactura::class),
    "parametro" => \DI\create(Parametro::class),
    "pedidio_detalle_online" => \DI\create(PedidioDetalleOnline::class),
    "pedido_de_compra_detalle_copia" => \DI\create(PedidoDeCompraDetalleCopia::class),
    "pedido_de_venta_detalle" => \DI\create(PedidoDeVentaDetalle::class),
    "pedido_de_venta_detalle_agregar" => \DI\create(PedidoDeVentaDetalleAgregar::class),
    "pedido_de_venta_detalle_copia" => \DI\create(PedidoDeVentaDetalleCopia::class),
    "pedido_de_venta_detalle_guardar" => \DI\create(PedidoDeVentaDetalleGuardar::class),
    "pedido_online" => \DI\create(PedidoOnline::class),
    "proveedor" => \DI\create(Proveedor::class),
    "proveedor_articulo" => \DI\create(ProveedorArticulo::class),
    "recarga" => \DI\create(Recarga::class),
    "recarga2" => \DI\create(Recarga2::class),
    "reimprimir_factura" => \DI\create(ReimprimirFactura::class),
    "reimprimir_factura_buscar_listar" => \DI\create(ReimprimirFacturaBuscarListar::class),
    "reimprimir_factura_ver" => \DI\create(ReimprimirFacturaVer::class),
    "rif_buscar" => \DI\create(RifBuscar::class),
    "salidas" => \DI\create(Salidas::class),
    "sesiones" => \DI\create(Sesiones::class),
    "sincronizar" => \DI\create(Sincronizar::class),
    "subir_costo" => \DI\create(SubirCosto::class),
    "subir_costo_guardar" => \DI\create(SubirCostoGuardar::class),
    "subir_por_desc_articulo" => \DI\create(SubirPorDescArticulo::class),
    "subir_por_desc_articulo_guardar" => \DI\create(SubirPorDescArticuloGuardar::class),
    "subir_tarifa" => \DI\create(SubirTarifa::class),
    "subir_tarifa_guardar" => \DI\create(SubirTarifaGuardar::class),
    "SyncItem" => \DI\create(SyncItem::class),
    "tabla" => \DI\create(Tabla::class),
    "tabla_retenciones" => \DI\create(TablaRetenciones::class),
    "tarifa" => \DI\create(Tarifa::class),
    "tarifa_anterior" => \DI\create(TarifaAnterior::class),
    "tarifa_articulo" => \DI\create(TarifaArticulo::class),
    "tarifa_articulo_temp" => \DI\create(TarifaArticuloTemp::class),
    "tarifa_temp" => \DI\create(TarifaTemp::class),
    "tasa_usd" => \DI\create(TasaUsd::class),
    "temp_consignacion" => \DI\create(TempConsignacion::class),
    "tipo_documento" => \DI\create(TipoDocumento::class),
    "unidad_medida" => \DI\create(UnidadMedida::class),
    "userlevelpermissions" => \DI\create(Userlevelpermissions::class),
    "userlevels" => \DI\create(Userlevels::class),
    "username_tipo_documento" => \DI\create(UsernameTipoDocumento::class),
    "usuario" => \DI\create(Usuario::class),
    "ventas_por_laboratorio" => \DI\create(VentasPorLaboratorio::class),
    "verificar_existencia" => \DI\create(VerificarExistencia::class),
    "verificar_existencia_update2" => \DI\create(VerificarExistenciaUpdate2::class),
    "verificar_venta" => \DI\create(VerificarVenta::class),
    "view_articulos" => \DI\create(ViewArticulos::class),
    "view_banco" => \DI\create(ViewBanco::class),
    "view_bultos" => \DI\create(ViewBultos::class),
    "view_costo_articulos_no_encontrados" => \DI\create(ViewCostoArticulosNoEncontrados::class),
    "view_entradas" => \DI\create(ViewEntradas::class),
    "view_entradas_salidas" => \DI\create(ViewEntradasSalidas::class),
    "view_factura_asesor" => \DI\create(ViewFacturaAsesor::class),
    "view_facturas_a_entregar" => \DI\create(ViewFacturasAEntregar::class),
    "view_facturas_vencidas" => \DI\create(ViewFacturasVencidas::class),
    "view_lotes" => \DI\create(ViewLotes::class),
    "view_lotes_subquery" => \DI\create(ViewLotesSubquery::class),
    "view_plancta" => \DI\create(ViewPlancta::class),
    "view_saldos" => \DI\create(ViewSaldos::class),
    "view_salidas" => \DI\create(ViewSalidas::class),
    "view_tarifa" => \DI\create(ViewTarifa::class),
    "view_tarifa_articulos_no_encontrados" => \DI\create(ViewTarifaArticulosNoEncontrados::class),
    "view_temp_tarifa" => \DI\create(ViewTempTarifa::class),
    "view_unidad_medida" => \DI\create(ViewUnidadMedida::class),
    "view_x_cobrar" => \DI\create(ViewXCobrar::class),
    "view_x_pagar" => \DI\create(ViewXPagar::class),
    "ya_fue_procesado" => \DI\create(YaFueProcesado::class),
    "view_saldos2" => \DI\create(ViewSaldos2::class),
    "puntos" => \DI\create(Puntos::class),

    // User table
    "usertable" => \DI\get("usuario"),
];
