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

<h4>Registro de Información Socioeconómica</h4>
<p class="text-muted mb-4">Por favor completa tus datos residenciales para control interno de becas.</p>

<div class="mb-3">
    <label class="form-label">Dirección de Domicilio Actual</label>
    <input 
        type="text" 
        name="direccion" 
        class="form-control" 
        placeholder="Ej: Av. Arce Nro 2412, Edif. Los Pinos"
        value="<?php echo htmlspecialchars($datos['direccion'] ?? ''); ?>" 
        required>
</div>

<div class="mb-3">
    <label class="form-label">Teléfono / Celular de Contacto</label>
    <input 
        type="text" 
        name="telefono" 
        class="form-control" 
        placeholder="Ej: 71234567"
        value="<?php echo htmlspecialchars($datos['telefono'] ?? ''); ?>" 
        required>
</div>