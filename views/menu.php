<?php

namespace PHPMaker2021\mandrake;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(19, "mci_Maestros", $MenuLanguage->MenuPhrase("19", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(53, "mi_fabricante", $MenuLanguage->MenuPhrase("53", "MenuText"), $MenuRelativePath . "FabricanteList", 19, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}fabricante'), false, false, "", "", false);
$sideMenu->addMenuItem(9, "mi_proveedor", $MenuLanguage->MenuPhrase("9", "MenuText"), $MenuRelativePath . "ProveedorList", 19, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}proveedor'), false, false, "", "", false);
$sideMenu->addMenuItem(1, "mi_articulo", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "ArticuloList", 19, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}articulo'), false, false, "", "", false);
$sideMenu->addMenuItem(212, "mi_view_articulos", $MenuLanguage->MenuPhrase("212", "MenuText"), $MenuRelativePath . "ViewArticulosList", 19, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}view_articulos'), false, false, "", "", false);
$sideMenu->addMenuItem(2, "mi_asesor", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "AsesorList", 19, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}asesor'), false, false, "", "", false);
$sideMenu->addMenuItem(5, "mi_cliente", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "ClienteList", 19, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}cliente'), false, false, "", "", false);
$sideMenu->addMenuItem(48, "mci_Movimientos", $MenuLanguage->MenuPhrase("48", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(102, "mci_Pedido_de_Compra", $MenuLanguage->MenuPhrase("102", "MenuText"), $MenuRelativePath . "EntradasList?tipo=TDCPDC", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(103, "mci_Nota_de_Recepción", $MenuLanguage->MenuPhrase("103", "MenuText"), $MenuRelativePath . "EntradasList?tipo=TDCNRP", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(104, "mci_Factura_de_Compra", $MenuLanguage->MenuPhrase("104", "MenuText"), $MenuRelativePath . "EntradasList?tipo=TDCFCC", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(105, "mci_Ajuste_de_Entrada", $MenuLanguage->MenuPhrase("105", "MenuText"), $MenuRelativePath . "EntradasList?tipo=TDCAEN", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(189, "mci_--", $MenuLanguage->MenuPhrase("189", "MenuText"), $MenuRelativePath . "javascript:void(0);", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(106, "mci_Pedido_de_Ventas", $MenuLanguage->MenuPhrase("106", "MenuText"), $MenuRelativePath . "SalidasList?tipo=TDCPDV", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(107, "mci_Nota_de_Entrega", $MenuLanguage->MenuPhrase("107", "MenuText"), $MenuRelativePath . "SalidasList?tipo=TDCNET", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(108, "mci_Factura_de_Venta", $MenuLanguage->MenuPhrase("108", "MenuText"), $MenuRelativePath . "SalidasList?tipo=TDCFCV", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(109, "mci_Ajuste_de_Salida", $MenuLanguage->MenuPhrase("109", "MenuText"), $MenuRelativePath . "SalidasList?tipo=TDCASA", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(423, "mci_--", $MenuLanguage->MenuPhrase("423", "MenuText"), $MenuRelativePath . "javascript:void(0);", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(329, "mi_view_bultos", $MenuLanguage->MenuPhrase("329", "MenuText"), $MenuRelativePath . "ViewBultosList", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}view_bultos'), false, false, "", "", false);
$sideMenu->addMenuItem(726, "mi_ActualizarExiste", $MenuLanguage->MenuPhrase("726", "MenuText"), $MenuRelativePath . "ActualizarExiste", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}ActualizarExiste.php'), false, false, "", "", false);
$sideMenu->addMenuItem(727, "mi_ajustar_existencia_lotes", $MenuLanguage->MenuPhrase("727", "MenuText"), $MenuRelativePath . "AjustarExistenciaLotes", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}ajustar_existencia_lotes.php'), false, false, "", "", false);
$sideMenu->addMenuItem(862, "mci_--", $MenuLanguage->MenuPhrase("862", "MenuText"), $MenuRelativePath . "javascript:void(0);", 48, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(728, "mi_compra", $MenuLanguage->MenuPhrase("728", "MenuText"), $MenuRelativePath . "CompraList", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}compra'), false, false, "", "", false);
$sideMenu->addMenuItem(863, "mi_cobros_cliente", $MenuLanguage->MenuPhrase("863", "MenuText"), $MenuRelativePath . "CobrosClienteList", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}cobros_cliente'), false, false, "", "", false);
$sideMenu->addMenuItem(930, "mi_nota_de_entrega_buscar", $MenuLanguage->MenuPhrase("930", "MenuText"), $MenuRelativePath . "NotaDeEntregaBuscar", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}nota_de_entrega_buscar.php'), false, false, "", "", false);
$sideMenu->addMenuItem(933, "mi_reimprimir_factura", $MenuLanguage->MenuPhrase("933", "MenuText"), $MenuRelativePath . "ReimprimirFactura", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}reimprimir_factura.php'), false, false, "", "", false);
$sideMenu->addMenuItem(926, "mi_devoluciones", $MenuLanguage->MenuPhrase("926", "MenuText"), $MenuRelativePath . "Devoluciones", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}devoluciones.php'), false, false, "", "", false);
$sideMenu->addMenuItem(864, "mi_pagos_proveedor", $MenuLanguage->MenuPhrase("864", "MenuText"), $MenuRelativePath . "PagosProveedorList", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}pagos_proveedor'), false, false, "", "", false);
$sideMenu->addMenuItem(918, "mi_cont_lotes_pagos", $MenuLanguage->MenuPhrase("918", "MenuText"), $MenuRelativePath . "ContLotesPagosList", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}cont_lotes_pagos'), false, false, "", "", false);
$sideMenu->addMenuItem(914, "mi_cont_lotes", $MenuLanguage->MenuPhrase("914", "MenuText"), $MenuRelativePath . "ContLotesList", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}cont_lotes'), false, false, "", "", false);
$sideMenu->addMenuItem(916, "mi_exportar_data", $MenuLanguage->MenuPhrase("916", "MenuText"), $MenuRelativePath . "ExportarData", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}exportar_data.php'), false, false, "", "", false);
$sideMenu->addMenuItem(937, "mi_sincronizar", $MenuLanguage->MenuPhrase("937", "MenuText"), $MenuRelativePath . "Sincronizar", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}sincronizar.php'), false, false, "", "", false);
$sideMenu->addMenuItem(938, "mi_abono", $MenuLanguage->MenuPhrase("938", "MenuText"), $MenuRelativePath . "AbonoList", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}abono'), false, false, "", "", false);
$sideMenu->addMenuItem(941, "mi_abono2", $MenuLanguage->MenuPhrase("941", "MenuText"), $MenuRelativePath . "Abono2List", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}abono2'), false, false, "", "", false);
$sideMenu->addMenuItem(925, "mi_view_saldos", $MenuLanguage->MenuPhrase("925", "MenuText"), $MenuRelativePath . "ViewSaldosList", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}view_saldos'), false, false, "", "", false);
$sideMenu->addMenuItem(943, "mi_view_saldos2", $MenuLanguage->MenuPhrase("943", "MenuText"), $MenuRelativePath . "ViewSaldos2List", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}view_saldos2'), false, false, "", "", false);
$sideMenu->addMenuItem(944, "mi_puntos", $MenuLanguage->MenuPhrase("944", "MenuText"), $MenuRelativePath . "PuntosList", 48, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}puntos'), false, false, "", "", false);
$sideMenu->addMenuItem(328, "mi_pedido_online", $MenuLanguage->MenuPhrase("328", "MenuText"), $MenuRelativePath . "PedidoOnlineList", -1, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}pedido_online'), false, false, "", "", false);
$sideMenu->addMenuItem(587, "mci_Contabilidad", $MenuLanguage->MenuPhrase("587", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(715, "mci_Periodos", $MenuLanguage->MenuPhrase("715", "MenuText"), "", 587, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(592, "mi_cont_periodo_contable", $MenuLanguage->MenuPhrase("592", "MenuText"), $MenuRelativePath . "ContPeriodoContableList", 715, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}cont_periodo_contable'), false, false, "", "", false);
$sideMenu->addMenuItem(591, "mi_cont_mes_contable", $MenuLanguage->MenuPhrase("591", "MenuText"), $MenuRelativePath . "ContMesContableList", 715, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}cont_mes_contable'), false, false, "", "", false);
$sideMenu->addMenuItem(730, "mi_cont_reglas_hr", $MenuLanguage->MenuPhrase("730", "MenuText"), $MenuRelativePath . "ContReglasHrList", 715, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}cont_reglas_hr'), false, false, "", "", false);
$sideMenu->addMenuItem(469, "mi_cont_plancta", $MenuLanguage->MenuPhrase("469", "MenuText"), $MenuRelativePath . "ContPlanctaList", 587, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}cont_plancta'), false, false, "", "", false);
$sideMenu->addMenuItem(589, "mi_cont_comprobante", $MenuLanguage->MenuPhrase("589", "MenuText"), $MenuRelativePath . "ContComprobanteList", 587, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}cont_comprobante'), false, false, "", "", false);
$sideMenu->addMenuItem(716, "mi_libro_diario", $MenuLanguage->MenuPhrase("716", "MenuText"), $MenuRelativePath . "LibroDiario", 587, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}libro_diario.php'), false, false, "", "", false);
$sideMenu->addMenuItem(717, "mi_libro_mayor", $MenuLanguage->MenuPhrase("717", "MenuText"), $MenuRelativePath . "LibroMayor", 587, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}libro_mayor.php'), false, false, "", "", false);
$sideMenu->addMenuItem(718, "mi_balance_general", $MenuLanguage->MenuPhrase("718", "MenuText"), $MenuRelativePath . "BalanceGeneral", 587, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}balance_general.php'), false, false, "", "", false);
$sideMenu->addMenuItem(719, "mi_balance_de_comprobacion", $MenuLanguage->MenuPhrase("719", "MenuText"), $MenuRelativePath . "BalanceDeComprobacion", 587, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}balance_de_comprobacion.php'), false, false, "", "", false);
$sideMenu->addMenuItem(720, "mi_estado_resultados", $MenuLanguage->MenuPhrase("720", "MenuText"), $MenuRelativePath . "EstadoResultados", 587, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}estado_resultados.php'), false, false, "", "", false);
$sideMenu->addMenuItem(304, "mci_Reportes", $MenuLanguage->MenuPhrase("304", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(208, "mi_main_report", $MenuLanguage->MenuPhrase("208", "MenuText"), $MenuRelativePath . "MainReport", 304, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}main_report.php'), false, false, "", "", false);
$sideMenu->addMenuItem(325, "mci_Alertas", $MenuLanguage->MenuPhrase("325", "MenuText"), "", 304, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(217, "mi_view_facturas_vencidas", $MenuLanguage->MenuPhrase("217", "MenuText"), $MenuRelativePath . "ViewFacturasVencidasList", 325, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}view_facturas_vencidas'), false, false, "", "", false);
$sideMenu->addMenuItem(216, "mi_view_facturas_a_entregar", $MenuLanguage->MenuPhrase("216", "MenuText"), $MenuRelativePath . "ViewFacturasAEntregarList", 325, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}view_facturas_a_entregar'), false, false, "", "", false);
$sideMenu->addMenuItem(724, "mi_view_factura_asesor", $MenuLanguage->MenuPhrase("724", "MenuText"), $MenuRelativePath . "ViewFacturaAsesorList", 304, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}view_factura_asesor'), false, false, "", "", false);
$sideMenu->addMenuItem(17, "mci_Sistema", $MenuLanguage->MenuPhrase("17", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(6, "mi_compania", $MenuLanguage->MenuPhrase("6", "MenuText"), $MenuRelativePath . "CompaniaList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}compania'), false, false, "", "", false);
$sideMenu->addMenuItem(8, "mi_parametro", $MenuLanguage->MenuPhrase("8", "MenuText"), $MenuRelativePath . "ParametroList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}parametro'), false, false, "", "", false);
$sideMenu->addMenuItem(11, "mi_tabla", $MenuLanguage->MenuPhrase("11", "MenuText"), $MenuRelativePath . "TablaList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}tabla'), false, false, "", "", false);
$sideMenu->addMenuItem(54, "mi_almacen", $MenuLanguage->MenuPhrase("54", "MenuText"), $MenuRelativePath . "AlmacenList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}almacen'), false, false, "", "", false);
$sideMenu->addMenuItem(67, "mi_tipo_documento", $MenuLanguage->MenuPhrase("67", "MenuText"), $MenuRelativePath . "TipoDocumentoList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}tipo_documento'), false, false, "", "", false);
$sideMenu->addMenuItem(55, "mi_unidad_medida", $MenuLanguage->MenuPhrase("55", "MenuText"), $MenuRelativePath . "UnidadMedidaList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}unidad_medida'), false, false, "", "", false);
$sideMenu->addMenuItem(449, "mci_Tarifas", $MenuLanguage->MenuPhrase("449", "MenuText"), "", 17, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(62, "mi_tarifa", $MenuLanguage->MenuPhrase("62", "MenuText"), $MenuRelativePath . "TarifaList", 449, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}tarifa'), false, false, "", "", false);
$sideMenu->addMenuItem(63, "mi_tarifa_articulo", $MenuLanguage->MenuPhrase("63", "MenuText"), $MenuRelativePath . "TarifaArticuloList?cmd=resetall", 449, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}tarifa_articulo'), false, false, "", "", false);
$sideMenu->addMenuItem(466, "mi_subir_por_desc_articulo", $MenuLanguage->MenuPhrase("466", "MenuText"), $MenuRelativePath . "SubirPorDescArticulo", 449, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}subir_por_desc_articulo.php'), false, false, "", "", false);
$sideMenu->addMenuItem(118, "mi_alicuota", $MenuLanguage->MenuPhrase("118", "MenuText"), $MenuRelativePath . "AlicuotaList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}alicuota'), false, false, "", "", false);
$sideMenu->addMenuItem(215, "mi_tasa_usd", $MenuLanguage->MenuPhrase("215", "MenuText"), $MenuRelativePath . "TasaUsdList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}tasa_usd'), false, false, "", "", false);
$sideMenu->addMenuItem(725, "mi_notificaciones", $MenuLanguage->MenuPhrase("725", "MenuText"), $MenuRelativePath . "NotificacionesList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}notificaciones'), false, false, "", "", false);
$sideMenu->addMenuItem(923, "mi_tabla_retenciones", $MenuLanguage->MenuPhrase("923", "MenuText"), $MenuRelativePath . "TablaRetencionesList", 17, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}tabla_retenciones'), false, false, "", "", false);
$sideMenu->addMenuItem(18, "mci_Seguridad", $MenuLanguage->MenuPhrase("18", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(15, "mi_audittrail", $MenuLanguage->MenuPhrase("15", "MenuText"), $MenuRelativePath . "AudittrailList", 18, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}audittrail'), false, false, "", "", false);
$sideMenu->addMenuItem(14, "mi_userlevels", $MenuLanguage->MenuPhrase("14", "MenuText"), $MenuRelativePath . "UserlevelsList", 18, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}userlevels'), false, false, "", "", false);
$sideMenu->addMenuItem(12, "mi_usuario", $MenuLanguage->MenuPhrase("12", "MenuText"), $MenuRelativePath . "UsuarioList", 18, "", AllowListMenu('{3E26D9A5-1A72-49C3-8E7A-1DDF1CCB6455}usuario'), false, false, "", "", false);
echo $sideMenu->toScript();
