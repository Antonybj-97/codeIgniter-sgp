<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Salida de Almacén<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-5">
            <h2 class="fw-bold m-0 text-dark">
                <i class="bi bi-box-arrow-right text-success me-2"></i>Salida de Almacén
            </h2>
            <p class="text-muted small mb-0">Gestión de despachos y logística de producto terminado.</p>
        </div>
        <div class="col-md-7 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2 flex-wrap">
            <div class="d-flex align-items-center bg-white border border-success-subtle rounded-3 px-3 shadow-sm me-2">
                <label class="small fw-bold text-success me-2 mb-0">COSECHA:</label>
                <select id="filterYear" class="form-select form-select-sm border-0 fw-bold" style="width:110px; cursor:pointer;">
                    <option value="all">Todos </option>
                    <?php for ($year = date('Y'); $year >= 2020; $year--): ?>
                        <option value="<?= $year ?>"><?= $year ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <a href="<?= site_url('lotes-salida/create') ?>" class="btn btn-orange shadow-sm text-white">
                <i class="bi bi-plus-lg me-1"></i> Nueva Salida
            </a>
            <a href="<?= site_url('lotes-salida/exportar/pdf') ?>"
               class="btn btn-outline-success shadow-sm"
               id="exportPDFBtn" target="_blank">
                <i class="bi bi-file-earmark-pdf me-1"></i> Reporte Anual
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Despachado</h6>
                            <h3 class="mb-0 fw-bold text-success" id="kpiTotalPeso">0.00 kg</h3>
                        </div>
                        <div class="bg-success-subtle p-3 rounded-circle">
                            <i class="bi bi-truck text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm border-start border-orange border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Movimientos</h6>
                            <h3 class="mb-0 fw-bold text-orange" id="kpiTotalSalidas">0</h3>
                        </div>
                        <div class="bg-orange-subtle p-3 rounded-circle">
                            <i class="bi bi-arrow-repeat text-orange fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= esc(session('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="lotesSalidaTable" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="ps-4">Folio</th>
                            <th>Cliente / Destino</th>
                            <th>Producto</th>
                            <th class="text-end">Cantidad</th>
                            <th>Docs Internos</th>
                            <th>Certificado</th>
                            <th>Fecha Embarque</th>
                            <th class="text-center pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Paleta de colores personalizada */
    :root {
        --primary-green: #1b4d3e;
        --accent-orange: #f59e0b;
        --light-green: #e8f5e9;
        --light-orange: #fff7ed;
    }

    .text-success { color: var(--primary-green) !important; }
    .text-orange { color: var(--accent-orange) !important; }
    .bg-success { background-color: var(--primary-green) !important; }
    .bg-success-subtle { background-color: var(--light-green) !important; }
    .bg-orange-subtle { background-color: var(--light-orange) !important; }
    .border-success { border-color: var(--primary-green) !important; }
    .border-orange { border-color: var(--accent-orange) !important; }

    .btn-orange { 
        background-color: var(--accent-orange); 
        border-color: var(--accent-orange);
        color: white;
    }
    .btn-orange:hover {
        background-color: #d97706;
        color: white;
    }

    .btn-outline-success {
        border-color: var(--primary-green);
        color: var(--primary-green);
    }
    .btn-outline-success:hover {
        background-color: var(--primary-green);
        color: white;
    }

    /* Estilo de Tabla */
    #lotesSalidaTable thead th {
        background-color: #f9fafb;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        color: #4b5563;
        padding: 15px 10px;
        border-bottom: 2px solid var(--light-green);
    }

    .badge-folio {
        background-color: var(--light-green);
        color: var(--primary-green);
        border: 1px solid var(--primary-green);
        padding: 0.5em 1em;
        font-weight: 800;
    }

    .card { border-radius: 15px; }
    .dropdown-menu { border-radius: 10px; border: 1px solid #eee; }
</style>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

<script>
    $(function () {
        const apiUrl = "<?= site_url('lotes-salida/api-salidas') ?>";

        function updateExportLink() {
            const year = $('#filterYear').val();
            const base = "<?= site_url('lotes-salida/exportar/pdf') ?>";
            $('#exportPDFBtn').attr('href', year !== 'all' ? base + '?anio=' + year : base);
        }

        const table = $('#lotesSalidaTable').DataTable({
            processing: true,
            ajax: {
                url: apiUrl,
                data: d => d.anio = $('#filterYear').val(),
                dataSrc: json => {
                    const totalKg = (json.data ?? []).reduce((acc, row) => acc + (parseFloat(row.cantidad) || 0), 0);
                    $('#kpiTotalPeso').text(totalKg.toLocaleString('es-MX', {minimumFractionDigits: 2}) + ' kg');
                    $('#kpiTotalSalidas').text(json.data?.length ?? 0);
                    return json?.data ?? [];
                }
            },
            columns: [
                {
                    data: 'folio_salida',
                    render: d => `<span class="badge rounded-pill badge-folio">${d}</span>`
                },
                { data: 'nombre_cliente', className: 'small fw-bold' },
                { data: 'producto', className: 'small' },
                {
                    data: 'cantidad',
                    className: 'text-end fw-bold text-dark',
                    render: d => d ? `${parseFloat(d).toLocaleString('es-MX', {minimumFractionDigits: 2})} kg` : '-'
                },
                {
                    data: null,
                    className: 'small',
                    render: row => `
                        <div class="mb-0 text-secondary">M: <span class="fw-bold text-dark">${row.no_maquila ?? '-'}</span></div>
                        <div class="text-secondary">F: <span class="fw-bold text-dark">${row.no_factura ?? '-'}</span></div>
                    `
                },
                { data: 'certificado', className: 'small' },
                {
                    data: 'fecha_embarque',
                    render: d => d ? `<span class="text-muted"><i class="bi bi-calendar3 me-1"></i> ${moment(d).format('DD/MM/YYYY')}</span>` : '-'
                },
                {
                    data: 'id_salida',
                    orderable: false,
                    className: 'text-center pe-4',
                    render: id => `
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border shadow-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu shadow border-0">
                                <li><a class="dropdown-item" href="<?= site_url('lotes-salida/show/') ?>${id}"><i class="bi bi-eye me-2 text-info"></i>Ver Detalles</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('lotes-salida/edit/') ?>${id}"><i class="bi bi-pencil me-2 text-warning"></i>Editar</a></li>
                                <li><a class="dropdown-item text-orange fw-bold" href="<?= site_url('lotes-salida/reportes/salida/pdf/') ?>${id}" target="_blank"><i class="bi bi-file-pdf me-2"></i>Descargar PDF</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item text-danger btn-delete" data-id="${id}"><i class="bi bi-trash me-2"></i>Eliminar</button></li>
                            </ul>
                        </div>`
                }
            ],
            order: [[6, 'desc']],
            language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json" },
            initComplete: updateExportLink
        });

        $('#filterYear').on('change', () => {
            table.ajax.reload();
            updateExportLink();
        });

        $(document).on('click', '.btn-delete', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar registro?',
                text: "Esta acción descontará o reintegrará stock según corresponda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(r => {
                if (r.isConfirmed) {
                    $.post("<?= site_url('lotes-salida/eliminar/') ?>" + id, {
                        "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                    }, () => {
                        table.ajax.reload();
                        Swal.fire('Actualizado', 'Registro eliminado correctamente.', 'success');
                    });
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>