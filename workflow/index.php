<?php

session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: inicio.php");
    exit();
}

$flujo = $_GET['flujo'];
$proceso = $_GET['proceso'];
$tramite = $_GET['tramite'];

$procesos = json_decode(
    file_get_contents("json/flujoproceso.json"),
    true
);

$procesoActual = null;

foreach($procesos as $p){

    if(
        $p['flujo'] == $flujo &&
        $p['proceso'] == $proceso
    ){
        $procesoActual = $p;
        break;
    }
}

if(!$procesoActual){
    die("Proceso no encontrado");
}

$pantalla = $procesoActual['pantalla'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Procesador de Flujo</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.flow-card{
    background:white;
    padding:2rem;
    border-radius:12px;
    box-shadow:0 8px 24px rgba(0,0,0,.08);
    width:1000px;
}

.dynamic-content{
    background:#f8f9fa;
    border:1px solid #dee2e6;
    border-radius:8px;
    padding:1.5rem;
    margin-bottom:1.5rem;
}

</style>

</head>
<body>

<div class="flow-card">

<form method="post" action="motor.php">

    <div class="text-center mb-4">

        <span class="badge bg-primary">
            <?php echo $flujo; ?>
        </span>

        <h4 class="mt-2">
            <?php echo $procesoActual['nombre']; ?>
        </h4>

        <small class="text-muted">
            Trámite: <?php echo $tramite; ?>
        </small>

    </div>

    <div class="dynamic-content">

        <?php

        $archivo = "inc/".$pantalla;

        if(file_exists($archivo)){
            include($archivo);
        }else{
            echo "<p>No existe la pantalla.</p>";
        }

        ?>

    </div>

    <input type="hidden" name="flujo" value="<?php echo $flujo; ?>">
    <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">
    <input type="hidden" name="tramite" value="<?php echo $tramite; ?>">

    <div class="d-flex gap-3">

        <button
            type="submit"
            name="accion"
            value="anterior"
            class="btn btn-outline-secondary w-50">

            ← Anterior

        </button>

        <button
            type="submit"
            name="accion"
            value="siguiente"
            class="btn btn-primary w-50">

            Siguiente →

        </button>

    </div>

</form>

</div>

</body>
</html>