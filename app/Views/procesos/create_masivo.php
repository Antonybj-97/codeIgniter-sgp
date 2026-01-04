<?= $this->extend('layouts/main') ?> 
<?= $this->section('content') ?>

<style>
body { background-color: #f8f9fa; }
.card-modern { border: none; border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.card-header-modern { background: linear-gradient(90deg, #007bff 0%, #0056b3 100%); color: white; text-align: center; padding: 1.5rem; }
.card-header-modern h2 { font-weight: 600; margin: 0; }
.table-modern { border-radius: .5rem; overflow: hidden; font-size: 0.9rem; }
.badge { font-size: 0.8rem; padding: 0.5em 0.8em; }
#lotesContainer { border: 1px solid #dee2e6; border-radius: .5rem; background: white; }
.lote-item { border-bottom: 1px solid #f1f1f1; padding: 0.6rem 0.4rem; transition: background-color .2s ease; }
.lote-item:hover { background-color: #f8f9fa; }
.lote-agotado { background-color: #f8f9fa; opacity: 0.7; }
.lote-agotado .form-check-input { display: none; }
</style>

<div class="container my-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-center">
            <h2 class="mb-0"><i class="bi bi-gear-fill me-2"></i>Iniciar Procesos Masivos</h2>
        </div>

        <div class="card-body">

            <!-- Alertas -->
            <?php foreach (['error', 'success', 'warning'] as $type): ?>
                <?php if (session()->getFlashdata($type)): ?>
                    <div class="alert alert-<?= $type ?> alert-dismissible fade show shadow-sm">
                        <?= session()->getFlashdata($type) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <form action="<?= site_url('procesos/iniciarMasivo') ?>" method="POST" id="formProcesosMasivos">
                <?= csrf_field() ?>

                <!-- Tipo de proceso -->
                <div class="mb-4">
                    <label for="tipo_proceso" class="form-label fw-bold">Tipo de Proceso</label>
                    <select name="tipo_proceso" id="tipo_proceso" class="form-select shadow-sm" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($tiposProceso as $tipo): ?>
                            <option value="<?= esc($tipo) ?>"><?= esc($tipo) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filtros -->
                <div class="row mb-4">

                    <div class="col-md-4">
                        <label class="form-label fw-bold" for="filtro_tipo_entrada">Tipo de Entrada</label>
                        <select id="filtro_tipo_entrada" class="form-select shadow-sm">
                            <option value="">Todos</option>
                            <?php foreach ($tiposEntrada as $te): ?>
                                <option value="<?= esc($te) ?>"><?= esc($te) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold" for="filtro_tipo_pimienta">Tipo de Pimienta</label>
                        <select id="filtro_tipo_pimienta" class="form-select shadow-sm">
                            <option value="">Todos</option>
                            <?php foreach ($tiposPimienta as $tp): ?>
                                <option value="<?= esc($tp) ?>"><?= esc($tp) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold" for="filtro_centro_acopio">Centro de Acopio</label>
                        <select id="filtro_centro_acopio" class="form-select shadow-sm">
                            <option value="">Todos</option>
                            <?php foreach ($centrosAcopio as $ca): ?>
                                <option value="<?= esc($ca) ?>"><?= esc($ca) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <!-- Lotes -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Seleccione Lotes</label>

                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="select_all">
                        <label for="select_all" class="form-check-label text-primary fw-bold">
                            Seleccionar todos los visibles
                        </label>
                    </div>

                    <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;" id="lotesContainer">

                        <?php foreach ($lotes as $l): ?>
                            <?php 
                                $id = $l['id']; 
                                $pesoDisponible = (float)$l['peso_disponible']; 
                                $pesoOriginal = (float)$l['peso_original']; 
                                $tipoEntrada = $l['tipo_entrada'];
                                $tipoPimienta = $l['tipo_pimienta'];
                                $centro = $l['centro_acopio'] ?? '';
                                $estaAgotado = $pesoDisponible <= 0;
                            ?>

                            <div class="form-check border-bottom py-2 lote-item <?= $estaAgotado ? 'lote-agotado' : '' ?>"
                                 data-tipo-entrada="<?= esc($tipoEntrada) ?>"
                                 data-tipo-pimienta="<?= esc($tipoPimienta) ?>"
                                 data-centro-acopio="<?= esc($centro) ?>"
                                 data-peso-disponible="<?= $pesoDisponible ?>">

                                <?php if (!$estaAgotado): ?>
                                    <input type="checkbox" 
                                           class="form-check-input lote-checkbox"
                                           name="lotes[]" 
                                           value="<?= $id ?>"
                                           id="lote_<?= $id ?>">
                                <?php endif; ?>

                                <label for="lote_<?= $id ?>" class="form-check-label d-block">
                                    <div>
                                        <strong>Lote #<?= esc($id) ?></strong> - <?= esc($l['proveedor']) ?>
                                        <?php if ($estaAgotado): ?>
                                            <span class="badge bg-danger ms-2">Agotado</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= esc($tipoEntrada) ?> | 
                                        Pimienta: <?= esc($tipoPimienta) ?> |
                                        Centro: <strong><?= esc($centro) ?></strong> |
                                        <span class="fw-bold text-primary">Peso original: <?= number_format($pesoOriginal, 2) ?> kg</span>
                                        <?php if (!$estaAgotado): ?>
                                            | <span class="fw-bold text-success">Disponible: <?= number_format($pesoDisponible, 2) ?> kg</span>
                                        <?php endif; ?>
                                    </small>
                                </label>

                                <?php if (!$estaAgotado): ?>
                                    <div class="mt-2">
                                        <label class="form-label small text-secondary">Peso a procesar (kg):</label>
                                        <input type="number"
                                               class="form-control form-control-sm lote-peso-parcial shadow-sm"
                                               name="peso_parcial[<?= $id ?>]"
                                               data-max="<?= $pesoDisponible ?>"
                                               min="0.01" max="<?= $pesoDisponible ?>" step="0.01"
                                               value="<?= number_format($pesoDisponible, 2) ?>"
                                               disabled>

                                        <input type="hidden"
                                               name="peso_estimado_final[<?= $id ?>]"
                                               class="peso_estimado_hidden"
                                               value="0">

                                        <div class="form-text">Máx: <?= number_format($pesoDisponible, 2) ?> kg</div>
                                    </div>
                                <?php else: ?>
                                    <input type="hidden"
                                           name="peso_parcial[<?= $id ?>]"
                                           value="0">
                                    <input type="hidden"
                                           name="peso_estimado_final[<?= $id ?>]"
                                           value="0">
                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>

                    </div>

                </div>

                <!-- Resumen -->
                <div class="alert alert-primary shadow-sm">
                    <strong>Total seleccionado:</strong> <span id="totalSeleccionado">0.00</span> kg
                    <span class="mx-2">|</span>
                    <strong>Lotes:</strong> <span id="totalLotes">0</span>
                </div>

                <div class="alert alert-warning d-none shadow-sm" id="estimadoContainer">
                    <strong>Peso estimado después del proceso:</strong> 
                    <span id="estimadoPeso">0.00</span> kg
                </div>

                <!-- Botones -->
                <div class="text-end mt-4">
                    <a href="<?= site_url('procesos') ?>" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success btn-lg shadow-sm" id="btnSubmit" disabled>
                        <i class="bi bi-play-fill me-1"></i>Iniciar Procesos Masivos
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(function () {

    const factores = { 'Desgranado': 0.9, 'Secado': 0.33333333333, 'Soplado': 0.98, 'Empaque': 1.0 };

    function actualizarVista() {

        const tipoProceso = $('#tipo_proceso').val();
        const filtroEntrada = $('#filtro_tipo_entrada').val();
        const filtroPimienta = $('#filtro_tipo_pimienta').val();
        const filtroCentro = $('#filtro_centro_acopio').val();

        let visibles = 0, seleccionados = 0, totalPeso = 0, totalEstimado = 0;

        $('.lote-item').each(function () {

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

            if (estaAgotado) return; // Saltar lotes agotados

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

        $('#estimadoContainer').toggleClass('d-none', totalEstimado === 0);

        $('#btnSubmit').prop('disabled', !tipoProceso || seleccionados === 0);
    }

    $('#select_all').on('change', function () {
        $('.lote-item:visible .lote-checkbox').prop('checked', this.checked).trigger('change');
    });

    $(document).on('change', '.lote-checkbox', function () {
        const $input = $(this).closest('.lote-item').find('.lote-peso-parcial');
        $input.prop('disabled', !this.checked);

        if (this.checked && !$input.val()) {
            $input.val($input.data('max')).trigger('input');
        }

        if (!this.checked) $input.val('');

        actualizarVista();
    });

    $(document).on('input', '.lote-peso-parcial', function () {
        const $input = $(this);
        const max = parseFloat($input.data('max'));
        let val = parseFloat($input.val()) || 0;

        val = Math.min(Math.max(val, 0.01), max);
        $input.val(val.toFixed(2));

        actualizarVista();
    });

    $('#tipo_proceso, #filtro_tipo_entrada, #filtro_tipo_pimienta, #filtro_centro_acopio').on('change', actualizarVista);

    actualizarVista();
});
</script>

<?= $this->endSection() ?>