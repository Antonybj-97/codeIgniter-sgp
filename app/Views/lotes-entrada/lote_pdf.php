<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Entrada #<?= esc($lote['folio'] ?? '0000') ?></title>
    <style>
        /* Configuración Global */
        @page { size: A4 portrait; margin: 0; }
        
        body { 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            font-size: 10pt; 
            color: #334155; 
            margin: 0; 
            padding: 0;
            line-height: 1.5;
        }

        /* Colores */
        :root {
            --primary: #14532d;
            --primary-light: #dcfce7;
            --secondary: #ea580c;
            --accent: #f8fafc;
            --border: #e2e8f0;
            --text-muted: #64748b;
        }

        .container { padding: 40px; }

        /* Marca de Agua */
        .watermark {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 70pt;
            color: rgba(226, 232, 240, 0.3);
            z-index: -1000;
            font-weight: 800;
            text-transform: uppercase;
        }

        /* Encabezado */
        .header { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .logo-img { max-height: 80px; width: auto; }
        .company-info h1 { margin: 0; font-size: 18pt; color: var(--primary); letter-spacing: -0.5px; }
        .company-info p { margin: 2px 0; font-size: 8.5pt; color: var(--text-muted); }
        .badge-tagline { 
            display: inline-block; 
            background: var(--primary-light); 
            color: var(--primary); 
            padding: 2px 8px; 
            border-radius: 4px; 
            font-size: 7.5pt; 
            font-weight: bold; 
            text-transform: uppercase;
        }

        /* Título de Documento */
        .doc-title-bar {
            background: var(--primary);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .folio-number {
            background: rgba(255,255,255,0.15);
            padding: 8px 15px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 14pt;
        }

        /* Secciones */
        .section-title {
            font-size: 8.5pt;
            font-weight: 800;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 10px;
            border-left: 4px solid var(--secondary);
            padding-left: 10px;
        }

        .grid-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .grid-table td { padding: 12px 10px; border-bottom: 1px solid var(--border); vertical-align: top; }
        
        .label { font-size: 7pt; color: var(--text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 4px; display: block; }
        .value { font-size: 10pt; font-weight: 600; color: #1e293b; }
        .highlight { color: var(--primary); }

        /* Card de Liquidación */
        .summary-card {
            background: var(--accent);
            border: 1px solid var(--border);
            border-radius: 12px;
            width: 320px;
            margin-left: auto;
            overflow: hidden;
        }
        .summary-item { padding: 10px 15px; border-bottom: 1px solid var(--border); }
        .summary-total {
            background: var(--secondary);
            color: white;
            padding: 15px;
            text-align: right;
        }

        /* Observaciones */
        .notes-box {
            background: #fff;
            border: 1.5px dashed var(--border);
            padding: 15px;
            border-radius: 8px;
            font-size: 9pt;
            color: #475569;
            min-height: 80px;
        }

        /* Firmas */
        .signature-section { margin-top: 50px; width: 100%; }
        .sig-box { width: 45%; text-align: center; display: inline-block; vertical-align: bottom; }
        .sig-line { border-top: 1px solid #94a3b8; width: 200px; margin: 0 auto 10px; }

        footer {
            position: fixed;
            bottom: 30px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 7.5pt;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
            padding-top: 15px;
        }
    </style>
</head>
<body>

<div class="watermark">ORIGINAL</div>

<div class="container">
    <table class="header">
        <tr>
            <td width="20%">
                <?php if (!empty($logo_base64)): ?>
                    <img src="<?= $logo_base64 ?>" class="logo-img">
                <?php endif; ?>
            </td>
            <td width="60%" class="company-info" style="text-align: center;">
                <h1>YANKUIK SENOJTOKALIS</h1>
                <span class="badge-tagline">S.C. DE R.L. DE C.V. • Orgánico & Sustentable</span>
                <p>RFC: YSE123456ABC • Cuetzálan del Progreso, Puebla</p>
                <p>Contacto: contacto@yankuik.org | +52 233 000 0000</p>
            </td>
            <td width="20%" style="text-align: right;">
                <?php if (!empty($companyLogoRight)): ?>
                    <img src="<?= $companyLogoRight ?>" class="logo-img">
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <div class="doc-title-bar">
        <table width="100%">
            <tr>
                <td>
                    <div style="font-size: 16pt; font-weight: 800; letter-spacing: -0.5px;">COMPROBANTE DE ENTRADA</div>
                    <div style="font-size: 9pt; opacity: 0.8;">Sistema de Gestión de pimienta</div>
                </td>
                <td align="right">
                    <span style="font-size: 8pt; margin-right: 10px;">FOLIO CONTROL</span>
                    <span class="folio-number">#<?= esc($lote['folio'] ?? '0000') ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">I. Datos de Recepción</div>
    <table class="grid-table">
        <tr>
            <td width="33%">
                <span class="label">Producto</span>
                <span class="value highlight"><?= esc($lote['tipo_pimienta']) ?></span>
            </td>
            <td width="33%">
                <span class="label">Fecha de Ingreso</span>
                <span class="value"><?= date('d / M / Y', strtotime($lote['fecha_entrada'] ?? 'now')) ?></span>
            <<td width="33%">
                <span class="label">Estatus de Recepción</span>
                <span class="value" style="color: <?= ($lote['estado'] == 'Recibido' || $lote['estado'] == 'Pendiente') ? '#15803d' : '#ea580c' ?>;">
                    <span style="font-size: 8pt; vertical-align: middle;"></span> <?= esc($lote['estado']) ?>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">Productor / Proveedor</span>
                <span class="value"><?= esc($lote['proveedor']) ?></span>
            </td>
            <td>
                <span class="label">Centro de Acopio</span>
                <span class="value"><?= esc($lote['centro']) ?></span>
            </td>
        </tr>
    </table>

    <div class="section-title">II. Detalles de Carga y Liquidación</div>
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td width="55%" style="vertical-align: top; padding-right: 20px;">
                <span class="label">Observaciones de Inspección</span>
                <div class="notes-box">
                    <?= $lote['observaciones'] ?: 'Sin observaciones adicionales.' ?>
                </div>
                
                <div style="margin-top: 20px;">
                    <span class="label">Responsable en Turno</span>
                    <span class="value" style="font-size: 9pt;"><?= esc($lote['usuario']) ?></span>
                </div>
            </td>
            <td width="45%" style="vertical-align: top;">
                <div class="summary-card">
                    <div class="summary-item">
                        <table width="100%">
                            <tr>
                                <td class="label">Peso Bruto</td>
                                <td align="right" class="value"><?= number_format($lote['peso_bruto_kg'], 2) ?> kg</td>
                            </tr>
                        </table>
                    </div>
                    <div class="summary-item">
                        <table width="100%">
                            <tr>
                                <td class="label">Precio Unitario</td>
                                <td align="right" class="value">$ <?= number_format($lote['precio_compra'], 2) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="summary-total">
                        <table width="100%">
                            <tr>
                                <td align="left" style="font-size: 8pt; font-weight: normal; opacity: 0.9;">MONTO TOTAL</td>
                                <td align="right" style="font-size: 16pt; font-weight: 800;">$ <?= number_format($lote['costo_total'], 2) ?></td>
                            </tr>
                        </table>
                        <div style="font-size: 7pt; margin-top: 5px; opacity: 0.8; text-align: right;">Pesos Mexicanos (MXN)</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="signature-section">
        <div class="sig-box">
            <div style="height: 50px;"></div>
            <div class="sig-line"></div>
            <span class="label">Firma del Productor</span>
            <span class="value" style="font-size: 8pt;"><?= esc($lote['proveedor']) ?></span>
        </div>
        <div class="sig-box" style="float: right;">
            <div style="height: 50px;"></div>
            <div class="sig-line"></div>
            <span class="label">Recepción Autorizada</span>
            <span class="value" style="font-size: 8pt;"><?= esc($lote['usuario']) ?></span>
        </div>
    </div>

    <footer>
        <strong>YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V.</strong><br>
        Este documento es un comprobante de trazabilidad interna. ID Registro: <?= $lote['id'] ?> | Fecha de impresión: <?= date('d/m/Y H:i') ?><br>
    </footer>
</div>

</body>
</html>