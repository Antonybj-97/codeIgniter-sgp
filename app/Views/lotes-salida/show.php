<?php // Rediseño de Nota de Salida - Estilo Moderno Agrícola ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nota de Salida - <?= esc($batch['folio_salida'] ?? 'FOLIO') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

    <style>
        :root {
            --primary-green: #065f46;
            --secondary-green: #10b981;
            --accent-amber: #d97706;
            --bg-soft: #f8fafc;
            --text-main: #1e293b;
        }

        /* Estilos de impresión */
        @media print {
            .no-print, .actions-bar { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .document-container { box-shadow: none !important; border: 1px solid #e2e8f0 !important; margin: 0 !important; max-width: 100% !important; }
            .info-card { break-inside: avoid; }
        }

        body {
            background-color: #f1f5f9;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--text-main);
        }

        .document-container {
            max-width: 1100px;
            margin: 2rem auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            overflow: hidden;
        }

        /* Header Estilizado */
        .header-gradient {
            background: linear-gradient(135deg, var(--primary-green) 0%, #064e3b 100%);
            padding: 3rem 2.5rem;
            color: white;
            position: relative;
        }

        .header-pattern {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            opacity: 0.1;
            background-image: radial-gradient(#fff 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .company-brand h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.025em;
            text-transform: uppercase;
        }

        .company-brand p {
            font-size: 0.875rem;
            opacity: 0.8;
            margin: 0.25rem 0 0 0;
        }

        .folio-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 1rem 2rem;
            border-radius: 15px;
            text-align: right;
        }

        .folio-box span {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--secondary-green);
        }

        .folio-box strong {
            font-size: 2rem;
            color: #fbbf24;
        }

        /* Grid de Información */
        .content-body {
            padding: 2.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .info-card {
            padding: 1.25rem;
            background: var(--bg-soft);
            border-radius: 12px;
            border-left: 4px solid var(--secondary-green);
        }

        .info-card label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .info-card .value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-green);
        }

        /* Tabla */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-custom th {
            background: transparent;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            font-weight: 700;
        }

        .table-custom td {
            background: white;
            padding: 1.25rem 1rem;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        .table-custom tr td:first-child { border-left: 1px solid #f1f5f9; border-radius: 10px 0 0 10px; }
        .table-custom tr td:last-child { border-right: 1px solid #f1f5f9; border-radius: 0 10px 10px 0; }

        /* Firmas */
        .signature-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            margin-top: 4rem;
            text-align: center;
        }

        .sig-line {
            border-top: 2px solid #e2e8f0;
            padding-top: 1rem;
        }

        .sig-line strong {
            color: var(--primary-green);
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        /* Floating Actions */
        .actions-bar {
            position: fixed;
            top: 2rem;
            right: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            z-index: 100;
        }

        .btn-action {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .btn-back { background: white; color: var(--text-main); }
        .btn-pdf { background: var(--accent-amber); color: white; }
        .btn-print { background: var(--primary-green); color: white; }

        .btn-action:hover { transform: translateX(-5px); }

        .badge-status {
            padding: 0.25rem 0.75rem;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 700;
            background: #dcfce7;
            color: #166534;
        }
    </style>

    <div class="actions-bar no-print">
        <a href="<?= site_url('lotes-salida') ?>" class="btn-action btn-back">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
        <a href="<?= site_url('lotes-salida/exportPDFIndividual/' . ($batch['id_salida'] ?? '')) ?>" class="btn-action btn-pdf" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="bi bi-printer"></i> Imprimir
        </button>
    </div>

    <div class="document-container">
        <header class="header-gradient">
            <div class="header-pattern"></div>
            <div class="header-content">
                <div class="company-brand">
                    <h1>Tosepan Titataniske</h1>
                    <p>YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V.</p>
                    <p><small>RFC: YSE201202B70</small></p>
                </div>
                <div class="folio-box">
                    <span>Nota de Salida</span>
                    <strong><?= esc($batch['folio_salida'] ?? '----') ?></strong>
                </div>
            </div>
        </header>

        <div class="content-body">
            <div class="info-grid">
                <div class="info-card">
                    <label>Fecha de Embarque</label>
                    <div class="value"><?= date('d / M / Y', strtotime($batch['fecha_embarque'])) ?></div>
                </div>
                <div class="info-card">
                    <label>Cliente / Destino</label>
                    <div class="value"><?= esc($batch['nombre_cliente']) ?></div>
                </div>
                <div class="info-card">
                    <label>Tipo de Producto</label>
                    <div class="value">
                        <?= esc($batch['tipo_producto']) ?>
                        <span class="badge-status">CERTIFICADO</span>
                    </div>
                </div>
            </div>

            <h4 style="color: var(--primary-green); font-weight: 700; margin-bottom: 1rem; display:flex; align-items:center; gap:0.5rem">
                <i class="bi bi-box-seam"></i> Detalle de Salida de Almacén
            </h4>

            <table class="table-custom">
                <thead>
                <tr>
                    <th>No. Maquila</th>
                    <th>Producto</th>
                    <th class="text-center">Clave Lote</th>
                    <th class="text-center">Certificado</th>
                    <th class="text-end">Cantidad</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><strong><?= esc($batch['no_maquila'] ?? '-') ?></strong></td>
                    <td><?= esc($batch['producto']) ?></td>
                    <td class="text-center"><code><?= esc($batch['clave_lote'] ?? '-') ?></code></td>
                    <td class="text-center"><?= esc($batch['certificado'] ?? '-') ?></td>
                    <td class="text-end">
                        <span style="font-size: 1.1rem; font-weight: 800; color: var(--primary-green);">
                            <?= number_format($batch['cantidad'], 2) ?>
                        </span>
                        <small class="text-muted"><?= esc($batch['unidad']) ?></small>
                    </td>
                </tr>
                </tbody>
            </table>

            <div style="margin-top: 2rem; padding: 1.5rem; background: #fffbeb; border-radius: 12px; border: 1px solid #fef3c7;">
                <label style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #92400e; display: block; margin-bottom: 0.5rem;">
                    <i class="bi bi-truck"></i> Logística y Transporte
                </label>
                <p style="margin: 0; font-size: 0.9rem; line-height: 1.6; color: #78350f;">
                    <?= !empty($batch['datos_transporte']) ? esc($batch['datos_transporte']) : 'No se registraron datos específicos de transporte para este folio.' ?>
                </p>
            </div>

            <div class="signature-container">
                <div class="sig-block">
                    <div style="height: 80px;"></div>
                    <div class="sig-line">
                        <strong><?= esc($batch['autoriza_salida'] ?? 'Autoriza Salida') ?></strong>
                        <p style="font-size: 0.7rem; color: #64748b; margin: 0;">Responsable Almacén / Beneficio</p>
                    </div>
                </div>
                <div class="sig-block">
                    <div style="height: 80px;"></div>
                    <div class="sig-line">
                        <strong><?= esc($batch['recibe_producto'] ?? 'Recibe Producto') ?></strong>
                        <p style="font-size: 0.7rem; color: #64748b; margin: 0;">Transportista / Cliente</p>
                    </div>
                </div>
            </div>
        </div>

        <footer style="background: #f8fafc; padding: 1.5rem 2.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #94a3b8;">
            <div>Generado el: <?= date('d/m/Y H:i') ?></div>
            <div style="display: flex; gap: 1rem;">
                <span>YANKUIK SENOJTOKALIS S.C.</span>
                <span>•</span>
                <span>Sistema Tosepan</span>
            </div>
        </footer>
    </div>

<?= $this->endSection() ?>