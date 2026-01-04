document.addEventListener('DOMContentLoaded', () => {

    /* =========================
       UTILIDADES
    ========================= */
    const num = v => parseFloat(v) || 0;

    /* =========================
       ELEMENTOS
    ========================= */
    const tablaVerde = document.querySelector('#tabla-verde tbody');
    const tablaSeca  = document.querySelector('#tabla-seca tbody');

    const totalVerdeInput = document.getElementById('total-verde');
    const totalSecaInput  = document.getElementById('total-seca');
    const totalComisiones = document.getElementById('total-comisiones');
    const totalComFinal   = document.getElementById('total-comisiones-final');
    const diferenciaFinal = document.getElementById('diferencia-final');

    /* =========================
       TEMPLATES DE FILAS
    ========================= */
    function filaVerde() {
        return `
            <tr>
                <td><input type="text" name="proveedor_verde[]" class="form-control" required></td>
                <td><input type="number" name="kilos_verde[]" class="form-control kilos" min="0" step="0.01" required></td>
                <td><input type="number" name="precio_verde[]" class="form-control precio" min="0" step="0.01" required></td>
                <td><input type="number" class="form-control total" readonly></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger btn-remove">✕</button>
                </td>
            </tr>
        `;
    }

    function filaSeca() {
        return `
            <tr>
                <td><input type="text" name="proceso_seca[]" class="form-control" required></td>
                <td><input type="number" name="kilos_seca[]" class="form-control kilos" min="0" step="0.01" required></td>
                <td><input type="number" name="precio_seca[]" class="form-control precio" min="0" step="0.01" required></td>
                <td><input type="number" class="form-control total" readonly></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger btn-remove">✕</button>
                </td>
            </tr>
        `;
    }

    /* =========================
       AGREGAR FILAS
    ========================= */
    document.querySelectorAll('[data-add]').forEach(btn => {
        btn.addEventListener('click', () => {
            const tipo = btn.dataset.add;
            if (tipo === 'verde') tablaVerde.insertAdjacentHTML('beforeend', filaVerde());
            if (tipo === 'seca')  tablaSeca.insertAdjacentHTML('beforeend', filaSeca());
        });
    });

    /* =========================
       EVENTOS DE TABLAS
    ========================= */
    document.addEventListener('input', e => {
        if (e.target.classList.contains('kilos') || e.target.classList.contains('precio')) {
            recalcularFila(e.target.closest('tr'));
            recalcularTotales();
        }
    });

    document.addEventListener('click', e => {
        if (e.target.classList.contains('btn-remove')) {
            e.target.closest('tr').remove();
            recalcularTotales();
        }
    });

    /* =========================
       CÁLCULOS
    ========================= */
    function recalcularFila(tr) {
        const kilos  = num(tr.querySelector('.kilos')?.value);
        const precio = num(tr.querySelector('.precio')?.value);
        tr.querySelector('.total').value = (kilos * precio).toFixed(2);
    }

    function sumarTabla(tbody) {
        let total = 0;
        tbody.querySelectorAll('.total').forEach(i => {
            total += num(i.value);
        });
        return total;
    }

    function recalcularTotales() {
        const totalVerde = sumarTabla(tablaVerde);
        const totalSeca  = sumarTabla(tablaSeca);

        totalVerdeInput.value = totalVerde.toFixed(2);
        totalSecaInput.value  = totalSeca.toFixed(2);

        // Comisión ejemplo 2%
        const comisiones = totalVerde * 0.02;
        totalComisiones.value = comisiones.toFixed(2);
        totalComFinal.value   = comisiones.toFixed(2);

        // Diferencia
        diferenciaFinal.value = (totalSeca - totalVerde - comisiones).toFixed(2);
    }

});
