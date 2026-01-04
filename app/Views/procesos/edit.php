<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    /* Premium Earth Tone Styles */
    .form-container {
        animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .premium-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2f 50%, #6b9b37 100%);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .card-header-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% {
            left: -100%;
        }

        100% {
            left: 100%;
        }
    }

    .header-title {
        color: white;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .section-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 2rem 0 1.5rem;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 1.1rem;
    }

    .section-divider i {
        font-size: 1.5rem;
        color: #4a7c2f;
    }

    .section-divider::after {
        content: '';
        flex: 1;
        height: 2px;
        background: linear-gradient(90deg, rgba(74, 124, 47, 0.3), transparent);
    }

    /* Form Elements */
    .form-group-enhanced {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-label-floating {
        position: absolute;
        left: 1rem;
        top: 0;
        transform: translateY(-50%);
        color: #4a7c2f;
        font-weight: 700;
        font-size: 0.75rem;
        background: var(--card-bg);
        padding: 0 0.5rem;
        z-index: 1;
    }

    .form-control-enhanced,
    .form-select-enhanced {
        border: 2px solid rgba(74, 124, 47, 0.2);
        border-radius: var(--radius-sm);
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--card-bg);
        color: var(--text-primary);
        width: 100%;
    }

    .form-control-enhanced:focus,
    .form-select-enhanced:focus {
        border-color: #4a7c2f;
        box-shadow: 0 0 0 4px rgba(74, 124, 47, 0.1);
        outline: none;
    }

    /* Lote Items */
    #lotesContainer {
        border: 1px solid rgba(74, 124, 47, 0.2);
        border-radius: var(--radius-sm);
        background: var(--card-bg);
        max-height: 400px;
        overflow-y: auto;
    }

    .lote-item {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem;
        transition: all 0.2s ease;
    }

    .lote-item:last-child {
        border-bottom: none;
    }

    .lote-item:hover {
        background-color: rgba(74, 124, 47, 0.05);
    }

    .lote-agotado {
        opacity: 0.6;
        background-color: rgba(0, 0, 0, 0.02);
    }

    /* Table Styles */
    .table-premium {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-premium th {
        background: #2d3319;
        color: white;
        padding: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table-premium th:first-child {
        border-top-left-radius: var(--radius-sm);
    }

    .table-premium th:last-child {
        border-top-right-radius: var(--radius-sm);
    }

    .table-premium td {
        padding: 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        vertical-align: middle;
    }

    .table-premium tr:hover td {
        background-color: rgba(74, 124, 47, 0.05);
    }

    /* Buttons */
    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #4a7c2f 0%, #3a6124 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(74, 124, 47, 0.3);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(74, 124, 47, 0.4);
    }

    .btn-secondary-custom {
        background: linear-gradient(135deg, #8b9c6f 0%, #5a6c3d 100%);
        color: white;
    }

    .btn-icon-only {
        padding: 0.5rem;
        width: 36px;
        height: 36px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }

    /* Badges & Progress */
    .badge-premium {
        padding: 0.35em 0.8em;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .badge-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .badge-secondary {
        background: #6b7280;
        color: white;
    }

    .badge-danger {
        background: #ef4444;
        color: white;
    }

    .progress-premium {
        height: 8px;
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar-premium {
        height: 100%;
        transition: width 0.6s ease;
    }

    /* Summary Cards */
    .summary-card {
        background: linear-gradient(135deg, rgba(74, 124, 47, 0.1), rgba(45, 80, 22, 0.1));
        border: 1px solid rgba(74, 124, 47, 0.2);
        border-radius: var(--radius-sm);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d5016;
    }

    .summary-label {
        font-size: 0.875rem;
        color: #4a7c2f;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>

<div class="container mt-4 form-container">
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="premium-card">
                <!-- Header -->
                <div class="card-header-gradient">
                    <h4 class="header-title">
                        <i class="bi bi-pencil-square"></i>
                        Editar Proceso Masivo
                    </h4>
                </div>

                <div class="card-body p-4 p-md-5">

                    <!-- Alerts -->
                    <?php foreach (['error', 'success', 'warning'] as $type): ?>
                        <?php if (session()->getFlashdata($type)): ?>
                            <div class="alert alert-<?= $type ?> alert-dismissible fade show shadow-sm mb-4">
                                <?= session()->getFlashdata($type) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if (!isset($procesoMasivo)): ?>
                        <div class="alert alert-danger">
                            No se encontró información del proceso masivo.
                            <a href="<?= site_url('procesos') ?>" class="alert-link">Volver a la lista</a>
                        </div>
                    <?php else: ?>

                        <form action="<?= site_url('procesos/actualizarMasivo/' . ($procesoMasivo['id'] ?? '')) ?>" method="POST" id="formProcesosMasivos">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="PUT">

                            <!-- Sección: Configuración -->
                            <div class="section-divider">
                                <i class="bi bi-sliders"></i>
                                <span>Configuración del Proceso</span>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-group-enhanced">
                                        <select name="tipo_proceso" id="tipo_proceso" class="form-select-enhanced" required>
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($tiposProceso as $tipo): ?>
                                                <option value="<?= esc($tipo) ?>" <?= ($procesoMasivo['tipo_proceso'] ?? '') === $tipo ? 'selected' : '' ?>>
                                                    <?= esc($tipo) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="tipo_proceso" class="form-label-floating">Tipo de Proceso</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Filtros -->
                            <div class="section-divider">
                                <i class="bi bi-funnel-fill"></i>
                                <span>Filtrar Lotes Disponibles</span>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <div class="form-group-enhanced">
                                        <select id="filtro_tipo_entrada" class="form-select-enhanced">
                                            <option value="">Todos</option>
                                            <?php foreach ($tiposEntrada as $te): ?>
                                                <option value="<?= esc($te) ?>"><?= esc($te) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label class="form-label-floating">Tipo de Entrada</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group-enhanced">
                                        <select id="filtro_tipo_pimienta" class="form-select-enhanced">
                                            <option value="">Todos</option>
                                            <?php foreach ($tiposPimienta as $tp): ?>
                                                <option value="<?= esc($tp) ?>"><?= esc($tp) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label class="form-label-floating">Tipo de Pimienta</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group-enhanced">
                                        <select id="filtro_centro_acopio" class="form-select-enhanced">
                                            <option value="">Todos</option>
                                            <?php foreach ($centrosAcopio as $ca): ?>
                                                <option value="<?= esc($ca) ?>"><?= esc($ca) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label class="form-label-floating">Centro de Acopio</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Selección de Lotes -->
                            <div class="section-divider">
                                <i class="bi bi-check2-square"></i>
                                <span>Selección de Lotes</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="select_all">
                                    <label for="select_all" class="form-check-label fw-bold text-success">
                                        Seleccionar todos los visibles
                                    </label>
                                </div>
                            </div>

                            <div id="lotesContainer" class="mb-4 custom-scrollbar">
                                <?php
                                $lotesSeleccionados = $lotesSeleccionados ?? [];
                                $pesosParciales = $pesosParciales ?? [];
                                ?>

                                <?php foreach ($lotes as $l): ?>
                                    <?php
                                    $id = $l['id'] ?? '';
                                    $pesoDisponible = isset($l['peso_disponible']) ? (float)$l['peso_disponible'] : 0;
                                    $pesoOriginal = isset($l['peso_original']) ? (float)$l['peso_original'] : 0;
                                    $tipoEntrada = $l['tipo_entrada'] ?? '';
                                    $tipoPimienta = $l['tipo_pimienta'] ?? '';
                                    $centro = $l['centro_acopio'] ?? '';
                                    $proveedor = $l['proveedor'] ?? '';
                                    $estaAgotado = $pesoDisponible <= 0;
                                    $estaSeleccionado = $l['seleccionado'] ?? false;
                                    $pesoParcial = $l['peso_parcial'] ?? $pesoDisponible;
                                    ?>

                                    <div class="lote-item <?= $estaAgotado ? 'lote-agotado' : '' ?>"
                                        data-tipo-entrada="<?= esc($tipoEntrada) ?>"
                                        data-tipo-pimienta="<?= esc($tipoPimienta) ?>"
                                        data-centro-acopio="<?= esc($centro) ?>"
                                        data-peso-disponible="<?= $pesoDisponible ?>">

                                        <div class="d-flex align-items-start gap-3">
                                            <?php if (!$estaAgotado): ?>
                                                <input type="checkbox"
                                                    class="form-check-input lote-checkbox mt-2"
                                                    name="lotes[]"
                                                    value="<?= $id ?>"
                                                    id="lote_<?= $id ?>"
                                                    <?= $estaSeleccionado ? 'checked' : '' ?>>
                                            <?php endif; ?>

                                            <div class="flex-grow-1">
                                                <label for="lote_<?= $id ?>" class="d-block cursor-pointer">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="fw-bold text-dark">Lote #<?= esc($id) ?> - <?= esc($proveedor) ?></span>
                                                        <div>
                                                            <?php if ($estaAgotado): ?>
                                                                <span class="badge badge-premium badge-danger">Agotado</span>
                                                            <?php endif; ?>
                                                            <?php if ($estaSeleccionado): ?>
                                                                <span class="badge badge-premium badge-success">Seleccionado</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="small text-muted mb-2">
                                                        <span class="me-2"><i class="bi bi-box-seam"></i> <?= esc($tipoEntrada) ?></span>
                                                        <span class="me-2"><i class="bi bi-flower1"></i> <?= esc($tipoPimienta) ?></span>
                                                        <span><i class="bi bi-geo-alt"></i> <?= esc($centro) ?></span>
                                                    </div>
                                                    <div class="d-flex gap-3 small">
                                                        <span>Original: <strong><?= number_format($pesoOriginal, 2) ?> kg</strong></span>
                                                        <?php if (!$estaAgotado): ?>
                                                            <span class="text-success">Disponible: <strong><?= number_format($pesoDisponible, 2) ?> kg</strong></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </label>

                                                <?php if (!$estaAgotado): ?>
                                                    <div class="mt-2 row align-items-center g-2">
                                                        <div class="col-auto">
                                                            <label class="col-form-label col-form-label-sm text-secondary">Procesar (kg):</label>
                                                        </div>
                                                        <div class="col-auto">
                                                            <input type="number"
                                                                class="form-control form-control-sm lote-peso-parcial"
                                                                name="peso_parcial[<?= $id ?>]"
                                                                data-max="<?= $pesoDisponible ?>"
                                                                min="0.01" max="<?= $pesoDisponible ?>" step="0.01"
                                                                value="<?= number_format($pesoParcial, 2) ?>"
                                                                <?= !$estaSeleccionado ? 'disabled' : '' ?>
                                                                style="width: 120px;">
                                                        </div>
                                                        <input type="hidden" name="peso_estimado_final[<?= $id ?>]" class="peso_estimado_hidden" value="0">
                                                    </div>
                                                <?php else: ?>
                                                    <input type="hidden" name="peso_parcial[<?= $id ?>]" value="0">
                                                    <input type="hidden" name="peso_estimado_final[<?= $id ?>]" value="0">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Resumen -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="summary-card">
                                        <div class="summary-label">Total Seleccionado</div>
                                        <div class="summary-value"><span id="totalSeleccionado">0.00</span> <small>kg</small></div>
                                        <div class="small text-muted mt-1"><span id="totalLotes">0</span> lotes seleccionados</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="summary-card" id="estimadoContainer">
                                        <div class="summary-label">Peso Estimado Final</div>
                                        <div class="summary-value text-warning"><span id="estimadoPeso">0.00</span> <small>kg</small></div>
                                        <div class="small text-muted mt-1">Basado en el factor del proceso</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Procesos Relacionados -->
                            <div class="section-divider">
                                <i class="bi bi-list-check"></i>
                                <span>Procesos Relacionados</span>
                            </div>

                            <?php if (!empty($procesosRelacionados)): ?>
                                <div class="table-responsive mb-4">
                                    <table class="table-premium">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>Estado</th>
                                                <th>Progreso</th>
                                                <th>Observaciones</th>
                                                <th>Peso Bruto</th>
                                                <th>Peso Final</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($procesosRelacionados as $proceso): ?>
                                                <?php
                                                $progreso = 0;
                                                $colorProgreso = '#6b7280';
                                                $estado = $proceso['estado_proceso'] ?? '';

                                                if (in_array($estado, ['Finalizado', 'completado'])) {
                                                    $progreso = 100;
                                                    $colorProgreso = '#10b981';
                                                } elseif (in_array($estado, ['En Proceso', 'Iniciado', 'en_proceso'])) {
                                                    $progreso = 50;
                                                    $colorProgreso = '#f59e0b';
                                                }
                                                ?>
                                                <tr>
                                                    <td><strong>#<?= $proceso['id'] ?? '' ?></strong></td>
                                                    <td><?= !empty($proceso['fecha_proceso']) ? date('d/m/Y', strtotime($proceso['fecha_proceso'])) : 'N/A' ?></td>
                                                    <td>
                                                        <select name="estados[<?= $proceso['id'] ?>]" class="form-select form-select-sm estado-select" style="min-width: 130px; border-color: rgba(0,0,0,0.1);">
                                                            <option value="Pendiente" <?= $estado === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                            <option value="En Proceso" <?= in_array($estado, ['En Proceso', 'Iniciado', 'en_proceso']) ? 'selected' : '' ?>>En Proceso</option>
                                                            <option value="Finalizado" <?= in_array($estado, ['Finalizado', 'completado']) ? 'selected' : '' ?>>Finalizado</option>
                                                            <option value="Cancelado" <?= $estado === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                                        </select>
                                                    </td>
                                                    <td style="width: 150px;">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="progress-premium flex-grow-1">
                                                                <div class="progress-bar-premium" style="width: <?= $progreso ?>%; background-color: <?= $colorProgreso ?>;"></div>
                                                            </div>
                                                            <small class="text-muted"><?= $progreso ?>%</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <textarea name="observaciones[<?= $proceso['id'] ?>]"
                                                            class="form-control form-control-sm"
                                                            rows="1"
                                                            placeholder="Observaciones..."
                                                            style="min-width: 150px; resize: vertical; border-color: rgba(0,0,0,0.1);"><?= esc($proceso['observacion_proceso'] ?? '') ?></textarea>
                                                    </td>
                                                    <td class="text-end fw-bold"><?= number_format($proceso['peso_bruto_kg'] ?? 0, 2) ?></td>
                                                    <td>
                                                        <input type="number"
                                                            name="pesos_finales[<?= $proceso['id'] ?>]"
                                                            class="form-control form-control-sm peso-final-input text-end"
                                                            value="<?= !empty($proceso['peso_final_kg']) ? number_format($proceso['peso_final_kg'], 2) : '' ?>"
                                                            placeholder="0.00"
                                                            step="0.01"
                                                            min="0"
                                                            style="min-width: 100px; border-color: rgba(0,0,0,0.1);">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button type="button" class="btn-action btn-icon-only btn-primary-custom btn-actualizar-individual"
                                                                data-proceso-id="<?= $proceso['id'] ?>"
                                                                title="Guardar cambios">
                                                                <i class="bi bi-check-lg"></i>
                                                            </button>
                                                            <a href="<?= site_url('procesos/detalles/' . $proceso['id']) ?>"
                                                                class="btn-action btn-icon-only btn-secondary-custom"
                                                                title="Ver detalles">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background: rgba(0,0,0,0.02);">
                                                <td colspan="5" class="text-end fw-bold text-uppercase text-secondary">Totales</td>
                                                <td class="text-end fw-bold"><?= number_format(array_sum(array_column($procesosRelacionados, 'peso_bruto_kg')), 2) ?></td>
                                                <td class="text-end fw-bold text-success" id="total-peso-final">
                                                    <?= number_format(array_sum(array_column($procesosRelacionados, 'peso_final_kg')), 2) ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle-fill"></i>
                                    No hay procesos relacionados para mostrar.
                                </div>
                            <?php endif; ?>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                                <a href="<?= site_url('procesos') ?>" class="btn-action btn-secondary-custom">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                                <button type="submit" class="btn-action btn-primary-custom" id="btnSubmit">
                                    <i class="bi bi-check-circle-fill"></i> Actualizar Proceso Masivo
                                </button>
                            </div>

                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function() {
        const factores = {
            'Desgranado': 0.9,
            'Secado': 0.33333333333,
            'Soplado': 0.98,
            'Empaque': 1.0
        };

        function actualizarVista() {
            const tipoProceso = $('#tipo_proceso').val();
            const filtroEntrada = $('#filtro_tipo_entrada').val();
            const filtroPimienta = $('#filtro_tipo_pimienta').val();
            const filtroCentro = $('#filtro_centro_acopio').val();

            let visibles = 0,
                seleccionados = 0,
                totalPeso = 0,
                totalEstimado = 0;

            $('.lote-item').each(function() {
                const $item = $(this);
                const tipoEntrada = $item.data('tipo-entrada');
                const tipoPimienta = $item.data('tipo-pimienta');
                const centro = $item.data('centro-acopio');
                const pesoDisponible = $item.data('peso-disponible');
                const estaAgotado = pesoDisponible <= 0;

                let mostrar = true;
                if (filtroEntrada && tipoEntrada !== filtroEntrada) mostrar = false;
                if (filtroPimienta && tipoPimienta !== filtroPimienta) mostrar = false;
                if (filtroCentro && centro !== filtroCentro) mostrar = false;

                $item.toggle(mostrar);

                if (estaAgotado) return;

                const $chk = $item.find('.lote-checkbox');
                const $peso = $item.find('.lote-peso-parcial');
                const $estimadoHidden = $item.find('.peso_estimado_hidden');

                $peso.prop('disabled', !$chk.is(':checked'));

                if ($chk.is(':checked') && mostrar) {
                    const val = parseFloat($peso.val()) || 0;
                    totalPeso += val;

                    const estimado = val * (factores[tipoProceso] || 1);
                    $estimadoHidden.val(estimado.toFixed(2));
                    totalEstimado += estimado;

                    seleccionados++;
                }

                if (!mostrar) {
                    $chk.prop('checked', false);
                    $peso.prop('disabled', true).val('');
                    $estimadoHidden.val(0);
                }

                if (mostrar) visibles++;
            });

            $('#totalSeleccionado').text(totalPeso.toFixed(2));
            $('#totalLotes').text(seleccionados);
            $('#estimadoPeso').text(totalEstimado.toFixed(2));

            $('#btnSubmit').prop('disabled', !tipoProceso || seleccionados === 0);
        }

        // Actualizar progreso
        $(document).on('change', '.estado-select', function() {
            const $row = $(this).closest('tr');
            const estado = $(this).val();
            let progreso = 0;
            let color = '#6b7280';

            switch (estado) {
                case 'Pendiente':
                    progreso = 0;
                    color = '#6b7280';
                    break;
                case 'En Proceso':
                    progreso = 50;
                    color = '#f59e0b';
                    break;
                case 'Finalizado':
                    progreso = 100;
                    color = '#10b981';
                    break;
                case 'Cancelado':
                    progreso = 0;
                    color = '#ef4444';
                    break;
            }

            $row.find('.progress-bar-premium')
                .css({
                    width: progreso + '%',
                    backgroundColor: color
                });
            $row.find('.progress-premium').next('small').text(progreso + '%');
        });

        // Actualizar proceso individual
        $(document).on('click', '.btn-actualizar-individual', function() {
            const $btn = $(this);
            const procesoId = $btn.data('proceso-id');
            const estado = $(`select[name="estados[${procesoId}]"]`).val();
            const observaciones = $(`textarea[name="observaciones[${procesoId}]"]`).val();
            const pesoFinal = $(`input[name="pesos_finales[${procesoId}]"]`).val();

            if (estado === 'Finalizado' && (!pesoFinal || parseFloat(pesoFinal) <= 0)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Para finalizar el proceso, debe ingresar un peso final válido.',
                    confirmButtonColor: '#4a7c2f'
                });
                return;
            }

            $.ajax({
                url: '<?= site_url("procesos/update/") ?>' + procesoId,
                method: 'POST',
                data: {
                    estado_proceso: estado,
                    observacion_proceso: observaciones,
                    peso_final_kg: pesoFinal,
                    _method: 'PUT'
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="<?= csrf_token() ?>"]').val()
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizado',
                            text: 'El proceso se ha actualizado correctamente',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        actualizarTotalPesoFinal();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error || 'No se pudo actualizar el proceso'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de comunicación con el servidor'
                    });
                }
            });
        });

        function actualizarTotalPesoFinal() {
            let total = 0;
            $('.peso-final-input').each(function() {
                total += (parseFloat($(this).val()) || 0);
            });
            $('#total-peso-final').text(total.toFixed(2));
        }

        $(document).on('input', '.peso-final-input', actualizarTotalPesoFinal);

        $('#select_all').on('change', function() {
            $('.lote-item:visible .lote-checkbox').prop('checked', this.checked).trigger('change');
        });

        $(document).on('change', '.lote-checkbox', function() {
            const $input = $(this).closest('.lote-item').find('.lote-peso-parcial');
            $input.prop('disabled', !this.checked);

            if (this.checked && !$input.val()) {
                $input.val($input.data('max')).trigger('input');
            }
            if (!this.checked) $input.val('');

            actualizarVista();
        });

        $(document).on('input', '.lote-peso-parcial', function() {
            const $input = $(this);
            const max = parseFloat($input.data('max'));
            let val = parseFloat($input.val()) || 0;
            val = Math.min(Math.max(val, 0.01), max);
            $input.val(val.toFixed(2));
            actualizarVista();
        });

        $('#tipo_proceso, #filtro_tipo_entrada, #filtro_tipo_pimienta, #filtro_centro_acopio').on('change', actualizarVista);

        actualizarVista();
        actualizarTotalPesoFinal();
    });
</script>

<?= $this->endSection() ?>