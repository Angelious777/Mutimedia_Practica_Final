<?php

session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: inicio.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'];

$claseRol = preg_replace("/[^A-Za-z0-9_]/", "", $rol);

$seguimiento = json_decode(
    file_get_contents("json/seguimiento.json"),
    true
);

$procesos = json_decode(
    file_get_contents("json/flujoproceso.json"),
    true
);

$nuevoTramiteId = time(); 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de Entrada - Workflow</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <link href="static/css/style.css" rel="stylesheet">
    <link href="static/css/bandeja.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">

</head>
<body class="role-<?php echo $claseRol; ?>">

<div class="container">

    <div class="main-container">

        <div class="welcome-box d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <div>
                <small class="text-white-50 text-uppercase fw-bold tracking-wider">Sesión Activa</small>
                <h2 class="m-0 fw-bold mt-1">
                    ¡Hola, <?php echo htmlspecialchars($_SESSION['nombre'] ?? $usuario); ?>!
                </h2>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="role-indicator d-flex align-items-center gap-1">
                    <i class="bi bi-person-badge-fill"></i> Vista: <?php echo htmlspecialchars($rol); ?>
                </span>
                
                <a href="inicio.php" class="btn btn-sm fw-semibold px-3 btn-logout shadow-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                </a>
            </div>
        </div>

        <?php if($rol == "ESTUDIANTE"): ?>
            <div class="card mb-4 border-0 bg-light shadow-sm">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="card-title mb-1 text-dark fw-bold">¿Deseas iniciar una nueva solicitud?</h5>
                        <p class="card-text text-muted small mb-0">Selecciona uno de los flujos disponibles para comenzar el trámite correspondiente paso a paso.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="motor.php" method="POST" class="m-0">
                            <input type="hidden" name="flujo" value="F1">
                            <input type="hidden" name="proceso" value="P1">
                            <input type="hidden" name="tramite" value="<?php echo $nuevoTramiteId; ?>">
                            <button type="submit" name="accion" value="crear" class="btn btn-success px-3 fw-semibold">
                                <i class="bi bi-journal-plus me-1"></i> Inscripción (F1)
                            </button>
                        </form>

                        <form action="motor.php" method="POST" class="m-0">
                            <input type="hidden" name="flujo" value="F2">
                            <input type="hidden" name="proceso" value="P1">
                            <input type="hidden" name="tramite" value="<?php echo $nuevoTramiteId; ?>">
                            <button type="submit" name="accion" value="crear" class="btn btn-primary px-3 fw-semibold">
                                <i class="bi bi-file-earmark-text me-1"></i> Certificados (F2)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="d-flex align-items-center gap-2 mb-3 mt-4">
            <i class="bi bi-mailbox text-secondary fs-4"></i>
            <h4 class="m-0 fw-bold text-secondary">Bandeja de Tareas Pendientes</h4>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border-top">
                <thead class="table-light">
                    <tr>
                        <th style="width: 15%;">Nro Trámite</th>
                        <th style="width: 10%;">Flujo</th>
                        <th>Proceso / Tarea</th>
                        <?php if($rol != "ESTUDIANTE"): ?>
                            <th>Solicitante</th>
                        <?php endif; ?>
                        <th style="width: 20%;">Fecha Entrada</th>
                        <th style="width: 15%;" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                $hayRegistros = false;

                foreach($seguimiento as $s){

                    if(!empty($s['fechafin'])){
                        continue;
                    }

                    foreach($procesos as $p){

                        if($rol == "ESTUDIANTE"){
                            $mostrar =
                                $p['flujo'] == $s['flujo'] &&
                                $p['proceso'] == $s['proceso'] &&
                                $p['rol'] == $rol &&
                                $s['usuario'] == $usuario;
                        }else{
                            $mostrar =
                                $p['flujo'] == $s['flujo'] &&
                                $p['proceso'] == $s['proceso'] &&
                                $p['rol'] == $rol;
                        }

                        if($mostrar){
                            $hayRegistros = true;

                            echo "<tr>";
                            echo "<td class='fw-bold text-secondary'>#".htmlspecialchars($s['nrotramite'])."</td>";
                            echo "<td>
                                    <span class='badge bg-light text-dark border px-2 py-1 fw-semibold'>
                                        ".htmlspecialchars($s['flujo'])."
                                    </span>
                                  </td>";
                            echo "<td><span class='fw-semibold text-dark'>".htmlspecialchars($p['nombre'])."</span></td>";

                            if($rol != "ESTUDIANTE"){
                                echo "<td><i class='bi bi-person me-1 text-muted'></i>".htmlspecialchars($s['usuario'])."</td>";
                            }

                            echo "<td class='text-muted small'>".htmlspecialchars($s['fechaini'])."</td>";
                            echo "<td class='text-center'>";
                            echo "<a
                                    href='index.php?flujo=".urlencode($s['flujo'])."&proceso=".urlencode($s['proceso'])."&tramite=".urlencode($s['nrotramite'])."'
                                    class='btn btn-primary btn-sm px-3 shadow-sm'>
                                    Atender <i class='bi bi-chevron-right ms-1'></i>
                                  </a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }

                if(!$hayRegistros){
                    $columnas = ($rol != "ESTUDIANTE") ? 6 : 5;
                    echo "
                    <tr>
                        <td colspan='{$columnas}' class='text-center text-muted py-5 bg-light-subtle rounded-bottom'>
                            <i class='bi bi-check-circle text-success fs-2 mb-2 d-block'></i>
                            <span class='fw-medium'>Felicidades, no tienes tareas pendientes asignadas a tu rol.</span>
                        </td>
                    </tr>";
                }
                ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>