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

<h4>Procesamiento de Certificado Oficial</h4>

<?php if($datos){ ?>
<div class="alert alert-success">
    El pago ha sido acreditado. Proceda a estructurar el contenido definitivo del certificado.
</div>

<div class="card">
    <div class="card-header bg-success text-white">Estructura del Certificado Digital</div>
    <div class="card-body">
        <p><strong>Destinatario / Estudiante:</strong> <?= htmlspecialchars($datos['usuario']) ?></p>
        <p><strong>Tipo de Documento:</strong> <?= htmlspecialchars($datos['tipo_certificado'] ?? 'Certificado General') ?></p>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Cuerpo de Texto / Promedio y Rendimiento Histórico</label>
            <textarea name="cuerpo_certificado" class="form-control" rows="5" required>Por la presente se certifica que el estudiante mencionado cuenta con un récord académico idóneo, habiendo aprobado las asignaturas troncales con un rendimiento regular satisfactorio en la gestión vigente...</textarea>
        </div>
    </div>
</div>
<?php } else { ?>
    <div class="alert alert-danger">Trámite inválido.</div>
<?php } ?>