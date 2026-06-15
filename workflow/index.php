<?php

session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: inicio.php");
    exit();
}

$flujo = $_GET['flujo'] ?? '';
$proceso = $_GET['proceso'] ?? '';
$tramite = $_GET['tramite'] ?? '';

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
    <title>Procesador de Flujo - Workflow</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">

    <style>
        /* FUERZA LA TIPOGRAFÍA SPACE GROTESK URBANA Y CUADRADA 
           Para el contenedor, el formulario e incluye todo el contenido dinámico del include 
        */
        body, .flow-card, input, button, select, textarea, label, table, td, th {
            font-family: "Space Grotesk", -apple-system, BlinkMacSystemFont, sans-serif !important;
            font-optical-sizing: auto;
        }

        .flow-card {
            background: white;
            padding: 2.5rem;
            border-radius: 4px;
            box-shadow: 0 8px 30px rgba(0,0,0,.05);
            border: 1px solid #e3e6ec;
            width: 100%;
            max-width: 1000px;
        }

        /* Barra superior con tu color celeste (#0e50b4) */
        .flow-meta-bar {
            background-color: #0e50b4; 
            color: #ffffff;
            padding: 1rem 1.5rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }

        .flow-badge {
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            font-weight: 700;
            padding: 0.35rem 0.8rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 2px;
        }

        .dynamic-content {
            background: #ffffff;
            border: 1px solid #dbdfec;
            border-radius: 4px;
            padding: 2rem;
            margin-bottom: 2rem;
            min-height: 200px;
        }

        .btn-flow {
            border-radius: 2px;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding: 2rem 0;">
    
    <div class="flow-card">
        <form method="post" action="motor.php">

            <div class="flow-meta-bar d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="flow-badge text-uppercase">
                        <i class="bi bi-diagram-3-fill me-1"></i> Flujo: <?php echo htmlspecialchars($flujo); ?>
                    </span>
                    <span class="flow-badge text-uppercase">
                        <i class="bi bi-sliders me-1"></i> Proceso: <?php echo htmlspecialchars($proceso); ?>
                    </span>
                </div>
                <div class="text-sm-end">
                    <small class="text-white text-uppercase fw-bold d-block" style="font-size: 0.75rem; letter-spacing: 0.05em;">Código de Seguimiento</small>
                    <span class="fw-bold text-white" style="font-size: 1.1rem;">
                        <i class="bi bi-hash text-white-50"></i><?php echo htmlspecialchars($tramite); ?>
                    </span>
                </div>
            </div>

            <div class="mb-4 border-bottom pb-3">
                <span class="text-muted text-uppercase fw-bold tracking-wider" style="font-size: 0.75rem; letter-spacing: 0.08em;">Tarea en ejecución</span>
                <h3 class="m-0 fw-bold text-dark mt-1">
                    <?php echo htmlspecialchars($procesoActual['nombre']); ?>
                </h3>
            </div>

            <div class="dynamic-content">
                <?php
                $archivo = "inc/".$pantalla;

                if(!empty($pantalla) && file_exists($archivo)){
                    include($archivo);
                }else{
                    echo "
                    <div class='text-center py-5 text-muted'>
                        <i class='bi bi-exclamation-triangle fs-1 text-warning mb-2 d-block'></i>
                        <p class='m-0 fw-medium'>La interfaz componente [".htmlspecialchars($pantalla)."] no se encuentra disponible en el servidor.</p>
                    </div>";
                }
                ?>
            </div>

            <input type="hidden" name="flujo" value="<?php echo htmlspecialchars($flujo); ?>">
            <input type="hidden" name="proceso" value="<?php echo htmlspecialchars($proceso); ?>">
            <input type="hidden" name="tramite" value="<?php echo htmlspecialchars($tramite); ?>">

            <div class="d-flex gap-3">
                <button
                    type="submit"
                    name="accion"
                    value="anterior"
                    class="btn btn-outline-secondary btn-flow w-50 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-arrow-left"></i> Anterior
                </button>

                <button
                    type="submit"
                    name="accion"
                    value="siguiente"
                    class="btn btn-primary btn-flow w-50 d-flex align-items-center justify-content-center gap-2">
                    Siguiente <i class="bi bi-arrow-right"></i>
                </button>
            </div>

        </form>
    </div>

</div>

</body>
</html>