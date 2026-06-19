<?php
$tramite = $_GET['tramite'] ?? '';
$tramites = json_decode(file_get_contents("json/tramites.json"), true);
$datos = null;

if (!empty($tramite) && is_array($tramites)) {
    foreach($tramites as $t){
        if($t['nrotramite'] == $tramite){
            $datos = $t;
            break;
        }
    }
}
?>

<h4>Antecedentes de Secundaria</h4>
<p class="text-muted mb-4">Último paso del registro de datos personales de la solicitud.</p>

<div class="mb-3">
    <label class="form-label">Unidad Educativa / Colegio de Graduación</label>
    <input 
        type="text" 
        name="colegio_origen" 
        class="form-control" 
        placeholder="Ej: Colegio Nacional San Martín"
        value="<?php echo htmlspecialchars($datos['colegio_origen'] ?? ''); ?>" 
        required>
</div>

<div class="alert alert-warning d-flex align-items-center gap-2">
    <i class="bi bi-info-circle-fill"></i>
    <span>Al pulsar <strong>Siguiente</strong>, tu trámite pasará a revisión del sistema académico de forma automática.</span>
</div>