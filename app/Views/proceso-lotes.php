<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-3">Registro de Entrada</h2>

    <a href="<?= site_url('lotes-entrada/create') ?>" class="btn btn-primary mb-3">
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

<!-- Modal Proceso -->
<div class="modal fade" id="modalProceso" tabindex="-1" aria-labelledby="modalProcesoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formProceso">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProcesoLabel">Crear Proceso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="lote_id" id="lote_id">
                    
                    <div class="mb-3">
                        <label for="tipo_proceso" class="form-label">Tipo de Proceso</label>
                        <select class="form-select" id="tipo_proceso" name="tipo_proceso" required>
                            <option value="">Selecciona</option>
                            <option value="Secado">Secado</option>
                            <option value="Empacado">Empacado</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="peso_salida_kg" class="form-label">Peso Salida (kg)</label>
                        <input type="number" step="0.01" class="form-control" id="peso_salida_kg" name="peso_salida_kg" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar Proceso</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
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
// Función de depuración global
const debug = {
    log: function(mensaje, datos) {
        console.log(`[DEBUG] ${mensaje}`, datos || '');
    },
    error: function(mensaje, error) {
        console.error(`[ERROR] ${mensaje}`, error || '');
    },
    warn: function(mensaje, datos) {
        console.warn(`[ADVERTENCIA] ${mensaje}`, datos || '');
    },
    info: function(mensaje, datos) {
        console.info(`[INFO] ${mensaje}`, datos || '');
    }
};

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
                url: "<?= site_url('lote-entrada/delete') ?>/" + id,
                type: "POST",
                data: { <?= csrf_token() ?>: "<?= csrf_hash() ?>" },
                success: function() {
                    Swal.fire('Eliminado', 'El lote ha sido eliminado.', 'success');
                    $('#tablaLotes').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                console.error('Error en la solicitud:', status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar el lote. Intente nuevamente.',
                    confirmButtonText: 'Entendido'
                });
            }
            });
        }
    });
}

function abrirProceso(loteId) {
    $('#lote_id').val(loteId);
    $('#tipo_proceso').val('');
    $('#peso_salida_kg').val('');
    $('#observaciones').val('');
    $('#modalProceso').modal('show');
}

$(document).ready(function() {
    const tabla = $('#tablaLotes').DataTable({
        ajax: {
            url: "<?= site_url('lote-entrada/apiEntradas') ?>",
            dataSrc: function(json) {
                console.log('Datos recibidos:', json);
                if (json.error) {
                    console.error('Error en los datos:', json.error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de datos',
                        text: json.error || 'Error al procesar los datos',
                        confirmButtonText: 'Entendido'
                    });
                    return [];
                }
                return json.data || [];
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', status, error);
                console.error('Respuesta del servidor:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: '¡Oops!',
                    text: 'Error al cargar los datos: ' + error,
                    confirmButtonText: 'Entendido'
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
                        <a href="<?= site_url('lote-entrada/edit') ?>/${data}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="eliminarLote(${data})">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-success" onclick="abrirProceso(${data})">
                            <i class="bi bi-play-circle"></i> Proceso
                        </button>
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

    // Envío del formulario del modal
    $('#formProceso').submit(function(e){
        e.preventDefault();
        console.log('Enviando datos del proceso:', $(this).serialize());
        
        $.ajax({
            url: "<?= site_url('proceso-pimienta/store') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta exitosa:', response);
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Proceso creado correctamente.',
                    confirmButtonText: 'Aceptar'
                });
                $('#modalProceso').modal('hide');
                tabla.ajax.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', status, error);
                console.error('Respuesta del servidor:', xhr.responseText);
                
                let mensaje = 'No se pudo crear el proceso.';
                try {
                    const respuesta = JSON.parse(xhr.responseText);
                    if (respuesta.message) {
                        mensaje = respuesta.message;
                    }
                } catch (e) {
                    console.error('Error al parsear respuesta:', e);
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje,
                    confirmButtonText: 'Entendido'
                });
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
