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

<h4>Revisión de Documentos - Kardex</h4>

<?php if($datos){ ?>
<div class="card mb-3">
    <div class="card-header bg-dark text-white">Datos de la Solicitud (Trámite: <?= htmlspecialchars($datos['nrotramite']) ?>)</div>
    <div class="card-body">
        <p><strong>Usuario Solicitante:</strong> <?= htmlspecialchars($datos['usuario']) ?></p>
        <p><strong>Tipo Documento:</strong> <?= htmlspecialchars($datos['tipo_certificado'] ?? 'Certificado General') ?></p>
        <p><strong>Motivo/Detalles:</strong> <?= htmlspecialchars($datos['observaciones'] ?? '-') ?></p>
    </div>
</div>

<div class="card border-warning">
    <div class="card-header bg-warning">Resolución del Estado de Documentación</div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-bold">Resultado de Validación</label>
            <select name="documentos" class="form-select" required>
                <option value="">Seleccione dictamen...</option>
                <option value="VALIDOS">VALIDOS - Enviar a Pago de Aranceles</option>
                <option value="OBSERVADOS">OBSERVADOS - Notificar Errores al Alumno</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Observaciones de la Validación (Kardex)</label>
            <textarea name="obs_documentos" class="form-control" rows="3" placeholder="Detalle si hay algún documento borroso o faltante..."></textarea>
        </div>
    </div>
</div>
<?php } else { ?>
    <div class="alert alert-danger">No se encontró información del trámite.</div>
<?php } ?>