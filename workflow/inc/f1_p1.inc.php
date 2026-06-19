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

<h4>Solicitud de Inscripción - Datos Generales</h4>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Gestión Académica</label>
        <input 
            type="number" 
            name="gestion" 
            class="form-control" 
            value="<?php echo htmlspecialchars($datos['gestion'] ?? ''); ?>" 
            required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Semestre</label>
        <select name="semestre" class="form-control">
            <option value="1" <?php echo (isset($datos['semestre']) && $datos['semestre'] == '1') ? 'selected' : ''; ?>>Primer Semestre</option>
            <option value="2" <?php echo (isset($datos['semestre']) && $datos['semestre'] == '2') ? 'selected' : ''; ?>>Segundo Semestre</option>
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Observaciones</label>
    <textarea
        name="observaciones"
        class="form-control"
        rows="4"><?php echo htmlspecialchars($datos['observaciones'] ?? ''); ?></textarea>
</div>

<div class="alert alert-info">
    Al enviar la solicitud el sistema verificará en pasos posteriores:
    <ul class="mb-0">
        <li>Estado académico</li>
        <li>Matrícula vigente</li>
        <li>Habilitación para inscripción</li>
    </ul>
</div>