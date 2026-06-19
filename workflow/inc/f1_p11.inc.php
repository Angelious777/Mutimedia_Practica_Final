<?php

$tramite = $_GET['tramite'] ?? '';

$tramites = json_decode(
    file_get_contents("json/tramites.json"),
    true
);

$datos = null;

foreach($tramites as $t){
    if($t['nrotramite'] == $tramite){
        $datos = $t;
        break;
    }
}

?>

<h4 class="mb-4">Comprobante de Inscripción</h4>

<?php if($datos){ ?>

<div class="alert alert-success">
    ✔ La inscripción fue completada exitosamente.
</div>

<div class="card mb-4">

    <div class="card-header bg-primary text-white">
        Comprobante Oficial
    </div>

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-4">
                <strong>Nro. Trámite:</strong><br>
                <?= htmlspecialchars($datos['nrotramite']) ?>
            </div>

            <div class="col-md-4">
                <strong>Estudiante:</strong><br>
                <?= htmlspecialchars($datos['usuario']) ?>
            </div>

            <div class="col-md-4">
                <strong>Estado:</strong><br>
                FINALIZADO
            </div>

        </div>

        <div class="row mb-3">

            <div class="col-md-4">
                <strong>Gestión:</strong><br>
                <?= htmlspecialchars($datos['gestion'] ?? '-') ?>
            </div>

            <div class="col-md-4">
                <strong>Semestre:</strong><br>
                <?= htmlspecialchars($datos['semestre'] ?? '-') ?>
            </div>

            <div class="col-md-4">
                <strong>Fecha:</strong><br>
                <?= date("Y-m-d H:i:s") ?>
            </div>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-header bg-secondary text-white">
        Materias Inscritas
    </div>

    <div class="card-body">

        <?php if(!empty($datos['materias'])){ ?>

            <ul class="list-group">

                <?php foreach($datos['materias'] as $m){ ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($m) ?>
                    </li>
                <?php } ?>

            </ul>

        <?php } else { ?>

            <div class="text-muted">
                No se registraron materias.
            </div>

        <?php } ?>

    </div>

</div>

<div class="alert alert-info mt-4">
    Este documento sirve como constancia de inscripción académica.
</div>

<?php } else { ?>

<div class="alert alert-danger">
    No se encontró el trámite.
</div>

<?php } ?>