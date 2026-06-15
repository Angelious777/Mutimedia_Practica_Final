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

<h4>Notificación de Rechazo de Requisitos</h4>

<div class="alert alert-danger">
    <strong>Trámite Cancelado / Observado:</strong> Su documentación no cumple con las directrices académicas estipuladas.
</div>

<div class="card">
    <div class="card-header bg-secondary text-white">Detalle de la Observación de Kardex</div>
    <div class="card-body">
        <p><strong>Nro. Trámite:</strong> <?= htmlspecialchars($tramite) ?></p>
        <div class="p-3 bg-light border rounded text-danger">
            <?= !empty($datos['obs_documentos']) ? htmlspecialchars($datos['obs_documentos']) : 'Documentación ilegible o incompleta.' ?>
        </div>
        <p class="mt-3 text-muted"><small>Para subsanar esta observación deberá iniciar un nuevo trámite adjuntando los archivos correctos.</small></p>
    </div>
</div>