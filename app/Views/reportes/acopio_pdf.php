<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        line-height: 1.25;
        margin: 0;
        padding: 15px;
        color: #333;
    }
    .header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 2px solid #2c5f2d;
        padding-bottom: 10px;
    }
    .header h1 {
        color: #2c5f2d;
        margin: 0 0 3px 0;
        font-size: 18px;
    }
    .header h2 {
        margin: 0;
        font-size: 14px;
        color: #333;
    }
    .section {
        margin-bottom: 14px;
        border: 1px solid #ddd;
        border-radius: 3px;
        padding: 10px;
        page-break-inside: avoid;
    }
    .section h3 {
        margin-top: 0;
        color: #2c5f2d;
        font-size: 13px;
        border-bottom: 1px solid #97bc62;
        padding-bottom: 5px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        margin-top: 5px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 4px;
    }
    th {
        background: #f0f5ee;
        font-weight: bold;
    }
    .total-row {
        background: #e9ecef;
        font-weight: bold;
    }
    .text-right { text-align: right; }
    .text-center { text-align: center; }

    .info-table td {
        border: none;
        padding: 2px 0;
    }
    .info-table td:first-child {
        font-weight: bold;
        width: 42%;
    }

    .signature-section {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        text-align: center;
        page-break-inside: avoid;
    }
    .signature-box { width: 30%; }
    .signature-line {
        border-bottom: 1px solid #000;
        margin: 32px 0 3px;
    }
</style>
</head>
<body>

<div class="header">
    <h1>COOPERATIVA MASEUAL XICAULIS S.C.L.</h1>
    <h2>CIERRE DE CUENTA - ACOPIADOR DE PIMIENTA</h2>
    <p><strong>Documento generado el:</strong> <?= date('d/m/Y'); ?></p>
</div>

<div class="section">
    <h3>I. INFORMACIÓN GENERAL</h3>
    <table class="info-table">
        <tr><td>Centro:</td><td><?= esc($centro) ?></td></tr>
        <tr><td>Fecha de Cierre:</td><td><?= esc($fecha) ?></td></tr>
        <tr><td>Acopiador:</td><td><?= esc($acopiador) ?></td></tr>
        <tr><td>Cosecha:</td><td><?= esc($cosecha) ?></td></tr>
    </table>
</div>

<div class="section">
    <h3>II. RESUMEN FINANCIERO</h3>
    <table>
        <tr><td>Dinero entregado por supervisor</td><td class="text-right">$<?= number_format($resumen_financiero['dinero_entregado'],2) ?></td></tr>
        <tr><td>Otros cargos (naylo, basura, etc.)</td><td class="text-right">$<?= number_format($resumen_financiero['otros_cargos'],2) ?></td></tr>
        <tr><td>Dinero comprobado en pimienta</td><td class="text-right">$<?= number_format($resumen_financiero['dinero_comprobado'],2) ?></td></tr>
        <tr class="total-row"><td>Saldo de dinero del acopiador</td><td class="text-right">$<?= number_format($resumen_financiero['saldo_acopiador'],2) ?></td></tr>
    </table>
</div>

<div class="section">
    <h3>III. PIMIENTA ACOPIADA</h3>

    <?php foreach (['con_rama'=>'Pimienta con Rama','verde'=>'Pimienta Verde','seca'=>'Pimienta Seca'] as $tipo=>$label): ?>
        <?php if (!empty($pimienta[$tipo])): ?>
        <div>
            <strong><?= $label ?></strong>
            <table>
                <thead>
                    <tr><th>Precio ($/kg)</th><th>Kilos</th><th>Importe</th></tr>
                </thead>
                <tbody>
                <?php foreach ($pimienta[$tipo] as $fila): ?>
                    <tr>
                        <td class="text-right">$<?= number_format($fila['precio'],2) ?></td>
                        <td class="text-right"><?= number_format($fila['kilos'],1) ?></td>
                        <td class="text-right">$<?= number_format($fila['importe'],2) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="text-right"><?= number_format($totales[$tipo]['kilos'],1) ?></td>
                        <td class="text-right">$<?= number_format($totales[$tipo]['importe'],2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<?php
$k_rama = $totales['con_rama']['kilos'] ?? 0;
$k_verde = $totales['verde']['kilos'] ?? 0;
$k_seca = $totales['seca']['kilos'] ?? 0;

$c_rama = $k_rama * $comisiones['con_rama'];
$c_verde = $k_verde * $comisiones['verde'];
$c_seca = $k_seca * $comisiones['seca'];
$c_beneficio = $k_seca * $comisiones['beneficio'];
$c_rend = $k_verde * $comisiones['rendimiento'];
$c_cierre = ($c_rama + $c_verde + $c_seca) * $comisiones['cierre_temprano'];
?>

<div class="section">
    <h3>IV. COMISIONES APLICADAS</h3>
    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th class="text-right">Kilos</th>
                <th class="text-right">Comisión ($/kg)</th>
                <th class="text-right">Importe ($)</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Pimienta con rama</td><td class="text-right"><?= number_format($k_rama,1) ?></td><td class="text-right">$<?= number_format($comisiones['con_rama'],2) ?></td><td class="text-right">$<?= number_format($c_rama,2) ?></td></tr>
            <tr><td>Pimienta verde</td><td class="text-right"><?= number_format($k_verde,1) ?></td><td class="text-right">$<?= number_format($comisiones['verde'],2) ?></td><td class="text-right">$<?= number_format($c_verde,2) ?></td></tr>
            <tr><td>Pimienta seca</td><td class="text-right"><?= number_format($k_seca,1) ?></td><td class="text-right">$<?= number_format($comisiones['seca'],2) ?></td><td class="text-right">$<?= number_format($c_seca,2) ?></td></tr>
            <tr><td>Beneficio</td><td class="text-right"><?= number_format($k_seca,1) ?></td><td class="text-right">$<?= number_format($comisiones['beneficio'],2) ?></td><td class="text-right">$<?= number_format($c_beneficio,2) ?></td></tr>
            <tr><td>Rendimiento bajo</td><td class="text-right"><?= number_format($k_verde,1) ?></td><td class="text-right">$<?= number_format($comisiones['rendimiento'],2) ?></td><td class="text-right">$<?= number_format($c_rend,2) ?></td></tr>
            <tr><td>Cierre temprano</td><td class="text-right">-</td><td class="text-right">$<?= number_format($comisiones['cierre_temprano'],2) ?></td><td class="text-right">$<?= number_format($c_cierre,2) ?></td></tr>
        </tbody>
    </table>
</div>

<div class="section">
    <h3>V. FIRMAS Y AUTORIZACIONES</h3>
    <div class="signature-section">

        <div class="signature-box">
            <p>Elaboró</p>
            <div class="signature-line"></div>
            <p><strong><?= esc($firmas['elaboro']) ?></strong></p>
            <p><?= esc($firmas['cargo_elaboro']) ?></p>
        </div>

        <div class="signature-box">
            <p>Autorizó</p>
            <div class="signature-line"></div>
            <p><strong><?= esc($firmas['autorizo']) ?></strong></p>
            <p><?= esc($firmas['cargo_autorizo']) ?></p>
        </div>

        <div class="signature-box">
            <p>Recibí conforme</p>
            <div class="signature-line"></div>
            <p><strong><?= esc($firmas['conformidad']) ?></strong></p>
            <p>Acopiador</p>
        </div>

    </div>
</div>

</body>
</html>
