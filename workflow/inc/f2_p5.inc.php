<?php
$tramite = $_GET['tramite'] ?? '';
$tramites = json_decode(file_get_contents("json/tramites.json"), true);
$datos = null;

foreach($tramites as $t){
    if($t['nrotramite'] == $tramite){
        $datos = $t;
        break;
    }
}
?>

<h4>Caja - Verificación de Pago de Valores</h4>

<?php if($datos){ ?>
<div class="card mb-3">
    <div class="card-header bg-primary text-white">Datos de Cobro</div>
    <div class="card-body">
        <p><strong>Nro. Trámite:</strong> <?= htmlspecialchars($datos['nrotramite']) ?></p>
        <p><strong>Estudiante:</strong> <?= htmlspecialchars($datos['usuario']) ?></p>
        <p><strong>Concepto:</strong> <?= htmlspecialchars($datos['tipo_certificado'] ?? 'Arancel Universitario') ?></p>
    </div>
</div>

<div class="card">
    <div class="card-header bg-warning">Validación de Transacción Bancaria</div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-bold">¿Registra pago en el sistema financiero?</label>
            <select name="pago" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="SI">SI - Pago Correcto Verificado</option>
                <option value="NO">NO - Sin Registro de Pago / Arancel Caducado</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Nota o Nro. de Comprobante de Caja</label>
            <textarea name="obs_pago" class="form-control" rows="2" placeholder="Ingrese el código de depósito de caja..."></textarea>
        </div>
    </div>
</div>
<?php } else { ?>
    <div class="alert alert-danger">Error al recuperar la información transaccional.</div>
<?php } ?>