<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<table id="tablaEntradas" class="table table-striped table-bordered">
    <thead style="background: linear-gradient(90deg,#28a745,#fd7e14); color:white;">
        <tr>
            <th>ID</th>
            <th>Tipo Pimienta</th>
            <th>Centro</th>
            <th>Tipo Entrada</th>
            <th>Usuario</th>
            <th>Proveedor</th>
            <th>Peso Bruto (kg)</th>
            <th>Peso Vendido (kg)</th>
            <th>Peso Restante (kg)</th>
            <th>Precio Compra</th>
            <th>Costo Total</th>
            <th>Fecha Entrada</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($entradas as $e): ?>
        <tr>
            <td><?= esc($e['id']) ?></td>
            <td><?= esc($e['tipo_pimienta']) ?></td>
            <td><?= esc($e['centro']) ?></td>
            <td><?= esc($e['tipo_entrada']) ?></td>
            <td><?= esc($e['usuario']) ?></td>
            <td><?= esc($e['proveedor']) ?></td>
            <td><?= esc($e['peso_bruto_kg']) ?></td>
            <td><?= esc($e['peso_vendido']) ?></td>
            <td><?= esc($e['peso_restante']) ?></td>
            <td><?= esc(number_format($e['precio_compra'], 2)) ?></td>
            <td><?= esc(number_format($e['costo_total'], 2)) ?></td>
            <td><?= esc($e['fecha_entrada']) ?></td>
            <td><?= esc($e['observaciones']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaEntradas').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', text: '📊 Exportar a Excel', className: 'btn btn-success' },
            { extend: 'pdfHtml5', text: '📄 Exportar a PDF', className: 'btn btn-danger', orientation: 'landscape', pageSize: 'A4' },
            { extend: 'print', text: '🖨️ Imprimir', className: 'btn btn-info' }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>
