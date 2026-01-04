<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
.card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}
.table th {
    border-top: none;
    font-weight: 600;
    background-color: #343a40;
    color: white;
}
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}
.text-truncate-custom {
    max-width: 250px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.badge {
    font-size: 0.75em;
}
.progress-bar {
    transition: width 0.3s ease;
}
</style>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📋 Detalles del Proceso de Transformación #<?= esc($proceso['id'] ?? 'N/A') ?></h4>
            <div>
                <a href="<?= site_url('procesos') ?>" class="btn btn-sm btn-light me-2">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <?php if (!empty($proceso['id'])): ?>
                    <a href="<?= site_url('procesos/pdf/' . $proceso['id']) ?>" class="btn btn-sm btn-danger" target="_blank">
                        <i class="bi bi-file-pdf"></i> PDF
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            
            <!-- Información General -->
            <h5 class="text-secondary border-bottom pb-2 mb-3">Información General</h5>

            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-2">
                        <strong>Tipo de proceso:</strong> 
                        <span class="badge bg-primary"><?= esc($proceso['tipo_proceso'] ?? 'N/A') ?></span>
                    </p>
                    <p class="mb-2"><strong>Proveedor Principal:</strong> <?= esc($proceso['proveedor'] ?? 'N/A') ?></p>
                    <p class="mb-2">
                        <strong>Peso bruto (Inicial):</strong> 
                        <span class="fw-bold text-primary"><?= number_format($proceso['peso_bruto_kg'] ?? 0, 2) ?> kg</span>
                    </p>
                    <p class="mb-2">
                        <strong>Peso estimado:</strong> 
                        <span class="fw-bold text-info"><?= number_format($proceso['peso_estimado_kg'] ?? 0, 2) ?> kg</span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2">
                        <strong>Estado General:</strong> 
                        <?php 
                            $estadoProceso = esc($proceso['estado_proceso'] ?? 'Desconocido');
                            $procesoClass = match($estadoProceso) {
                                'Finalizado', 'Completo', 'completado' => 'bg-success',
                                'En Proceso', 'Iniciado', 'en_proceso' => 'bg-warning text-dark',
                                'Cancelado' => 'bg-danger',
                                'Pendiente' => 'bg-info',
                                default => 'bg-secondary',
                            };
                        ?>
                        <span class="badge <?= $procesoClass ?> text-uppercase fw-bold">
                            <?= $estadoProceso ?>
                        </span>
                    </p>
                    <p class="mb-2">
                        <strong>Fecha de proceso:</strong> 
                        <?php if (!empty($proceso['fecha_proceso'])): ?>
                            <?= date('d/m/Y H:i', strtotime($proceso['fecha_proceso'])) ?>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($proceso['peso_final_kg'])): ?>
                        <p class="mb-2">
                            <strong>Peso final:</strong> 
                            <span class="fw-bold text-success"><?= number_format($proceso['peso_final_kg'], 2) ?> kg</span>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($proceso['observacion_proceso'])): ?>
                        <p class="mb-2">
                            <strong>Observaciones:</strong> 
                            <span class="text-muted"><?= esc($proceso['observacion_proceso']) ?></span>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Información del Lote si existe -->
            <?php if (!empty($lote)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2 mb-3">📦 Información del Lote Asociado</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Folio Lote:</strong> <?= esc($lote['folio'] ?? 'N/A') ?></p>
                            <p class="mb-1"><strong>Proveedor:</strong> <?= esc($lote['proveedor'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Tipo Pimienta:</strong> <?= esc($lote['tipo_pimienta_nombre'] ?? 'N/A') ?></p>
                            <p class="mb-1"><strong>Tipo Entrada:</strong> <?= esc($lote['tipo_entrada_nombre'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Centro:</strong> <?= esc($lote['centro_nombre'] ?? 'N/A') ?></p>
                            <p class="mb-1"><strong>Peso Bruto Lote:</strong> <?= number_format($lote['peso_bruto_kg'] ?? 0, 2) ?> kg</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <hr class="my-4">

            <!-- Seguimiento y Trazabilidad -->
            <h5 class="text-primary border-bottom pb-2 mb-3">📊 Seguimiento y Trazabilidad (Detalles)</h5>

            <?php if (!empty($detalles) && is_array($detalles)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle" id="tablaDetalles">
                        <thead class="table-dark sticky-top">
                            <tr class="text-center">
                                <th width="80">ID</th>
                                <th style="min-width: 250px;">Descripción</th>
                                <th width="120">Peso Parcial (kg)</th>
                                <th width="120">Peso Estimado (kg)</th> 
                                <th width="120">Estado</th>
                                <th width="150">Fecha Registro</th>
                                <th width="100">Lote Fuente</th>
                                <th style="min-width: 150px;">Proveedor</th>
                                <th width="120">Tipo Pimienta</th>
                                <th width="150">Centro</th>
                                <th width="120">Peso Bruto (kg)</th>
                                <th width="120">Tipo Entrada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles as $d): ?>
                                <?php
                                    $estadoDetalle = esc($d['estado'] ?? $d['estado_detalle'] ?? 'Desconocido');
                                    $detalleClass = match($estadoDetalle) {
                                        'Completo', 'EXITOSO', 'Finalizado' => 'bg-success',
                                        'En Proceso', 'PENDIENTE', 'Iniciado' => 'bg-warning text-dark',
                                        'Cancelado', 'FALLIDO' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };

                                    $fechaRegistro = $d['fecha_registro'] ?? $d['registro'] ?? null;
                                    $pesoParcial = floatval($d['peso_parcial_kg'] ?? 0);
                                    $pesoEstimado = floatval($d['peso_estimado_kg'] ?? 0);
                                    $pesoBruto = floatval($d['peso_bruto'] ?? 0);
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($d['id'] ?? '-') ?></td>
                                    <td>
                                        <span class="text-truncate-custom d-inline-block" data-bs-toggle="tooltip" 
                                              title="<?= esc($d['descripcion'] ?? $d['descripcion_lote'] ?? 'Sin descripción') ?>">
                                            <?= esc($d['descripcion'] ?? $d['descripcion_lote'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold"><?= number_format($pesoParcial, 2) ?></td>
                                    <td class="text-end text-info"><?= number_format($pesoEstimado, 2) ?></td> 
                                    <td class="text-center">
                                        <span class="badge <?= $detalleClass ?> text-uppercase">
                                            <?= $estadoDetalle ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($fechaRegistro): ?>
                                            <?= date('d/m/Y H:i', strtotime($fechaRegistro)) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center fw-bold"><?= esc($d['lote'] ?? '-') ?></td>
                                    <td><?= esc($d['proveedor_lote'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($d['tipo_pimienta'] ?? '-') ?></td>
                                    <td><?= esc($d['centro'] ?? '-') ?></td>
                                    <td class="text-end"><?= number_format($pesoBruto, 2) ?></td>
                                    <td class="text-center"><?= esc($d['tipo_entrada_nombre'] ?? $d['tipo_entrada'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold">TOTALES:</td>
                                <td class="text-end fw-bold text-success border-top border-3 border-success">
                                    <?= number_format($totalPesoParcial ?? 0, 2) ?>
                                </td>
                                <td class="text-end fw-bold text-info border-top border-3 border-info">
                                    <?= number_format($totalPesoEstimado ?? 0, 2) ?>
                                </td>
                                <td colspan="2"></td>
                                <td colspan="3"></td>
                                <td class="text-end fw-bold border-top border-3 border-primary">
                                    <?= number_format($totalPesoBruto ?? 0, 2) ?>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Resumen Estadístico -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted">Total Detalles</h6>
                                <h3 class="text-primary"><?= count($detalles) ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted">Peso Parcial</h6>
                                <h3 class="text-success"><?= number_format($totalPesoParcial ?? 0, 2) ?> kg</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted">Peso Estimado</h6>
                                <h3 class="text-info"><?= number_format($totalPesoEstimado ?? 0, 2) ?> kg</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted">Peso Bruto</h6>
                                <h3 class="text-warning"><?= number_format($totalPesoBruto ?? 0, 2) ?> kg</h3>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle me-2"></i> No hay detalles de trazabilidad registrados para este proceso.
                </div>
            <?php endif; ?>

            <!-- Botones de Acción -->
            <div class="mt-4 text-center">
                <a href="<?= site_url('procesos') ?>" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Volver al Listado
                </a>
                <?php if (!empty($proceso['id'])): ?>
                    <a href="<?= site_url('procesos/edit/' . $proceso['id']) ?>" class="btn btn-warning me-2">
                        <i class="bi bi-pencil"></i> Editar Proceso
                    </a>
                    <a href="<?= site_url('procesos/pdf/' . $proceso['id']) ?>" class="btn btn-danger me-2" target="_blank">
                        <i class="bi bi-file-pdf"></i> Generar PDF
                    </a>
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar tooltips de Bootstrap
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }
    
    // Resaltar filas al pasar el mouse
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
            this.style.cursor = 'pointer';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
        
        // Click en fila para resaltar
        row.addEventListener('click', function() {
            tableRows.forEach(r => r.classList.remove('table-active'));
            this.classList.add('table-active');
        });
    });

    // Ordenar tabla por columnas
    const tabla = document.getElementById('tablaDetalles');
    if (tabla) {
        const headers = tabla.querySelectorAll('th');
        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                sortTable(index);
            });
        });
    }

    function sortTable(columnIndex) {
        const table = document.getElementById('tablaDetalles');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const aText = a.cells[columnIndex].textContent.trim();
            const bText = b.cells[columnIndex].textContent.trim();
            
            // Intentar convertir a número si es posible
            const aNum = parseFloat(aText.replace(/[^\d.-]/g, ''));
            const bNum = parseFloat(bText.replace(/[^\d.-]/g, ''));
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return aNum - bNum;
            }
            
            return aText.localeCompare(bText);
        });
        
        // Reordenar filas
        rows.forEach(row => tbody.appendChild(row));
    }
});
</script>

<?= $this->endSection() ?>