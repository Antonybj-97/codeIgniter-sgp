<?= $this->extend('layouts/main') ?> 
<?= $this->section('content') ?>

<style>
:root {
    --primary-color: #2c5f2d;
    --secondary-color: #97bc62;
    --accent-color: #f0f7ee;
    --border-color: #c8c8c8;
    --text-color: #333;
    --header-bg: #f8f9fa;
    --error-color: #e74c3c;
    --success-color: #27ae60;
}
* { box-sizing: border-box; margin:0; padding:0; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
body { background-color:#f5f5f5; padding:20px; line-height:1.6; color:var(--text-color);}
.container { max-width:1000px; margin:0 auto; background:white; box-shadow:0 0 20px rgba(0,0,0,0.1); border-radius:8px; overflow:hidden;}
.header { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color:white; padding:25px 20px; text-align:center; border-bottom:5px solid var(--secondary-color);}
.header h1 { font-size:28px; margin-bottom:10px;}
.header p { font-size:16px; opacity:0.9;}
.form-container { padding:30px;}
.form-section { margin-bottom:30px; padding:20px; border:1px solid var(--border-color); border-radius:5px; background-color: var(--header-bg);}
.form-section h2 { color:var(--primary-color); margin-bottom:20px; padding-bottom:10px; border-bottom:2px solid var(--secondary-color);}
.form-group { margin-bottom:20px;}
.form-row { display:flex; flex-wrap:wrap; gap:20px; margin-bottom:15px;}
.form-column { flex:1; min-width:200px;}
label { display:block; margin-bottom:8px; font-weight:600; color:var(--primary-color);}
input, select, textarea { width:100%; padding:12px; border:1px solid var(--border-color); border-radius:4px; font-size:16px; transition:border-color 0.3s;}
input:focus, select:focus, textarea:focus { outline:none; border-color:var(--secondary-color); box-shadow:0 0 0 2px rgba(151,188,98,0.2);}
.btn { padding:12px 24px; border:none; border-radius:4px; font-size:16px; font-weight:600; cursor:pointer; transition:all 0.3s;}
.btn-primary { background-color:var(--primary-color); color:white;}
.btn-primary:hover { background-color:#235023;}
.btn-secondary { background-color:#6c757d; color:white;}
.btn-secondary:hover { background-color:#545b62;}
.btn-success { background-color:var(--success-color); color:white;}
.btn-success:hover { background-color:#219653;}
.btn-pdf { background-color:#e74c3c; color:white;}
.btn-pdf:hover { background-color:#c0392b;}
.form-actions { display:flex; justify-content:space-between; margin-top:30px; padding-top:20px; border-top:1px solid var(--border-color);}
.table-container { overflow-x:auto; margin-top:15px;}
table { width:100%; border-collapse:collapse; margin-bottom:15px;}
th, td { border:1px solid var(--border-color); padding:10px; text-align:left;}
th { background-color:var(--header-bg); color:var(--primary-color); font-weight:600;}
.add-row-btn { background-color: var(--secondary-color); color:white; border:none; padding:8px 15px; border-radius:4px; cursor:pointer; margin-bottom:15px;}
.remove-row-btn { background-color: var(--error-color); color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;}
.alert { padding:15px; margin-bottom:20px; border-radius:4px;}
.alert-success { background-color:#d4edda; color:#155724; border:1px solid #c3e6cb;}
.alert-error { background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb;}
.hidden { display:none;}

.preview-container {
    background: white;
    padding: 20px;
    border: 2px solid var(--primary-color);
    border-radius: 8px;
    margin-top: 20px;
}

.preview-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 3px solid var(--primary-color);
}

.preview-section {
    margin-bottom: 25px;
    padding: 15px;
    border: 1px solid var(--border-color);
    border-radius: 5px;
}

.preview-section h3 {
    color: var(--primary-color);
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--secondary-color);
}

.preview-table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
}

.preview-table th, .preview-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.preview-table th {
    background-color: var(--header-bg);
    color: var(--primary-color);
    font-weight: bold;
}

.signature-section {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 2px solid var(--primary-color);
}

.signature-box {
    text-align: center;
    width: 30%;
}

.signature-line {
    border-bottom: 1px solid #000;
    margin: 40px 0 10px 0;
}

@media(max-width:768px){
    .form-row { flex-direction:column; gap:10px;}
    .form-actions { flex-direction:column; gap:10px;}
    .btn { width:100%;}
    .signature-section { flex-direction: column; }
    .signature-box { width: 100%; margin-bottom: 20px; }
}
</style>

<div class="container">
<div class="header">
<h1>COOPERATIVA MASEUAL XICAULIS S.C.L.</h1>
<p>Formulario de Cierre de Cuentas - Acopiadores de Pimienta</p>
</div>

<div class="form-container">
<div id="alert-message" class="alert hidden"></div>

<form id="cierre-cuenta-form" method="POST" action="<?= base_url('lotes-entrada/reportes/acopio_pdf') ?>" target="_blank">
<div class="form-section">
<h2>I. INFORMACIÓN GENERAL</h2>
<div class="form-row">
<div class="form-column">
<label for="centro">Centro</label>
<select id="centro" name="centro" required>
    <option value="">Seleccione un centro</option>
    <?php foreach($centros as $centro): ?>
        <option value="<?= $centro['id'] ?>"><?= $centro['nombre'] ?></option>
    <?php endforeach; ?>
</select>
</div>
<div class="form-column">
<label for="fecha">Fecha de Cierre</label>
<input type="date" id="fecha" name="fecha" required>
</div>
</div>

<div class="form-row">
<div class="form-column">
<label for="acopiador">Acopiador</label>
<input type="text" id="acopiador" name="acopiador" placeholder="Nombre completo del acopiador" required>
</div>
<div class="form-column">
<label for="cosecha">Cosecha</label>
<input type="text" id="cosecha" name="cosecha" value="2025" required>
</div>
</div>
</div>

<div class="form-section">
<h2>II. RESUMEN FINANCIERO</h2>
<div class="form-row">
<div class="form-column">
<label for="dinero-entregado">Dinero entregado por supervisor</label>
<input type="number" id="dinero-entregado" name="dinero_entregado" step="0.01" min="0" required>
</div>
<div class="form-column">
<label for="otros-cargos">Otros cargos (naylo, basura, etc.)</label>
<input type="number" id="otros-cargos" name="otros_cargos" step="0.01" min="0" value="0">
</div>
</div>

<div class="form-row">
<div class="form-column">
<label for="dinero-comprobado">Dinero comprobado en pimienta acopiada</label>
<input type="number" id="dinero-comprobado" name="dinero_comprobado" step="0.01" min="0" required>
</div>
</div>

<div class="form-row">
<div class="form-column">
<label for="saldo-acopiado">Saldo de dinero del acopiador</label>
<input type="number" id="saldo-acopiado" name="saldo_acopiado" step="0.01" readonly value="0.00">
</div>
</div>
</div>

<div class="form-section">
<h2>III. PIMIENTA ACOPIADA</h2>

<h3>Pimienta con Rama</h3>
<button type="button" class="add-row-btn" id="add-con-rama">+ Agregar Fila</button>
<div class="table-container">
<table id="tabla-con-rama">
<thead>
<tr>
<th>Precio ($)</th>
<th>Kilos</th>
<th>Importe ($)</th>
<th>Acción</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="number" name="precio_con_rama[]" step="0.01" min="0" class="input-precio"></td>
<td><input type="number" name="kilos_con_rama[]" step="0.1" min="0" class="input-kilos"></td>
<td><input type="number" name="importe_con_rama[]" step="0.01" min="0" readonly value="0.00" class="input-importe"></td>
<td><button type="button" class="remove-row-btn">Eliminar</button></td>
</tr>
</tbody>
<tfoot>
<tr>
<td colspan="2" style="text-align:right; font-weight:bold;">Total Con Rama:</td>
<td><input type="number" id="total-con-rama" name="total_con_rama" readonly value="0.00"></td>
<td></td>
</tr>
</tfoot>
</table>
</div>

<h3>Pimienta Verde</h3>
<button type="button" class="add-row-btn" id="add-verde">+ Agregar Fila</button>
<div class="table-container">
<table id="tabla-verde">
<thead>
<tr>
<th>Precio ($)</th>
<th>Kilos</th>
<th>Importe ($)</th>
<th>Acción</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="number" name="precio_verde[]" step="0.01" min="0" class="input-precio"></td>
<td><input type="number" name="kilos_verde[]" step="0.1" min="0" class="input-kilos"></td>
<td><input type="number" name="importe_verde[]" step="0.01" min="0" readonly value="0.00" class="input-importe"></td>
<td><button type="button" class="remove-row-btn">Eliminar</button></td>
</tr>
</tbody>
<tfoot>
<tr>
<td colspan="2" style="text-align:right; font-weight:bold;">Total Verde:</td>
<td><input type="number" id="total-verde" name="total_verde" readonly value="0.00"></td>
<td></td>
</tr>
</tfoot>
</table>
</div>

<h3>Pimienta Seca</h3>
<button type="button" class="add-row-btn" id="add-seca">+ Agregar Fila</button>
<div class="table-container">
<table id="tabla-seca">
<thead>
<tr>
<th>Precio ($)</th>
<th>Kilos</th>
<th>Importe ($)</th>
<th>Acción</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="number" name="precio_seca[]" step="0.01" min="0" class="input-precio"></td>
<td><input type="number" name="kilos_seca[]" step="0.1" min="0" class="input-kilos"></td>
<td><input type="number" name="importe_seca[]" step="0.01" min="0" readonly value="0.00" class="input-importe"></td>
<td><button type="button" class="remove-row-btn">Eliminar</button></td>
</tr>
</tbody>
<tfoot>
<tr>
<td colspan="2" style="text-align:right; font-weight:bold;">Total Seca:</td>
<td><input type="number" id="total-seca" name="total_seca" readonly value="0.00"></td>
<td></td>
</tr>
</tfoot>
</table>
</div>
</div>

<div class="form-section">
<h2>IV. COMISIONES POR PAGAR</h2>
<div class="table-container">
<table id="tabla-comisiones">
<thead>
<tr>
<th>CONCEPTO</th>
<th>KILOS</th>
<th>COMISIÓN POR KILO ($)</th>
<th>IMPORTE ($)</th>
</tr>
</thead>
<tbody>
<tr>
<td>Pimienta con Rama</td>
<td><input type="number" id="kilos-comision-con-rama" name="kilos_comision_con_rama" step="0.1" min="0" value="0" readonly></td>
<td><input type="number" id="comision-con-rama" name="comision_con_rama" step="0.01" min="0" value="0.50" class="comision-input"></td>
<td><input type="number" id="importe-comision-con-rama" name="importe_comision_con_rama" step="0.01" readonly value="0.00"></td>
</tr>
<tr>
<td>Pimienta Verde</td>
<td><input type="number" id="kilos-comision-verde" name="kilos_comision_verde" step="0.1" min="0" value="0" readonly></td>
<td><input type="number" id="comision-verde" name="comision_verde" step="0.01" min="0" value="0.00" class="comision-input"></td>
<td><input type="number" id="importe-comision-verde" name="importe_comision_verde" step="0.01" readonly value="0.00"></td>
</tr>
<tr>
<td>Pimienta Seca</td>
<td><input type="number" id="kilos-comision-seca" name="kilos_comision_seca" step="0.1" min="0" value="0" readonly></td>
<td><input type="number" id="comision-seca" name="comision_seca" step="0.01" min="0" value="0.30" class="comision-input"></td>
<td><input type="number" id="importe-comision-seca" name="importe_comision_seca" step="0.01" readonly value="0.00"></td>
</tr>
<tr>
<td>Comisión por beneficio</td>
<td><input type="number" id="kilos-comision-beneficio" name="kilos_comision_beneficio" step="0.1" min="0" value="0" readonly></td>
<td><input type="number" id="comision-beneficio" name="comision_beneficio" step="0.01" min="0" value="1.00" class="comision-input"></td>
<td><input type="number" id="importe-comision-beneficio" name="importe_comision_beneficio" step="0.01" readonly value="0.00"></td>
</tr>
<tr>
<td>Comisión por rendimiento bajo</td>
<td><input type="number" id="kilos-comision-rendimiento" name="kilos_comision_rendimiento" step="0.1" min="0" value="0" readonly></td>
<td><input type="number" id="comision-rendimiento" name="comision_rendimiento" step="0.01" min="0" value="0.20" class="comision-input"></td>
<td><input type="number" id="importe-comision-rendimiento" name="importe_comision_rendimiento" step="0.01" readonly value="0.00"></td>
</tr>
<tr>
<td>Comisión por cierre temprano</td>
<td><input type="number" id="kilos-comision-fecha" name="kilos_comision_fecha" step="0.1" min="0" value="0" readonly></td>
<td><input type="number" id="comision-fecha" name="comision_fecha" step="0.01" min="0" value="0.10" class="comision-input"></td>
<td><input type="number" id="importe-comision-fecha" name="importe_comision_fecha" step="0.01" readonly value="0.00"></td>
</tr>
</tbody>
<tfoot>
<tr>
<td colspan="3" style="text-align:right; font-weight:bold;">TOTAL COMISIONES:</td>
<td><input type="number" id="total-comisiones" name="total_comisiones" readonly value="0.00"></td>
</tr>
</tfoot>
</table>
</div>
</div>

<div class="form-section">
<h2>V. FIRMAS Y AUTORIZACIONES</h2>
<div class="form-row">
<div class="form-column">
<label for="elaboro">Elaborado por</label>
<input type="text" id="elaboro" name="elaboro" placeholder="Nombre completo" required>
</div>
<div class="form-column">
<label for="cargo-elaboro">Cargo</label>
<input type="text" id="cargo-elaboro" name="cargo_elaboro" value="Administración" required>
</div>
</div>

<div class="form-row">
<div class="form-column">
<label for="autorizo">Autorizado por</label>
<input type="text" id="autorizo" name="autorizo" placeholder="Nombre completo" required>
</div>
<div class="form-column">
<label for="cargo-autorizo">Cargo</label>
<input type="text" id="cargo-autorizo" name="cargo_autorizo" value="Supervisor" required>
</div>
</div>

<div class="form-row">
<div class="form-column">
<label for="conformidad">Conformidad del acopiador</label>
<input type="text" id="conformidad" name="conformidad" placeholder="Nombre completo" required>
</div>
</div>
</div>

<div class="form-actions">
<button type="button" class="btn btn-secondary" id="btn-limpiar">Limpiar Formulario</button>
<button type="button" class="btn btn-primary" id="btn-previsualizar">Previsualizar</button>
<button type="submit" class="btn btn-pdf" id="btn-generar-pdf">Generar PDF</button>
<button type="button" class="btn btn-success" id="btn-guardar">Guardar Cierre de Cuenta</button>
</div>
</form>

<div id="previsualizacion-pdf" class="preview-container hidden"></div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').value = today;

    // Inicializar cálculos
    actualizarTotales();
    actualizarComisiones();

    // Configurar event listeners para las tablas de pimienta
    const tables = {
        'add-con-rama':'tabla-con-rama',
        'add-verde':'tabla-verde',
        'add-seca':'tabla-seca'
    };

    function actualizarTotales() {
        const tipos = ['con-rama','verde','seca'];
        tipos.forEach(tipo => {
            const table = document.getElementById('tabla-' + tipo);
            const tbody = table.querySelector('tbody');
            let total = 0;
            
            tbody.querySelectorAll('tr').forEach(row => {
                const precioInput = row.querySelector('.input-precio');
                const kilosInput = row.querySelector('.input-kilos');
                const importeInput = row.querySelector('.input-importe');
                
                const precio = parseFloat(precioInput.value) || 0;
                const kilos = parseFloat(kilosInput.value) || 0;
                const importe = precio * kilos;
                
                importeInput.value = importe.toFixed(2);
                total += importe;
            });
            
            document.getElementById('total-' + tipo).value = total.toFixed(2);
        });
        
        actualizarSaldo();
        actualizarComisiones();
    }

    function addTableRow(tableId){
        const table = document.getElementById(tableId);
        const tbody = table.querySelector('tbody');
        const newRow = tbody.insertRow();
        
        newRow.innerHTML = `
            <td><input type="number" name="precio_${tableId.replace('tabla-','').replace('-','_')}[]" step="0.01" min="0" class="input-precio"></td>
            <td><input type="number" name="kilos_${tableId.replace('tabla-','').replace('-','_')}[]" step="0.1" min="0" class="input-kilos"></td>
            <td><input type="number" name="importe_${tableId.replace('tabla-','').replace('-','_')}[]" step="0.01" min="0" readonly value="0.00" class="input-importe"></td>
            <td><button type="button" class="remove-row-btn">Eliminar</button></td>
        `;
        
        // Agregar event listeners a los nuevos inputs
        const precioInput = newRow.querySelector('.input-precio');
        const kilosInput = newRow.querySelector('.input-kilos');
        
        precioInput.addEventListener('input', actualizarTotales);
        kilosInput.addEventListener('input', actualizarTotales);
    }

    // Configurar botones para agregar filas
    Object.keys(tables).forEach(btnId => {
        document.getElementById(btnId).addEventListener('click', () => {
            addTableRow(tables[btnId]);
        });
    });

    // Configurar event listeners para inputs existentes
    document.querySelectorAll('.input-precio, .input-kilos').forEach(input => {
        input.addEventListener('input', actualizarTotales);
    });

    // Configurar event listeners para comisiones
    document.querySelectorAll('.comision-input').forEach(input => {
        input.addEventListener('input', actualizarComisiones);
    });

    // Eliminar filas
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-row-btn')){
            const row = e.target.closest('tr');
            const tbody = row.parentElement;
            if(tbody.rows.length > 1) {
                row.remove();
                actualizarTotales();
            }
        }
    });

    function actualizarSaldo() {
        const dineroEntregado = parseFloat(document.getElementById('dinero-entregado').value) || 0;
        const dineroComprobado = parseFloat(document.getElementById('dinero-comprobado').value) || 0;
        const otrosCargos = parseFloat(document.getElementById('otros-cargos').value) || 0;
        const saldo = dineroEntregado - dineroComprobado - otrosCargos;
        document.getElementById('saldo-acopiado').value = saldo.toFixed(2);
    }

    function actualizarComisiones() {
        // Calcular kilos totales por tipo
        const kilosConRama = calcularKilosTotales('con-rama');
        const kilosVerde = calcularKilosTotales('verde');
        const kilosSeca = calcularKilosTotales('seca');
        const kilosTotales = kilosConRama + kilosVerde + kilosSeca;

        // Actualizar campos de kilos
        document.getElementById('kilos-comision-con-rama').value = kilosConRama.toFixed(1);
        document.getElementById('kilos-comision-verde').value = kilosVerde.toFixed(1);
        document.getElementById('kilos-comision-seca').value = kilosSeca.toFixed(1);
        document.getElementById('kilos-comision-beneficio').value = kilosTotales.toFixed(1);
        document.getElementById('kilos-comision-rendimiento').value = kilosTotales.toFixed(1);
        document.getElementById('kilos-comision-fecha').value = kilosTotales.toFixed(1);

        // Calcular importes de comisiones
        let totalComisiones = 0;
        
        const comisiones = [
            { id: 'con-rama', kilos: kilosConRama },
            { id: 'verde', kilos: kilosVerde },
            { id: 'seca', kilos: kilosSeca },
            { id: 'beneficio', kilos: kilosTotales },
            { id: 'rendimiento', kilos: kilosTotales },
            { id: 'fecha', kilos: kilosTotales }
        ];

        comisiones.forEach(comision => {
            const comisionPorKilo = parseFloat(document.getElementById(`comision-${comision.id.replace('-','-')}`).value) || 0;
            const importe = comision.kilos * comisionPorKilo;
            document.getElementById(`importe-comision-${comision.id.replace('-','-')}`).value = importe.toFixed(2);
            totalComisiones += importe;
        });

        document.getElementById('total-comisiones').value = totalComisiones.toFixed(2);
    }

    function calcularKilosTotales(tipo) {
        const table = document.getElementById(`tabla-${tipo}`);
        const tbody = table.querySelector('tbody');
        let totalKilos = 0;
        
        tbody.querySelectorAll('.input-kilos').forEach(input => {
            totalKilos += parseFloat(input.value) || 0;
        });
        
        return totalKilos;
    }

    // Event listeners para campos financieros
    ['dinero-entregado','dinero-comprobado','otros-cargos'].forEach(id => {
        document.getElementById(id).addEventListener('input', actualizarSaldo);
    });

    // Botón limpiar
    document.getElementById('btn-limpiar').addEventListener('click', function(){
        if(confirm('¿Está seguro de que desea limpiar todo el formulario?')){
            document.getElementById('cierre-cuenta-form').reset();
            document.getElementById('fecha').value = today;
            
            // Limpiar tablas dinámicas
            ['tabla-con-rama','tabla-verde','tabla-seca'].forEach(tableId => {
                const table = document.getElementById(tableId);
                const tbody = table.querySelector('tbody');
                // Mantener solo la primera fila y limpiar sus valores
                while(tbody.rows.length > 1) {
                    tbody.deleteRow(1);
                }
                // Limpiar primera fila
                tbody.querySelectorAll('input').forEach(input => {
                    if(input.readOnly) {
                        input.value = '0.00';
                    } else {
                        input.value = '';
                    }
                });
            });
            
            showAlert('Formulario limpiado correctamente','success');
            actualizarSaldo();
            actualizarTotales();
            actualizarComisiones();
        }
    });

    // Botón previsualizar
    document.getElementById('btn-previsualizar').addEventListener('click', function(){
        if(validateForm()){
            generarPrevisualizacion();
            document.getElementById('previsualizacion-pdf').classList.remove('hidden');
            // Scroll a la previsualización
            document.getElementById('previsualizacion-pdf').scrollIntoView({ behavior: 'smooth' });
            showAlert('Previsualización generada correctamente.','success');
        }
    });

    // Botón guardar
    document.getElementById('btn-guardar').addEventListener('click', function(e){
        e.preventDefault();
        if(validateForm()){
            showAlert('Procesando cierre de cuenta...','success');
            // Aquí iría la lógica para enviar los datos al servidor
            setTimeout(() => {
                showAlert('Cierre de cuenta guardado correctamente.','success');
            }, 1500);
        }
    });

    // Función de previsualización
    function generarPrevisualizacion() {
        const form = document.getElementById('cierre-cuenta-form');
        
        let html = `
        <div class="preview-header">
            <h1>COOPERATIVA MASEUAL XICAULIS S.C.L.</h1>
            <h2>Cierre de Cuenta - Acopiador de Pimienta</h2>
            <p>Documento generado el: ${new Date().toLocaleDateString('es-ES')}</p>
        </div>`;
        
        // Información General
        html += '<div class="preview-section">';
        html += '<h3>I. INFORMACIÓN GENERAL</h3>';
        html += '<table class="preview-table">';
        html += `<tr><td><strong>Centro:</strong></td><td>${document.getElementById('centro').options[document.getElementById('centro').selectedIndex].text}</td></tr>`;
        html += `<tr><td><strong>Fecha de Cierre:</strong></td><td>${document.getElementById('fecha').value}</td></tr>`;
        html += `<tr><td><strong>Acopiador:</strong></td><td>${document.getElementById('acopiador').value}</td></tr>`;
        html += `<tr><td><strong>Cosecha:</strong></td><td>${document.getElementById('cosecha').value}</td></tr>`;
        html += '</table></div>';

        // Resumen Financiero
        html += '<div class="preview-section">';
        html += '<h3>II. RESUMEN FINANCIERO</h3>';
        html += '<table class="preview-table">';
        html += `<tr><td><strong>Dinero entregado por supervisor:</strong></td><td>$${parseFloat(document.getElementById('dinero-entregado').value || 0).toFixed(2)}</td></tr>`;
        html += `<tr><td><strong>Otros cargos:</strong></td><td>$${parseFloat(document.getElementById('otros-cargos').value || 0).toFixed(2)}</td></tr>`;
        html += `<tr><td><strong>Dinero comprobado en pimienta acopiada:</strong></td><td>$${parseFloat(document.getElementById('dinero-comprobado').value || 0).toFixed(2)}</td></tr>`;
        html += `<tr style="background-color: #f0f7ee;"><td><strong>Saldo de dinero del acopiador:</strong></td><td><strong>$${parseFloat(document.getElementById('saldo-acopiado').value || 0).toFixed(2)}</strong></td></tr>`;
        html += '</table></div>';

        // Pimienta Acopiada
        const tipos = [
            {key: 'con-rama', label: 'Pimienta con Rama'},
            {key: 'verde', label: 'Pimienta Verde'}, 
            {key: 'seca', label: 'Pimienta Seca'}
        ];

        tipos.forEach(tipo => {
            const table = document.getElementById(`tabla-${tipo.key}`);
            const rows = table.querySelectorAll('tbody tr');
            let tieneDatos = false;

            // Verificar si hay datos
            rows.forEach(row => {
                const precio = row.querySelector('.input-precio').value;
                const kilos = row.querySelector('.input-kilos').value;
                if (precio && kilos && parseFloat(precio) > 0 && parseFloat(kilos) > 0) {
                    tieneDatos = true;
                }
            });

            if (tieneDatos) {
                html += `<div class="preview-section">`;
                html += `<h3>III. ${tipo.label.toUpperCase()}</h3>`;
                html += `<table class="preview-table">`;
                html += `<thead><tr><th>Precio ($/kg)</th><th>Kilos (kg)</th><th>Importe ($)</th></tr></thead>`;
                html += `<tbody>`;
                
                let totalKilos = 0;
                let totalImporte = 0;
                
                rows.forEach(row => {
                    const precio = parseFloat(row.querySelector('.input-precio').value) || 0;
                    const kilos = parseFloat(row.querySelector('.input-kilos').value) || 0;
                    const importe = parseFloat(row.querySelector('.input-importe').value) || 0;
                    
                    if (precio > 0 && kilos > 0) {
                        html += `<tr>`;
                        html += `<td>$${precio.toFixed(2)}</td>`;
                        html += `<td>${kilos.toFixed(1)}</td>`;
                        html += `<td>$${importe.toFixed(2)}</td>`;
                        html += `</tr>`;
                        
                        totalKilos += kilos;
                        totalImporte += importe;
                    }
                });
                
                html += `</tbody>`;
                html += `<tfoot><tr style="background-color: #f8f9fa;">`;
                html += `<td><strong>Total:</strong></td>`;
                html += `<td><strong>${totalKilos.toFixed(1)} kg</strong></td>`;
                html += `<td><strong>$${totalImporte.toFixed(2)}</strong></td>`;
                html += `</tr></tfoot>`;
                html += `</table></div>`;
            }
        });

        // Comisiones
        html += '<div class="preview-section">';
        html += '<h3>IV. COMISIONES POR PAGAR</h3>';
        html += '<table class="preview-table">';
        html += '<thead><tr><th>CONCEPTO</th><th>KILOS</th><th>COMISIÓN POR KILO ($)</th><th>IMPORTE ($)</th></tr></thead>';
        html += '<tbody>';
        
        const conceptosComisiones = [
            { id: 'con-rama', concepto: 'Pimienta con Rama' },
            { id: 'verde', concepto: 'Pimienta Verde' },
            { id: 'seca', concepto: 'Pimienta Seca' },
            { id: 'beneficio', concepto: 'Comisión por beneficio' },
            { id: 'rendimiento', concepto: 'Comisión por rendimiento bajo' },
            { id: 'fecha', concepto: 'Comisión por cierre temprano' }
        ];

        conceptosComisiones.forEach(item => {
            const kilos = parseFloat(document.getElementById(`kilos-comision-${item.id}`).value) || 0;
            const comision = parseFloat(document.getElementById(`comision-${item.id}`).value) || 0;
            const importe = parseFloat(document.getElementById(`importe-comision-${item.id}`).value) || 0;
            
            html += `<tr>`;
            html += `<td>${item.concepto}</td>`;
            html += `<td>${kilos.toFixed(1)}</td>`;
            html += `<td>$${comision.toFixed(2)}</td>`;
            html += `<td>$${importe.toFixed(2)}</td>`;
            html += `</tr>`;
        });
        
        html += '</tbody>';
        html += `<tfoot><tr style="background-color: #f8f9fa;">`;
        html += `<td colspan="3"><strong>TOTAL COMISIONES:</strong></td>`;
        html += `<td><strong>$${document.getElementById('total-comisiones').value}</strong></td>`;
        html += `</tr></tfoot>`;
        html += '</table></div>';

        // Firmas
        html += '<div class="preview-section">';
        html += '<h3>V. FIRMAS Y AUTORIZACIONES</h3>';
        html += '<div class="signature-section">';
        html += '<div class="signature-box">';
        html += '<p>Elaborado por:</p>';
        html += '<div class="signature-line"></div>';
        html += `<p>${document.getElementById('elaboro').value}</p>`;
        html += `<p>${document.getElementById('cargo-elaboro').value}</p>`;
        html += '</div>';
        html += '<div class="signature-box">';
        html += '<p>Autorizado por:</p>';
        html += '<div class="signature-line"></div>';
        html += `<p>${document.getElementById('autorizo').value}</p>`;
        html += `<p>${document.getElementById('cargo-autorizo').value}</p>`;
        html += '</div>';
        html += '<div class="signature-box">';
        html += '<p>Conformidad del acopiador:</p>';
        html += '<div class="signature-line"></div>';
        html += `<p>${document.getElementById('conformidad').value}</p>`;
        html += '</div>';
        html += '</div></div>';

        document.getElementById('previsualizacion-pdf').innerHTML = html;
    }

    function validateForm(){
        const required = document.querySelectorAll('input[required], select[required]');
        let valid = true;
        
        required.forEach(field => {
            if(!field.value.trim()){ 
                field.style.borderColor = 'var(--error-color)'; 
                valid = false;
            } else {
                field.style.borderColor = ''; 
            }
        });
        
        if(!valid) {
            showAlert('Por favor, complete todos los campos obligatorios.','error');
        }
        return valid;
    }

    function showAlert(message, type){
        const alertDiv = document.getElementById('alert-message');
        alertDiv.textContent = message;
        alertDiv.className = `alert alert-${type}`;
        alertDiv.classList.remove('hidden');
        setTimeout(() => alertDiv.classList.add('hidden'), 5000);
    }
});
</script>

<?= $this->endSection() ?>