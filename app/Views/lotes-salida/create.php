<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Crear Nota de Salida
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-lg">

        <div class="card-header bg-primary text-white">
            <h4 class="card-title mb-0">
                <i class="bi bi-receipt"></i> Crear Nota de Salida de Almacén
            </h4>
        </div>

        <div class="card-body">

            <!-- Alertas de error general -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Errores de validación -->
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i>
                    <ul class="mb-0">
                        <?php foreach ($validation->getErrors() as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('lotes-salida/store') ?>" method="POST" id="salida-form">
                <?= csrf_field() ?>

                <!-- ENCABEZADO -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha de embarque *</label>
                        <input type="date" 
                               class="form-control" 
                               name="fecha_embarque" 
                               value="<?= esc(old('fecha_embarque', $ship_date ?? '')) ?>" 
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Cliente *</label>
                        <input type="text" 
                               class="form-control" 
                               name="nombre_cliente" 
                               placeholder="Ej: Cabesi Internacional S.A. de C.V."
                               value="<?= esc(old('nombre_cliente')) ?>"
                               required
                               maxlength="100">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Folio *</label>
                        <input type="text" 
                               class="form-control" 
                               name="folio_salida"
                               value="<?= esc(old('folio_salida', $folio ?? '')) ?>"
                               placeholder="Ej: 0001"
                               required
                               maxlength="10">
                    </div>
                </div>

                <!-- DETALLES DEL PRODUCTO -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Detalles del Producto</h5>

                <div class="row mb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Producto *</label>
                        <input type="text" 
                               class="form-control" 
                               name="producto"
                               value="<?= esc(old('producto', $product_name ?? '')) ?>" 
                               required
                               maxlength="150"
                               placeholder="Ej: Pimienta gorda convencional">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Tipo de Producto *</label>
                        <select class="form-select" name="tipo_producto" required>
                            <option value="">Seleccionar tipo</option>
                            <?php foreach ($tipos_producto as $tipo): ?>
                                <option value="<?= esc($tipo) ?>" 
                                    <?= (old('tipo_producto', $product_type ?? '') === $tipo) ? 'selected' : '' ?>>
                                    <?= esc($tipo) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Unidad *</label>
                        <select class="form-select" name="unidad" required>
                            <option value="">Seleccionar</option>
                            <?php foreach ($unidades as $unidad): ?>
                                <option value="<?= esc($unidad) ?>" 
                                    <?= (old('unidad', $unit ?? '') === $unidad) ? 'selected' : '' ?>>
                                    <?= esc($unidad) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Cantidad *</label>
                        <input type="number" 
                               class="form-control" 
                               name="cantidad" 
                               min="0.01"
                               step="0.01"
                               value="<?= esc(old('cantidad')) ?>"
                               required
                               placeholder="Ej: 1000">
                    </div>
                </div>

                <!-- INFORMACIÓN ADICIONAL -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Información Adicional</h5>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">No. de Maquila</label>
                        <input type="text" 
                               class="form-control" 
                               name="no_maquila"
                               value="<?= esc(old('no_maquila')) ?>"
                               placeholder="Opcional"
                               maxlength="50">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">No. de Factura</label>
                        <input type="text" 
                               class="form-control" 
                               name="no_factura"
                               value="<?= esc(old('no_factura')) ?>"
                               placeholder="Opcional"
                               maxlength="50">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Certificado</label>
                        <input type="text" 
                               class="form-control" 
                               name="certificado"
                               value="<?= esc(old('certificado')) ?>"
                               placeholder="Opcional"
                               maxlength="50">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Clave del Lote</label>
                        <input type="text" 
                               class="form-control"
                               name="clave_lote"
                               value="<?= esc(old('clave_lote')) ?>"
                               placeholder="Ej: LOTE-2023-XYZ"
                               maxlength="50">
                    </div>
                </div>

                <!-- DATOS DEL TRANSPORTE -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Datos del Transporte</h5>
                <div class="mb-4">
                    <textarea class="form-control" 
                              name="datos_transporte" 
                              rows="3"
                              placeholder="Ej: Nombre del chofer, placas, tipo de vehículo..."><?= esc(old('datos_transporte')) ?></textarea>
                </div>

                <!-- RESPONSABLES -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Responsables</h5>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Recibe Producto</label>
                        <input type="text" 
                               class="form-control" 
                               name="recibe_producto"
                               value="<?= esc(old('recibe_producto')) ?>"
                               placeholder="Nombre de quien recibe"
                               maxlength="100">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Autoriza Salida</label>
                        <input type="text" 
                               class="form-control" 
                               name="autoriza_salida"
                               value="<?= esc(old('autoriza_salida')) ?>"
                               placeholder="Nombre de quien autoriza"
                               maxlength="100">
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="text-end">
                    <a href="<?= site_url('lotes-salida') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Guardar Nota de Salida
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    
    .form-control:focus, .form-select:focus, textarea:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-label {
        font-size: 0.9rem;
    }
    
    h5 {
        font-size: 1.1rem;
    }
    
    .border-bottom {
        border-bottom: 2px solid #dee2e6 !important;
    }
</style>

<?= $this->endSection() ?>