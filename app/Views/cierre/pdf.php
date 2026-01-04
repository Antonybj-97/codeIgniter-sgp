<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Cierre de cuentas - PDF</title>

<style>
  /* ==== CONFIGURACIÓN DE PÁGINA ==== */
  @page { margin: 36pt 36pt 60pt 36pt; }

  body {
    font-family: "DejaVu Sans", sans-serif;
    font-size: 11px;
    color: #333;
    line-height: 1.4;
  }

  .page { width: 100%; }

  /* ==== ENCABEZADO ==== */
  .header { 
    text-align: center; 
    margin-bottom: 15px;
    border-bottom: 2px solid #1f4e2a;
    padding-bottom: 10px;
  }

  .brand {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 8px;
  }

  .brand img {
    width: 72px;
    height: auto;
    object-fit: contain;
  }

  .company .name {
    font-size: 16px;
    font-weight: bold;
    color: #1f4e2a;
    margin-bottom: 2px;
  }

  .company .subtitle {
    font-size: 11px;
    color: #4b4b4b;
  }

  .title {
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    color: #1f4e2a;
    margin-top: 8px;
  }

  .title small {
    display: block;
    font-size: 10px;
    color: #666;
    margin-top: 3px;
  }

  /* ==== SECCIONES ==== */
  .section {
    margin-top: 15px;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #dcdcdc;
    background: #fafafa;
  }

  .section h3 {
    margin: 0 0 8px 0;
    font-size: 13px;
    font-weight: bold;
    color: #1f4e2a;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 4px;
  }

  /* ==== TABLAS ==== */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8px;
    font-size: 10px;
  }

  th {
    background: #e6f3e7;
    border: 1px solid #cccccc;
    padding: 6px 8px;
    text-align: center;
    font-weight: bold;
  }

  td {
    border: 1px solid #cccccc;
    padding: 6px 8px;
    vertical-align: middle;
  }

  tfoot td {
    background: #f3faf3;
    font-weight: bold;
  }

  .align-right { text-align: right; }
  .align-center { text-align: center; }
  .align-left { text-align: left; }

  tr, td, th { page-break-inside: avoid; }

  /* ==== PIE ==== */
  .footer {
    position: fixed;
    left: 0; right: 0; bottom: 16pt;
    text-align: center;
    font-size: 9px;
    color: #777;
    border-top: 1px solid #ddd;
    padding-top: 5px;
  }

  /* ==== FIRMAS ==== */
  .signs td {
    padding-top: 40px;
    text-align: center;
    vertical-align: bottom;
    font-size: 11px;
    border: none !important;
  }

  .signature-line {
    border-top: 1px solid #333;
    width: 80%;
    margin: 0 auto;
    padding-top: 25px;
  }

  /* ==== ESTADOS ==== */
  .no-data {
    text-align: center;
    color: #666;
    font-style: italic;
    padding: 10px;
  }

  .section-title {
    font-weight: bold;
    color: #1f4e2a;
    margin: 10px 0 5px 0;
    font-size: 11px;
  }

  .total-row {
    background: #f8fff8 !important;
    font-weight: bold;
  }

  .page-break {
    page-break-before: always;
  }
</style>
</head>

<body>

<div class="footer">
  Cooperativa Yankuik Senojtokalis S.C. de R.L. de C.V. — Página: <span class="pagenum"></span>
</div>

<div class="page">

<!-- ENCABEZADO -->
<div class="header">
  <div class="brand">
    <img src="<?= base_url('assets/img/logo01.jpg') ?>" alt="Logo">

    <div class="company">
      <div class="name">COOPERATIVA YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V.</div>
      <div class="subtitle">Cierre de cuentas final — Cosecha <?= esc($data['cosecha'] ?? '2025') ?></div>
    </div>
  </div>

  <div class="title">
    CIERRE DE CUENTAS — RESUMEN FINAL
    <small>Documento generado el <?= date('d/m/Y H:i:s') ?></small>
  </div>
</div>

<!-- INFORMACIÓN GENERAL -->
<div class="section">
  <h3>I. Información General</h3>

  <table>
    <tr>
      <td style="font-weight:bold; width:25%">Centro de acopio</td>
      <td style="width:30%"><?= esc($data['centro'] ?? 'No especificado') ?></td>
      <td style="font-weight:bold; width:15%">Fecha</td>
      <td style="width:30%"><?= esc($data['fecha'] ?? date('Y-m-d')) ?></td>
    </tr>
    <tr>
      <td style="font-weight:bold;">Acopiador</td>
      <td><?= esc($data['acopiador'] ?? 'No especificado') ?></td>
      <td style="font-weight:bold;">Cosecha</td>
      <td><?= esc($data['cosecha'] ?? '2025') ?></td>
    </tr>
    <tr>
      <td style="font-weight:bold;">Folio</td>
      <td colspan="3"><?= esc($data['folio'] ?? 'SIN FOLIO') ?></td>
    </tr>
  </table>
</div>

<!-- RESUMEN FINANCIERO -->
<div class="section">
  <h3>II. Resumen Financiero</h3>

  <table>
    <tr>
      <td class="align-left">Dinero entregado por el supervisor</td>
      <td class="align-right">$<?= number_format(floatval($data['dinero_entregado'] ?? 0), 2) ?></td>
    </tr>
    <tr>
      <td class="align-left">Otros cargos (nailo, báscula, etc.)</td>
      <td class="align-right">$<?= number_format(floatval($data['otros_cargos'] ?? 0), 2) ?></td>
    </tr>
    <tr class="total-row">
      <td class="align-left"><strong>Total de dinero con cargo al acopiador</strong></td>
      <td class="align-right"><strong>$<?= number_format((floatval($data['dinero_entregado'] ?? 0) + floatval($data['otros_cargos'] ?? 0)), 2) ?></strong></td>
    </tr>
    <tr>
      <td class="align-left">Dinero comprobado en pimienta</td>
      <td class="align-right">$<?= number_format(floatval($data['dinero_comprobado'] ?? 0), 2) ?></td>
    </tr>
    <tr class="total-row">
      <td class="align-left"><strong>Saldo del Acopiador</strong></td>
      <td class="align-right"><strong>$<?= number_format(floatval($data['saldo_acopiador'] ?? 0), 2) ?></strong></td>
    </tr>
  </table>
</div>

<!-- PRODUCTO Y RENDIMIENTOS -->
<div class="section">
  <h3>III. Resumen de Producto y Rendimientos</h3>

<?php
// Definir tipos de pimienta
$tipos = [
  'con-rama' => 'Pimienta con Rama',
  'verde'    => 'Pimienta Verde', 
  'seca'     => 'Pimienta Seca'
];

// Función helper para procesar arrays de datos
function procesarDatosTabla($precios, $kilos, $importes = []) {
    $registros = [];
    $totalKilos = 0;
    $totalImporte = 0;
    
    if (is_array($precios) && is_array($kilos)) {
        $count = min(count($precios), count($kilos));
        for ($i = 0; $i < $count; $i++) {
            $precio = floatval($precios[$i] ?? 0);
            $kilo = floatval($kilos[$i] ?? 0);
            
            // Solo incluir registros con datos válidos
            if ($precio > 0 || $kilo > 0) {
                $importe = !empty($importes[$i]) ? floatval($importes[$i]) : ($precio * $kilo);
                $registros[] = [
                    'precio' => $precio,
                    'kilos' => $kilo,
                    'importe' => $importe
                ];
                $totalKilos += $kilo;
                $totalImporte += $importe;
            }
        }
    }
    
    return [
        'registros' => $registros,
        'totalKilos' => $totalKilos,
        'totalImporte' => $totalImporte
    ];
}
?>

<?php foreach ($tipos as $id => $titulo): ?>
<?php
    $precios = $data["precio-$id"] ?? [];
    $kilos = $data["kilos-$id"] ?? [];
    $importes = $data["importe-$id"] ?? [];
    
    $datos = procesarDatosTabla($precios, $kilos, $importes);
    $hayDatos = !empty($datos['registros']);
?>

<div class="section-title"><?= $titulo ?></div>

<table>
  <thead>
    <tr>
      <th width="33%">Precio ($)</th>
      <th width="33%">Kilos</th>
      <th width="34%">Importe ($)</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($hayDatos): ?>
        <?php foreach ($datos['registros'] as $registro): ?>
            <tr>
              <td class="align-right">$<?= number_format($registro['precio'], 2) ?></td>
              <td class="align-right"><?= number_format($registro['kilos'], 2) ?></td>
              <td class="align-right">$<?= number_format($registro['importe'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3" class="align-center no-data">No hay registros</td>
        </tr>
    <?php endif; ?>
  </tbody>
  <tfoot>
    <tr class="total-row">
      <td class="align-right">Total:</td>
      <td class="align-right"><?= number_format($datos['totalKilos'], 2) ?></td>
      <td class="align-right">$<?= number_format($datos['totalImporte'], 2) ?></td>
    </tr>
  </tfoot>
</table>

<?php endforeach; ?>

<!-- PIMIENTA ENTREGADA EN ALMACÉN -->
<div class="section-title" style="margin-top: 15px;">Pimienta Entregada en Almacén</div>

<?php
// Procesar datos de almacén
$almacenFechas = $data['almacen_fecha'] ?? [];
$almacenFolios = $data['almacen_folio'] ?? [];
$almacenKilosC = $data['almacen_kilos_c'] ?? [];
$almacenKilosA = $data['almacen_kilos_a'] ?? [];

$almacenRegistros = [];
$totalKilosCentro = 0;
$totalKilosAlmacen = 0;
$totalDiferencia = 0;

if (is_array($almacenFechas) && is_array($almacenKilosC) && is_array($almacenKilosA)) {
    $count = min(count($almacenFechas), count($almacenKilosC), count($almacenKilosA));
    for ($i = 0; $i < $count; $i++) {
        $kilosCentro = floatval($almacenKilosC[$i] ?? 0);
        $kilosAlmacen = floatval($almacenKilosA[$i] ?? 0);
        
        if ($kilosCentro > 0 || $kilosAlmacen > 0) {
            $diferencia = $kilosCentro - $kilosAlmacen;
            $almacenRegistros[] = [
                'fecha' => $almacenFechas[$i] ?? '',
                'folio' => $almacenFolios[$i] ?? '',
                'kilos_centro' => $kilosCentro,
                'kilos_almacen' => $kilosAlmacen,
                'diferencia' => $diferencia
            ];
            $totalKilosCentro += $kilosCentro;
            $totalKilosAlmacen += $kilosAlmacen;
            $totalDiferencia += $diferencia;
        }
    }
}
?>

<table>
  <thead>
    <tr>
      <th>Fecha</th>
      <th>Folio de nota</th>
      <th>Kilos en centro</th>
      <th>Kilos en almacén</th>
      <th>Diferencia</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($almacenRegistros)): ?>
        <?php foreach ($almacenRegistros as $registro): ?>
            <tr>
              <td class="align-center"><?= esc($registro['fecha']) ?></td>
              <td class="align-center"><?= esc($registro['folio']) ?></td>
              <td class="align-right"><?= number_format($registro['kilos_centro'], 2) ?></td>
              <td class="align-right"><?= number_format($registro['kilos_almacen'], 2) ?></td>
              <td class="align-right"><?= number_format($registro['diferencia'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="align-center no-data">No hay entregas registradas</td>
        </tr>
    <?php endif; ?>
  </tbody>
  <tfoot>
    <tr class="total-row">
      <td colspan="2" class="align-right">Totales:</td>
      <td class="align-right"><?= number_format($totalKilosCentro, 2) ?></td>
      <td class="align-right"><?= number_format($totalKilosAlmacen, 2) ?></td>
      <td class="align-right"><?= number_format($totalDiferencia, 2) ?></td>
    </tr>
  </tfoot>
</table>

<!-- RENDIMIENTOS -->
<div style="margin-top: 15px;">
  <table>
    <tr>
      <td width="33%" class="align-left">Rendimiento obtenido en beneficio</td>
      <td width="33%" class="align-left">Rendimiento en centro de acopio</td>
      <td width="34%" class="align-left">Rendimiento general</td>
    </tr>
    <tr>
      <td class="align-right"><?= number_format(floatval($data['rendimiento_beneficio'] ?? 0), 2) ?>%</td>
      <td class="align-right"><?= number_format(floatval($data['rendimiento_centro'] ?? 0), 2) ?>%</td>
      <td class="align-right"><?= number_format(floatval($data['rendimiento_general'] ?? 0), 2) ?>%</td>
    </tr>
  </table>
</div>

</div>

<!-- RESUMEN DE PAGOS Y COMISIONES -->
<div class="page-break"></div>
<div class="section">
  <h3>IV. Producto a pagar y Comisiones</h3>

  <!-- RESUMEN DE PAGOS -->
  <div class="section-title">Resumen de Pagos</div>
  <table>
    <tr>
      <td class="align-left">Importe total a pagar por pimienta</td>
      <td class="align-right">$<?= number_format(floatval($data['importe_total_pimienta'] ?? 0), 2) ?></td>
    </tr>
    <tr>
      <td class="align-left">Total comisiones</td>
      <td class="align-right">$<?= number_format(floatval($data['total_comisiones'] ?? 0), 2) ?></td>
    </tr>
    <tr class="total-row">
      <td class="align-left"><strong>Total a pagar al acopiador</strong></td>
      <td class="align-right"><strong>$<?= number_format(floatval($data['total_a_pagar'] ?? 0), 2) ?></strong></td>
    </tr>
    <tr>
      <td class="align-left">Saldo final</td>
      <td class="align-right">$<?= number_format(floatval($data['saldo_final'] ?? 0), 2) ?></td>
    </tr>
  </table>

  <!-- COMISIONES -->
  <div class="section-title" style="margin-top: 15px;">Comisiones por pagar</div>

  <?php
  $conceptos = $data['comision_concepto'] ?? [];
  $comisionKilos = $data['comision_kilos'] ?? [];
  $comisionTasas = $data['comision_tasa'] ?? [];
  $comisionTipos = $data['comision_tipo'] ?? [];
  $comisionImportes = $data['comision_importe'] ?? [];
  
  $comisionesRegistros = [];
  $totalComisiones = 0;
  
  if (is_array($conceptos)) {
      $count = min(count($conceptos), count($comisionKilos), count($comisionTasas), count($comisionTipos));
      for ($i = 0; $i < $count; $i++) {
          $concepto = trim($conceptos[$i] ?? '');
          $kilos = floatval($comisionKilos[$i] ?? 0);
          $tasa = floatval($comisionTasas[$i] ?? 0);
          $tipo = $comisionTipos[$i] ?? 'por_kilo';
          $importe = floatval($comisionImportes[$i] ?? 0);
          
          if (!empty($concepto) || $kilos > 0 || $tasa > 0) {
              $comisionesRegistros[] = [
                  'concepto' => $concepto,
                  'kilos' => $kilos,
                  'tasa' => $tasa,
                  'tipo' => $tipo,
                  'importe' => $importe
              ];
              $totalComisiones += $importe;
          }
      }
  }
  ?>

  <table>
    <thead>
      <tr>
        <th>Concepto</th>
        <th>Kilos</th>
        <th>Tasa</th>
        <th>Tipo</th>
        <th>Importe</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($comisionesRegistros)): ?>
          <?php foreach ($comisionesRegistros as $comision): ?>
              <tr>
                <td class="align-left"><?= esc($comision['concepto']) ?></td>
                <td class="align-right"><?= number_format($comision['kilos'], 2) ?></td>
                <td class="align-right"><?= number_format($comision['tasa'], 2) ?></td>
                <td class="align-center"><?= esc($comision['tipo']) ?></td>
                <td class="align-right">$<?= number_format($comision['importe'], 2) ?></td>
              </tr>
          <?php endforeach; ?>
      <?php else: ?>
          <tr>
              <td colspan="5" class="align-center no-data">No hay comisiones registradas</td>
          </tr>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="4" class="align-right">Total Comisiones</td>
        <td class="align-right">$<?= number_format($totalComisiones, 2) ?></td>
      </tr>
    </tfoot>
  </table>
</div>

<!-- OBSERVACIONES Y FIRMAS -->
<div class="section">
  <h3>V. Observaciones y Firmas</h3>

  <!-- OBSERVACIONES -->
  <?php if (!empty($data['obs_acopio_verde']) || !empty($data['obs_acopio_seca'])): ?>
  <div class="section-title">Observaciones</div>
  <table>
    <?php if (!empty($data['obs_acopio_verde'])): ?>
    <tr>
      <td class="align-left"><strong>Acopio pimienta verde:</strong> <?= esc($data['obs_acopio_verde']) ?></td>
    </tr>
    <?php endif; ?>
    <?php if (!empty($data['obs_acopio_seca'])): ?>
    <tr>
      <td class="align-left"><strong>Acopio pimienta seca:</strong> <?= esc($data['obs_acopio_seca']) ?></td>
    </tr>
    <?php endif; ?>
  </table>
  <?php endif; ?>

  <!-- FIRMAS -->
  <div class="section-title" style="margin-top: 15px;">Firmas</div>
  <table class="signs">
    <tr>
      <td>
        <div class="signature-line"></div>
        <strong>Elaboró</strong><br>
        <?= esc($data['firmo_elaboro'] ?? '') ?>
      </td>
      <td>
        <div class="signature-line"></div>
        <strong>Autorizó</strong><br>
        <?= esc($data['firmo_autorizo'] ?? '') ?>
      </td>
      <td>
        <div class="signature-line"></div>
        <strong>Acopiador</strong><br>
        <?= esc($data['firmo_acopiador'] ?? '') ?>
      </td>
    </tr>
  </table>
</div>

</div>

<script type="text/php">
  // Script para numeración de páginas (funciona con Dompdf)
  if (isset($pdf)) {
      $font = $fontMetrics->get_font("DejaVu Sans", "normal");
      $size = 9;
      $pageText = "Página " . $PAGE_NUM . " de " . $PAGE_COUNT;
      $y = $pdf->get_height() - 30;
      $x = $pdf->get_width() - 100;
      $pdf->page_text($x, $y, $pageText, $font, $size);
  }
</script>

</body>
</html>