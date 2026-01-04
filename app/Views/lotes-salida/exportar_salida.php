<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 8pt;
            color: #333;
            line-height: 1.3;
            margin: 0;
        }

        /* Colores del Sistema */
        .bg-main { background-color: #2d5016; }
        .text-main { color: #2d5016; }
        
        /* Header */
        .header {
            width: 100%;
            border-bottom: 2px solid #2d5016;
            margin-bottom: 15px;
            padding-bottom: 5px;
        }
        .header table { margin-bottom: 0; border: none; }
        .header td { border: none; padding: 0; }
        
        .header-title {
            font-size: 13pt;
            font-weight: bold;
            color: #2d5016;
            text-transform: uppercase;
        }

        /* Resumen Ejecutivo (KPIs) */
        .kpi-container {
            width: 100%;
            margin-bottom: 20px;
            clear: both;
        }
        .kpi-card {
            width: 30%;
            display: inline-block;
            background-color: #f3f7f0;
            border-left: 3px solid #d97706; /* Detalle naranja para resaltar */
            padding: 10px;
            margin-right: 2%;
            vertical-align: top;
        }
        .kpi-label { font-size: 7pt; text-transform: uppercase; color: #666; display: block; margin-bottom: 3px; }
        .kpi-value { font-size: 12pt; font-weight: bold; color: #2d5016; }

        /* Tabla Principal */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #2d5016;
            color: white;
            padding: 8px 4px;
            font-size: 7.5pt;
            text-transform: uppercase;
            border: 0.5px solid #1a300d;
        }
        td {
            padding: 6px 4px;
            border: 0.5px solid #e0e0e0;
            font-size: 8pt;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }

        /* Fila de Totales */
        .total-row td {
            background-color: #eee;
            font-weight: bold;
            border-top: 2px solid #2d5016;
            font-size: 9pt;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 7pt;
            color: #999;
            border-top: 0.5px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

<?php 
    // Cálculos previos
    $totalGral = 0;
    $numRegistros = count($lotes ?? []);
    if (!empty($lotes)) {
        foreach ($lotes as $l) { $totalGral += $l['cantidad']; }
    }
?>

<div class="header">
    <table>
        <tr>
            <td style="width: 65%;">
                <div class="header-title">YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V.</div>
                <div style="font-size: 10pt; color: #555; font-weight: bold;"><?= esc($title) ?></div>
            </td>
            <td style="width: 35%; text-align: right; font-size: 8pt; color: #666;">
                <strong>Fecha Impresión:</strong> <?= date('d/m/Y H:i') ?><br>
                <strong>Usuario:</strong> <?= session()->get('nombre') ?? 'Sistema' ?><br>
                Cuetzálan, Puebla
            </td>
        </tr>
    </table>
</div>

<div class="kpi-container">
    <div class="kpi-card">
        <span class="kpi-label">Total de Registros</span>
        <span class="kpi-value"><?= $numRegistros ?></span>
    </div>
    <div class="kpi-card">
        <span class="kpi-label">Volumen Total</span>
        <span class="kpi-value"><?= number_format($totalGral, 2) ?> <small style="font-size: 8pt;"><?= esc($lotes[0]['unidad'] ?? 'U') ?></small></span>
    </div>
    <div class="kpi-card" style="margin-right: 0; border-left-color: #2d5016;">
        <span class="kpi-label">Estado del Reporte</span>
        <span class="kpi-value" style="font-size: 10pt;">CONSOLIDADO</span>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th width="8%">Folio</th>
            <th width="10%">Fecha</th>
            <th width="24%">Cliente / Destino</th>
            <th width="24%">Producto</th>
            <th width="12%">Certificación</th>
            <th width="12%">Cantidad</th>
            <th width="10%">Unidad</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($lotes)): ?>
            <?php foreach ($lotes as $lote): ?>
                <tr>
                    <td class="text-center font-bold" style="color: #2d5016;"><?= esc($lote['folio_salida']) ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($lote['fecha_embarque'])) ?></td>
                    <td class="text-left"><?= mb_strtoupper(esc($lote['nombre_cliente'])) ?></td>
                    <td class="text-left"><?= esc($lote['producto']) ?></td>
                    <td class="text-center"><?= esc($lote['tipo_producto']) ?></td>
                    <td class="text-right font-bold"><?= number_format($lote['cantidad'], 2) ?></td>
                    <td class="text-center"><?= esc($lote['unidad']) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="5" class="text-right">RESUMEN TOTAL</td>
                <td class="text-right"><?= number_format($totalGral, 2) ?></td>
                <td class="text-center"><?= esc($lotes[0]['unidad'] ?? '') ?></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #999;">
                    No se encontraron movimientos registrados en este periodo.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="footer">
    YankuiK Senojtokalis S.C. de R.L. de C.V. - Sistema de Gestión de Inventarios
    <br>
    Página <script type="text/php">
        if (isset($pdf)) {
            $x = 280; 
            $y = 820; 
            $text = "{PAGE_NUM} de {PAGE_COUNT}";
            $font = $fontMetrics->get_font("dejavu sans", "normal");
            $size = 7;
            $pdf->page_text($x, $y, $text, $font, $size, array(0.5,0.5,0.5));
        }
    </script>
</div>

</body>
</html>