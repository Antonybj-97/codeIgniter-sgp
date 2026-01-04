<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    /* Configuración para PDF */
    @page { margin: 1cm 1.5cm; }
    
    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        font-size: 8pt;
        color: #333;
        line-height: 1.3;
        margin: 0;
    }

    /* Encabezado */
    .header-table {
        width: 100%;
        border-bottom: 2px solid #1a2a3a;
        margin-bottom: 20px;
    }
    .header-logo { width: 60px; vertical-align: middle; border:none; }
    .header-logo img { width: 60px; height: auto; }
    .header-title { text-align: right; vertical-align: middle; border:none; }
    .header-title h1 { margin: 0; font-size: 14pt; color: #1a2a3a; text-transform: uppercase; }
    .header-title p { margin: 0; color: #7f8c8d; font-size: 8pt; }

    /* Estilo de Tablas */
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    th {
        background-color: #f2f4f6;
        color: #2c3e50;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 7pt;
        padding: 8px 5px;
        border-bottom: 1px solid #bdc3c7;
    }
    td { padding: 6px 5px; border-bottom: 0.5px solid #eee; }
    tbody tr:nth-child(even) { background-color: #fafafa; }

    /* Indicadores de Tipo */
    .tipo-cell { border-left: 4px solid #ddd; padding-left: 8px; font-weight: bold; }
    .Desgranado { border-left-color: #3498db; }
    .Secado { border-left-color: #e67e22; }
    .Soplado { border-left-color: #9b59b6; }
    .Empaque { border-left-color: #27ae60; }

    /* Badges de Estado */
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 6.5pt;
        font-weight: bold;
        color: white;
        text-align: center;
        min-width: 50px;
    }
    .Completado { background-color: #27ae60; }
    .Iniciado   { background-color: #2980b9; }
    .Pendiente  { background-color: #f1c40f; color: #333; }
    .Otros      { background-color: #95a5a6; }

    /* Totales de Tabla */
    .total-row { background-color: #1a2a3a !important; color: #ffffff; font-weight: bold; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }

    /* Contenedor de Liquidación Final */
    .total-general-container {
        margin-top: 30px;
        text-align: right;
        width: 100%;
    }
    .total-general-card {
        display: inline-block;
        background-color: #f8f9fa;
        border: 2px solid #1a2a3a;
        border-radius: 8px;
        padding: 15px 25px;
        min-width: 250px;
    }
    .total-label {
        display: block;
        font-size: 9pt;
        color: #7f8c8d;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .total-amount {
        display: block;
        font-size: 18pt;
        font-weight: bold;
        color: #1a2a3a;
    }

    /* Footer */
    .footer {
        margin-top: 40px;
        text-align: center;
        font-size: 7.5pt;
        color: #7f8c8d;
        border-top: 1px solid #eee;
        padding-top: 10px;
    }
</style>
</head>
<body>

<table class="header-table">
    <tr>
        <td class="header-logo">
            <?php if (!empty($logo_base64)): ?>
                <img src="<?= $logo_base64 ?>" alt="Logo">
            <?php endif; ?>
        </td>
        <td class="header-title">
            <h1>Reporte General de Procesos</h1>
            <p>Generado el: <strong><?= date('d/m/Y H:i') ?></strong></p>
            <p>Control de Inventario y Calidad - Pimienta</p>
        </td>
    </tr>
</table>

<h2>1. Resumen de Operaciones</h2>
<table>
    <thead>
        <tr>
            <th>Tipo de Proceso</th>
            <th class="text-center">Cant. Lotes</th>
            <th class="text-right">Total Peso Bruto</th>
            <th class="text-right">Total Peso Estimado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resumen as $tipo => $datos): ?>
        <tr>
            <td class="tipo-cell <?= htmlspecialchars($tipo) ?>"><?= htmlspecialchars($tipo) ?></td>
            <td class="text-center"><?= $datos['cantidad'] ?></td>
            <td class="text-right"><?= number_format($datos['peso_bruto'], 2) ?> kg</td>
            <td class="text-right"><?= number_format($datos['peso_estimado'], 2) ?> kg</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>2. Detalle Cronológico</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Lote</th>
            <th>Proceso / Proveedor</th>
            <th class="text-right">Bruto (kg)</th>
            <th class="text-right">Estimado (kg)</th>
            <th class="text-right">Final (kg)</th>
            <th class="text-center">Estado</th>
            <th class="text-right">Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php $totalGeneralPagar = 0; ?>
        <?php foreach ($procesos as $p): 
            $tipo = $p['tipo_proceso'] ?? 'Otros';
            $estado = $p['estado_proceso'] ?? 'Otros';
            $claseEstado = ($estado === 'Finalizado' || $estado === 'Completado') ? 'Completado' : $estado;
            
            // Sumar al total general (ajusta la lógica de negocio si es necesario)
            if(isset($p['subtotal_costo'])) { $totalGeneralPagar += $p['subtotal_costo']; }
        ?>
        <tr>
            <td style="color:#7f8c8d;">#<?= $p['id'] ?></td>
            <td><strong><?= $p['lote_entrada_id'] ?></strong></td>
            <td class="tipo-cell <?= htmlspecialchars($tipo) ?>">
                <span style="font-size: 7.5pt;"><?= htmlspecialchars($tipo) ?></span><br>
                <small style="color:#7f8c8d;"><?= htmlspecialchars($p['proveedor'] ?? 'S/P') ?></small>
            </td>
            <td class="text-right"><?= number_format($p['peso_bruto_kg'] ?? 0, 2) ?></td>
            <td class="text-right"><?= number_format($p['peso_estimado_kg'] ?? 0, 2) ?></td>
            <td class="text-right" style="font-weight:bold;"><?= number_format($p['peso_final_kg'] ?? 0, 2) ?></td>
            <td class="text-center">
                <span class="badge <?= htmlspecialchars($claseEstado) ?>"><?= $estado ?></span>
            </td>
            <td class="text-right" style="font-size: 7pt; color: #7f8c8d;">
                <?= !empty($p['fecha_proceso']) ? date('d/m/y', strtotime($p['fecha_proceso'])) : '-' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="3" class="text-right">TOTALES ACUMULADOS:</td>
            <td class="text-right"><?= number_format($totalBruto ?? 0, 2) ?></td>
            <td class="text-right"><?= number_format($totalEstimado ?? 0, 2) ?></td>
            <td class="text-right"><?= number_format($totalFinal ?? 0, 2) ?></td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
</table>

<div class="total-general-container">
    <div class="total-general-card">
        <span class="total-label">Liquidación Total General</span>
        <span class="total-amount">$<?= number_format($totalGeneralPagar, 2) ?></span>
    </div>
</div>

<div class="footer">
    <strong>YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V.</strong><br>
    Sistema de Control de Trazabilidad • Cuetzálan del Progreso, Puebla • <?= date('Y') ?>
</div>

</body>
</html>