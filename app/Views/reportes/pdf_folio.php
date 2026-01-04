<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reporte de Lotes de Entrada - Folio <?= esc($folio ?? 'N/A') ?></title>
<style>
    /* Configuración de Página */
    @page { margin: 1cm; size: A4 landscape; }
    
    * { box-sizing: border-box; -webkit-print-color-adjust: exact; }
    
    :root {
        --primary-green: #2d5a27;   /* Verde Bosque */
        --soft-green: #f1f7f0;      /* Fondo verde muy claro */
        --accent-orange: #ea580c;   /* Naranja fuerte */
        --soft-orange: #fff7ed;     /* Fondo naranja claro */
        --dark-text: #1a2e1a;
        --border-color: #d1d5db;
    }

    body {
        font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
        font-size: 8.5pt;
        margin: 0;
        background: #ffffff;
        color: var(--dark-text);
        line-height: 1.2;
    }

    .container { width: 100%; padding: 0; }

    /* Encabezado */
    header {
        display: table;
        width: 100%;
        border-bottom: 4px solid var(--primary-green);
        padding-bottom: 12px;
        margin-bottom: 20px;
    }
    .header-left { display: table-cell; vertical-align: middle; }
    .header-right { display: table-cell; text-align: right; vertical-align: middle; width: 120px; }
    
    header h1 {
        margin: 0;
        font-size: 17pt;
        color: var(--primary-green);
        font-weight: 800;
        text-transform: uppercase;
    }
    header p { margin: 3px 0 0; font-size: 9pt; color: #666; font-weight: 500; }
    .logo-img { max-height: 65px; width: auto; }

    /* Folio y Títulos */
    .folio-banner {
        background: var(--soft-orange);
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        border-left: 5px solid var(--accent-orange);
    }
    .folio-banner span { color: var(--accent-orange); font-size: 8pt; text-transform: uppercase; font-weight: bold; }
    .folio-banner strong { font-size: 13pt; color: var(--dark-text); margin-left: 10px; }

    .section-title {
        background: var(--primary-green);
        color: #ffffff;
        padding: 6px 15px;
        margin: 22px 0 10px 0;
        font-size: 10pt;
        font-weight: bold;
        border-radius: 4px;
        letter-spacing: 0.5px;
    }

    /* Tablas */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        border: 1px solid var(--border-color);
    }
    th {
        background: #f8fafc;
        color: var(--primary-green);
        padding: 10px 5px;
        font-size: 7.5pt;
        text-transform: uppercase;
        border-bottom: 2px solid var(--primary-green);
        border-right: 1px solid var(--border-color);
    }
    td {
        padding: 7px 5px;
        border: 1px solid var(--border-color);
        text-align: center;
    }
    tr:nth-child(even) td { background-color: var(--soft-green); }
    
    /* Clases de Utilidad */
    .text-left { text-align: left; padding-left: 10px; }
    .text-right { text-align: right; padding-right: 10px; }
    .font-bold { font-weight: bold; }
    .price-text { color: var(--accent-orange); font-weight: 700; }

    /* Totales por Grupo */
    .total-row td {
        background: #e2e8f0 !important;
        font-weight: bold;
        color: var(--primary-green);
        border-top: 2px solid var(--primary-green);
    }

    /* Caja de Total General */
    .total-general-container {
        margin-top: 25px;
        text-align: right;
    }
    .total-general-card {
        display: inline-block;
        background: var(--primary-green);
        color: white;
        padding: 15px 30px;
        border-radius: 8px;
        border-bottom: 5px solid var(--accent-orange);
    }
    .total-label { font-size: 9pt; text-transform: uppercase; opacity: 0.9; display: block; margin-bottom: 5px; }
    .total-amount { font-size: 20pt; font-weight: 800; color: #fff; }

    /* Badges */
    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 7pt;
        font-weight: bold;
        text-transform: uppercase;
    }
    .status-completado { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-pendiente { background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa; }

    .footer {
        margin-top: 40px;
        text-align: center;
        font-size: 8pt;
        color: #666;
        border-top: 1px solid var(--primary-green);
        padding-top: 12px;
    }
</style>
</head>
<body>
<div class="container">

<header>
    <div class="header-left">
        <h1>YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V.</h1>
        <p>Reporte Consolidado de Lotes de Entrada | Generado: <?= esc($fecha ?? date('d/m/Y H:i')) ?></p>
    </div>
    <div class="header-right">
        <?php if (!empty($logo_base64)): ?>
            <img src="<?= esc($logo_base64) ?>" class="logo-img" alt="Logo">
        <?php endif; ?>
    </div>
</header>

<div class="folio-banner">
    <span>Referencia de Documento</span>
    <strong>FOLIO GENERAL: <?= esc($folio ?? 'N/A') ?></strong>
</div>

<?php if (empty($lotesAgrupados)): ?>
    <div style="padding: 30px; background: var(--soft-orange); color: var(--accent-orange); border: 2px dashed var(--accent-orange); border-radius: 8px; text-align: center;">
        <strong style="font-size: 11pt;">No se han detectado registros de lotes para este folio.</strong>
    </div>
<?php else: ?>
    <?php $contador = 0; $totalGeneralPagar = 0; ?>

    <?php foreach ($lotesAgrupados as $tipo => $grupo): ?>
        <h2 class="section-title">Pimienta: <?= esc($tipo) ?></h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th style="width: 70px;">Folio</th>
                    <th style="width: 90px;">Centro</th>
                    <th style="width: 80px;">Usuario</th>
                    <th style="width: 75px;">Fecha</th>
                    <th>Productor / Proveedor</th>
                    <th style="width: 75px;">Peso (kg)</th>
                    <th style="width: 75px;">Precio</th>
                    <th style="width: 85px;">Costo Total</th>
                    <th>Observaciones</th>
                    <th style="width: 85px;">Estatus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grupo['lotes'] as $lote): $contador++; ?>
                <tr>
                    <td><?= $contador ?></td>
                    <td class="font-bold" style="color: var(--primary-green);"><?= esc($lote['folio'] ?? 'F-' . $lote['id']) ?></td>
                    <td><?= esc($lote['centro'] ?? '-') ?></td>
                    <td><?= esc($lote['usuario'] ?? '-') ?></td>
                    <td><?= !empty($lote['fecha_entrada']) ? date('d/m/Y', strtotime($lote['fecha_entrada'])) : '-' ?></td>
                    <td class="text-left font-bold"><?= esc($lote['proveedor'] ?? '-') ?></td>
                    <td class="text-right"><?= number_format($lote['peso'],2) ?></td>
                    <td class="text-right">$<?= number_format($lote['precio'],2) ?></td>
                    <td class="text-right price-text">$<?= number_format($lote['costo_total'],2) ?></td>
                    <td class="text-left" style="font-size: 7.5pt; color: #4b5563; font-style: italic;">
                        <?= esc($lote['observaciones'] ?? '-') ?>
                    </td>
                    <td>
                        <span class="badge <?= ($lote['estado'] ?? '') === 'completado' ? 'status-completado' : 'status-pendiente' ?>">
                            <?= esc($lote['estado'] ?? 'pendiente') ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="6" class="text-right">SUBTOTAL <?= esc($tipo) ?>:</td>
                    <td class="text-right"><?= number_format($grupo['subtotal_peso'],2) ?> kg</td>
                    <td></td>
                    <td class="text-right">$<?= number_format($grupo['subtotal_costo'],2) ?></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
        <?php $totalGeneralPagar += $grupo['subtotal_costo']; ?>
    <?php endforeach; ?>

    <div class="total-general-container">
        <div class="total-general-card">
            <span class="total-label">Liquidación Total General</span>
            <span class="total-amount">$<?= number_format($totalGeneralPagar,2) ?></span>
        </div>
    </div>
<?php endif; ?>

<div class="footer">
    <strong>YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V.</strong><br>
    Sistema de Control de Trazabilidad • Cuetzálan del Progreso, Puebla • <?= date('Y') ?>
</div>

</div>
</body>
</html>