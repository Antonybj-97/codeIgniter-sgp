<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0">
                <i class="bi bi-gear-fill text-success me-2"></i>Procesos de Pimienta
            </h2>
            <p class="text-muted small mb-0">Gestión y monitoreo de la producción en tiempo real.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="<?= site_url('procesos/crearMasivo') ?>" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Crear Masivo
            </a>
            <a href="<?= site_url('procesos/exportarPDF') ?>" class="btn btn-outline-danger shadow-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF General
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold">Total Peso Bruto</h6>
                    <h3 class="mb-0 fw-bold" id="kpiBruto">0.00 kg</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold">Total Estimado</h6>
                    <h3 class="mb-0 fw-bold text-info" id="kpiEstimado">0.00 kg</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold">Total Final</h6>
                    <h3 class="mb-0 fw-bold text-success" id="kpiFinal">0.00 kg</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0"> <div class="table-responsive">
                <table id="tablaProcesos" class="table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Centro Acopio</th>
                            <th>Estado</th>
                            <th style="width: 150px;">Progreso</th>
                            <th>Proveedor</th>
                            <th class="d-none d-md-table-cell">Peso Bruto</th>
                            <th class="d-none d-md-table-cell">Peso Final</th>
                            <th class="text-center pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {
    const csrfName = '<?= csrf_token() ?>';
    const csrfHash = '<?= csrf_hash() ?>';

    const tabla = $('#tablaProcesos').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('procesos/ajaxList') ?>",
            type: "POST",
            data: d => { d[csrfName] = csrfHash },
            dataSrc: json => json.data ?? []
        },
        columns: [
            { data: "id", className: "ps-3 fw-bold" },
            {
                data: "fecha_proceso",
                render: data => {
                    if (!data) return "-";
                    const fecha = new Date(data);
                    return `<span class="small">${fecha.toLocaleDateString('es-MX')}<br><span class="text-muted">${fecha.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' })}</span></span>`;
                }
            },
            { data: "tipo_proceso" },
            { data: "centro", defaultContent: '<span class="text-muted">-</span>' },
            {
                data: "estado_proceso",
                render: estado => {
                    const colores = {
                        'Pendiente': 'bg-warning text-dark',
                        'Iniciado': 'bg-info text-white',
                        'Finalizado': 'bg-success text-white'
                    };
                    return `<span class="badge rounded-pill ${colores[estado] ?? 'bg-secondary'}">${estado}</span>`;
                }
            },
            {
                data: null,
                render: row => {
                    const est = parseFloat(row.peso_estimado_kg) || 0;
                    const fin = parseFloat(row.peso_final_kg) || 0;
                    let pct = 0;

                    if (row.estado_proceso === 'Iniciado') pct = est > 0 ? (fin / est) * 100 : 50;
                    else if (row.estado_proceso === 'Finalizado') pct = 100;

                    pct = Math.max(0, Math.min(100, pct));
                    let color = pct >= 75 ? 'bg-success' : (pct >= 40 ? 'bg-info' : 'bg-warning');

                    return `
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1" style="height:8px;">
                                <div class="progress-bar ${color}" style="width:${pct}%;"></div>
                            </div>
                            <span class="ms-2 small fw-bold">${pct.toFixed(0)}%</span>
                        </div>`;
                }
            },
            { data: "proveedor", className: "small" },
            { 
                data: "peso_bruto_kg", 
                className: "d-none d-md-table-cell fw-bold",
                render: d => (parseFloat(d) || 0).toFixed(2) + " kg"
            },
            { 
                data: "peso_final_kg", 
                className: "d-none d-md-table-cell fw-bold text-success",
                render: d => (parseFloat(d) || 0).toFixed(2) + " kg"
            },
            {
                data: "id",
                className: "text-center pe-3",
                render: (id, _, row) => {
                    const loteID = row.lote_entrada_id ?? null;
                    const historialBtn = loteID
                        ? `<li><a class="dropdown-item" href="<?= site_url('procesos/historial') ?>/${loteID}"><i class="bi bi-clock-history me-2 text-primary"></i>Historial</a></li>`
                        : `<li><span class="dropdown-item text-muted small"><i class="bi bi-clock-history me-2"></i>Sin lote</span></li>`;

                    return `
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border shadow-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="<?= site_url('procesos/detalles') ?>/${id}"><i class="bi bi-eye me-2 text-info"></i>Ver Detalles</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('procesos/edit') ?>/${id}"><i class="bi bi-pencil me-2 text-warning"></i>Editar</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('procesos/pdf') ?>/${id}/ver" target="_blank"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>Ver PDF</a></li>
                                <li><hr class="dropdown-divider"></li>
                                ${historialBtn}
                                ${row.estado_proceso === 'Pendiente' ? `<li><a class="dropdown-item text-success fw-bold" href="<?= site_url('procesos/iniciar') ?>/${id}"><i class="bi bi-play-circle me-2"></i>Iniciar Proceso</a></li>` : ""}
                                ${row.estado_proceso === 'Iniciado' ? `<li><a class="dropdown-item text-danger fw-bold" href="<?= site_url('procesos/finalizar') ?>/${id}"><i class="bi bi-stop-circle me-2"></i>Finalizar</a></li>` : ""}
                            </ul>
                        </div>`;
                }
            }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json" },
        order: [[0, 'desc']],
        responsive: true,
        drawCallback: function () {
            const api = this.api();
            const num = x => parseFloat(x) || 0;

            const bruto = api.column(7, {search: 'applied'}).data().reduce((a, b) => num(a) + num(b), 0);
            const estimado = api.column(5, {search: 'applied'}).data().reduce((a, b) => num(a) + (parseFloat(b.peso_estimado_kg) || 0), 0);
            const final = api.column(8, {search: 'applied'}).data().reduce((a, b) => num(a) + num(b), 0);

            $('#kpiBruto').text(bruto.toLocaleString('es-MX', {minimumFractionDigits: 2}) + " kg");
            $('#kpiEstimado').text(estimado.toLocaleString('es-MX', {minimumFractionDigits: 2}) + " kg");
            $('#kpiFinal').text(final.toLocaleString('es-MX', {minimumFractionDigits: 2}) + " kg");
        }
    });
});
</script>

<style>
    /* Estilos adicionales para mejorar la apariencia */
    .table thead th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #6c757d; }
    .card { border-radius: 12px; }
    .btn { border-radius: 8px; }
    .progress { border-radius: 10px; background-color: #e9ecef; }
    .dropdown-item { padding: 0.5rem 1rem; font-size: 0.9rem; }
    .badge { padding: 0.5em 0.8em; }
</style>

<?= $this->endSection() ?>