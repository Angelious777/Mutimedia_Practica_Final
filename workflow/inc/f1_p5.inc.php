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
    Autorizar Inscripción
</h4>

<?php if($datos){ ?>

<div class="alert alert-info">
    Revise la información del estudiante antes de autorizar la inscripción.
</div>

<div class="card">

    <div class="card-header bg-primary text-white">
        Datos del Trámite
    </div>

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-4">
                <label class="form-label fw-bold">
                    Nro. Trámite
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['nrotramite']; ?>"
                    readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">
                    Usuario
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['usuario']; ?>"
                    readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">
                    Gestión
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['gestion']; ?>"
                    readonly>
            </div>

        </div>

        <div class="row mb-3">

            <div class="col-md-4">
                <label class="form-label fw-bold">
                    Semestre
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['semestre']; ?>"
                    readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">
                    Estado Académico
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['habilitado'] ?? 'NO REGISTRADO'; ?>"
                    readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">
                    Pago Matrícula
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['pago'] ?? 'NO REGISTRADO'; ?>"
                    readonly>
            </div>

        </div>

        <?php if(!empty($datos['observaciones'])){ ?>

        <div class="mb-3">

            <label class="form-label fw-bold">
                Observaciones del Estudiante
            </label>

            <textarea
                class="form-control"
                rows="3"
                readonly><?php echo $datos['observaciones']; ?></textarea>

        </div>

        <?php } ?>

    </div>

</div>

<div class="card mt-3">

    <div class="card-header bg-success text-white">
        Resolución
    </div>

    <div class="card-body">

        <div class="alert alert-success mb-0">

            El estudiante cumple los requisitos académicos y registra el pago correspondiente.
            Al presionar <strong>Siguiente</strong>, se habilitará la selección de materias.

        </div>

    </div>

</div>

<?php } else { ?>

<div class="alert alert-danger">
    No se encontró información del trámite.
</div>

<?php } ?>