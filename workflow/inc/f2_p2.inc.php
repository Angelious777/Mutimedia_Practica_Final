<?php
$tramite = $_GET['tramite'] ?? '';
?>

<h4>Carga de Requisitos Digitales</h4>

<div class="alert alert-warning">
    Para continuar, asegúrese de adjuntar sus documentos en formato PDF legible.
</div>

<div class="card mb-4">
    <div class="card-header bg-secondary text-white">Requisitos Obligatorios</div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-bold">1. Cédula de Identidad (Anverso y Reverso)</label>
            <input type="file" name="file_ci" class="form-control" disabled placeholder="Simulado">
            <small class="text-muted">Por motivos de desarrollo de entorno local, la carga real está pre-configurada.</small>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">2. Matrícula de Inscripción Vigente</label>
            <input type="file" name="file_matricula" class="form-control" disabled>
        </div>
    </div>
</div>

<input type="hidden" name="documentos_cargados" value="SI">