<?php if(isset($proceso) && !empty($proceso)): ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Nombre del Lote:</strong> <?= $proceso['nombre_lote'] ?>
        </div>
        <div class="col-md-6">
            <strong>Estado:</strong> <?= $proceso['estado'] ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Fecha de Proceso:</strong> <?= date('d/m/Y H:i', strtotime($proceso['fecha_proceso'])) ?>
        </div>
        <div class="col-md-6">
            <?php if(isset($proceso['responsable'])): ?>
            <strong>Responsable:</strong> <?= $proceso['responsable'] ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if(isset($proceso['tipo_entrada'])): ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Tipo de Entrada:</strong> <?= $proceso['tipo_entrada'] ?>
        </div>
        <div class="col-md-6">
            <strong>Tipo Pimienta:</strong> <?= $proceso['tipo_pimienta'] ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if(isset($proceso['peso'])): ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Peso (kg):</strong> <?= number_format($proceso['peso'], 2) ?>
        </div>
        <div class="col-md-6">
            <strong>Costo Total ($):</strong> <?= number_format($proceso['peso'] * $proceso['precio'], 2) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if(isset($proceso['observaciones']) && $proceso['observaciones'] != ''): ?>
    <div class="row mb-3">
        <div class="col-12">
            <strong>Observaciones:</strong> <?= $proceso['observaciones'] ?>
        </div>
    </div>
    <?php endif; ?>
<?php else: ?>
    <p>No se encontraron detalles para este proceso.</p>
<?php endif; ?>
