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
    Verificación de Habilitación Académica
</h4>

<?php if($datos){ ?>

<div class="card mb-4">

    <div class="card-header bg-primary text-white">
        Datos de la Solicitud
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

        </div>

        <div class="row mb-3">

            <div class="col-md-6">
                <label class="form-label fw-bold">
                    Gestión
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['gestion']; ?>"
                    readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">
                    Semestre
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['semestre']; ?>"
                    readonly>
            </div>

        </div>

        <div class="mb-3">

            <label class="form-label fw-bold">
                Observaciones del Estudiante
            </label>

            <textarea
                class="form-control"
                rows="3"
                readonly><?php echo $datos['observaciones']; ?></textarea>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-header bg-warning">
        Resultado de la Verificación
    </div>

    <div class="card-body">

        <div class="mb-3">

            <label class="form-label">
                Estado Académico
            </label>

            <select
                name="habilitado"
                class="form-select"
                required>

                <option value="">
                    Seleccione una opción
                </option>

                <option value="SI">
                    Habilitado
                </option>

                <option value="NO">
                    No Habilitado
                </option>

            </select>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Observación Administrativa
            </label>

            <textarea
                name="obs_habilitacion"
                class="form-control"
                rows="3"></textarea>

        </div>

    </div>

</div>

<?php } else { ?>

<div class="alert alert-danger">
    No se encontró información del trámite.
</div>

<?php } ?>