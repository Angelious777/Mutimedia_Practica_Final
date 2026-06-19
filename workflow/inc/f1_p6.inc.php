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
    Verificación de Pago de Matrícula
</h4>

<?php if($datos){ ?>

<div class="card mb-4">

    <div class="card-header bg-primary text-white">
        Datos del Estudiante
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">
                    Nro. Trámite
                </label>
                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['nrotramite']; ?>"
                    readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">
                    Usuario
                </label>
                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['usuario']; ?>"
                    readonly>
            </div>

            <div class="col-md-4 mb-3">
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

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">
                    Semestre
                </label>
                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['semestre']; ?>"
                    readonly>
            </div>

            <div class="col-md-8 mb-3">
                <label class="form-label fw-bold">
                    Observaciones Iniciales
                </label>
                <input
                    type="text"
                    class="form-control"
                    value="<?php echo $datos['observaciones']; ?>"
                    readonly>
            </div>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-header bg-warning">
        Validación de Pago
    </div>

    <div class="card-body">

        <div class="mb-3">

            <label class="form-label fw-bold">
                Estado del Pago
            </label>

            <select
                name="pago"
                class="form-select"
                required>

                <option value="">
                    Seleccione...
                </option>

                <option value="SI">
                    Matrícula Pagada
                </option>

                <option value="NO">
                    Matrícula No Pagada
                </option>

            </select>

        </div>

        <div class="mb-3">

            <label class="form-label fw-bold">
                Observaciones de Caja
            </label>

            <textarea
                name="obs_pago"
                class="form-control"
                rows="4"
                placeholder="Detalle de la verificación realizada..."
                required></textarea>

        </div>

    </div>

</div>

<?php } else { ?>

<div class="alert alert-danger">
    No se encontró información del trámite.
</div>

<?php } ?>