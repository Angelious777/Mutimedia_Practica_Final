<?php

$tramite = $_GET['tramite'] ?? '';

$tramites = json_decode(
    file_get_contents("json/tramites.json"),
    true
);

$cupos = json_decode(
    file_get_contents("json/cupos.json"),
    true
);

$datos = null;

foreach($tramites as $t){
    if($t['nrotramite'] == $tramite){
        $datos = $t;
        break;
    }
}

$materias = $datos['materias'] ?? [];

?>

<h4 class="mb-4">Verificación de Cupos</h4>

<?php if($datos){ ?>

<div class="alert alert-warning">
    El sistema está verificando la disponibilidad de cupos en las materias seleccionadas.
</div>

<div class="card mb-4">

    <div class="card-header bg-secondary text-white">
        Información del Trámite
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <strong>Nro. Trámite:</strong><br>
                <?= $datos['nrotramite'] ?>
            </div>

            <div class="col-md-4">
                <strong>Usuario:</strong><br>
                <?= $datos['usuario'] ?>
            </div>

            <div class="col-md-4">
                <strong>Estado:</strong><br>
                EN VALIDACIÓN
            </div>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-header bg-primary text-white">
        Materias Seleccionadas
    </div>

    <div class="card-body">

        <?php if(count($materias) > 0){ ?>

            <ul class="list-group">

                <?php foreach($materias as $m){ ?>
                    <li class="list-group-item">
                        <?= $m ?>
                    </li>
                <?php } ?>

            </ul>

        <?php } else { ?>

            <div class="text-muted">
                No se seleccionaron materias.
            </div>

        <?php } ?>

    </div>

</div>

<?php

$cupoDisponible = true;
$detalle = [];

foreach($materias as $m){

    $encontrado = false;

    foreach($cupos as $c){

        if($c['materia'] == $m){

            $encontrado = true;

            if($c['inscritos'] >= $c['capacidad']){
                $cupoDisponible = false;
                $detalle[] = "✖ $m (SIN CUPOS)";
            } else {
                $detalle[] = "✔ $m (OK)";
            }

            break;
        }
    }

    if(!$encontrado){
        $cupoDisponible = false;
        $detalle[] = "✖ $m (NO EXISTE)";
    }
}
?>

<div class="mt-4">

    <?php if($cupoDisponible){ ?>

        <div class="alert alert-success">
            ✔ Cupos disponibles. El sistema puede continuar con la inscripción.
        </div>

    <?php } else { ?>

        <div class="alert alert-danger">
            ✖ No existen cupos suficientes. El trámite será redirigido.
        </div>

    <?php } ?>

</div>

<?php if(!empty($detalle)){ ?>

<div class="card mt-3">

    <div class="card-header bg-dark text-white">
        Detalle de validación
    </div>

    <div class="card-body">

        <ul>
            <?php foreach($detalle as $d){ ?>
                <li><?= $d ?></li>
            <?php } ?>
        </ul>

    </div>

</div>

<?php } ?>

<?php } else { ?>

<div class="alert alert-danger">
    Trámite no encontrado.
</div>

<?php } ?>