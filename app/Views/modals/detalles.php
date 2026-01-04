<div class="container py-2">
    <h5 class="mb-3 text-center">Detalle del Proceso</h5>

    <div class="row">
        <div class="col-md-6 mb-2">
            <div class="p-3 border rounded shadow-sm bg-light">
                <strong>ID del Proceso:</strong>
                <p><?= esc($proceso['id']) ?></p>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="p-3 border rounded shadow-sm bg-light">
                <strong>Lote:</strong>
                <p><?= esc($proceso['nombre_lote']) ?></p>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="p-3 border rounded shadow-sm bg-light">
                <strong>Estado:</strong>
                <p>
                    <?php if($proceso['estado'] == 'En proceso'): ?>
                        <span class="badge bg-primary"><?= esc($proceso['estado']) ?></span>
                    <?php elseif($proceso['estado'] == 'Finalizado'): ?>
                        <span class="badge bg-secondary"><?= esc($proceso['estado']) ?></span>
                    <?php else: ?>
                        <span class="badge bg-success"><?= esc($proceso['estado']) ?></span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="p-3 border rounded shadow-sm bg-light">
                <strong>Fecha del Proceso:</strong>
                <p><?= esc($proceso['fecha_proceso']) ?></p>
            </div>
        </div>
    </div>
</div>
