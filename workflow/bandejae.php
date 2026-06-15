<?php

session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: inicio.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'];

$seguimiento = json_decode(
    file_get_contents("json/seguimiento.json"),
    true
);

$procesos = json_decode(
    file_get_contents("json/flujoproceso.json"),
    true
);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de Entrada</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body{
            background-color:#f8f9fa;
            padding-top:3rem;
        }

        .main-container{
            background:white;
            padding:2rem;
            border-radius:10px;
            box-shadow:0 4px 6px rgba(0,0,0,0.05);
        }

        .welcome-box{
            background:linear-gradient(135deg,#0d6efd,#0a58ca);
            color:white;
            padding:1.5rem;
            border-radius:8px;
            margin-bottom:2rem;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="main-container">

        <div class="welcome-box d-flex justify-content-between align-items-center">

            <div>
                <h2 class="m-0">
                    ¡Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!
                </h2>

                <p class="m-0 opacity-75">
                    Rol: <?php echo htmlspecialchars($_SESSION['rol']); ?>
                </p>
            </div>

            <a href="inicio.php" class="btn btn-outline-light btn-sm">
                Cerrar Sesión
            </a>

        </div>

        <h4 class="mb-3 text-secondary">
            Bandeja de Entrada
        </h4>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Nro Trámite</th>
                        <th>Flujo</th>
                        <th>Proceso</th>
                        <?php
                        if($rol!="ESTUDIANTE"){
                            echo "<th>Usuario</th>";
                        }
                        ?>
                        <th>Fecha Inicio</th>
                        <th class="text-center">Acción</th>
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

                            echo "<td>".
                                    htmlspecialchars($s['nrotramite']).
                                 "</td>";

                            echo "<td>
                                    <span class='badge bg-secondary'>
                                        ".htmlspecialchars($s['flujo'])."
                                    </span>
                                  </td>";

                            echo "<td>".
                                    htmlspecialchars($p['nombre']).
                                 "</td>";

                            if($rol!="ESTUDIANTE"){
                                echo "<td>".
                                    htmlspecialchars($s['usuario']).
                                 "</td>";
                            }

                            echo "<td>".
                                    htmlspecialchars($s['fechaini']).
                                 "</td>";

                            echo "<td class='text-center'>";

                            echo "<a
                                    href='index.php?flujo="
                                    .urlencode($s['flujo'])
                                    ."&proceso="
                                    .urlencode($s['proceso'])
                                    ."&tramite="
                                    .urlencode($s['nrotramite'])
                                    ."'
                                    class='btn btn-primary btn-sm'>
                                    <i class='bi bi-arrow-right-circle'></i>
                                    Continuar
                                  </a>";

                            echo "</td>";

                            echo "</tr>";
                        }
                    }
                }

                if(!$hayRegistros){

                    echo "
                    <tr>
                        <td colspan='6'
                            class='text-center text-muted py-4'>
                            No tienes tareas pendientes.
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