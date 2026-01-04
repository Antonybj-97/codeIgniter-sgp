<?php // Se agregó la apertura de etiqueta PHP correcta ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Editar Salida de Almacén<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="container-fluid py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold text-dark m-0">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Editar Nota de Salida
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('lotes-salida') ?>">Salidas</a></li>
                        <li class="breadcrumb-item active">Folio: <?= esc($lote['folio_salida']) ?></li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="<?= site_url('lotes-salida') ?>" class="btn btn-outline-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la lista
                </a>
            </div>
        </div>

        <?php if (session('error')): ?>
            <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                    <div><?= session('error') ?></div>
                </div>
                <button class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <h6 class="fw-bold"><i class="bi bi-x-circle-fill me-2"></i>Por favor, corrija los siguientes errores:</h6>
                <ul class="mb-0 small">
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">Información General</h5>
                        <span class="badge bg-primary px-3 py-2 rounded-pill">FOLIO: <?= esc($lote['folio_salida']) ?></span>
                    </div>

                    <div class="card-body p-4">
                        <form action="<?= site_url('lotes-salida/update/' . $lote['id_salida']) ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-uppercase">Folio de Salida <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-hash"></i></span>
                                        <input type="text" name="folio_salida" class="form-control" value="<?= old('folio_salida', $lote['folio_salida']) ?>" required maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-uppercase">Fecha de Embarque <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-calendar3"></i></span>
                                        <input type="date" name="fecha_embarque" class="form-control" value="<?= old('fecha_embarque', $lote['fecha_embarque']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-uppercase">Cliente <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person-fill"></i></span>
                                        <input type="text" name="nombre_cliente" class="form-control" value="<?= old('nombre_cliente', $lote['nombre_cliente']) ?>" required maxlength="100">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-light p-3 rounded-3 mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-box-fill text-primary me-2"></i>Detalles del Producto</h6>
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label small fw-bold">Nombre del Producto <span class="text-danger">*</span></label>
                                        <input type="text" name="producto" class="form-control border-0 shadow-sm" value="<?= old('producto', $lote['producto']) ?>" required maxlength="150">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Tipo <span class="text-danger">*</span></label>
                                        <select name="tipo_producto" class="form-select border-0 shadow-sm" required>
                                            <option value="">Seleccionar tipo</option>
                                            <?php foreach ($tipos_producto as $tipo): ?>
                                                <option value="<?= esc($tipo) ?>"
                                                    <?= old('tipo_producto', $lote['tipo_producto']) == $tipo ? 'selected' : '' ?>>
                                                    <?= esc($tipo) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold">Cantidad <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="cantidad" class="form-control border-0 shadow-sm" value="<?= old('cantidad', $lote['cantidad']) ?>" required min="0.01">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold">Unidad <span class="text-danger">*</span></label>
                                        <select name="unidad" class="form-select border-0 shadow-sm" required>
                                            <option value="">Seleccionar</option>
                                            <?php foreach ($unidades as $unidad): ?>
                                                <option value="<?= esc($unidad) ?>"
                                                    <?= old('unidad', $lote['unidad']) == $unidad ? 'selected' : '' ?>>
                                                    <?= esc($unidad) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-file-text text-primary me-2"></i>Información Adicional</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold small text-uppercase">No. Maquila</label>
                                        <input type="text" name="no_maquila" class="form-control" value="<?= old('no_maquila', $lote['no_maquila']) ?>" maxlength="50">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold small text-uppercase">No. Factura</label>
                                        <input type="text" name="no_factura" class="form-control" value="<?= old('no_factura', $lote['no_factura']) ?>" maxlength="50">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold small text-uppercase">Certificado</label>
                                        <input type="text" name="certificado" class="form-control" value="<?= old('certificado', $lote['certificado']) ?>" maxlength="50">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold small text-uppercase">Clave de Lote</label>
                                        <input type="text" name="clave_lote" class="form-control" value="<?= old('clave_lote', $lote['clave_lote']) ?>" maxlength="50" placeholder="Ej: LOTE-2023-XYZ">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-truck text-primary me-2"></i>Datos del Transporte</h6>
                                <div class="form-group">
                                    <label class="form-label small text-muted">Información del transporte (chofer, placas, vehículo, etc.)</label>
                                    <textarea name="datos_transporte" class="form-control" rows="3"><?= old('datos_transporte', $lote['datos_transporte']) ?></textarea>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-person-check text-primary me-2"></i>Responsables</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase">Recibe Producto</label>
                                        <input type="text" name="recibe_producto" class="form-control" value="<?= old('recibe_producto', $lote['recibe_producto']) ?>" maxlength="100" placeholder="Nombre de quien recibe">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase">Autoriza Salida</label>
                                        <input type="text" name="autoriza_salida" class="form-control" value="<?= old('autoriza_salida', $lote['autoriza_salida']) ?>" maxlength="100" placeholder="Nombre de quien autoriza">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                <div>
                                    <a href="<?= site_url('lotes-salida/show/' . $lote['id_salida']) ?>" class="btn btn-outline-info me-2">
                                        <i class="bi bi-eye me-1"></i> Ver Detalles
                                    </a>
                                    <a href="<?= site_url('lotes-salida/exportPDFIndividual/' . $lote['id_salida']) ?>" class="btn btn-outline-success" target="_blank">
                                        <i class="bi bi-file-pdf me-1"></i> PDF
                                    </a>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="<?= site_url('lotes-salida') ?>" class="btn btn-light px-4 border">Cancelar</a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                        <i class="bi bi-save me-1"></i> Actualizar Registro
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card { border-radius: 12px; }
        .form-control:focus, .form-select:focus, textarea:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
        .input-group-text { border-right: none; }
        .form-control, .form-select, textarea { border-radius: 8px; }
        .btn { border-radius: 8px; font-weight: 500; }
        .breadcrumb-item a { text-decoration: none; color: #6c757d; }
        .breadcrumb-item.active { color: #0d6efd; font-weight: 600; }
        .bg-light { background-color: #f8f9fa !important; }
    </style>

<?= $this->endSection() ?>