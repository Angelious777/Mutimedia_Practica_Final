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

$materias = [
    "INF-111 Programación I",
    "INF-112 Estructuras de Datos",
    "INF-113 Base de Datos",
    "MAT-101 Álgebra",
    "FIS-102 Física I"
];

?>

<h4 class="mb-4">Selección de Materias</h4>

<?php if($datos){ ?>

<div class="alert alert-info">
    Seleccione las materias que desea inscribir.
</div>

<div class="card mb-4">

    <div class="card-header bg-secondary text-white">
        Información del Trámite
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <strong>Nro. Trámite:</strong><br>
                <?php echo $datos['nrotramite']; ?>
            </div>

            <div class="col-md-4">
                <strong>Usuario:</strong><br>
                <?php echo $datos['usuario']; ?>
            </div>

            <div class="col-md-4">
                <strong>Estado:</strong><br>
                AUTORIZADO
            </div>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-header bg-primary text-white">
        Materias Disponibles
    </div>

    <div class="card-body">

        <?php foreach($materias as $m){ ?>

            <div class="form-check mb-2">

                <input
                    class="form-check-input"
                    type="checkbox"
                    name="materias[]"
                    value="<?php echo $m; ?>"
                    <?php echo in_array($m, $datos['materias'] ?? []) ? 'checked' : ''; ?>>

                <label class="form-check-label">
                    <?php echo $m; ?>
                </label>

            </div>

        <?php } ?>

    </div>

</div>

<?php } else { ?>

<div class="alert alert-danger">
    No se encontró el trámite.
</div>

<?php } ?>