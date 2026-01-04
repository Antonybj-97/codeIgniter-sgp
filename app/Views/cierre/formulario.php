<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('cierre/cierre.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<div class="container mt-4 mb-5">
    <h1 class="text-center fw-bold text-success mb-1">
        <i class="bi bi-calculator"></i> SISTEMA DE CIERRE DE CUENTAS
    </h1>
    <p class="text-center text-secondary mb-4">
        Formulario universal para pimienta orgánica y convencional — Cosecha 2025
    </p>

    <form id="form-cierre-universal" method="post" action="<?= site_url('cierre/guardar_universal') ?>">
        <!-- SELECTOR DE TIPO DE PIMIENTA -->
        <div class="card-custom">
            <div class="section-title"><i class="bi bi-filter-circle"></i> I. Selección de Tipo de Pimienta</div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card tipo-selector" id="selector-organica">
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                <span class="badge badge-organica me-2">ORGÁNICA</span>
                                Pimienta Orgánica
                            </h5>
                            <p class="card-text small text-muted">
                                <i class="bi bi-check-circle-fill text-success"></i> Solo pimienta seca<br>
                                <i class="bi bi-check-circle-fill text-success"></i> Anticipo a productores<br>
                                <i class="bi bi-check-circle-fill text-success"></i> Rendimientos específicos
                            </p>
                            <input type="radio" class="btn-check" name="tipo_pimienta" id="tipo_organica" value="organica" autocomplete="off" checked>
                            <label class="btn btn-outline-success" for="tipo_organica">Seleccionar</label>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card tipo-selector" id="selector-convencional">
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                <span class="badge badge-convencional me-2">CONVENCIONAL</span>
                                Pimienta Convencional
                            </h5>
                            <p class="card-text small text-muted">
                                <i class="bi bi-check-circle-fill text-primary"></i> Con rama, verde y seca<br>
                                <i class="bi bi-check-circle-fill text-primary"></i> Factores de conversión<br>
                                <i class="bi bi-check-circle-fill text-primary"></i> Comisiones diferenciadas
                            </p>
                            <input type="radio" class="btn-check" name="tipo_pimienta" id="tipo_convencional" value="convencional" autocomplete="off">
                            <label class="btn btn-outline-primary" for="tipo_convencional">Seleccionar</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FACTORES DE CONVERSIÓN -->
            <div class="row mt-3" id="factores-conversion" style="display: none;">
                <div class="col-md-12">
                    <div class="factor-box">
                        <h6><i class="bi bi-calculator"></i> Factores de Conversión (Convencional)</h6>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="small fw-bold">Verde → Seca</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.000001" name="factor_verde_seca" 
                                           class="form-control" value="3.061900" min="1">
                                    <span class="input-group-text">:1</span>
                                </div>
                                <small class="text-muted">6 decimales permitidos</small>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold">Rama → Seca</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.000001" name="factor_rama_seca" 
                                           class="form-control" value="2.800000" min="1">
                                    <span class="input-group-text">:1</span>
                                </div>
                                <small class="text-muted">6 decimales permitidos</small>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold">Factor Pago</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="factor_precio_pago" 
                                           class="form-control" value="3.00" min="1">
                                    <span class="input-group-text">×</span>
                                </div>
                                <small class="text-muted">2 decimales permitidos</small>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold">Rendimiento base</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.000001" name="rendimiento_base" 
                                           class="form-control" value="2.800000" min="0">
                                    <span class="input-group-text">kg</span>
                                </div>
                                <small class="text-muted">6 decimales permitidos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- II. INFORMACIÓN GENERAL -->
        <div class="card-custom">
            <div class="section-title"><i class="bi bi-info-circle-fill"></i> II. Información General</div>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="fw-bold text-dark">Cooperativa</label>
                    <select name="cooperativa" id="cooperativa" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <?php if(isset($cooperativas) && is_array($cooperativas)): ?>
                            <?php foreach($cooperativas as $coop): ?>
                                <option value="<?= htmlspecialchars($coop) ?>"><?= htmlspecialchars($coop) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="Cooperativa Maseual Xicaualis SCL" selected>
                                Cooperativa Maseual Xicaualis SCL
                            </option>
                            <option value="Cooperativa YANKUIK SENOJTOKALIS S.C DE R.L DE C.V">
                                Cooperativa YANKUIK SENOJTOKALIS S.C DE R.L DE C.V
                            </option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="fw-bold text-dark">Centro de acopio</label>
                    <select name="centro" id="centro" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <?php if(isset($centros) && is_array($centros)): ?>
                            <?php foreach($centros as $c): ?>
                                <option value="<?= $c['id'] ?>" data-codigo="<?= strtoupper(substr($c['nombre'], 0, 3)) ?>">
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="fw-bold text-dark">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" required 
                           value="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="col-md-6">
                    <label class="fw-bold text-dark">Nombre del Acopiador</label>
                    <input type="text" name="acopiador" id="acopiador" class="form-control" required>
                </div>
                
                <div class="col-md-6">
                    <label class="fw-bold text-dark">Folio / Identificador</label>
                    <div class="input-group">
                        <input type="text" name="folio" id="folio" class="form-control" readonly>
                        <button type="button" class="btn btn-outline-secondary" id="btn-generar-folio">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </div>
                    <small class="text-muted">Formato: YYYYMMDD-0001 (fecha + consecutivo)</small>
                </div>
            </div>
        </div>

        <!-- III. RESUMEN FINANCIERO -->
        <div class="card-custom">
            <div class="section-title"><i class="bi bi-cash-stack"></i> III. Resumen Financiero</div>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="fw-bold">Dinero entregado por supervisor</label>
                    <input type="number" step="0.01" name="dinero_entregado" id="dinero-entregado" 
                           class="form-control campo-importante" value="0" min="0" required>
                </div>
                
                <div class="col-md-4" id="campo-anticipo">
                    <label class="fw-bold">Descuento Anticipo a productores</label>
                    <input type="number" step="0.01" name="descuento_anticipo" id="descuento-anticipo" 
                           class="form-control" value="0" min="0">
                    <small class="text-muted">Solo para pimienta orgánica</small>
                </div>
                
                <div class="col-md-4" id="campo-otros-cargos" style="display: none;">
                    <label class="fw-bold">Otros cargos</label>
                    <input type="number" step="0.01" name="otros_cargos" id="otros-cargos" 
                           class="form-control" value="0" min="0">
                    <small class="text-muted">Naylo, báscula, etc.</small>
                </div>
                
                <div class="col-md-4">
                    <label class="fw-bold">Total dinero con cargo al acopiador</label>
                    <input type="text" name="total_dinero_cargo" id="total-dinero-cargo" 
                           class="form-control campo-calculado" readonly data-format-commas="true" data-decimales="2">
                </div>
                
                <div class="col-md-4">
                    <label class="fw-bold">Dinero comprobado en pimienta acopiada</label>
                    <input type="text" name="dinero_comprobado" id="dinero-comprobado" 
                           class="form-control campo-calculado" readonly data-format-commas="true" data-decimales="2">
                </div>
                
                <div class="col-md-4">
                    <label class="fw-bold">Saldo del acopiador</label>
                    <input type="text" name="saldo_acopiador" id="saldo-acopiador" 
                           class="form-control campo-calculado" readonly data-format-commas="true" data-decimales="2">
                    <small class="text-muted">Total cargo - Dinero comprobado</small>
                </div>
            </div>
        </div>

        <!-- IV. PIMIENTA ACOPIADA -->
        <div class="card-custom">
            <div class="section-title">
                <i class="bi bi-box"></i> IV. Pimienta Acopiada 
                <span id="subtitulo-tipo" class="badge badge-organica ms-2">ORGÁNICA</span>
            </div>
            
            <!-- SECCIÓN ORGÁNICA -->
            <div id="seccion-organica">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-droplet-fill text-success"></i> Pimienta Seca (Precios: 105-116)
                    </h6>
                    <button type="button" class="btn btn-success btn-sm" id="btn-agregar-seca-organica">
                        <i class="bi bi-plus-circle"></i> Agregar registro
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-fixed" id="tabla-seca-organica">
                        <thead class="table-light">
                            <tr>
                                <th width="30%">Precio ($)</th>
                                <th width="30%">Kilos</th>
                                <th width="30%">Importe</th>
                                <th width="10%" class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas dinámicas -->
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td class="text-end fw-bold">Total pimienta seca:</td>
                                <td><input type="text" class="form-control form-control-sm" id="total-kilos-organica" readonly data-format-commas="true" data-decimales="1"></td>
                                <td><input type="text" class="form-control form-control-sm" id="total-importe-organica" readonly data-format-commas="true" data-decimales="2"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- SECCIÓN CONVENCIONAL -->
            <div id="seccion-convencional" style="display: none;">
                <!-- CON RAMA -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-tree-fill text-primary"></i> Pimienta con Rama
                    </h6>
                    <button type="button" class="btn btn-primary btn-sm" id="btn-agregar-con-rama">
                        <i class="bi bi-plus-circle"></i> Agregar fila
                    </button>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm" id="tabla-con-rama">
                        <thead class="table-light">
                            <tr>
                                <th>Precio ($)</th>
                                <th>Kilos</th>
                                <th>Importe</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas dinámicas -->
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td class="text-end fw-bold">Total con rama:</td>
                                <td><input type="text" class="form-control form-control-sm" id="total-kilos-rama" readonly data-format-commas="true" data-decimales="1"></td>
                                <td><input type="text" class="form-control form-control-sm" id="total-importe-rama" readonly data-format-commas="true" data-decimales="2"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- VERDE -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-droplet text-primary"></i> Pimienta Verde
                    </h6>
                    <button type="button" class="btn btn-primary btn-sm" id="btn-agregar-verde">
                        <i class="bi bi-plus-circle"></i> Agregar fila
                    </button>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm" id="tabla-verde">
                        <thead class="table-light">
                            <tr>
                                <th>Precio ($)</th>
                                <th>Kilos</th>
                                <th>Importe</th>
                                <th>Kilos seca equivalente</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas dinámicas -->
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td class="text-end fw-bold">Total verde:</td>
                                <td><input type="text" class="form-control form-control-sm" id="total-kilos-verde" readonly data-format-commas="true" data-decimales="1"></td>
                                <td><input type="text" class="form-control form-control-sm" id="total-importe-verde" readonly data-format-commas="true" data-decimales="2"></td>
                                <td><input type="text" class="form-control form-control-sm" id="total-seca-equivalente" readonly data-format-commas="true" data-decimales="6"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- SECA -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-droplet-half text-primary"></i> Pimienta Seca
                    </h6>
                    <button type="button" class="btn btn-primary btn-sm" id="btn-agregar-seca-convencional">
                        <i class="bi bi-plus-circle"></i> Agregar fila
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="tabla-seca-convencional">
                        <thead class="table-light">
                            <tr>
                                <th>Precio ($)</th>
                                <th>Kilos</th>
                                <th>Importe</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas dinámicas -->
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td class="text-end fw-bold">Total seca:</td>
                                <td><input type="text" class="form-control form-control-sm" id="total-kilos-seca-conv" readonly data-format-commas="true" data-decimales="1"></td>
                                <td><input type="text" class="form-control form-control-sm" id="total-importe-seca-conv" readonly data-format-commas="true" data-decimales="2"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- V. ENTREGAS EN ALMACÉN -->
        <div class="card-custom">
            <div class="section-title"><i class="bi bi-arrow-left-right"></i> V. Pimienta Entregada en Almacén</div>
            
            <!-- PIMIENTA VERDE O EN PROCESO -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-arrow-down-circle text-success"></i> Pimienta Verde o en proceso de secado
                </h6>
                <button type="button" class="btn btn-success btn-sm" id="btn-agregar-verde-proceso">
                    <i class="bi bi-plus-circle"></i> Agregar entrega
                </button>
            </div>
            
            <div class="alert alert-info py-2 mb-2">
                <small><i class="bi bi-info-circle"></i> Diferencia = Kilos en centro - Kilos en beneficio</small>
            </div>
            
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-sm" id="tabla-verde-proceso">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Sacos / Folio</th>
                            <th>Kilos en centro</th>
                            <th>Kilos en beneficio</th>
                            <th>Diferencia</th>
                            <th>Kilos seca (resultado)</th>
                            <th>Rendimiento obtenido</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas dinámicas -->
                    </tbody>
                </table>
            </div>
            
            <!-- PIMIENTA SECA ENTREGADA -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-check-circle text-success"></i> Pimienta Seca Entregada
                </h6>
                <button type="button" class="btn btn-success btn-sm" id="btn-agregar-seca-entregada">
                    <i class="bi bi-plus-circle"></i> Agregar entrega seca
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="tabla-seca-entregada">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Folio Nota</th>
                            <th>Kilos en centro</th>
                            <th>Kilos en almacén</th>
                            <th>Diferencia</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas dinámicas -->
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Totales:</td>
                            <td><input type="text" class="form-control form-control-sm" id="total-kilos-centro" readonly data-format-commas="true" data-decimales="1"></td>
                            <td><input type="text" class="form-control form-control-sm" id="total-kilos-almacen" readonly data-format-commas="true" data-decimales="1"></td>
                            <td><input type="text" class="form-control form-control-sm" id="total-diferencia" readonly data-format-commas="true" data-decimales="1"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- VI. PRODUCTO A PAGAR -->
        <div class="card-custom">
            <div class="section-title"><i class="bi bi-receipt"></i> VI. Producto a Pagar</div>
            
            <!-- ORGÁNICA -->
            <div id="pago-organica">
                <div class="row">
                    <div class="col-md-6">
                        <label class="fw-bold">Importe total a pagar por pimienta entregada</label>
                        <input type="text" name="importe_total_organica" 
                               id="importe-total-organica" class="form-control campo-calculado" readonly data-format-commas="true" data-decimales="2">
                    </div>
                </div>
            </div>
            
            <!-- CONVENCIONAL -->
            <div id="pago-convencional" style="display: none;">
                <!-- RAMA -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">A) Pimienta acopiada en rama y entregada seca</h6>
                    <div>
                        <button type="button" class="btn btn-warning btn-sm me-2" id="btn-sincronizar-rama">
                            <i class="bi bi-arrow-repeat"></i> Sincronizar
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-agregar-rama-pago">
                            <i class="bi bi-plus-circle"></i> Agregar
                        </button>
                    </div>
                </div>
                <div class="alert alert-info py-2 mb-2">
                    <small><i class="bi bi-calculator"></i> <strong>Fórmula:</strong> Precio a pagar = Precio acopio × Factor de pago</small>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm" id="tabla-rama-pago">
                        <thead class="table-light">
                            <tr>
                                <th>Precio acopio ($)</th>
                                <th>Kilos acopiados</th>
                                <th>Kilos entregados verde</th>
                                <th>Kilos seca a pagar</th>
                                <th>Precio a pagar ($)</th>
                                <th>Importe ($)</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas dinámicas -->
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Subtotal rama:</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="subtotal-rama-pago" readonly data-format-commas="true" data-decimales="2">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- VERDE -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">B) Pimienta acopiada verde y entregada seca</h6>
                    <div>
                        <button type="button" class="btn btn-warning btn-sm me-2" id="btn-sincronizar-verde">
                            <i class="bi bi-arrow-repeat"></i> Sincronizar
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-agregar-verde-pago">
                            <i class="bi bi-plus-circle"></i> Agregar
                        </button>
                    </div>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm" id="tabla-verde-pago">
                        <thead class="table-light">
                            <tr>
                                <th>Precio acopio ($)</th>
                                <th>Kilos acopiados</th>
                                <th>Kilos entregados verde</th>
                                <th>Kilos seca a pagar</th>
                                <th>Precio a pagar ($)</th>
                                <th>Importe ($)</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas dinámicas -->
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Subtotal verde:</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="subtotal-verde-pago" readonly data-format-commas="true" data-decimales="2">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- SECA -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">C) Pimienta seca a precio de acopio</h6>
                    <div>
                        <button type="button" class="btn btn-warning btn-sm me-2" id="btn-sincronizar-seca">
                            <i class="bi bi-arrow-repeat"></i> Sincronizar
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-agregar-seca-pago">
                            <i class="bi bi-plus-circle"></i> Agregar
                        </button>
                    </div>
                </div>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm" id="tabla-seca-pago">
                        <thead class="table-light">
                            <tr>
                                <th>Precio acopio ($)</th>
                                <th>Kilos acopiados</th>
                                <th>Kilos entregados</th>
                                <th>Importe ($)</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas dinámicas -->
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Subtotal seca:</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="subtotal-seca-pago" readonly data-format-commas="true" data-decimales="2">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label class="fw-bold">Importe total a pagar</label>
                        <input type="text" name="importe_total_convencional" 
                               id="importe-total-convencional" class="form-control campo-calculado" readonly data-format-commas="true" data-decimales="2">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Factor de precio a pagar</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="factor_precio_pago_calc" 
                                   id="factor-precio-pago-calc" class="form-control" value="3" min="1">
                            <span class="input-group-text">×</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- VII. COMISIONES POR PAGAR -->
        <div class="card-custom">
            <div class="section-title"><i class="bi bi-percent"></i> VII. Comisiones por Pagar</div>
            
            <!-- COMISIONES CONVENCIONALES -->
            <div id="comisiones-convencional" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">Comisiones para pimienta convencional</h6>
                    <div>
                        <button type="button" class="btn btn-warning btn-sm me-2" id="btn-restablecer-comisiones">
                            <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                        </button>
                        <button type="button" class="btn btn-info btn-sm" id="btn-sincronizar-comisiones">
                            <i class="bi bi-arrow-repeat"></i> Sincronizar Kilos
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm" id="tabla-comisiones">
                        <thead class="table-light">
                            <tr>
                                <th>Concepto</th>
                                <th>Kilos</th>
                                <th>Comisión por kilo</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Comisiones para Pimienta con Palillo -->
                            <tr>
                                <td>Comisión por acopio de Pimienta con palillo</td>
                                <td><input type="text" name="kilos_comision_palillo" class="form-control form-control-sm kilos-comision" min="0" value="0" readonly data-format-commas="true" data-decimales="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="comision_kilo_palillo" class="form-control form-control-sm comision-kilo-editable" value="0.50" min="0">
                                    </div>
                                </td>
                                <td><input type="text" name="importe_comision_palillo" class="form-control form-control-sm importe-comision" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                            
                            <!-- Comisiones para Pimienta Verde -->
                            <tr>
                                <td>Comisión por acopio de pimienta verde</td>
                                <td><input type="text" name="kilos_comision_verde" class="form-control form-control-sm kilos-comision" min="0" value="0" readonly data-format-commas="true" data-decimales="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="comision_kilo_verde" class="form-control form-control-sm comision-kilo-editable" value="0.60" min="0">
                                    </div>
                                </td>
                                <td><input type="text" name="importe_comision_verde" class="form-control form-control-sm importe-comision" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                            
                            <!-- Separador para Verde Entregada Seca -->
                            <tr class="table-info">
                                <td colspan="4" class="fw-bold">Por acopio y beneficio de pimienta verde entregada seca</td>
                            </tr>
                            
                            <!-- Comisión base para verde entregada seca -->
                            <tr>
                                <td>&nbsp;&nbsp;- Comisión base (calculado por kilo de pimienta seca entregada)</td>
                                <td><input type="text" name="kilos_comision_verde_base" class="form-control form-control-sm kilos-comision" min="0" value="0" readonly data-format-commas="true" data-decimales="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="comision_kilo_verde_base" class="form-control form-control-sm comision-kilo-editable" value="1.00" min="0">
                                    </div>
                                </td>
                                <td><input type="text" name="importe_comision_verde_base" class="form-control form-control-sm importe-comision" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                            
                            <!-- Comisión por rendimiento -->
                            <tr>
                                <td>&nbsp;&nbsp;- Comisión por obtener un rendimiento igual o menor a 2.80</td>
                                <td><input type="text" name="kilos_comision_verde_rendimiento" class="form-control form-control-sm kilos-comision" min="0" value="0" readonly data-format-commas="true" data-decimales="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="comision_kilo_verde_rendimiento" class="form-control form-control-sm comision-kilo-editable" value="0.20" min="0">
                                    </div>
                                </td>
                                <td><input type="text" name="importe_comision_verde_rendimiento" class="form-control form-control-sm importe-comision" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                            
                            <!-- Comisión por manejo de recursos -->
                            <tr>
                                <td>&nbsp;&nbsp;- Comisión por correcto manejo de recursos (saldo promedio -20%)</td>
                                <td><input type="text" name="kilos_comision_verde_recursos" class="form-control form-control-sm kilos-comision" min="0" value="0" readonly data-format-commas="true" data-decimales="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="comision_kilo_verde_recursos" class="form-control form-control-sm comision-kilo-editable" value="0.20" min="0">
                                    </div>
                                </td>
                                <td><input type="text" name="importe_comision_verde_recursos" class="form-control form-control-sm importe-comision" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                            
                            <!-- Comisión por cierre oportuno -->
                            <tr>
                                <td>&nbsp;&nbsp;- Comisión por hacer cierre de cuentas antes fecha límite (30 Nov)</td>
                                <td><input type="text" name="kilos_comision_verde_cierre" class="form-control form-control-sm kilos-comision" min="0" value="0" readonly data-format-commas="true" data-decimales="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="comision_kilo_verde_cierre" class="form-control form-control-sm comision-kilo-editable" value="0.10" min="0">
                                    </div>
                                </td>
                                <td><input type="text" name="importe_comision_verde_cierre" class="form-control form-control-sm importe-comision" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                            
                            <!-- Separador para Pimienta Seca -->
                            <tr class="table-info">
                                <td colspan="4" class="fw-bold">Por acopio de pimienta seca</td>
                            </tr>
                            
                            <!-- Comisión base para seca -->
                            <tr>
                                <td>&nbsp;&nbsp;- Comisión base (calculado por kilo de pimienta seca entregada)</td>
                                <td><input type="text" name="kilos_comision_seca_base" class="form-control form-control-sm kilos-comision" min="0" value="0" readonly data-format-commas="true" data-decimales="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="comision_kilo_seca_base" class="form-control form-control-sm comision-kilo-editable" value="0.30" min="0">
                                    </div>
                                </td>
                                <td><input type="text" name="importe_comision_seca_base" class="form-control form-control-sm importe-comision" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                            
                            <!-- Comisión por manejo de recursos para seca -->
                            <tr>
                                <td>&nbsp;&nbsp;- Comisión por correcto manejo de recursos (saldo promedio -20%)</td>
                                <td><input type="text" name="kilos_comision_seca_recursos" class="form-control form-control-sm kilos-comision" min="0" value="0" readonly data-format-commas="true" data-decimales="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="comision_kilo_seca_recursos" class="form-control form-control-sm comision-kilo-editable" value="0.20" min="0">
                                    </div>
                                </td>
                                <td><input type="text" name="importe_comision_seca_recursos" class="form-control form-control-sm importe-comision" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                            
                            <!-- Comisión por cierre oportuno para seca -->
                            <tr>
                                <td>&nbsp;&nbsp;- Comisión por hacer cierre de cuentas antes fecha límite (30 Nov)</td>
                                <td><input type="text" name="kilos_comision_seca_cierre" class="form-control form-control-sm kilos-comision" min="0" value="0" readonly data-format-commas="true" data-decimales="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="comision_kilo_seca_cierre" class="form-control form-control-sm comision-kilo-editable" value="0.10" min="0">
                                    </div>
                                </td>
                                <td><input type="text" name="importe_comision_seca_cierre" class="form-control form-control-sm importe-comision" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Suma de comisiones:</td>
                                <td><input type="text" step="0.01" id="total-comisiones" class="form-control form-control-sm" readonly data-format-commas="true" data-decimales="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- COMISIONES ORGÁNICAS -->
            <div id="comisiones-organica">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="fw-bold">Comisión base (por kilo)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.1" name="comision_base_org" 
                                   class="form-control comision-input comision-kilo-editable" value="0.50" min="0">
                            <span class="input-group-text">/kg</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Comisión por rendimiento ≤ 2.80</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.1" name="comision_rendimiento_org" 
                                   class="form-control comision-input comision-kilo-editable" value="0.20" min="0">
                            <span class="input-group-text">/kg</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Comisión por cierre oportuno</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.1" name="comision_cierre_org" 
                                   class="form-control comision-input comision-kilo-editable" value="0.10" min="0">
                            <span class="input-group-text">/kg</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- TOTALES -->
            <div class="row mt-3">
                <div class="col-md-4">
                    <label class="fw-bold">Total pimienta (importe)</label>
                    <input type="text" id="total-pimienta" 
                           class="form-control campo-calculado" readonly data-format-commas="true" data-decimales="2">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Total comisiones</label>
                    <input type="text" id="total-comisiones-final" 
                           class="form-control campo-calculado" readonly data-format-commas="true" data-decimales="2">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Total a pagar al acopiador</label>
                    <input type="text" id="total-a-pagar" 
                           class="form-control campo-calculado" readonly data-format-commas="true" data-decimales="2">
                </div>
            </div>
            
            <!-- SALDO FINAL -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="fw-bold">Saldo final (positivo = pagar, negativo = debe)</label>
                    <input type="text" id="saldo-final" 
                           class="form-control campo-importante" readonly data-format-commas="true" data-decimales="2">
                    <small class="text-muted">(Dinero entregado + Otros) - Total a pagar</small>
                </div>
            </div>
        </div>

        <!-- VIII. FIRMAS -->
        <div class="card-custom">
            <div class="section-title"><i class="bi bi-pen"></i> VIII. Firmas</div>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="fw-bold">Elaboró</label>
                    <input type="text" name="firmo_elaboro" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Autorizó</label>
                    <input type="text" name="firmo_autorizo" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Acopiador</label>
                    <input type="text" name="firmo_acopiador" class="form-control" required>
                </div>
            </div>
        </div>

        <!-- BOTONES -->
        <div class="d-flex justify-content-between mt-3 gap-3">
            <div>
                <button type="button" class="btn btn-secondary" id="btn-limpiar">
                    <i class="bi bi-arrow-counterclockwise"></i> Limpiar todo
                </button>
            </div>
            
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="btn-previsualizar">
                    <i class="bi bi-eye-fill"></i> Previsualizar
                </button>
                <button type="button" class="btn btn-info" id="btn-generar-pdf">
                    <i class="bi bi-file-pdf"></i> Generar PDF
                </button>
                <button type="submit" class="btn btn-success" id="btn-guardar">
                    <i class="bi bi-save-fill"></i> Guardar Cierre
                </button>
            </div>
        </div>

    </form>
</div>

<!-- TEMPLATES PARA FILAS DINÁMICAS -->
<template id="template-fila-seca-organica">
    <tr>
        <td><input type="number" step="0.01" name="precio_seca_organica[]" class="form-control form-control-sm precio" min="0" placeholder="105"></td>
        <td><input type="number" step="0.1" name="kilos_seca_organica[]" class="form-control form-control-sm kilos" min="0" value="0"></td>
        <td><input type="text" name="importe_seca_organica[]" class="form-control form-control-sm importe" readonly data-format-commas="true" data-decimales="2"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<template id="template-fila-con-rama">
    <tr>
        <td><input type="number" step="0.01" name="precio_con_rama[]" class="form-control form-control-sm precio" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_con_rama[]" class="form-control form-control-sm kilos" min="0" value="0"></td>
        <td><input type="text" name="importe_con_rama[]" class="form-control form-control-sm importe" readonly data-format-commas="true" data-decimales="2"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<template id="template-fila-verde">
    <tr>
        <td><input type="number" step="0.01" name="precio_verde[]" class="form-control form-control-sm precio" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_verde[]" class="form-control form-control-sm kilos" min="0" value="0"></td>
        <td><input type="text" name="importe_verde[]" class="form-control form-control-sm importe" readonly data-format-commas="true" data-decimales="2"></td>
        <td><input type="text" name="seca_equivalente_verde[]" class="form-control form-control-sm seca-equivalente" readonly data-format-commas="true" data-decimales="6"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<template id="template-fila-seca-convencional">
    <tr>
        <td><input type="number" step="0.01" name="precio_seca_convencional[]" class="form-control form-control-sm precio" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_seca_convencional[]" class="form-control form-control-sm kilos" min="0" value="0"></td>
        <td><input type="text" name="importe_seca_convencional[]" class="form-control form-control-sm importe" readonly data-format-commas="true" data-decimales="2"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<template id="template-verde-proceso">
    <tr>
        <td><input type="date" name="fecha_verde_proceso[]" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>"></td>
        <td><input type="text" name="folio_verde_proceso[]" class="form-control form-control-sm" placeholder="Sacos o folio"></td>
        <td><input type="number" step="0.1" name="kilos_centro_verde_proceso[]" class="form-control form-control-sm kilos-centro" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_beneficio_verde_proceso[]" class="form-control form-control-sm kilos-beneficio" min="0" value="0"></td>
        <td><input type="text" name="diferencia_verde_proceso[]" class="form-control form-control-sm diferencia" readonly data-format-commas="true" data-decimales="1"></td>
        <td><input type="number" step="0.1" name="kilos_seca_resultado_verde_proceso[]" class="form-control form-control-sm kilos-seca-resultado" min="0" value="0"></td>
        <td><input type="text" name="rendimiento_verde_proceso[]" class="form-control form-control-sm rendimiento" readonly data-format-commas="true" data-decimales="6"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<template id="template-seca-entregada">
    <tr>
        <td><input type="date" name="fecha_seca_entregada[]" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>"></td>
        <td><input type="text" name="folio_seca_entregada[]" class="form-control form-control-sm" placeholder="Folio Nota"></td>
        <td><input type="number" step="0.1" name="kilos_centro_seca_entregada[]" class="form-control form-control-sm kilos-centro" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_almacen_seca_entregada[]" class="form-control form-control-sm kilos-almacen" min="0" value="0"></td>
        <td><input type="text" name="diferencia_seca_entregada[]" class="form-control form-control-sm diferencia" readonly data-format-commas="true" data-decimales="1"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<template id="template-rama-pago">
    <tr>
        <td><input type="number" step="0.01" name="precio_acopio_rama_pago[]" class="form-control form-control-sm precio-acopio" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_acopiados_rama_pago[]" class="form-control form-control-sm kilos-acopiados" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_entregados_verde_rama_pago[]" class="form-control form-control-sm kilos-entregados" min="0" value="0"></td>
        <td><input type="text" name="kilos_seca_pagar_rama_pago[]" class="form-control form-control-sm kilos-seca-pagar" readonly data-format-commas="true" data-decimales="6"></td>
        <td><input type="text" name="precio_pagar_rama_pago[]" class="form-control form-control-sm precio-pagar" readonly data-format-commas="true" data-decimales="2"></td>
        <td><input type="text" name="importe_rama_pago[]" class="form-control form-control-sm importe" readonly data-format-commas="true" data-decimales="2"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<template id="template-verde-pago">
    <tr>
        <td><input type="number" step="0.01" name="precio_acopio_verde_pago[]" class="form-control form-control-sm precio-acopio" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_acopiados_verde_pago[]" class="form-control form-control-sm kilos-acopiados" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_entregados_verde_verde_pago[]" class="form-control form-control-sm kilos-entregados" min="0" value="0"></td>
        <td><input type="text" name="kilos_seca_pagar_verde_pago[]" class="form-control form-control-sm kilos-seca-pagar" readonly data-format-commas="true" data-decimales="6"></td>
        <td><input type="text" name="precio_pagar_verde_pago[]" class="form-control form-control-sm precio-pagar" readonly data-format-commas="true" data-decimales="2"></td>
        <td><input type="text" name="importe_verde_pago[]" class="form-control form-control-sm importe" readonly data-format-commas="true" data-decimales="2"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<template id="template-seca-pago">
    <tr>
        <td><input type="number" step="0.01" name="precio_acopio_seca_pago[]" class="form-control form-control-sm precio-acopio" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_acopiados_seca_pago[]" class="form-control form-control-sm kilos-acopiados" min="0" value="0"></td>
        <td><input type="number" step="0.1" name="kilos_entregados_seca_pago[]" class="form-control form-control-sm kilos-entregados" min="0" value="0"></td>
        <td><input type="text" name="importe_seca_pago[]" class="form-control form-control-sm importe" readonly data-format-commas="true" data-decimales="2"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</template>

<script>
// ============================================
// SISTEMA DE CIERRE DE CUENTAS - JAVASCRIPT
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let tipoActual = 'organica';
    let factorVerdeSeca = 3.061900;
    let factorRamaSeca = 2.800000;
    let factorPrecioPago = 3.00;
    
    // Comisiones por defecto para pimienta convencional
    const comisionesPorDefecto = {
        palillo: 0.50,
        verde: 0.60,
        verde_base: 1.00,
        verde_rendimiento: 0.20,
        verde_recursos: 0.20,
        verde_cierre: 0.10,
        seca_base: 0.30,
        seca_recursos: 0.20,
        seca_cierre: 0.10
    };
    
    // Comisiones por defecto para pimienta orgánica
    const comisionesOrganicaPorDefecto = {
        base: 0.50,
        rendimiento: 0.20,
        cierre: 0.10
    };
    
    // Referencias a elementos principales
    const elementos = {
        // Selectores de tipo
        tipoOrganica: document.getElementById('tipo_organica'),
        tipoConvencional: document.getElementById('tipo_convencional'),
        selectorOrganica: document.getElementById('selector-organica'),
        selectorConvencional: document.getElementById('selector-convencional'),
        subtituloTipo: document.getElementById('subtitulo-tipo'),
        
        // Factores de conversión
        factoresConversion: document.getElementById('factores-conversion'),
        
        // Campos financieros
        dineroEntregado: document.getElementById('dinero-entregado'),
        descuentoAnticipo: document.getElementById('descuento-anticipo'),
        otrosCargos: document.getElementById('otros-cargos'),
        totalDineroCargo: document.getElementById('total-dinero-cargo'),
        dineroComprobado: document.getElementById('dinero-comprobado'),
        saldoAcopiador: document.getElementById('saldo-acopiador'),
        
        // Secciones
        seccionOrganica: document.getElementById('seccion-organica'),
        seccionConvencional: document.getElementById('seccion-convencional'),
        campoAnticipo: document.getElementById('campo-anticipo'),
        campoOtrosCargos: document.getElementById('campo-otros-cargos'),
        pagoOrganica: document.getElementById('pago-organica'),
        pagoConvencional: document.getElementById('pago-convencional'),
        comisionesOrganica: document.getElementById('comisiones-organica'),
        comisionesConvencional: document.getElementById('comisiones-convencional'),
        
        // Totales
        totalPimienta: document.getElementById('total-pimienta'),
        totalComisionesFinal: document.getElementById('total-comisiones-final'),
        totalAPagar: document.getElementById('total-a-pagar'),
        saldoFinal: document.getElementById('saldo-final')
    };
    
    // ============================================
    // FUNCIONES DE UTILIDAD
    // ============================================
    
    function parseNumber(valor) {
        if (valor === null || valor === undefined || valor === '') return 0;
        // Eliminar comas y convertir a número
        const num = parseFloat(String(valor).replace(/,/g, ''));
        return isNaN(num) ? 0 : num;
    }
    
    function formatNumberWithCommas(num, decimales = 2) {
        // Redondear a los decimales especificados
        const factor = Math.pow(10, decimales);
        const redondeado = Math.round(num * factor) / factor;
        
        // Separar parte entera y decimal
        let [parteEntera, parteDecimal] = redondeado.toFixed(decimales).split('.');
        
        // Agregar comas a la parte entera cada 3 dígitos
        parteEntera = parteEntera.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        // Combinar partes
        if (decimales > 0) {
            return `${parteEntera}.${parteDecimal}`;
        }
        return parteEntera;
    }
    
    function $(selector) {
        return document.querySelector(selector);
    }
    
    function $$(selector) {
        return Array.from(document.querySelectorAll(selector));
    }
    
    // ============================================
    // GENERACIÓN DE FOLIO CONSECUTIVO
    // ============================================
    
    // Contador de folios por fecha usando localStorage
    function obtenerContadorFolio(fecha) {
        const clave = `folio_contador_${fecha}`;
        const contador = localStorage.getItem(clave);
        return contador ? parseInt(contador) : 0;
    }
    
    function guardarContadorFolio(fecha, contador) {
        const clave = `folio_contador_${fecha}`;
        localStorage.setItem(clave, contador);
    }
    
    function generarFolio() {
        const fechaInput = $('#fecha');
        const folioInput = $('#folio');
        
        let fecha = '';
        
        // Obtener fecha
        if (fechaInput && fechaInput.value) {
            fecha = fechaInput.value.replace(/-/g, '');
        } else {
            fecha = new Date().toISOString().slice(0,10).replace(/-/g, '');
        }
        
        // Obtener y actualizar contador
        let contador = obtenerContadorFolio(fecha) + 1;
        guardarContadorFolio(fecha, contador);
        
        // Formatear número a 4 dígitos
        const numeroFormateado = contador.toString().padStart(4, '0');
        
        // Crear folio: YYYYMMDD-0001
        const folio = fecha + '-' + numeroFormateado;
        
        if (folioInput) {
            folioInput.value = folio;
        }
    }
    
    // Botón para regenerar folio
    $('#btn-generar-folio')?.addEventListener('click', generarFolio);
    
    // Generar folio al cambiar fecha
    $('#fecha')?.addEventListener('change', generarFolio);
    
    // ============================================
    // MANEJO DEL TIPO DE PIMIENTA
    // ============================================
    
    function cambiarTipoPimienta(tipo) {
        tipoActual = tipo;
        
        // Actualizar interfaz visual
        if (tipo === 'organica') {
            elementos.selectorOrganica.classList.add('active');
            elementos.selectorConvencional.classList.remove('active');
            elementos.subtituloTipo.className = 'badge badge-organica ms-2';
            elementos.subtituloTipo.textContent = 'ORGÁNICA';
            
            // Mostrar/ocultar elementos
            elementos.seccionOrganica.style.display = 'block';
            elementos.seccionConvencional.style.display = 'none';
            elementos.factoresConversion.style.display = 'none';
            elementos.campoAnticipo.style.display = 'block';
            elementos.campoOtrosCargos.style.display = 'none';
            elementos.pagoOrganica.style.display = 'block';
            elementos.pagoConvencional.style.display = 'none';
            elementos.comisionesOrganica.style.display = 'block';
            elementos.comisionesConvencional.style.display = 'none';
            
        } else {
            elementos.selectorOrganica.classList.remove('active');
            elementos.selectorConvencional.classList.add('active');
            elementos.subtituloTipo.className = 'badge badge-convencional ms-2';
            elementos.subtituloTipo.textContent = 'CONVENCIONAL';
            
            // Mostrar/ocultar elementos
            elementos.seccionOrganica.style.display = 'none';
            elementos.seccionConvencional.style.display = 'block';
            elementos.factoresConversion.style.display = 'block';
            elementos.campoAnticipo.style.display = 'none';
            elementos.campoOtrosCargos.style.display = 'block';
            elementos.pagoOrganica.style.display = 'none';
            elementos.pagoConvencional.style.display = 'block';
            elementos.comisionesOrganica.style.display = 'none';
            elementos.comisionesConvencional.style.display = 'block';
            
            // Sincronizar comisiones automáticamente al cambiar a convencional
            setTimeout(sincronizarComisiones, 100);
        }
        
        recalcularTodo();
    }
    
    // Event listeners para cambio de tipo
    elementos.tipoOrganica.addEventListener('change', () => cambiarTipoPimienta('organica'));
    elementos.tipoConvencional.addEventListener('change', () => cambiarTipoPimienta('convencional'));
    
    // ============================================
    // MANEJO DE FILAS DINÁMICAS CON ELIMINACIÓN
    // ============================================
    
    function agregarFila(tablaId, templateId) {
        const tablaBody = $(`#${tablaId} tbody`);
        const template = $(templateId);
        
        if (!tablaBody || !template) return;
        
        const nuevaFila = template.content.cloneNode(true);
        tablaBody.appendChild(nuevaFila);
        
        // Agregar event listeners a la nueva fila
        const ultimaFila = tablaBody.lastElementChild;
        const inputs = ultimaFila.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', recalcularTodo);
        });
        
        // Agregar evento al botón eliminar
        const btnEliminar = ultimaFila.querySelector('.btn-eliminar');
        if (btnEliminar) {
            btnEliminar.addEventListener('click', function() {
                if (tablaBody.children.length > 1) {
                    this.closest('tr').remove();
                    recalcularTodo();
                } else {
                    alert('Debe haber al menos una fila en la tabla');
                }
            });
        }
        
        recalcularTodo();
    }
    
    // ============================================
    // FUNCIONES PARA COMISIONES EDITABLES
    // ============================================
    
    function restablecerComisiones() {
        if (tipoActual === 'organica') {
            // Restablecer comisiones orgánicas
            $('input[name="comision_base_org"]').value = comisionesOrganicaPorDefecto.base;
            $('input[name="comision_rendimiento_org"]').value = comisionesOrganicaPorDefecto.rendimiento;
            $('input[name="comision_cierre_org"]').value = comisionesOrganicaPorDefecto.cierre;
            alert('Comisiones orgánicas restablecidas a valores por defecto.');
        } else {
            // Restablecer comisiones convencionales
            $('input[name="comision_kilo_palillo"]').value = comisionesPorDefecto.palillo;
            $('input[name="comision_kilo_verde"]').value = comisionesPorDefecto.verde;
            $('input[name="comision_kilo_verde_base"]').value = comisionesPorDefecto.verde_base;
            $('input[name="comision_kilo_verde_rendimiento"]').value = comisionesPorDefecto.verde_rendimiento;
            $('input[name="comision_kilo_verde_recursos"]').value = comisionesPorDefecto.verde_recursos;
            $('input[name="comision_kilo_verde_cierre"]').value = comisionesPorDefecto.verde_cierre;
            $('input[name="comision_kilo_seca_base"]').value = comisionesPorDefecto.seca_base;
            $('input[name="comision_kilo_seca_recursos"]').value = comisionesPorDefecto.seca_recursos;
            $('input[name="comision_kilo_seca_cierre"]').value = comisionesPorDefecto.seca_cierre;
            alert('Comisiones convencionales restablecidas a valores por defecto.');
        }
        
        recalcularTodo();
    }
    
    function sincronizarComisiones() {
        if (tipoActual !== 'convencional') return;
        
        // Calcular total de kilos con rama
        let totalKilosRama = 0;
        $$('#tabla-con-rama tbody tr').forEach(fila => {
            const kilos = parseNumber(fila.querySelector('.kilos')?.value);
            totalKilosRama += kilos;
        });
        
        // Calcular total de kilos verde
        let totalKilosVerde = 0;
        $$('#tabla-verde tbody tr').forEach(fila => {
            const kilos = parseNumber(fila.querySelector('.kilos')?.value);
            totalKilosVerde += kilos;
        });
        
        // Calcular total de kilos seca de verde (de la tabla de verde pago)
        let totalKilosVerdeSeca = 0;
        $$('#tabla-verde-pago tbody tr').forEach(fila => {
            const kilosSecaPagar = parseNumber(fila.querySelector('.kilos-seca-pagar')?.value);
            totalKilosVerdeSeca += kilosSecaPagar;
        });
        
        // Calcular total de kilos seca (de la tabla de seca pago)
        let totalKilosSeca = 0;
        $$('#tabla-seca-pago tbody tr').forEach(fila => {
            const kilosEntregados = parseNumber(fila.querySelector('.kilos-entregados')?.value);
            totalKilosSeca += kilosEntregados;
        });
        
        // Actualizar los kilos en la tabla de comisiones
        $('input[name="kilos_comision_palillo"]').value = formatNumberWithCommas(totalKilosRama, 1);
        $('input[name="kilos_comision_verde"]').value = formatNumberWithCommas(totalKilosVerde, 1);
        $('input[name="kilos_comision_verde_base"]').value = formatNumberWithCommas(totalKilosVerdeSeca, 1);
        $('input[name="kilos_comision_verde_rendimiento"]').value = formatNumberWithCommas(totalKilosVerdeSeca, 1);
        $('input[name="kilos_comision_verde_recursos"]').value = formatNumberWithCommas(totalKilosVerdeSeca, 1);
        $('input[name="kilos_comision_verde_cierre"]').value = formatNumberWithCommas(totalKilosVerdeSeca, 1);
        $('input[name="kilos_comision_seca_base"]').value = formatNumberWithCommas(totalKilosSeca, 1);
        $('input[name="kilos_comision_seca_recursos"]').value = formatNumberWithCommas(totalKilosSeca, 1);
        $('input[name="kilos_comision_seca_cierre"]').value = formatNumberWithCommas(totalKilosSeca, 1);
        
        recalcularTodo();
        alert('Kilos de comisiones sincronizados con los datos de pimienta.');
    }
    
    // Event listeners para botones de agregar
    const botonesAgregar = {
        'btn-agregar-seca-organica': ['tabla-seca-organica', '#template-fila-seca-organica'],
        'btn-agregar-con-rama': ['tabla-con-rama', '#template-fila-con-rama'],
        'btn-agregar-verde': ['tabla-verde', '#template-fila-verde'],
        'btn-agregar-seca-convencional': ['tabla-seca-convencional', '#template-fila-seca-convencional'],
        'btn-agregar-verde-proceso': ['tabla-verde-proceso', '#template-verde-proceso'],
        'btn-agregar-seca-entregada': ['tabla-seca-entregada', '#template-seca-entregada'],
        'btn-agregar-rama-pago': ['tabla-rama-pago', '#template-rama-pago'],
        'btn-agregar-verde-pago': ['tabla-verde-pago', '#template-verde-pago'],
        'btn-agregar-seca-pago': ['tabla-seca-pago', '#template-seca-pago']
    };
    
    Object.entries(botonesAgregar).forEach(([botonId, [tablaId, templateId]]) => {
        const boton = document.getElementById(botonId);
        if (boton) {
            boton.addEventListener('click', () => agregarFila(tablaId, templateId));
        }
    });
    
    // ============================================
    // CÁLCULOS PARA PIMIENTA ORGÁNICA
    // ============================================
    
    function calcularOrganica() {
        let totalKilos = 0;
        let totalImporte = 0;
        
        $$('#tabla-seca-organica tbody tr').forEach(fila => {
            const precio = parseNumber(fila.querySelector('.precio')?.value);
            const kilos = parseNumber(fila.querySelector('.kilos')?.value);
            const importe = precio * kilos;
            
            const importeInput = fila.querySelector('.importe');
            if (importeInput) importeInput.value = formatNumberWithCommas(importe, 2);
            
            totalKilos += kilos;
            totalImporte += importe;
        });
        
        $('#total-kilos-organica').value = formatNumberWithCommas(totalKilos, 1);
        $('#total-importe-organica').value = formatNumberWithCommas(totalImporte, 2);
        elementos.dineroComprobado.value = formatNumberWithCommas(totalImporte, 2);
        $('#importe-total-organica').value = formatNumberWithCommas(totalImporte, 2);
        elementos.totalPimienta.value = formatNumberWithCommas(totalImporte, 2);
        
        return totalImporte;
    }
    
    // ============================================
    // CÁLCULOS PARA PIMIENTA CONVENCIONAL
    // ============================================
    
    function calcularConvencional() {
        // 1. Actualizar factores con mayor precisión
        const factorVerdeInput = $('input[name="factor_verde_seca"]');
        const factorRamaInput = $('input[name="factor_rama_seca"]');
        const factorPagoInput = $('input[name="factor_precio_pago"]');
        
        if (factorVerdeInput) factorVerdeSeca = parseNumber(factorVerdeInput.value);
        if (factorRamaInput) factorRamaSeca = parseNumber(factorRamaInput.value);
        if (factorPagoInput) factorPrecioPago = parseNumber(factorPagoInput.value);
        
        // 2. Calcular pimienta con rama
        let totalKilosRama = 0;
        let totalImporteRama = 0;
        
        $$('#tabla-con-rama tbody tr').forEach(fila => {
            const precio = parseNumber(fila.querySelector('.precio')?.value);
            const kilos = parseNumber(fila.querySelector('.kilos')?.value);
            const importe = precio * kilos;
            
            const importeInput = fila.querySelector('.importe');
            if (importeInput) importeInput.value = formatNumberWithCommas(importe, 2);
            
            totalKilosRama += kilos;
            totalImporteRama += importe;
        });
        
        $('#total-kilos-rama').value = formatNumberWithCommas(totalKilosRama, 1);
        $('#total-importe-rama').value = formatNumberWithCommas(totalImporteRama, 2);
        
        // 3. Calcular pimienta verde con mayor precisión
        let totalKilosVerde = 0;
        let totalImporteVerde = 0;
        let totalSecaEquivalente = 0;
        
        $$('#tabla-verde tbody tr').forEach(fila => {
            const precio = parseNumber(fila.querySelector('.precio')?.value);
            const kilos = parseNumber(fila.querySelector('.kilos')?.value);
            const importe = precio * kilos;
            const secaEquivalente = kilos / factorVerdeSeca;
            
            const importeInput = fila.querySelector('.importe');
            const secaInput = fila.querySelector('.seca-equivalente');
            
            if (importeInput) importeInput.value = formatNumberWithCommas(importe, 2);
            if (secaInput) secaInput.value = formatNumberWithCommas(secaEquivalente, 6);
            
            totalKilosVerde += kilos;
            totalImporteVerde += importe;
            totalSecaEquivalente += secaEquivalente;
        });
        
        $('#total-kilos-verde').value = formatNumberWithCommas(totalKilosVerde, 1);
        $('#total-importe-verde').value = formatNumberWithCommas(totalImporteVerde, 2);
        $('#total-seca-equivalente').value = formatNumberWithCommas(totalSecaEquivalente, 6);
        
        // 4. Calcular pimienta seca convencional
        let totalKilosSecaConv = 0;
        let totalImporteSecaConv = 0;
        
        $$('#tabla-seca-convencional tbody tr').forEach(fila => {
            const precio = parseNumber(fila.querySelector('.precio')?.value);
            const kilos = parseNumber(fila.querySelector('.kilos')?.value);
            const importe = precio * kilos;
            
            const importeInput = fila.querySelector('.importe');
            if (importeInput) importeInput.value = formatNumberWithCommas(importe, 2);
            
            totalKilosSecaConv += kilos;
            totalImporteSecaConv += importe;
        });
        
        $('#total-kilos-seca-conv').value = formatNumberWithCommas(totalKilosSecaConv, 1);
        $('#total-importe-seca-conv').value = formatNumberWithCommas(totalImporteSecaConv, 2);
        
        // 5. Calcular dinero comprobado
        const totalImporteAcopiado = totalImporteRama + totalImporteVerde + totalImporteSecaConv;
        elementos.dineroComprobado.value = formatNumberWithCommas(totalImporteAcopiado, 2);
        
        // 6. Calcular producto a pagar con mayor precisión
        const subtotalRama = calcularPagoRama();
        const subtotalVerde = calcularPagoVerde();
        const subtotalSeca = calcularPagoSeca();
        
        const totalImporteConvencional = subtotalRama + subtotalVerde + subtotalSeca;
        $('#importe-total-convencional').value = formatNumberWithCommas(totalImporteConvencional, 2);
        elementos.totalPimienta.value = formatNumberWithCommas(totalImporteConvencional, 2);
        
        return totalImporteAcopiado;
    }
    
    function calcularPagoRama() {
        let subtotal = 0;
        
        $$('#tabla-rama-pago tbody tr').forEach(fila => {
            const precioAcopio = parseNumber(fila.querySelector('.precio-acopio')?.value);
            const kilosAcopiados = parseNumber(fila.querySelector('.kilos-acopiados')?.value);
            const kilosEntregados = parseNumber(fila.querySelector('.kilos-entregados')?.value);
            
            const kilosParaCalculo = kilosEntregados > 0 ? kilosEntregados : kilosAcopiados;
            const precioAPagar = precioAcopio * factorPrecioPago;
            const kilosSecaPagar = kilosParaCalculo / factorRamaSeca;
            const importe = kilosParaCalculo * precioAPagar;
            
            const kilosSecaPagarInput = fila.querySelector('.kilos-seca-pagar');
            const precioPagarInput = fila.querySelector('.precio-pagar');
            const importeInput = fila.querySelector('.importe');
            
            if (kilosSecaPagarInput) kilosSecaPagarInput.value = formatNumberWithCommas(kilosSecaPagar, 6);
            if (precioPagarInput) precioPagarInput.value = formatNumberWithCommas(precioAPagar, 2);
            if (importeInput) {
                importeInput.value = formatNumberWithCommas(importe, 2);
                subtotal += importe;
            }
        });
        
        $('#subtotal-rama-pago').value = formatNumberWithCommas(subtotal, 2);
        return subtotal;
    }
    
    function calcularPagoVerde() {
        let subtotal = 0;
        
        $$('#tabla-verde-pago tbody tr').forEach(fila => {
            const precioAcopio = parseNumber(fila.querySelector('.precio-acopio')?.value);
            const kilosAcopiados = parseNumber(fila.querySelector('.kilos-acopiados')?.value);
            const kilosEntregados = parseNumber(fila.querySelector('.kilos-entregados')?.value);
            
            const kilosParaCalculo = kilosEntregados > 0 ? kilosEntregados : kilosAcopiados;
            const precioAPagar = precioAcopio * factorPrecioPago;
            const kilosSecaPagar = kilosParaCalculo / factorVerdeSeca;
            const importe = kilosParaCalculo * precioAPagar;
            
            const kilosSecaPagarInput = fila.querySelector('.kilos-seca-pagar');
            const precioPagarInput = fila.querySelector('.precio-pagar');
            const importeInput = fila.querySelector('.importe');
            
            if (kilosSecaPagarInput) kilosSecaPagarInput.value = formatNumberWithCommas(kilosSecaPagar, 6);
            if (precioPagarInput) precioPagarInput.value = formatNumberWithCommas(precioAPagar, 2);
            if (importeInput) {
                importeInput.value = formatNumberWithCommas(importe, 2);
                subtotal += importe;
            }
        });
        
        $('#subtotal-verde-pago').value = formatNumberWithCommas(subtotal, 2);
        return subtotal;
    }
    
    function calcularPagoSeca() {
        let subtotal = 0;
        
        $$('#tabla-seca-pago tbody tr').forEach(fila => {
            const precioAcopio = parseNumber(fila.querySelector('.precio-acopio')?.value);
            const kilosAcopiados = parseNumber(fila.querySelector('.kilos-acopiados')?.value);
            const kilosEntregados = parseNumber(fila.querySelector('.kilos-entregados')?.value);
            
            const kilosParaCalculo = kilosEntregados > 0 ? kilosEntregados : kilosAcopiados;
            const importe = kilosParaCalculo * precioAcopio;
            
            const importeInput = fila.querySelector('.importe');
            if (importeInput) {
                importeInput.value = formatNumberWithCommas(importe, 2);
                subtotal += importe;
            }
        });
        
        $('#subtotal-seca-pago').value = formatNumberWithCommas(subtotal, 2);
        return subtotal;
    }
    
    // ============================================
    // CÁLCULOS DE ENTREGAS EN ALMACÉN
    // ============================================
    
    function calcularEntregasAlmacen() {
        // Pimienta verde o en proceso
        $$('#tabla-verde-proceso tbody tr').forEach(fila => {
            const kilosCentro = parseNumber(fila.querySelector('.kilos-centro')?.value);
            const kilosBeneficio = parseNumber(fila.querySelector('.kilos-beneficio')?.value);
            const kilosSecaResultado = parseNumber(fila.querySelector('.kilos-seca-resultado')?.value);
            
            const diferencia = kilosCentro - kilosBeneficio;
            const diferenciaInput = fila.querySelector('.diferencia');
            const rendimientoInput = fila.querySelector('.rendimiento');
            
            if (diferenciaInput) {
                diferenciaInput.value = formatNumberWithCommas(diferencia, 1);
                if (diferencia < 0) {
                    diferenciaInput.classList.add('diferencia-negativa');
                    diferenciaInput.classList.remove('diferencia-positiva');
                } else if (diferencia > 0) {
                    diferenciaInput.classList.add('diferencia-positiva');
                    diferenciaInput.classList.remove('diferencia-negativa');
                } else {
                    diferenciaInput.classList.remove('diferencia-negativa', 'diferencia-positiva');
                }
            }
            
            if (rendimientoInput && kilosBeneficio > 0) {
                const rendimiento = kilosSecaResultado / kilosBeneficio;
                rendimientoInput.value = formatNumberWithCommas(rendimiento, 6);
            }
        });
        
        // Pimienta seca entregada
        let totalKilosCentro = 0;
        let totalKilosAlmacen = 0;
        let totalDiferencia = 0;
        
        $$('#tabla-seca-entregada tbody tr').forEach(fila => {
            const kilosCentro = parseNumber(fila.querySelector('.kilos-centro')?.value);
            const kilosAlmacen = parseNumber(fila.querySelector('.kilos-almacen')?.value);
            const diferencia = kilosCentro - kilosAlmacen;
            
            const diferenciaInput = fila.querySelector('.diferencia');
            if (diferenciaInput) {
                diferenciaInput.value = formatNumberWithCommas(diferencia, 1);
                if (diferencia < 0) {
                    diferenciaInput.classList.add('diferencia-negativa');
                    diferenciaInput.classList.remove('diferencia-positiva');
                } else if (diferencia > 0) {
                    diferenciaInput.classList.add('diferencia-positiva');
                    diferenciaInput.classList.remove('diferencia-negativa');
                } else {
                    diferenciaInput.classList.remove('diferencia-negativa', 'diferencia-positiva');
                }
            }
            
            totalKilosCentro += kilosCentro;
            totalKilosAlmacen += kilosAlmacen;
            totalDiferencia += diferencia;
        });
        
        $('#total-kilos-centro').value = formatNumberWithCommas(totalKilosCentro, 1);
        $('#total-kilos-almacen').value = formatNumberWithCommas(totalKilosAlmacen, 1);
        
        const totalDiferenciaInput = $('#total-diferencia');
        if (totalDiferenciaInput) {
            totalDiferenciaInput.value = formatNumberWithCommas(totalDiferencia, 1);
            if (totalDiferencia < 0) {
                totalDiferenciaInput.classList.add('diferencia-negativa');
                totalDiferenciaInput.classList.remove('diferencia-positiva');
            } else if (totalDiferencia > 0) {
                totalDiferenciaInput.classList.add('diferencia-positiva');
                totalDiferenciaInput.classList.remove('diferencia-negativa');
            } else {
                totalDiferenciaInput.classList.remove('diferencia-negativa', 'diferencia-positiva');
            }
        }
    }
    
    // ============================================
    // CÁLCULOS FINANCIEROS
    // ============================================
    
    function calcularFinanciero() {
        const dineroEntregado = parseNumber(elementos.dineroEntregado?.value) || 0;
        const descuentoAnticipo = parseNumber(elementos.descuentoAnticipo?.value) || 0;
        const otrosCargos = parseNumber(elementos.otrosCargos?.value) || 0;
        const dineroComprobado = parseNumber(elementos.dineroComprobado?.value) || 0;
        
        let totalCargo = dineroEntregado;
        
        if (tipoActual === 'organica') {
            totalCargo += descuentoAnticipo;
        } else {
            totalCargo += otrosCargos;
        }
        
        const saldoAcopiador = totalCargo - dineroComprobado;
        
        if (elementos.totalDineroCargo) elementos.totalDineroCargo.value = formatNumberWithCommas(totalCargo, 2);
        if (elementos.saldoAcopiador) elementos.saldoAcopiador.value = formatNumberWithCommas(saldoAcopiador, 2);
    }
    
    function calcularTotalesFinales() {
        const totalPimienta = parseNumber(elementos.totalPimienta?.value) || 0;
        let totalComisiones = 0;
        
        // Calcular comisiones según el tipo
        if (tipoActual === 'organica') {
            const comisionBase = parseNumber($('input[name="comision_base_org"]')?.value) || 0;
            const comisionRendimiento = parseNumber($('input[name="comision_rendimiento_org"]')?.value) || 0;
            const comisionCierre = parseNumber($('input[name="comision_cierre_org"]')?.value) || 0;
            const totalKilosOrganica = parseNumber($('#total-kilos-organica')?.value) || 0;
            
            totalComisiones = (comisionBase + comisionRendimiento + comisionCierre) * totalKilosOrganica;
        } else {
            // Calcular comisiones para convencional (valores editables)
            const comisiones = [
                { 
                    kilos: $('input[name="kilos_comision_palillo"]'), 
                    comision: $('input[name="comision_kilo_palillo"]'), 
                    importe: $('input[name="importe_comision_palillo"]') 
                },
                { 
                    kilos: $('input[name="kilos_comision_verde"]'), 
                    comision: $('input[name="comision_kilo_verde"]'), 
                    importe: $('input[name="importe_comision_verde"]') 
                },
                { 
                    kilos: $('input[name="kilos_comision_verde_base"]'), 
                    comision: $('input[name="comision_kilo_verde_base"]'), 
                    importe: $('input[name="importe_comision_verde_base"]') 
                },
                { 
                    kilos: $('input[name="kilos_comision_verde_rendimiento"]'), 
                    comision: $('input[name="comision_kilo_verde_rendimiento"]'), 
                    importe: $('input[name="importe_comision_verde_rendimiento"]') 
                },
                { 
                    kilos: $('input[name="kilos_comision_verde_recursos"]'), 
                    comision: $('input[name="comision_kilo_verde_recursos"]'), 
                    importe: $('input[name="importe_comision_verde_recursos"]') 
                },
                { 
                    kilos: $('input[name="kilos_comision_verde_cierre"]'), 
                    comision: $('input[name="comision_kilo_verde_cierre"]'), 
                    importe: $('input[name="importe_comision_verde_cierre"]') 
                },
                { 
                    kilos: $('input[name="kilos_comision_seca_base"]'), 
                    comision: $('input[name="comision_kilo_seca_base"]'), 
                    importe: $('input[name="importe_comision_seca_base"]') 
                },
                { 
                    kilos: $('input[name="kilos_comision_seca_recursos"]'), 
                    comision: $('input[name="comision_kilo_seca_recursos"]'), 
                    importe: $('input[name="importe_comision_seca_recursos"]') 
                },
                { 
                    kilos: $('input[name="kilos_comision_seca_cierre"]'), 
                    comision: $('input[name="comision_kilo_seca_cierre"]'), 
                    importe: $('input[name="importe_comision_seca_cierre"]') 
                }
            ];
            
            comisiones.forEach(item => {
                const kilos = parseNumber(item.kilos?.value);
                const comision = parseNumber(item.comision?.value);
                const importe = kilos * comision;
                
                // Actualizar importe en la tabla
                if (item.importe) {
                    item.importe.value = formatNumberWithCommas(importe, 2);
                }
                
                totalComisiones += importe;
            });
            
            $('#total-comisiones').value = formatNumberWithCommas(totalComisiones, 2);
        }
        
        const totalAPagar = totalPimienta + totalComisiones;
        const dineroEntregado = parseNumber(elementos.dineroEntregado?.value) || 0;
        const otros = parseNumber(elementos.otrosCargos?.value) || 0;
        const saldoFinal = (dineroEntregado + otros) - totalAPagar;
        
        if (elementos.totalComisionesFinal) elementos.totalComisionesFinal.value = formatNumberWithCommas(totalComisiones, 2);
        if (elementos.totalAPagar) elementos.totalAPagar.value = formatNumberWithCommas(totalAPagar, 2);
        if (elementos.saldoFinal) {
            elementos.saldoFinal.value = formatNumberWithCommas(saldoFinal, 2);
            // Aplicar estilos según el saldo
            if (saldoFinal < 0) {
                elementos.saldoFinal.classList.add('diferencia-negativa');
                elementos.saldoFinal.classList.remove('diferencia-positiva');
            } else if (saldoFinal > 0) {
                elementos.saldoFinal.classList.add('diferencia-positiva');
                elementos.saldoFinal.classList.remove('diferencia-negativa');
            } else {
                elementos.saldoFinal.classList.remove('diferencia-negativa', 'diferencia-positiva');
            }
        }
    }
    
    // ============================================
    // FUNCIÓN PRINCIPAL DE RECÁLCULO
    // ============================================
    
    function recalcularTodo() {
        // Actualizar factores
        const factorPagoInput = $('input[name="factor_precio_pago_calc"]');
        if (factorPagoInput) {
            factorPrecioPago = parseNumber(factorPagoInput.value);
        }
        
        // Calcular según el tipo
        if (tipoActual === 'organica') {
            calcularOrganica();
        } else {
            calcularConvencional();
        }
        
        // Calcular entregas en almacén
        calcularEntregasAlmacen();
        
        // Calcular financiero y totales
        calcularFinanciero();
        calcularTotalesFinales();
    }
    
    // ============================================
    // SINCRONIZACIÓN DE DATOS
    // ============================================
    
    function sincronizarRamaPago() {
        if (tipoActual !== 'convencional') return;
        
        const tbody = $('#tabla-rama-pago tbody');
        if (!tbody) return;
        
        // Limpiar tabla actual
        tbody.innerHTML = '';
        
        // Sincronizar desde acopio con rama
        $$('#tabla-con-rama tbody tr').forEach(fila => {
            const precio = parseNumber(fila.querySelector('.precio')?.value);
            const kilos = parseNumber(fila.querySelector('.kilos')?.value);
            
            if (precio > 0 && kilos > 0) {
                agregarFila('tabla-rama-pago', '#template-rama-pago');
                
                const ultimaFila = tbody.lastElementChild;
                if (ultimaFila) {
                    const precioInput = ultimaFila.querySelector('.precio-acopio');
                    const kilosInput = ultimaFila.querySelector('.kilos-acopiados');
                    
                    if (precioInput) precioInput.value = precio;
                    if (kilosInput) kilosInput.value = kilos;
                }
            }
        });
        
        recalcularTodo();
    }
    
    function sincronizarVerdePago() {
        if (tipoActual !== 'convencional') return;
        
        const tbody = $('#tabla-verde-pago tbody');
        if (!tbody) return;
        
        // Limpiar tabla actual
        tbody.innerHTML = '';
        
        // Sincronizar desde acopio verde
        $$('#tabla-verde tbody tr').forEach(fila => {
            const precio = parseNumber(fila.querySelector('.precio')?.value);
            const kilos = parseNumber(fila.querySelector('.kilos')?.value);
            
            if (precio > 0 && kilos > 0) {
                agregarFila('tabla-verde-pago', '#template-verde-pago');
                
                const ultimaFila = tbody.lastElementChild;
                if (ultimaFila) {
                    const precioInput = ultimaFila.querySelector('.precio-acopio');
                    const kilosInput = ultimaFila.querySelector('.kilos-acopiados');
                    
                    if (precioInput) precioInput.value = precio;
                    if (kilosInput) kilosInput.value = kilos;
                }
            }
        });
        
        recalcularTodo();
    }
    
    function sincronizarSecaPago() {
        if (tipoActual !== 'convencional') return;
        
        const tbody = $('#tabla-seca-pago tbody');
        if (!tbody) return;
        
        // Limpiar tabla actual
        tbody.innerHTML = '';
        
        // Sincronizar desde acopio seca convencional
        $$('#tabla-seca-convencional tbody tr').forEach(fila => {
            const precio = parseNumber(fila.querySelector('.precio')?.value);
            const kilos = parseNumber(fila.querySelector('.kilos')?.value);
            
            if (precio > 0 && kilos > 0) {
                agregarFila('tabla-seca-pago', '#template-seca-pago');
                
                const ultimaFila = tbody.lastElementChild;
                if (ultimaFila) {
                    const precioInput = ultimaFila.querySelector('.precio-acopio');
                    const kilosInput = ultimaFila.querySelector('.kilos-acopiados');
                    
                    if (precioInput) precioInput.value = precio;
                    if (kilosInput) kilosInput.value = kilos;
                }
            }
        });
        
        recalcularTodo();
    }
    
    // Event listeners para botones de sincronización
    document.getElementById('btn-sincronizar-rama')?.addEventListener('click', sincronizarRamaPago);
    document.getElementById('btn-sincronizar-verde')?.addEventListener('click', sincronizarVerdePago);
    document.getElementById('btn-sincronizar-seca')?.addEventListener('click', sincronizarSecaPago);
    document.getElementById('btn-sincronizar-comisiones')?.addEventListener('click', sincronizarComisiones);
    document.getElementById('btn-restablecer-comisiones')?.addEventListener('click', restablecerComisiones);
    
    // ============================================
    // VALIDACIÓN DEL FORMULARIO
    // ============================================
    
    function validarFormulario() {
        let valido = true;
        
        // Validar campos requeridos
        const camposRequeridos = [
            '#cooperativa',
            '#centro',
            '#fecha',
            '#acopiador',
            '#dinero-entregado',
            'input[name="firmo_elaboro"]',
            'input[name="firmo_autorizo"]',
            'input[name="firmo_acopiador"]'
        ];
        
        camposRequeridos.forEach(selector => {
            const campo = $(selector);
            if (campo && !campo.value.trim()) {
                campo.classList.add('is-invalid');
                valido = false;
            } else if (campo) {
                campo.classList.remove('is-invalid');
            }
        });
        
        // Validar que haya datos de pimienta
        let tieneDatos = false;
        
        if (tipoActual === 'organica') {
            tieneDatos = $$('#tabla-seca-organica tbody tr').length > 0;
        } else {
            tieneDatos = $$('#tabla-con-rama tbody tr').length > 0 ||
                         $$('#tabla-verde tbody tr').length > 0 ||
                         $$('#tabla-seca-convencional tbody tr').length > 0;
        }
        
        if (!tieneDatos) {
            alert('Debe agregar al menos un registro de pimienta acopiada');
            valido = false;
        }
        
        return valido;
    }
    
    // ============================================
    // FUNCIONALIDAD DE BOTONES
    // ============================================
    
    // Limpiar formulario
    $('#btn-limpiar')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('¿Está seguro de limpiar todo el formulario? Se perderán todos los datos.')) {
            document.getElementById('form-cierre-universal').reset();
            
            // Restaurar valores de comisiones
            restablecerComisiones();
            
            // Limpiar tablas dinámicas (manteniendo una fila en cada una)
            const tablas = [
                'tabla-seca-organica',
                'tabla-con-rama',
                'tabla-verde',
                'tabla-seca-convencional',
                'tabla-verde-proceso',
                'tabla-seca-entregada',
                'tabla-rama-pago',
                'tabla-verde-pago',
                'tabla-seca-pago'
            ];
            
            tablas.forEach(tablaId => {
                const tablaBody = $(`#${tablaId} tbody`);
                if (tablaBody) {
                    const primeraFila = tablaBody.querySelector('tr');
                    tablaBody.innerHTML = '';
                    if (primeraFila) {
                        tablaBody.appendChild(primeraFila);
                    }
                }
            });
            
            // Restaurar valores iniciales
            cambiarTipoPimienta('organica');
            generarFolio();
            
            recalcularTodo();
        }
    });
    
    // Previsualizar
    $('#btn-previsualizar')?.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (!validarFormulario()) {
            alert('Por favor complete todos los campos requeridos');
            return;
        }
        
        alert('Función de previsualización en desarrollo');
    });
    
    // Generar PDF
    $('#btn-generar-pdf')?.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (!validarFormulario()) {
            alert('Por favor complete todos los campos requeridos');
            return;
        }
        
        alert('Función de generación de PDF en desarrollo');
    });
    
    // Enviar formulario
    $('#form-cierre-universal')?.addEventListener('submit', function(e) {
        if (!validarFormulario()) {
            e.preventDefault();
            alert('Por favor complete todos los campos requeridos');
        }
    });
    
    // ============================================
    // EVENT LISTENERS GLOBALES
    // ============================================
    
    // Agregar event listeners a todos los inputs numéricos para recalcular
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[type="number"], select, .comision-input, .comision-kilo-editable, .precio, .kilos')) {
            recalcularTodo();
        }
    });
    
    // ============================================
    // INICIALIZACIÓN
    // ============================================
    
    // Generar folio inicial
    generarFolio();
    
    // Agregar filas iniciales
    agregarFila('tabla-seca-organica', '#template-fila-seca-organica');
    agregarFila('tabla-con-rama', '#template-fila-con-rama');
    agregarFila('tabla-verde', '#template-fila-verde');
    agregarFila('tabla-seca-convencional', '#template-fila-seca-convencional');
    agregarFila('tabla-verde-proceso', '#template-verde-proceso');
    agregarFila('tabla-seca-entregada', '#template-seca-entregada');
    agregarFila('tabla-rama-pago', '#template-rama-pago');
    agregarFila('tabla-verde-pago', '#template-verde-pago');
    agregarFila('tabla-seca-pago', '#template-seca-pago');
    
    // Calcular valores iniciales
    recalcularTodo();
    
    console.log('Sistema de cierre de cuentas inicializado correctamente');
});
</script>

<style>
/* Estilos para diferencias */
.diferencia-negativa {
    background-color: rgba(220, 53, 69, 0.1) !important;
    color: #dc3545;
    font-weight: bold;
}

.diferencia-positiva {
    background-color: rgba(25, 135, 84, 0.1) !important;
    color: #198754;
    font-weight: bold;
}

/* Estilos para comisiones editables */
.comision-kilo-editable {
    background-color: #fff3cd !important;
    border-color: #ffc107 !important;
    font-weight: bold;
}

.comision-kilo-editable:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
}

/* Estilos para tablas de comisiones */
.table-info {
    background-color: #d1ecf1 !important;
}

/* Estilos para campos calculados */
.campo-calculado {
    background-color: #f8f9fa;
    font-weight: bold;
}

.campo-importante {
    background-color: #fff3cd;
    font-weight: bold;
}

/* Estilos para selectores de tipo */
.tipo-selector.active {
    border-color: #198754;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

.tipo-selector.active#selector-convencional {
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.25rem rgba(253, 126, 20, 0.25);
}

.badge-organica {
    background-color: #198754;
    color: white;
}

.badge-convencional {
    background-color: #fd7e14; 
    color: white;
}

/* Estilos para tablas */
.table-fixed {
    table-layout: fixed;
}

/* Estilos para botones */
.btn-outline-success.active {
    background-color: #198754;
    color: white;
}

.btn-outline-primary.active {
    background-color: #fd7e14;
    color: white;
}

/* Estilos adicionales para mantener consistencia */
.btn-outline-primary {
    color: #fd7e14;
    border-color: #fd7e14;
}

.btn-outline-primary:hover {
    background-color: #fd7e14;
    color: white;
    border-color: #fd7e14;
}

/* Ajuste para el botón convencional en el selector */
.btn-outline-primary[for="tipo_convencional"]:active,
.btn-outline-primary[for="tipo_convencional"].active {
    background-color: #fd7e14;
    color: white;
    border-color: #fd7e14;
}

/* Estilos para botón convencional cuando está seleccionado */
#selector-convencional.active {
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.25rem rgba(253, 126, 20, 0.25);
}

/* Ajuste para el subtítulo convencional */
#subtitulo-tipo.badge-convencional {
    background-color: #fd7e14;
    color: white;
}

/* Estilos para campos formateados con comas */
input[data-format-commas] {
    text-align: right;
    font-family: 'Courier New', monospace;
    background-color: #f8f9fa !important;
    color: #495057;
}

/* Estilos específicos para campos de solo lectura */
.campo-calculado[readonly] {
    background-color: #e9ecef !important;
    font-weight: bold;
    color: #495057;
}

/* Mejorar la legibilidad de números con comas */
.table input[readonly] {
    background-color: #f8f9fa !important;
    border-color: #dee2e6 !important;
}

/* Asegurar que los campos con comas mantengan alineación */
.table-fixed td input {
    width: 100%;
    text-align: right;
}

/* Estilos para inputs en tablas */
.table input.form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

<?= $this->endSection() ?>