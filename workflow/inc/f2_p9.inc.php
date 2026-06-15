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

<h4>Su Certificado Digital ha Sido Emitido</h4>

<div class="card text-center shadow-sm">
    <div class="card-header bg-success text-white">✔ Emisión Completada Exitosamente</div>
    <div class="card-body py-4">
        <i class="bi bi-file-earmark-check text-success" style="font-size: 3rem;"></i>
        <h4 class="mt-2"><?= htmlspecialchars($datos['tipo_certificado'] ?? 'Certificado Académico') ?></h4>
        <p class="card-text text-muted">Firmado digitalmente por la Secretaría General el <?= date("Y-m-d") ?>.</p>
        
        <div class="my-4 mx-auto p-3 bg-light border rounded" style="max-width: 500px;">
            <p class="small text-start mb-1"><strong>Código de Validación Único (Hash):</strong></p>
            <p class="font-monospace text-start small text-truncate text-primary mb-0">
                <?= md5($tramite . "2026") ?>a8b3cd4fe9219
            </p>
        </div>
        
        <button type="button" class="btn btn-primary" onclick="alert('Descargando documento simulado...')">
            <i class="bi bi-download"></i> Descargar Certificado PDF
        </button>
    </div>
    <div class="card-footer text-muted font-monospace" style="font-size: 0.8rem;">
        Trámite Nro: <?= htmlspecialchars($tramite) ?>
    </div>
</div>