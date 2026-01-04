<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cierre de Cuentas - Cooperativa Maseual Xicaulis S.C.L.</title>

<style>
:root{
  --primary:#2c5f2d; --secondary:#97bc62; --muted:#f0f0f0;
  --border:#c8c8c8; --danger:#e74c3c; --success:#27ae60; --text:#333;
}
*{box-sizing:border-box;margin:0;padding:0;font-family:Segoe UI, Tahoma, sans-serif}
body{background:#f5f5f5;padding:18px;color:var(--text);line-height:1.5}
.container{max-width:1100px;margin:0 auto;background:#fff;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,.08);overflow:hidden}
.header{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;text-align:center;padding:22px}
.header h1{font-size:20px;letter-spacing:.6px;margin-bottom:6px}
.header p{opacity:.95;margin:0;font-size:14px}

/* Form */
.form-container{padding:20px}
.form-section{background:#fafafa;border:1px solid var(--border);padding:14px;border-radius:6px;margin-bottom:16px}
.form-section h2{color:var(--primary);font-size:15px;margin-bottom:10px;border-bottom:2px solid var(--secondary);padding-bottom:6px}
.form-row{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:10px}
.form-column{flex:1;min-width:200px}
label{display:block;font-weight:600;color:var(--primary);margin-bottom:6px}
input[type="text"],input[type="number"],input[type="date"],select{width:100%;padding:8px;border:1px solid var(--border);border-radius:4px;font-size:14px}
input[readonly]{background:#eee}

/* tables */
table{width:100%;border-collapse:collapse;margin-top:8px}
th,td{border:1px solid var(--border);padding:8px;font-size:13px;vertical-align:middle}
th{background:var(--muted);color:var(--primary);font-weight:700}
.add-row-btn{background:var(--secondary);color:#fff;border:none;padding:6px 10px;border-radius:4px;cursor:pointer}
.remove-row-btn{background:var(--danger);color:#fff;border:none;padding:6px 8px;border-radius:4px;cursor:pointer}
.tfoot-total{font-weight:700;text-align:right}

/* actions */
.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:12px}
.btn{padding:10px 14px;border-radius:6px;border:none;font-weight:700;cursor:pointer}
.btn-primary{background:var(--primary);color:#fff}
.btn-secondary{background:#6c757d;color:#fff}
.btn-success{background:var(--success);color:#fff}

/* responsive */
@media(max-width:900px){.form-row{flex-direction:column}}
.small{font-size:13px}
.alert{padding:10px;border-radius:5px;margin-bottom:12px;border:1px solid #e0e0e0;background:#fff}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>COOPERATIVA MASEUAL XICAULIS S.C.L.</h1>
    <p>Cierre de Cuentas final con acopiadores de pimienta convencional — Cosecha 2025</p>
  </div>

  <div class="form-container">
    <div id="alert-message" class="alert hidden" style="display:none"></div>

    <form id="cierre-form" method="post" action="<?= site_url('cierre/guardar') ?>">

      <!-- I. Información general -->
      <div class="form-section">
        <h2>I. Información General</h2>
        <div class="form-row">
          <div class="form-column">
            <label>Centro</label>
            <select id="centro" name="centro" required>
              <option value="">Seleccione un centro</option>
              <?php if(isset($centros)): foreach($centros as $c): ?>
                <option value="<?= esc($c['id']) ?>"><?= esc($c['nombre']) ?></option>
              <?php endforeach; endif; ?>
            </select>
          </div>

          <div class="form-column">
            <label>Fecha</label>
            <input type="date" id="fecha" name="fecha" required>
          </div>

          <div class="form-column">
            <label>Acopiador</label>
            <input type="text" name="acopiador" required>
          </div>

          <div class="form-column">
            <label>Cosecha</label>
            <input type="text" name="cosecha" value="2025" required>
          </div>
        </div>
      </div>

      <!-- II. Resumen financiero -->
      <div class="form-section">
        <h2>II. Resumen Financiero</h2>

        <div class="form-row">
          <div class="form-column">
            <label>Dinero entregado</label>
            <input type="number" step="0.01" min="0" id="dinero-entregado" name="dinero_entregado" value="0.00" required>
          </div>

          <div class="form-column">
            <label>Otros cargos</label>
            <input type="number" step="0.01" min="0" id="otros-cargos" name="otros_cargos" value="0.00">
          </div>

          <div class="form-column">
            <label>Dinero comprobado</label>
            <input type="number" step="0.01" id="dinero-comprobado" name="dinero_comprobado" readonly value="0.00">
          </div>

          <div class="form-column">
            <label>Saldo del acopiador</label>
            <input type="number" step="0.01" id="saldo-acopiador" name="saldo_acopiador" readonly value="0.00">
          </div>
        </div>
      </div>

      <!-- III. Pimienta acopiada (por tipos) -->
      <div class="form-section">
        <h2>III. Pimienta Acopiada</h2>

        <?php
          $pimienta = ['con-rama'=>'Con Rama','verde'=>'Verde','seca'=>'Seca'];
          foreach($pimienta as $key => $label):
            $id = str_replace(' ','-',$key);
        ?>
        <h3 class="small"><?= $label ?></h3>
        <button type="button" class="add-row-btn" data-table="<?= $id ?>">+ Agregar Fila</button>

        <table id="tabla-<?= $id ?>">
          <thead>
            <tr><th>Precio</th><th>Kilos</th><th>Importe</th><th></th></tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="number" step="0.01" class="precio"></td>
              <td><input type="number" step="0.1" class="kilos"></td>
              <td><input type="number" step="0.01" class="importe" readonly value="0.00"></td>
              <td><button type="button" class="remove-row-btn">X</button></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2" class="tfoot-total">Total <?= $label ?></td>
              <td><input type="number" step="0.01" id="total-<?= $id ?>" readonly value="0.00"></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
        <br>
        <?php endforeach; ?>

      </div>

      <!-- III.b Pimienta acopiada verde y entregada seca (tabla compleja) -->
      <div class="form-section">
        <h2>III.b Pimienta acopiada verde y entregada seca</h2>
        <p class="small">Registra por grupos: precio de acopio, kilos acopiados, kilos entregados en verde, kilos de pimienta seca a pagar, precio a pagar, importe.</p>

        <button type="button" class="add-row-btn" id="add-conversion">+ Agregar fila (verde→seca)</button>
        <table id="tabla-conversion">
          <thead>
            <tr>
              <th>Precio acopio</th><th>Kilos acopiados</th><th>Kilos entregados en verde</th>
              <th>Kilos de pimienta seca a pagar</th><th>Precio a pagar</th><th>Importe</th><th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input class="precio-acopio" type="number" step="0.01"></td>
              <td><input class="kilos-acopiados" type="number" step="0.1"></td>
              <td><input class="kilos-entrega-verde" type="number" step="0.1"></td>
              <td><input class="kilos-seca-pagar" type="number" step="0.1"></td>
              <td><input class="precio-pagar" type="number" step="0.01"></td>
              <td><input class="importe-conv" type="number" step="0.01" readonly value="0.00"></td>
              <td><button type="button" class="remove-row-btn">X</button></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" class="tfoot-total">Sumas</td>
              <td><input type="number" step="0.01" id="total-conversion" readonly value="0.00"></td>
              <td></td>
            </tr>
          </tfoot>
        </table>

      </div>

      <!-- III.c Pimienta seca a precio de acopio -->
      <div class="form-section">
        <h2>III.c Pimienta seca a precio de acopio</h2>
        <button type="button" class="add-row-btn" id="add-seca">+ Agregar fila (seca)</button>
        <table id="tabla-seca">
          <thead>
            <tr><th>Precio</th><th>Kilos</th><th>Importe</th><th></th></tr>
          </thead>
          <tbody>
            <tr>
              <td><input class="precio-seca" type="number" step="0.01" value="95.00"></td>
              <td><input class="kilos-seca" type="number" step="0.1" value="0.0"></td>
              <td><input class="importe-seca" type="number" step="0.01" readonly value="0.00"></td>
              <td><button type="button" class="remove-row-btn">X</button></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2" class="tfoot-total">Sumas</td>
              <td><input type="number" step="0.01" id="total-seca" readonly value="0.00"></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- IV. Comisiones por pagar -->
      <div class="form-section">
        <h2>IV. Comisiones por pagar</h2>
        <p class="small">Define las comisiones (por kilo) y el sistema las aplicará a los kilos secos a pagar.</p>

        <table id="tabla-comisiones">
          <thead>
            <tr><th>Descripción</th><th>Kilos (base)</th><th>Comisión ($/kilo)</th><th>Importe</th><th></th></tr>
          </thead>
          <tbody>
            <!-- default rows (similar al PDF) -->
            <tr>
              <td>Comisión base (kilo pimienta seca entregada)</td>
              <td><input class="com-kilos" type="number" step="0.1" value="3518.0"></td>
              <td><input class="com-rate" type="number" step="0.01" value="1.00"></td>
              <td><input class="com-importe" type="number" readonly value="0.00"></td>
              <td><button type="button" class="remove-row-btn">X</button></td>
            </tr>
            <tr>
              <td>Comisión por rendimiento ≤ 2.80</td>
              <td><input class="com-kilos" type="number" step="0.1" value="3518.0"></td>
              <td><input class="com-rate" type="number" step="0.01" value="0.20"></td>
              <td><input class="com-importe" type="number" readonly value="0.00"></td>
              <td><button type="button" class="remove-row-btn">X</button></td>
            </tr>
            <tr>
              <td>Comisión por correcto manejo (saldo promedio -20%)</td>
              <td><input class="com-kilos" type="number" step="0.1" value="3518.0"></td>
              <td><input class="com-rate" type="number" step="0.01" value="0.20"></td>
              <td><input class="com-importe" type="number" readonly value="0.00"></td>
              <td><button type="button" class="remove-row-btn">X</button></td>
            </tr>
            <tr>
              <td>Comisión por cierre antes de fecha límite</td>
              <td><input class="com-kilos" type="number" step="0.1" value="3518.0"></td>
              <td><input class="com-rate" type="number" step="0.01" value="0.10"></td>
              <td><input class="com-importe" type="number" readonly value="0.00"></td>
              <td><button type="button" class="remove-row-btn">X</button></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" class="tfoot-total">Total Comisiones</td>
              <td><input type="number" step="0.01" id="total-comisiones" readonly value="0.00"></td>
              <td></td>
            </tr>
          </tfoot>
        </table>

      </div>

      <!-- V. Firmas -->
      <div class="form-section">
        <h2>V. Firmas</h2>
        <div class="form-row">
          <div class="form-column">
            <label>Elaboró</label><input type="text" name="firma_elaboro" required>
          </div>
          <div class="form-column">
            <label>Autorizó</label><input type="text" name="firma_autorizo" required>
          </div>
          <div class="form-column">
            <label>Acopiador</label><input type="text" name="firma_acopiador" required>
          </div>
        </div>
      </div>

      <!-- actions -->
      <div class="form-actions">
        <button type="button" id="btn-limpiar" class="btn btn-secondary">Limpiar</button>
        <button type="button" id="btn-previsualizar" class="btn btn-primary">Previsualizar</button>
        <button type="submit" id="btn-guardar" class="btn btn-success">Guardar</button>
      </div>

    </form>
  </div>
</div>

<script>
// DOM Ready
document.addEventListener('DOMContentLoaded',()=>{

  // Fecha por defecto hoy
  document.getElementById('fecha').value = new Date().toISOString().split('T')[0];

  // Helpers para añadir filas
  function addRowToTable(tableSelector, rowHtml){
    const tbody = document.querySelector(tableSelector + ' tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = rowHtml;
    tbody.appendChild(tr);
  }

  // Agregar filas para los tres tipos iniciales
  document.querySelectorAll('.add-row-btn[data-table]').forEach(btn=>{
    btn.addEventListener('click',()=>{
      const tableId = '#tabla-' + btn.dataset.table;
      const rowHtml = `<td><input type="number" step="0.01" class="precio"></td>
                       <td><input type="number" step="0.1" class="kilos"></td>
                       <td><input type="number" step="0.01" class="importe" readonly value="0.00"></td>
                       <td><button type="button" class="remove-row-btn">X</button></td>`;
      addRowToTable(tableId, rowHtml);
    });
  });

  // Agregar conversion (verde->seca)
  document.getElementById('add-conversion').addEventListener('click',()=>{
    const rowHtml = `<td><input class="precio-acopio" type="number" step="0.01"></td>
                     <td><input class="kilos-acopiados" type="number" step="0.1"></td>
                     <td><input class="kilos-entrega-verde" type="number" step="0.1"></td>
                     <td><input class="kilos-seca-pagar" type="number" step="0.1"></td>
                     <td><input class="precio-pagar" type="number" step="0.01"></td>
                     <td><input class="importe-conv" type="number" step="0.01" readonly value="0.00"></td>
                     <td><button type="button" class="remove-row-btn">X</button></td>`;
    addRowToTable('#tabla-conversion', rowHtml);
  });

  // Agregar seca rows
  document.getElementById('add-seca').addEventListener('click',()=>{
    const rowHtml = `<td><input class="precio-seca" type="number" step="0.01" value="95.00"></td>
                     <td><input class="kilos-seca" type="number" step="0.1" value="0.0"></td>
                     <td><input class="importe-seca" type="number" step="0.01" readonly value="0.00"></td>
                     <td><button type="button" class="remove-row-btn">X</button></td>`;
    addRowToTable('#tabla-seca', rowHtml);
  });

  // Delegación para eliminar filas y recalcular
  document.addEventListener('click', (e)=>{
    if(e.target.classList.contains('remove-row-btn')){
      const tr = e.target.closest('tr');
      const tbody = tr.parentElement;
      // Evitar eliminar última fila si quieres: permitiremos siempre
      tr.remove();
      calcularTodo();
    }
  });

  // Recalcular al cambiar input dentro de tablas
  document.addEventListener('input', (e)=>{
    // cualquiera de los inputs que afectan cálculos
    if(e.target.closest('table') || e.target.matches('#dinero-entregado') || e.target.matches('#otros-cargos')){
      // Recalcular importes de tablas
      // Tablas simples (con-rama, verde, seca)
      ['con-rama','verde','seca'].forEach(tipo=>{
        const tbody = document.querySelector(`#tabla-${tipo} tbody`);
        if(!tbody) return;
        let sum = 0;
        tbody.querySelectorAll('tr').forEach(row=>{
          const precio = parseFloat(row.querySelector('.precio')?.value) || 0;
          const kilos = parseFloat(row.querySelector('.kilos')?.value) || 0;
          const importeInput = row.querySelector('.importe');
          const val = precio * kilos;
          if(importeInput) importeInput.value = val.toFixed(2);
          sum += val;
        });
        const totalInput = document.getElementById('total-' + tipo.replace(/\s+/g,'-'));
        if(totalInput) totalInput.value = sum.toFixed(2);
      });

      // Conversion table (verde->seca)
      let totalConv = 0;
      document.querySelectorAll('#tabla-conversion tbody tr').forEach(row=>{
        const kilos_seca = parseFloat(row.querySelector('.kilos-seca-pagar')?.value)
                          || parseFloat(row.querySelector('.kilos-seca-pagar')?.value) || 0;
        // prefer explicit class kilos-seca-pagar (some rows may use .kilos-seca-pagar or .kilos-seca-pagar)
        const precio = parseFloat(row.querySelector('.precio-pagar')?.value) || 0;
        // fallback: if precio-pagar empty, use precio-acopio
        const precioEffective = precio || (parseFloat(row.querySelector('.precio-acopio')?.value) || 0);
        const importeInput = row.querySelector('.importe-conv');
        // if importe not present, compute from kilos-seca-pagar * precioEffective
        const kilosParaPagar = parseFloat(row.querySelector('.kilos-seca-pagar')?.value) || 0;
        const val = precioEffective * kilosParaPagar;
        if(importeInput) importeInput.value = val.toFixed(2);
        totalConv += val;
      });
      document.getElementById('total-conversion').value = totalConv.toFixed(2);

      // Tabla seca
      let totalSeca = 0;
      document.querySelectorAll('#tabla-seca tbody tr').forEach(row=>{
        const precio = parseFloat(row.querySelector('.precio-seca')?.value) || 0;
        const kilos = parseFloat(row.querySelector('.kilos-seca')?.value) || 0;
        const val = precio * kilos;
        row.querySelector('.importe-seca').value = val.toFixed(2);
        totalSeca += val;
      });
      document.getElementById('total-seca').value = totalSeca.toFixed(2);

      // Dinero comprobado = sum de importes (todos)
      const totConRama = parseFloat(document.getElementById('total-con-rama')?.value) || 0;
      const totVerde = parseFloat(document.getElementById('total-verde')?.value) || 0;
      const totSeca = parseFloat(document.getElementById('total-seca')?.value) || 0;
      // pero totConRama may not exist with that exact id if spaces; safer sum all .importe, .importe-conv, .importe-seca
      let dineroComprobado = 0;
      document.querySelectorAll('.importe, .importe-conv, .importe-seca').forEach(i=>{
        dineroComprobado += parseFloat(i.value) || 0;
      });
      document.getElementById('dinero-comprobado').value = dineroComprobado.toFixed(2);

      // Saldo = entregado - comprobado - otros
      const entregado = parseFloat(document.getElementById('dinero-entregado').value) || 0;
      const otros = parseFloat(document.getElementById('otros-cargos').value) || 0;
      document.getElementById('saldo-acopiador').value = (entregado - dineroComprobado - otros).toFixed(2);

      // Recalcular comisiones
      calcularComisiones();
    }
  });

  // función global de cálculo de comisiones
  function calcularComisiones(){
    let totalCom = 0;
    document.querySelectorAll('#tabla-comisiones tbody tr').forEach(row=>{
      const kilos = parseFloat(row.querySelector('.com-kilos')?.value) || 0;
      const rate = parseFloat(row.querySelector('.com-rate')?.value) || 0;
      const importe = kilos * rate;
      const out = row.querySelector('.com-importe');
      if(out) out.value = importe.toFixed(2);
      totalCom += importe;
    });
    document.getElementById('total-comisiones').value = totalCom.toFixed(2);
  }

  // Inicial: forzar primer cálculo
  calcularTodo();

  function calcularTodo(){ // function wrapper to trigger updates
    // trigger input event on form to run recalculos
    const evt = new Event('input', {bubbles:true});
    document.getElementById('cierre-form').dispatchEvent(evt);
  }

  // Limpiar formulario
  document.getElementById('btn-limpiar').addEventListener('click', ()=>{
    if(confirm('¿Deseas limpiar el formulario?')) location.reload();
  });

  // Previsualizar: abre una ventana con resumen básico (puedes reemplazar por modal o vista /pdf)
  document.getElementById('btn-previsualizar').addEventListener('click', ()=>{
    const popup = window.open('', '_blank', 'width=900,height=700,scrollbars=yes');
    const html = `<h2>Previsualización - Resumen</h2>
      <p><strong>Acopiador:</strong> ${document.querySelector('input[name="acopiador"]')?.value || ''}</p>
      <p><strong>Dinero entregado:</strong> ${document.getElementById('dinero-entregado').value}</p>
      <p><strong>Dinero comprobado:</strong> ${document.getElementById('dinero-comprobado').value}</p>
      <p><strong>Saldo:</strong> ${document.getElementById('saldo-acopiador').value}</p>
      <p><strong>Total comisiones:</strong> ${document.getElementById('total-comisiones').value}</p>
      <hr><p>Para generar PDF final, integra con tu controlador que mande estos datos a una vista /pdf y conviértela con DomPDF/TCPDF.</p>`;
    popup.document.write(html);
  });

  // submit: ejemplo simple (puedes hacer AJAX o normal)
  document.getElementById('cierre-form').addEventListener('submit', (e)=>{
    // aquí podrías validar más antes de enviar
    // por ahora dejamos enviar de forma normal
  });

});
</script>

</body>
</html>

<?= $this->endSection() ?>
