<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Proceso Pimienta<?= $this->endSection() ?>
<?= $this->section('section_title') ?>Gestión de Procesos<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Lotes -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">Lotes</div>
                <div class="card-body p-0">
                    <table id="tablaLotes" class="table table-hover table-striped table-bordered mb-0"></table>
                </div>
            </div>
        </div>

        <!-- Procesos -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">Procesos en Curso</div>
                <div class="card-body p-0">
                    <table id="tablaProcesos" class="table table-hover table-striped table-bordered mb-0"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Detalles</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detallesContenido">Cargando...</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Iniciar Proceso -->
<div class="modal fade" id="modalProceso" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Iniciar Proceso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="procesoContenido"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btnIniciarProceso">Iniciar</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentLoteId = null;

// Tabla Lotes
const tablaLotes = $('#tablaLotes').DataTable({
    ajax: '<?= base_url('proceso/apiLotes') ?>',
    columns: [
        {title:'ID', data:'id', render:d=>`ENT-${d}`},
        {title:'Tipo Pimienta', data:'tipo_pimienta'},
        {title:'Peso (kg)', data:'peso_bruto_kg', render:d=>parseFloat(d).toFixed(2)},
        {title:'Estado', data:'estado', render:d=>`<span class="badge bg-${d=='Registrado'?'success':'warning'}">${d}</span>`},
        {title:'Acciones', data:null, orderable:false, render:(d,row)=>
            `<button class="btn btn-info btn-sm btn-detalles" data-id="${row.id}"><i class="fas fa-eye"></i></button>
             ${row.estado=='Registrado'?`<button class="btn btn-primary btn-sm btn-proceso" data-id="${row.id}"><i class="fas fa-play"></i></button>`:''}`
        }
    ],
    responsive:true,
    language:{url:"//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"},
    order:[[0,'desc']]
});

// Tabla Procesos
const tablaProcesos = $('#tablaProcesos').DataTable({
    ajax: '<?= base_url('proceso/apiProcesos') ?>',
    columns: [
        {title:'ID Lote', data:'lote_entrada_id', render:d=>`ENT-${d}`},
        {title:'Tipo Proceso', data:'tipo_proceso'},
        {title:'Responsable', data:'responsable'},
        {title:'Fecha Inicio', data:'fecha_inicio', render:d=>new Date(d).toLocaleString()},
        {title:'Estado', data:'estado', render:d=>`<span class="badge bg-${d=='Iniciado'?'primary':'secondary'}">${d}</span>`},
        {title:'Acciones', data:null, orderable:false, render:(d,row)=>
            `<button class="btn btn-info btn-sm btn-detalles-proceso" data-id="${row.id}"><i class="fas fa-eye"></i></button>
             ${row.estado=='Iniciado'?`<button class="btn btn-success btn-sm btn-finalizar" data-id="${row.id}"><i class="fas fa-check"></i></button>`:''}`
        }
    ],
    responsive:true,
    language:{url:"//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"},
    order:[[0,'desc']]
});

// Delegación de eventos
$('#tablaLotes tbody').on('click', '.btn-detalles', function(){
    const id = $(this).data('id');
    $('#detallesContenido').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
    new bootstrap.Modal(document.getElementById('modalDetalles')).show();
    fetch(`<?= base_url('proceso/apiLote') ?>/${id}`)
    .then(r=>r.json())
    .then(d=>{
        const lote=d.data;
        $('#detallesContenido').html(`
            <p><strong>ID:</strong> ENT-${lote.id}</p>
            <p><strong>Tipo Pimienta:</strong> ${lote.tipo_pimienta}</p>
            <p><strong>Peso:</strong> ${parseFloat(lote.peso_bruto_kg).toFixed(2)}</p>
            <p><strong>Estado:</strong> ${lote.estado}</p>
            <p><strong>Proveedor:</strong> ${lote.proveedor}</p>
            <p><strong>Observaciones:</strong> ${lote.observaciones || 'Sin observaciones'}</p>
        `);
    });
});

$('#tablaLotes tbody').on('click', '.btn-proceso', function(){
    currentLoteId = $(this).data('id');
    $('#procesoContenido').html(`
        <form id="formProceso">
            <div class="mb-3">
                <label>Tipo de Proceso</label>
                <select class="form-select" name="tipo_proceso" required>
                    <option value="">Seleccione</option>
                    <option value="secado">Secado</option>
                    <option value="limpieza">Limpieza</option>
                    <option value="clasificacion">Clasificación</option>
                    <option value="empaquetado">Empaquetado</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Responsable</label>
                <input class="form-control" name="responsable" required>
            </div>
        </form>
    `);
    new bootstrap.Modal(document.getElementById('modalProceso')).show();
});

$('#btnIniciarProceso').on('click', function(){
    const formData = Object.fromEntries(new FormData(document.getElementById('formProceso')).entries());
    formData.lote_id = currentLoteId;
    fetch('<?= base_url('proceso/iniciar') ?>',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify(formData)
    }).then(r=>r.json())
      .then(d=>{
        if(d.success){
            Swal.fire('¡Éxito!','Proceso iniciado','success');
            bootstrap.Modal.getInstance(document.getElementById('modalProceso')).hide();
            tablaLotes.ajax.reload(null,false);
            tablaProcesos.ajax.reload(null,false);
        }else Swal.fire('Error',d.message,'error');
    });
});

// Finalizar proceso
$('#tablaProcesos tbody').on('click', '.btn-finalizar', function(){
    const id = $(this).data('id');
    Swal.fire({
        title:'Finalizar proceso?',
        icon:'question',
        showCancelButton:true,
        confirmButtonText:'Sí, finalizar',
        cancelButtonText:'Cancelar'
    }).then(res=>{
        if(res.isConfirmed){
            fetch(`<?= base_url('proceso/finalizar') ?>/${id}`)
            .then(r=>r.json())
            .then(d=>{
                if(d.success){
                    Swal.fire('¡Éxito!','Proceso finalizado','success');
                    tablaLotes.ajax.reload(null,false);
                    tablaProcesos.ajax.reload(null,false);
                }else Swal.fire('Error',d.message,'error');
            });
        }
    });
});

// Actualización automática cada 10s
setInterval(()=>{ tablaLotes.ajax.reload(null,false); tablaProcesos.ajax.reload(null,false); },10000);
</script>
<?= $this->endSection() ?>
