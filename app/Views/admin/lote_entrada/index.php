<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-3">Registro de Entrada</h2>

    <a href="<?= base_url('lotes-entrada/create') ?>" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Registrar Lote
    </a>

    <div class="table-responsive">
        <table id="tablaLotes" class="table table-striped table-bordered">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Centro</th>
                <th>Tipo Pimienta</th>
                <th>Tipo Entrada</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Peso (kg)</th>
                <th>Precio ($/kg)</th>
                <th>Costo Total ($)</th>
                <th>Observaciones</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- jQuery y DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function eliminarLote(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esta acción",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= base_url('lote-entrada/delete') ?>/" + id,
                type: "POST",
                data: { <?= csrf_token() ?>: "<?= csrf_hash() ?>" },
                success: function() {
                    Swal.fire('Eliminado', 'El lote ha sido eliminado.', 'success');
                    $('#tablaLotes').DataTable().ajax.reload();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo eliminar el lote.', 'error');
                }
            });
        }
    });
}

$(document).ready(function() {
    $('#tablaLotes').DataTable({
        ajax: {
            url: "<?= base_url('lote-entrada/apiEntradas') ?>",
            dataSrc: 'data',
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Oops!',
                    text: 'Error al cargar los datos',
                });
            }
        },
        columns: [
            { data: "id" },
            { data: "centro" },
            { data: "tipo_pimienta" },
            { data: "tipo_entrada" },
            { data: "usuario" },
            { data: "fecha_entrada" },
            { data: "proveedor" },
            { data: "peso_bruto_kg" },
            { data: "precio_compra" },
            { data: "costo_total" },
            { data: "observaciones" },
            { data: "estado" },
            {
                data: "id",
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <a href="<?= base_url('lote-entrada/edit') ?>/${data}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="eliminarLote(${data})">
                            <i class="bi bi-trash"></i>
                        </button>
                        <a href="<?= base_url('proceso-pimienta/create') ?>/${data}" class="btn btn-sm btn-success">
                            <i class="bi bi-play-circle"></i> Iniciar Proceso
                        </a>
                    `;
                }
            }
        ],
        order: [[5, 'desc']],
        pageLength: 10,
        responsive: true,
        language: {
            url: "<?= base_url('js/es-ES.json') ?>"
        }
    });
});
</script>

<?= $this->endSection() ?>
