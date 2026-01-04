<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Lotes de Entrada - Yankuik</title>
    <style>
        /* Configuración de página para PDF */
        @page {
            size: A4 portrait;
            margin: 15mm 12mm 20mm 12mm;
        }
        
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            background-color: #fff;
        }

        :root {
            --primary: #1b4d3e;     /* Verde Bosque */
            --secondary: #4a6741;   /* Verde Olivo */
            --accent: #92400e;      /* Ámbar Oscuro */
            --muted: #64748b;       /* Gris */
            --bg-light: #f8fafc;
            --border: #e2e8f0;
        }

        /* Utilidades */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .text-primary { color: var(--primary); }

        /* Encabezado */
        .header-container {
            width: 100%;
            border-bottom: 3px solid var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        
        .brand-title {
            font-size: 18pt;
            letter-spacing: -0.5px;
            margin: 0;
            color: var(--primary);
        }

        /* Grid de KPIs con Tablas (Más estable en PDF) */
        .kpi-table {
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 5px;
            border-collapse: separate;
        }
        .kpi-card {
            background: var(--bg-light);
            border: 1px solid var(--border);
            padding: 10px;
            text-align: center;
        }
        .kpi-label {
            font-size: 7pt;
            color: var(--muted);
            display: block;
            margin-bottom: 4px;
        }
        .kpi-value {
            font-size: 12pt;
            font-weight: bold;
            color: var(--primary);
        }

        /* Estilos de Tabla Principal */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .main-table th {
            background-color: var(--primary);
            color: #ffffff;
            font-size: 7.5pt;
            padding: 8px 5px;
            text-transform: uppercase;
        }
        .main-table td {
            padding: 7px 5px;
            border-bottom: 1px solid var(--border);
        }
        .main-table tr:nth-child(even) td {
            background-color: #fafafa;
        }

        /* Badges */
        .badge {
            padding: 3px 7px;
            border-radius: 4px;
            font-size: 6.5pt;
            font-weight: bold;
        }
        .badge-completado { background-color: #dcfce7; color: #166534; }
        .badge-pendiente { background-color: #fef9c3; color: #854d0e; }
        .badge-cancelado { background-color: #fee2e2; color: #991b1b; }

        /* Secciones de Resumen Inferior */
        .section-header {
            background: #f1f5f9;
            border-left: 4px solid var(--accent);
            padding: 6px 12px;
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 10px;
        }

        .summary-wrapper {
            width: 100%;
        }
        .summary-column {
            width: 49%;
            display: inline-block;
            vertical-align: top;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 7.5pt;
            color: var(--muted);
            border-top: 1px solid var(--border);
            padding-top: 8px;
        }
    </style>
</head>
<body>

<div class="header-container">
    <table style="width: 100%;">
        <tr>
            <td>
                <h1 class="brand-title">YANKUIK SENOJTOKALIS</h1>
                <div style="color: var(--secondary); font-weight: bold; font-size: 10pt;">
                    Reporte Consolidado de Lotes de Entrada
                </div>
                 <div class="header-cell header-right" style="text-align:right;">
            <?php if (!empty($companyLogoRight)): ?>
                <img src="<?= $companyLogoRight ?>">
            <?php endif; ?>
            <div style="font-size:12px;margin-top:6px;">Folio</div>
            <div class="folio-box"><?= esc($batch['folio_salida'] ?? '----') ?></div>
        </div>
            </td>
            <td class="text-right" style="color: var(--muted); font-size: 8pt;">
                <strong>Fecha Emisión:</strong> <?= date('d/m/Y H:i') ?><br>
                Cuetzálan del Progreso, Puebla
            </td>
        </tr>
    </table>
</div>

<?php if (empty($entradas)): ?>
    <div style="padding: 30px; border: 2px dashed #cbd5e1; text-align: center; color: var(--accent); border-radius: 8px;">
        <p style="font-size: 11pt; font-weight: bold;">No se encontraron registros</p>
        <p>No hay datos disponibles para el periodo o filtros seleccionados.</p>
    </div>
<?php else: 
    // Procesamiento de datos
    $totalPeso = 0; $totalCosto = 0; $totalesCat = [];
    foreach($entradas as $e) {
        $p = (float)($e['peso_bruto_kg'] ?? 0);
        $c = (float)($e['costo_total'] ?? 0);
        $totalPeso += $p; 
        $totalCosto += $c;
        
        $cat = $e['tipo_entrada'] ?? 'GENERAL';
        $tipo = $e['tipo_pimienta'] ?? 'N/A';
        $totalesCat[$cat][$tipo]['p'] = ($totalesCat[$cat][$tipo]['p'] ?? 0) + $p;
        $totalesCat[$cat][$tipo]['c'] = ($totalesCat[$cat][$tipo]['c'] ?? 0) + $c;
    }
?>

<table class="kpi-table">
    <tr>
        <td class="kpi-card" style="border-left: 4px solid var(--primary);">
            <span class="kpi-label uppercase">Volumen Acumulado</span>
            <span class="kpi-value"><?= number_format($totalPeso, 2) ?> <small>kg</small></span>
        </td>
        <td class="kpi-card" style="border-left: 4px solid var(--accent);">
            <span class="kpi-label uppercase">Inversión Total</span>
            <span class="kpi-value">$<?= number_format($totalCosto, 2) ?></span>
        </td>
        <td class="kpi-card" style="border-left: 4px solid var(--secondary);">
            <span class="kpi-label uppercase">Total de Lotes</span>
            <span class="kpi-value"><?= count($entradas) ?></span>
        </td>
    </tr>
</table>

<table class="main-table">
    <thead>
        <tr>
            <th style="width: 10%;">Folio</th>
            <th style="width: 25%;">Centro / Origen</th>
            <th style="width: 15%;">Pimienta</th>
            <th style="width: 20%;">Productor</th>
            <th class="text-right" style="width: 10%;">Peso (kg)</th>
            <th class="text-right" style="width: 10%;">Total ($)</th>
            <th class="text-center" style="width: 10%;">Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($entradas as $e): 
            $st = strtolower($e['estado'] ?? 'pendiente');
        ?>
        <tr>
            <td class="text-bold text-primary">#<?= esc($e['folio']) ?></td>
            <td>
                <div class="text-bold"><?= esc($e['centro'] ?? 'N/A') ?></div>
                <div style="font-size: 7pt; color: var(--muted);"><?= esc($e['tipo_entrada'] ?? '-') ?></div>
            </td>
            <td><?= esc($e['tipo_pimienta'] ?? '-') ?></td>
            <td style="font-size: 7.5pt;"><?= esc($e['proveedor'] ?? 'S/N') ?></td>
            <td class="text-right text-bold"><?= number_format($e['peso_bruto_kg'], 2) ?></td>
            <td class="text-right text-bold" style="color: var(--primary);">$<?= number_format($e['costo_total'], 2) ?></td>
            <td class="text-center">
                <span class="badge badge-<?= $st ?>"><?= strtoupper($st) ?></span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="section-header uppercase">Desglose por Clasificación y Variedad</div>

<div class="summary-wrapper">
    <?php 
    $count = 0;
    foreach($totalesCat as $cat => $variedades): 
        // Abrir columna nueva cada 2 tablas o manejar con inline-block
    ?>
    <div class="summary-column" style="<?= ($count % 2 != 0) ? 'margin-left: 10px;' : '' ?>">
        <table style="width: 100%; border: 1px solid var(--border); margin-bottom: 10px;">
            <thead>
                <tr>
                    <th colspan="3" style="background: var(--secondary); font-size: 8pt; text-align: center; padding: 4px;">
                        <?= esc($cat) ?>
                    </th>
                </tr>
                <tr style="background: #f1f5f9;">
                    <td class="text-bold" style="font-size: 6.5pt; padding: 4px;">VARIEDAD</td>
                    <td class="text-right text-bold" style="font-size: 6.5pt; padding: 4px;">PESO</td>
                    <td class="text-right text-bold" style="font-size: 6.5pt; padding: 4px;">COSTO</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($variedades as $v => $vls): ?>
                <tr>
                    <td style="padding: 4px; border-bottom: 0.5px solid var(--border);"><?= esc($v) ?></td>
                    <td class="text-right" style="padding: 4px; border-bottom: 0.5px solid var(--border);"><?= number_format($vls['p'], 2) ?></td>
                    <td class="text-right text-bold" style="padding: 4px; border-bottom: 0.5px solid var(--border);">$<?= number_format($vls['c'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php 
        $count++;
    endforeach; 
    ?>
</div>

<?php endif; ?>

<div class="footer">
    <strong>YankuiK Senojtokalis S.C. de R.L. de C.V.</strong><br>
    Sistema de Gestión de Acopio | Control Interno
</div>

<script type="text/php">
    if (isset($pdf)) {
        $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
        $font = $fontMetrics->get_font("dejavu sans", "normal");
        $size = 7;
        $color = array(0.4, 0.4, 0.4);
        $y = $pdf->get_height() - 35;
        $x = $pdf->get_width() - 90;
        $pdf->page_text($x, $y, $text, $font, $size, $color);
    }
</script>

</body>
</html>