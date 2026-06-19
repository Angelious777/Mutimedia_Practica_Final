<?php
// 1. Inicializar las variables para evitar errores si el trámite es nuevo
$tipo_certificado_guardado = "";
$observaciones_guardadas = "";

// 2. Recuperar el número de trámite actual desde la URL (inyectado por index.php)
$tramite_actual = $_GET['tramite'] ?? '';

if (!empty($tramite_actual)) {
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    
    // 3. Buscar si este trámite ya existe en el histórico para recuperar los valores
    foreach ($tramites as $t) {
        if ($t['nrotramite'] == $tramite_actual) {
            $tipo_certificado_guardado = $t['tipo_certificado'] ?? '';
            $observaciones_guardadas = $t['observaciones'] ?? '';
            break;
        }
    }
}
?>

<h4>Solicitud de Certificado Académico</h4>

<div class="alert alert-info">
    Bienvenido al asistente de emisión de certificados digitales. Por favor, seleccione el tipo de documento requerido.
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Tipo de Certificado</label>
    <select name="tipo_certificado" class="form-select" required>
        <option value="">Seleccione una opción...</option>
        <option value="Certificado de Estudios" <?php echo ($tipo_certificado_guardado == "Certificado de Estudios") ? 'selected' : ''; ?>>
            Certificado de Calificaciones / Estudios
        </option>
        <option value="Certificado de Alumno Regular" <?php echo ($tipo_certificado_guardado == "Certificado de Alumno Regular") ? 'selected' : ''; ?>>
            Certificado de Alumno Regular
        </option>
        <option value="Certificado de No Adeudo" <?php echo ($tipo_certificado_guardado == "Certificado de No Adeudo") ? 'selected' : ''; ?>>
            Certificado de Historial de No Adeudo
        </option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Motivo de la Solicitud</label>
    <textarea name="observaciones" class="form-control" rows="3" placeholder="Ej. Para trámites laborales, becas, etc." required><?php echo htmlspecialchars($observaciones_guardadas); ?></textarea>
</div>