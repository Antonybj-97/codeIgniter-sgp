<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Nota de Salida - <?= esc($batch['folio_salida'] ?? 'FOLIO') ?></title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; margin: 0; }
        .sheet { width: 100%; }
        .header { display: table; width: 100%; border-bottom: 2px solid #333; margin-bottom: 20px; padding-bottom: 8px; }
        .header-cell { display: table-cell; vertical-align: middle; }
        .header-left, .header-right { width: 20%; }
        .header-center { width: 60%; text-align: center; }
        .header img { max-height: 70px; max-width: 160px; }
        .company-name { font-size: 18px; font-weight: 800; }
        .subinfo { font-size: 11px; margin-top: 3px; }
        .doc-title { font-size: 20px; margin-top: 6px; font-weight: 900; text-transform: uppercase; }
        .folio-box { border: 2px solid #B80000; padding: 6px 14px; font-size: 16px; font-weight: bold; color: #B80000; border-radius: 6px; display: inline-block; margin-top: 6px; }
        .info-row { display: table; width: 100%; margin-bottom: 12px; }
        .info-block { display: table-cell; width: 33.33%; padding-right: 10px; }
        .info-block label { font-size: 11px; font-weight: 700; }
        .field { border-bottom: 1px solid #222; padding: 5px 4px; min-height: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        thead th { background: #EAEAEA; border: 1px solid #333; padding: 7px; font-size: 12px; font-weight: 700; text-align: center; }
        tbody td { border: 1px solid #444; padding: 7px; font-size: 12px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .transport { margin-top: 18px; }
        .transport label { font-size: 12px; font-weight: 700; }
        .field-wide { border-bottom: 1px solid #222; padding: 8px 4px; min-height: 36px; }

        /* ESTILO DE FIRMAS CORREGIDO */
        .signatures { margin-top: 60px; display: table; width: 100%; }
        .signature { display: table-cell; width: 50%; text-align: center; vertical-align: bottom; }
        .line { border-bottom: 1px solid #222; width: 70%; margin: 0 auto 5px; }
        .name-under-line { font-weight: bold; font-size: 12px; text-transform: uppercase; margin-bottom: 2px; }
        .role-text { font-size: 10px; color: #444; }
        .footnote { margin-top: 30px; text-align: right; font-size: 10px; opacity: 0.7; }
    </style>
</head>
<body>
<div class="sheet">
    <div class="header">
        <div class="header-cell header-left">
            <?php if (!empty($companyLogoLeft)): ?>
                <img src="<?= $companyLogoLeft ?>">
            <?php endif; ?>
        </div>
        <div class="header-cell header-center">
            <div class="company-name">SOCIEDAD COOPERATIVA AGROPECUARIA YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V.</div>
            <div class="subinfo">YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V. — RFC: YSE201202B70</div>
            <div class="doc-title">Nota de Salida de Almacén</div>
        </div>
        <div class="header-cell header-right" style="text-align:right;">
            <?php if (!empty($companyLogoRight)): ?>
                <img src="<?= $companyLogoRight ?>">
            <?php endif; ?>
            <div style="font-size:12px;margin-top:6px;">Folio</div>
            <div class="folio-box"><?= esc($batch['folio_salida'] ?? '----') ?></div>
        </div>
    </div>

    <div class="info-row">
        <div class="info-block"><label>Fecha de embarque</label><div class="field"><?= esc($batch['fecha_embarque'] ?? '-') ?></div></div>
        <div class="info-block"><label>Cliente</label><div class="field"><?= esc($batch['nombre_cliente'] ?? '-') ?></div></div>
        <div class="info-block"><label>Tipo de producto</label><div class="field"><?= esc($batch['tipo_producto'] ?? '-') ?></div></div>
    </div>

    <table>
        <thead>
        <tr>
            <th>No. Maquila</th>
            <th>Producto</th>
            <th>Unidad</th>
            <th>Cantidad</th>
            <th>No. Factura</th>
            <th>Certificado</th>
            <th>Clave Lote</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center"><?= esc($batch['no_maquila'] ?? '-') ?></td>
            <td><?= esc($batch['producto'] ?? '-') ?></td>
            <td class="text-center"><?= esc($batch['unidad'] ?? '-') ?></td>
            <td class="text-right"><?= number_format((float)($batch['cantidad'] ?? 0), 2) ?></td>
            <td class="text-center"><?= esc($batch['no_factura'] ?? '-') ?></td>
            <td class="text-center"><?= esc($batch['certificado'] ?? '-') ?></td>
            <td class="text-center"><?= esc($batch['clave_lote'] ?? '-') ?></td>
        </tr>
        </tbody>
    </table>

    <div class="transport">
        <label>Datos del transporte</label>
        <div class="field-wide"><?= nl2br(esc($batch['datos_transporte'] ?? '-')) ?></div>
    </div>

    <div class="signatures">
        <div class="signature">
            <div class="name-under-line">
                <?= esc($batch['autoriza_salida'] ?? '___________________________') ?>
            </div>
            <div class="line"></div>
            <strong>Autoriza salida</strong><br>
            <span class="role-text">Responsable del beneficio seco</span>
        </div>

        <div class="signature">
            <div class="name-under-line">
                <?= esc($batch['recibe_producto'] ?? '___________________________') ?>
            </div>
            <div class="line"></div>
            <strong>Recibe producto</strong><br>
            <span class="role-text">Nombre y firma del cliente</span>
        </div>
    </div>

    <div class="footnote">
        Generado automáticamente — <?= date('d/m/Y H:i:s') ?>
    </div>
</div>
</body>
</html>