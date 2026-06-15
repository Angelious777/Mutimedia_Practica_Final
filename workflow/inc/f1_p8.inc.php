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

<h4 class="mb-4">Confirmación de Inscripción</h4>

<?php if($datos){ ?>

<div class="alert alert-success">
    ✔ Las materias fueron validadas correctamente por el sistema.
    La inscripción está lista para ser confirmada.
</div>

<div class="card mb-4">

    <div class="card-header bg-secondary text-white">
        Datos del Estudiante
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <strong>Nro. Trámite:</strong><br>
                <?= htmlspecialchars($datos['nrotramite']) ?>
            </div>

            <div class="col-md-4">
                <strong>Usuario:</strong><br>
                <?= htmlspecialchars($datos['usuario']) ?>
            </div>

            <div class="col-md-4">
                <strong>Estado:</strong><br>
                INSCRIPCIÓN VALIDADA
            </div>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-header bg-primary text-white">
        Materias Confirmadas
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
                No hay materias registradas.
            </div>

        <?php } ?>

    </div>

</div>

<div class="alert alert-info mt-4">
    El sistema generará el comprobante final de inscripción en el siguiente proceso.
</div>

<?php } else { ?>

<div class="alert alert-danger">
    No se encontró el trámite.
</div>

<?php } ?>