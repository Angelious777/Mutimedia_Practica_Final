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

<h4>Despacho de Secretaría - Firma Digital</h4>

<?php if($datos){ ?>
<div class="card border-success">
    <div class="card-header bg-dark text-white">Documento Pendiente de Visado</div>
    <div class="card-body">
        <h5 class="card-title text-primary"><?= htmlspecialchars($datos['tipo_certificado'] ?? 'Certificado') ?></h5>
        <blockquote class="blockquote bg-light p-3 border rounded font-monospace" style="font-size: 0.9rem;">
            <?= htmlspecialchars($datos['cuerpo_certificado'] ?? 'Sin contenido guardado.') ?>
        </blockquote>
        
        <hr>
        
        <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" name="firmado" id="firmaSwitch" value="SI" required>
            <label class="form-check-label fw-bold text-success" for="firmaSwitch">
                Estampar Token de Firma Digital Institucional (Secretaría General)
            </label>
        </div>
    </div>
</div>
<?php } else { ?>
    <div class="alert alert-danger">Trámite no encontrado para firma.</div>
<?php } ?>