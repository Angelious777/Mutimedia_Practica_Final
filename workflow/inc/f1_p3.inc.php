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

<h4 class="mb-4">
    Resultado de la Solicitud de Inscripción
</h4>

<?php if($datos){ ?>

<?php

$listaObservaciones = [];

if(
    isset($datos['habilitado']) &&
    $datos['habilitado'] == "NO" &&
    !empty($datos['obs_habilitacion'])
){
    $listaObservaciones[] = $datos['obs_habilitacion'];
}

if(
    isset($datos['pago']) &&
    $datos['pago'] == "NO" &&
    !empty($datos['obs_pago'])
){
    $listaObservaciones[] = $datos['obs_pago'];
}

?>

<div class="alert alert-danger">

    <h5 class="mb-2">
        Trámite Observado
    </h5>

    El trámite no pudo continuar debido a las siguientes observaciones.

</div>

<div class="card">

    <div class="card-header bg-secondary text-white">
        Información del Trámite
    </div>

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-4">
                <strong>Nro. Trámite</strong><br>
                <?php echo $datos['nrotramite']; ?>
            </div>

            <div class="col-md-4">
                <strong>Usuario</strong><br>
                <?php echo $datos['usuario']; ?>
            </div>

            <div class="col-md-4">
                <strong>Gestión</strong><br>
                <?php echo $datos['gestion']; ?>
            </div>

        </div>

        <div class="row mb-3">

            <div class="col-md-6">
                <strong>Semestre</strong><br>
                <?php echo $datos['semestre']; ?>
            </div>

        </div>

        <div class="mb-3">

            <label class="form-label fw-bold">
                Observaciones Registradas
            </label>

            <?php if(count($listaObservaciones) > 0){ ?>

                <ul class="list-group">

                    <?php foreach($listaObservaciones as $obs){ ?>

                        <li class="list-group-item">
                            <?php echo htmlspecialchars($obs); ?>
                        </li>

                    <?php } ?>

                </ul>

            <?php } else { ?>

                <div class="alert alert-secondary mb-0">
                    No existe ninguna observación registrada.
                </div>

            <?php } ?>

        </div>

    </div>

</div>

<?php } else { ?>

<div class="alert alert-danger">
    No se encontró información del trámite.
</div>

<?php } ?>