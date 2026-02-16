<?php

namespace PHPMaker2021\mandrake;

// Page object
$ListadoMaster = &$Page;
?>
<?php

// Reemplaza los nombres de reportes con los códigos
$reporte_id = $_GET["id"] ?? '';
$url = "listado_master_buscar.php";
$titulo = $reporte_id;

// Array de mapeo (Cópialo del script anterior)
$reportes = [
    'ims' => [
        'heading' => 'Reportes IMS',
        'items' => [
            'CIMS' => 'EXPORTAR CLIENTES IMS',
            'AIMS' => 'EXPORTAR ARTICULOS IMS',
            'FIMS' => 'EXPORTAR FACTURAS IMS',
        ],
    ],
    'libros' => [
        'heading' => 'Libros y Reportes Financieros',
        'items' => [
            'LBCOMP' => 'LIBRO DE COMPRAS',
            'LBVENT' => 'LIBRO DE VENTAS',
            'FCCVSP' => 'FACTURAS COSTOS VS PRECIO',
            'KARDEX' => 'KARDEX DE INVENTARIO',
        ],
    ],
    'ventas' => [
        'heading' => 'Reportes de Ventas',
        'items' => [
            'VENFAB' => 'VENTAS POR FABRICANTE',
            'VENART' => 'VENTAS POR ARTICULO (NOTAS DE ENTREGA)',
            'VENCAN' => 'CANJES POR PREMIO (NOTAS DE ENTREGA)',
            'VENARC' => 'VENTAS POR ARTICULO (SOLO CANTIDADES)',
            'SALGEN' => 'SALIDAS GENERALES POR FABRICANTE',
            'SALART' => 'SALIDAS GENERALES POR ARTICULO',
            'VENCLI' => 'VENTAS POR CLIENTE',
        ],
    ],
    'consignaciones' => [
        'heading' => 'Consignaciones',
        'items' => [
            'CONCLI' => 'CONSIGNACIONES POR CLIENTE',
            'FACCON' => 'FACTURAS POR CONSIGNACION',
        ],
    ],
    'clientes' => [
        'heading' => 'Reportes de Clientes',
        'items' => [
            'COMPRE' => 'CLIENTES CON COMPRAS RECIENTES',
            'COMSIN' => 'CLIENTES SIN COMPRAS RECIENTES',
        ],
    ],
    'varios' => [
        'heading' => 'Varios',
        'items' => [
            'INVENT' => 'INVENTARIO ENTRE FECHA',
            'DEVOLU' => 'DEVOLUCIONES ENTRE FECHA',
            'DESCON' => 'DESCARGA ENTRADAS A CONSIGNACION',
        ],
    ],
    'cierre' => [
        'heading' => 'Cierres Fiscales',
        'items' => [
            'REPX' => 'REPORTE X',
            'REPZ' => 'REPORTE Z',
        ],
    ],
];

// Función auxiliar para obtener el nombre completo del reporte
function getReportName($reporte_id, $reportes_map) {
    foreach ($reportes_map as $grupo) {
        if (isset($grupo['items'][$reporte_id])) {
            return $grupo['items'][$reporte_id];
        }
    }
    return 'Reporte Desconocido';
}

// Opcional: Obtener el nombre para el título
$titulo = getReportName($reporte_id, $reportes);

?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    /* Estilos generales */
    :root {
        --primary-color: #007bff;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --bg-light: #f4f6f9;
        --border-color: #ced4da;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --transition-speed: 0.3s;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
        color: #333;
    }
    .container {
        padding: 30px;
        margin-top: 20px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: var(--shadow);
    }
    h3 {
        color: #1a237e;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    /* Estilos de botones */
    .btn {
        padding: 10px 20px;
        font-size: 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
    }
    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #0056b3, #007bff);
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Estilos de formulario */
    .form-inline .form-group {
        display: flex;
        align-items: center;
        margin-right: 25px;
        margin-bottom: 15px; /* Added for better spacing on smaller screens */
    }
    .form-group label {
        margin-right: 10px;
        font-weight: 500;
        color: #555;
    }

    .form-control, select {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        height: auto; /* Changed from fixed height */
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
        transition: border-color var(--transition-speed) ease-in-out, box-shadow var(--transition-speed) ease-in-out;
        width: 250px;
    }
    .form-control:focus, select:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>

<div class="container-fluid py-4">
    <button type="button" class="btn btn-primary" id="regresar" name="regresar" onClick="js:window.history.back();">
        <i class="fas fa-arrow-left"></i> Regresar a Reportes
    </button>
    <div class="container mt-4">
        <h3><?php echo "Reporte: " . $titulo; ?></h3>
        <form class="form-inline d-flex flex-wrap align-items-center">
            <div class="form-group">
                <label for="fecha_desde">Rango de Fecha:</label>
                <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
            </div>
            <div class="form-group">
                <label for="fecha_hasta" class="d-none d-sm-block"> - </label>
                <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
            </div>
            
            <?php
            switch ($reporte_id) {
                // The logic for all switch cases remains the same as your previous code
                case 'CIMS':
                case 'AIMS':
                case 'FIMS':
                    echo '<div class="form-group">';
                    echo '<label for="tipo">Tipo:</label>';
                    echo '<select id="tipo" name="tipo">';
                    echo '<option value="">TODOS</option>';
                    $sql = "SELECT id, nombre FROM tarifa WHERE activo = 'S';";
                    $rows = ExecuteRows($sql);
                    foreach ($rows as $row) {
                        echo '<option value="' . htmlspecialchars($row["id"]) . '">' . htmlspecialchars($row["nombre"]) . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    break;
                case 'LBCOMP':
                    echo '<div class="form-group">';
                    echo '<label for="tipo">Tipo:</label>';
                    echo '<select id="tipo" name="tipo">';
                    echo '<option value="">TODOS</option>';
                    echo '</select>';
                    echo '</div>';
                    break;
                case 'LBVENT':
                    echo '<div class="form-group">';
                    echo '<label for="tipo">Tipo:</label>';
                    echo '<select id="tipo" name="tipo">';
                    echo '<option value="">TODOS</option>';
                    echo '<option value="FC">FACTURA</option>';
                    echo '<option value="NC">NOTA DE CREDITO</option>';
                    echo '<option value="ND">NOTA DE DEBITO</option>';
                    echo '</select>';
                    echo '</div>';
                    break;
                case 'VENFAB':
                case 'SALGEN':
                case 'DESCON':
                    echo '<div class="form-group">';
                    echo '<label for="tipo">Tipo:</label>';
                    echo '<select id="tipo" name="tipo">';
                    echo '<option value="">TODOS</option>';
                    $sql = "SELECT id, nombre FROM fabricante ORDER BY nombre;";
                    $rows = ExecuteRows($sql);
                    foreach ($rows as $row) {
                        echo '<option value="' . htmlspecialchars($row["id"]) . '">' . htmlspecialchars($row["nombre"]) . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    break;
                case 'VENART':
                case 'SALART':
                case 'VENARC':
                    echo '<div class="form-group">';
                    echo '<label for="tipo"># Ref:</label>';
                    echo '<input type="text" class="form-control" id="tipo" name="tipo" placeholder="# Ref">';
                    echo '</div>';
                    break;
                case 'COMPRE':
                case 'COMSIN':
                    echo '<div class="form-group">';
                    echo '<label for="tipo">Tipo:</label>';
                    echo '<select id="tipo" name="tipo">';
                    echo '<option value="">TODOS</option>';
                    $sql = "SELECT id, nombre FROM asesor ORDER BY nombre;";
                    $rows = ExecuteRows($sql);
                    foreach ($rows as $row) {
                        echo '<option value="' . htmlspecialchars($row["id"]) . '">' . htmlspecialchars($row["nombre"]) . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    break;
                case 'CONCLI':
                case 'FACCON':
                    echo '<div class="form-group">';
                    echo '<label for="tipo">Tipo:</label>';
                    echo '<select id="tipo" name="tipo">';
                    echo '<option value="">TODOS</option>';
                    $sql = "SELECT DISTINCT a.cliente, b.nombre FROM salidas AS a JOIN cliente AS b ON b.id = a.cliente WHERE a.consignacion = 'S' AND a.tipo_documento IN ('TDCNET', 'TDCFCV') AND a.estatus = 'PROCESADO' ORDER BY b.nombre;";
                    $rows = ExecuteRows($sql);
                    foreach ($rows as $row) {
                        echo '<option value="' . htmlspecialchars($row["cliente"]) . '">' . htmlspecialchars($row["nombre"]) . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    break;
                case 'INVENT':
                case 'DEVOLU':
                    echo '<div class="form-group">';
                    echo '<label for="tipo"># Ref:</label>';
                    echo '<input type="text" class="form-control" id="tipo" name="tipo" placeholder="# Ref">';
                    echo '</div>';
                    break;
                case 'VENCLI':
                    echo '<div class="form-group">';
                    echo '<label for="tipo">Tipo:</label>';
                    echo '<select id="tipo" name="tipo">';
                    echo '<option value="">TODOS</option>';
                    $sql = "SELECT campo_codigo, campo_descripcion FROM tabla WHERE tabla = 'CIUDAD' ORDER BY campo_descripcion;";
                    $rows = ExecuteRows($sql);
                    foreach ($rows as $row) {
                        echo '<option value="' . htmlspecialchars($row["campo_codigo"]) . '">' . htmlspecialchars($row["campo_descripcion"]) . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    break;
                case 'REPX':
                case 'REPZ':
                    echo '<div class="form-group">';
                    echo '<label for="tipo">Tipo:</label>';
                    echo '<select id="tipo" name="tipo">';
                    echo '<option value="1" selected="selected">ENTRE FECHAS</option>';
                    echo '</select>';
                    echo '</div>';
                    break;
            }
            ?>

            <div id="xCliente" class="d-flex flex-wrap align-items-center">
                <?php if ($reporte_id == 'LBVENT'): ?>
                    <div class="form-group">
                        <label for="cliente" class="ms-3">Cliente:</label>
                        <select id="cliente" name="cliente">
                            <option value="">TODOS</option>
                            <?php
                            $sql = "SELECT id, nombre FROM cliente ORDER BY nombre;";
                            $rows = ExecuteRows($sql);
                            foreach ($rows as $row) {
                                echo '<option value="' . htmlspecialchars($row["id"]) . '">' . htmlspecialchars(substr($row["nombre"], 0, 40)) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="asesor">Asesor:</label>
                        <select id="asesor" name="asesor">
                            <option value="">TODOS</option>
                            <?php
                            $sql = "SELECT id, nombre FROM asesor ORDER BY nombre;";
                            $rows = ExecuteRows($sql);
                            foreach ($rows as $row) {
                                echo '<option value="' . htmlspecialchars($row["id"]) . '">' . htmlspecialchars(substr($row["nombre"], 0, 40)) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

            <button type="button" class="btn btn-primary" id="buscar" name="buscar">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>
    </div>
</div>

<div class="container mt-4">
    <div id="result"></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#buscar").click(function() {
            var fecha_desde = $("#fecha_desde").val();
            var fecha_hasta = $("#fecha_hasta").val();
            var tipo = $("#tipo").val();
            var cliente = $("#cliente").val();
            var asesor = $("#asesor").val();

            if (fecha_desde === "" || fecha_hasta === "") {
                alert("Fecha Incorrectas!");
                return false;
            }

            $.ajax({
                url: "<?php echo $url; ?>",
                type: "GET",
                data: {
                    id: '<?php echo $reporte_id; ?>',
                    fecha_desde: fecha_desde,
                    fecha_hasta: fecha_hasta,
                    tipo: tipo,
                    proveedor: 0,
                    cliente: cliente,
                    asesor: asesor
                },
                beforeSend: function() {
                    $("#result").html("<p class='text-center text-secondary'>Cargando... Por favor, espere.</p>");
                }
            }).done(function(data) {
                $("#result").html(data);
            }).fail(function(data) {
                alert("Error: " + data);
            });
        });
    });
</script>

<?= GetDebugMessage() ?>
