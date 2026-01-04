<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Proceso Masivo - Folio <?= esc($proceso['id']) ?></title>
<style>
    * { box-sizing: border-box; }
    body {
        font-family: "Arial", sans-serif;
        font-size: 10px;
        color: #000;
        margin: 20px;
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        border-bottom: 2px solid #000;
        padding-bottom: 5px;
    }

    header img {
        width: 80px;
        height: auto;
    }

    h2 {
        text-align: center;
        margin: 0;
        font-size: 14px;
        text-transform: uppercase;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        border: 1px solid #444;
        padding: 5px;
        text-align: center;
    }

    th {
        background-color: #eaeaea;
        font-weight: bold;
    }

    .totales {
        font-weight: bold;
        background-color: #f8f8f8;
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 9px;
        border-top: 1px solid #000;
        padding-top: 3px;
    }

    .info {
        margin-top: 5px;
        font-size: 10px;
    }

    .section {
        margin-top: 25px;
    }

    .section h2 {
        text-align: left;
        font-size: 12px;
        margin-bottom: 8px;
        border-bottom: 1px solid #000;
        display: inline-block;
    }
</style>
</head>
<body>

<header>
    <?php if (!empty($logo_base64)): ?>
        <img src="<?= $logo_base64 ?>" alt="Logo">
    <?php endif; ?>
    <div>
        <h2>REPORTE DE PROCESO MASIVO</h2>
        <p><strong>Folio:</strong> <?= esc($proceso['id']) ?><br>
        <strong>Tipo de proceso:</strong> <?= esc($proceso['tipo_proceso']) ?><br>
        <strong>Fecha:</strong> <?= esc($proceso['fecha_proceso']) ?></p>
    </div>
</header>

<section>
    <p><strong>Observaciones:</strong> <?= esc($proceso['observacion_proceso'] ?? 'N/A') ?></p>
</section>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Lote ID</th>
            <th>Proveedor</th>
            <th>Centro de Acopio</th>
            <th>Tipo Pimienta</th>
            <th>Peso Parcial (kg)</th>
            <th>Peso Estimado (kg)</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $totalParcial = 0;
        $totalEstimado = 0;
        foreach ($detalles as $i => $detalle): 
            $totalParcial += $detalle['peso_parcial_kg'];
            $totalEstimado += $detalle['peso_estimado_kg'];
        ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($detalle['lote_entrada_id']) ?></td>
            <td><?= esc($detalle['proveedor'] ?? 'N/D') ?></td>
            <td><?= esc($detalle['centro_nombre'] ?? 'N/D') ?></td>
            <td><?= esc($detalle['tipo_pimienta'] ?? 'N/D') ?></td>
            <td><?= number_format($detalle['peso_parcial_kg'], 2) ?></td>
            <td><?= number_format($detalle['peso_estimado_kg'], 2) ?></td>
            <td><?= esc($detalle['estado'] ?? 'N/D') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="totales">
            <td colspan="5">TOTALES</td>
            <td><?= number_format($totalParcial, 2) ?></td>
            <td><?= number_format($totalEstimado, 2) ?></td>
            <td></td>
        </tr>
    </tfoot>
</table>

<!-- Nueva sección de detalles del lote asociado -->
<section class="section">
    <h2>Detalles del Lote Asociado</h2>
    <table>
        <thead>
            <tr>
                <th>Peso Parcial (kg)</th>
                <th>Fecha</th>
                <th>ID Lote</th>
                <th>Proveedor</th>
                <th>Tipo de Pimienta</th>
                <th>Centro de Acopio</th>
                <th>Peso Bruto (kg)</th>
                <th>Fecha de Entrada</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= number_format($proceso['peso_final_kg'] ?? 0, 2) ?></td>
                <td><?= esc($proceso['fecha_proceso'] ?? '-') ?></td>
                <td><?= esc($lote['id'] ?? 'N/A') ?></td>
                <td><?= esc($lote['proveedor'] ?? 'N/A') ?></td>
                <td><?= esc($lote['tipo_pimienta'] ?? 'Desconocido') ?></td>
                <td><?= esc($lote['centro_nombre'] ?? 'No asignado') ?></td>
                <td><?= number_format($lote['peso_bruto_kg'] ?? 0, 2) ?></td>
                <td><?= esc($lote['fecha_entrada'] ?? '-') ?></td>
            </tr>
        </tbody>
    </table>
</section>

<div class="info">
    <p><strong>Creado el:</strong> <?= esc($proceso['created_at']) ?></p>
    <p><strong>Estado del proceso:</strong> <?= esc($proceso['estado_proceso']) ?></p>
</div>

<footer>
    <p>Reporte generado automáticamente por el sistema — <?= date('d/m/Y H:i') ?></p>
</footer>

</body>
</html>
