<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Configuración de Página */
        @page { 
            margin: 2.5cm 1.2cm; 
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.3;
            margin: 0;
        }

        /* Colores corporativos */
        .text-primary { color: #1b5e20; }
        .bg-primary { background-color: #1b5e20; }
        .border-primary { border-color: #1b5e20; }

        /* Encabezado */
        header {
            position: fixed;
            top: -1.8cm;
            left: 0;
            right: 0;
            height: 2cm;
            border-bottom: 2px solid #1b5e20;
            padding-bottom: 5px;
        }

        .header-table {
            width: 100%;
            border: none;
        }

        .header-logo { width: 60px; text-align: left; }
        .header-title { text-align: center; }
        .header-folio { width: 120px; text-align: right; }

        .folio-box {
            border: 2px solid #1b5e20;
            background: #e8f5e9;
            padding: 5px;
            border-radius: 5px;
            text-align: center;
        }

        /* Contenedores */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1b5e20;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
            padding-bottom: 3px;
        }

        .info-grid {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-grid td {
            padding: 4px;
            border: none;
            text-align: left;
            vertical-align: top;
        }

        /* Tabla de Detalles - Optimizada para muchas columnas */
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px; /* Ligeramente más pequeña para que quepa todo */
        }

        table.items-table th {
            background-color: #e9f4e7;
            color: #1b5e20;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 3px;
            border: 0.5px solid #ccc;
        }

        table.items-table td {
            padding: 5px 3px;
            border: 0.5px solid #eee;
            text-align: center;
        }

        /* Badges de Estado */
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
            font-size: 7.5px;
        }
        .bg-success { background: #2e7d32; }
        .bg-warning { background: #f9a825; color: #000; }
        .bg-danger { background: #c62828; }
        .bg-secondary { background: #757575; }

        /* Footer */
        footer {
            position: fixed;
            bottom: -1cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .page-number:after { content: counter(page); }
    </style>
</head>

<body>

    <header>
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <?php if (!empty($logo_base64)): ?>
                        <img src="<?= $logo_base64 ?>" width="55">
                    <?php endif; ?>
                </td>
                <td class="header-title">
                    <h1 style="margin:0; font-size: 14px; color: #1b5e20;">YANKUIK SENOJTOKALIS S.C. DE R.L. DE C.V.</h1>
                    <p style="margin:2px 0; font-size: 10px;">REPORTE TÉCNICO DE PROCESO DE TRANSFORMACIÓN</p>
                </td>
                <td class="header-folio">
                    <div class="folio-box">
                        <span style="display:block; font-size: 8px;">FOLIO PROCESO</span>
                        <strong>#<?= esc($proceso['id']) ?></strong>
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        YANKUIK SENOJTOKALIS - Cuetzalan, Puebla | Página <span class="page-number"></span>
    </footer>

    <main>
        <div class="section-title">Información del Proceso</div>
        <table class="info-grid">
            <tr>
                <td width="33%"><strong>Tipo de Proceso:</strong><br><?= esc($proceso['tipo_proceso'] ?? 'N/A') ?></td>
               <td width="33%">
    <strong>Tipo de Entrada:</strong><br>
    <?= esc($lote['tipo_entrada'] ?? $proceso['tipo_entrada'] ?? 'N/A') ?>
</td>
                <td width="33%"><strong>Fecha Registro:</strong><br><?= !empty($proceso['fecha_proceso']) ? date('d/m/Y H:i', strtotime($proceso['fecha_proceso'])) : 'N/A' ?></td>
                <td width="33%"><strong>Estado General:</strong><br>
                    <?php 
                        $estado = $proceso['estado_proceso'] ?? 'PENDIENTE';
                        $class = match($estado) { 'EXITOSO', 'Completo' => 'bg-success', 'En Proceso', 'PENDIENTE' => 'bg-warning', default => 'bg-secondary' };
                    ?>
                    <span class="badge <?= $class ?>"><?= esc($estado) ?></span>
                </td>
            </tr>
            <tr>
                <td><strong>Peso Bruto Inicial:</strong><br><?= number_format($proceso['peso_bruto_kg'] ?? 0, 2) ?> kg</td>
                <td><strong>Peso Estimado Total:</strong><br><?= number_format($proceso['peso_estimado_kg'] ?? 0, 2) ?> kg</td>
                <td><strong>Peso Final Total:</strong><br><?= number_format($proceso['peso_final_kg'] ?? 0, 2) ?> kg</td>
                <td><strong>Proveedor Principal:</strong><br><?= esc($proceso['proveedor'] ?? 'N/A') ?></td>
            </tr>
        </table>

        <div class="section-title">Trazabilidad de Lotes (Insumos)</div>
        <?php if (!empty($detalles)): ?>
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="8%">Folio Lote</th>
                        <th>Proveedor / Acopiador</th>
                        <th width="12%">Pimienta</th>
                        <th width="12%">Centro Acopio</th>
                        <th width="10%">Peso (kg)</th>
                        <th width="10%">Estado</th>
                        <th width="12%">Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $d): ?>
                    <tr>
                        <td><?= esc($d['id']) ?></td>
                        <td><strong><?= esc($d['lote'] ?? $d['lote_entrada_id']) ?></strong></td>
                        <td style="text-align: left;"><?= esc($d['proveedor_lote'] ?? $d['acopiador'] ?? '-') ?></td>
                        <td><?= esc($d['tipo_pimienta'] ?? '-') ?></td>
                        <td><?= esc($d['centro'] ?? '-') ?></td>
                        <td style="font-weight: bold;"><?= number_format($d['peso_parcial_kg'] ?? 0, 2) ?></td>
                        <td>
                            <?php 
                                $d_est = $d['estado'] ?? 'N/A';
                                $d_class = match($d_est) { 'EXITOSO' => 'bg-success', 'EN_EJECUCION' => 'bg-warning', default => 'bg-secondary' };
                            ?>
                            <span class="badge <?= $d_class ?>"><?= $d_est ?></span>
                        </td>
                        <td><?= !empty($d['fecha_registro']) ? date('d/m/y H:i', strtotime($d['fecha_registro'])) : '-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="margin-top: 15px; text-align: right; font-size: 11px;">
                <strong>Suma Total de Pesos Parciales:</strong> 
                <span style="color: #1b5e20; font-size: 13px;"><?= number_format($totalPesoParcial ?? 0, 2) ?> kg</span>
            </div>
        <?php else: ?>
            <div style="padding: 20px; text-align: center; border: 1px dashed #ccc;">
                No se encontraron registros de trazabilidad para este proceso.
            </div>
        <?php endif; ?>

        <div style="margin-top: 40px;">
            <table width="100%">
                <tr>
                    <td width="45%" style="border-top: 1px solid #333; text-align: center; padding-top: 5px;">
                        Firma Responsable de Planta
                    </td>
                    <td width="10%"></td>
                    <td width="45%" style="border-top: 1px solid #333; text-align: center; padding-top: 5px;">
                        Firma Control de Calidad
                    </td>
                </tr>
            </table>
        </div>
    </main>

</body>
</html>