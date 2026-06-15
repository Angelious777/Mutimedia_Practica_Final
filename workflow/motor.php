<?php

session_start();

$flujo = $_POST['flujo'];
$proceso = $_POST['proceso'];
$tramite = $_POST['tramite'];

$accion = $_POST['accion'];

if($accion != "siguiente"){
    header("Location: bandejae.php");
    exit();
}

$seguimiento = json_decode(
    file_get_contents("json/seguimiento.json"),
    true
);

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

$fechaActual = date("Y-m-d H:i:s");

$usuarioTramite = "";

foreach($seguimiento as &$s){

    if(
        $s['nrotramite'] == $tramite &&
        $s['flujo'] == $flujo &&
        $s['proceso'] == $proceso &&
        empty($s['fechafin'])
    ){

        $usuarioTramite = $s['usuario'];

        $s['fechafin'] = $fechaActual;

        break;
    }
}

unset($s);

if(
    $flujo == "F1" &&
    $proceso == "P1"
){

    $tramites = json_decode(
        file_get_contents("json/tramites.json"),
        true
    );

    $tramites[] = [

        "nrotramite" => $tramite,

        "usuario" => $_SESSION['usuario'],

        "gestion" => $_POST['gestion'],

        "semestre" => $_POST['semestre'],

        "observaciones" => $_POST['observaciones']

    ];

    file_put_contents(
        "json/tramites.json",
        json_encode(
            $tramites,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );
}

if(
    $flujo == "F1" &&
    $proceso == "P2"
){

    $tramites = json_decode(
        file_get_contents("json/tramites.json"),
        true
    );

    foreach($tramites as &$t){

        if($t['nrotramite'] == $tramite){

            $t['habilitado'] = $_POST['habilitado'];

            $t['obs_habilitacion'] =
                $_POST['obs_habilitacion'];

            break;
        }
    }

    unset($t);

    file_put_contents(
        "json/tramites.json",
        json_encode(
            $tramites,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );
}

if(
    $flujo == "F1" &&
    $proceso == "P4"
){

    $tramites = json_decode(
        file_get_contents("json/tramites.json"),
        true
    );

    foreach($tramites as &$t){

        if($t['nrotramite'] == $tramite){

            $t['pago'] = $_POST['pago'];

            $t['obs_pago'] =
                $_POST['obs_pago'];

            break;
        }
    }

    unset($t);

    file_put_contents(
        "json/tramites.json",
        json_encode(
            $tramites,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );
}

if(
    $flujo == "F1" && 
    $proceso == "P6"
){

    $tramites = json_decode(
        file_get_contents("json/tramites.json"),
        true
    );

    foreach($tramites as &$t){

        if($t['nrotramite'] == $tramite){

            $t['materias'] = $_POST['materias'] ?? [];

            break;
        }
    }

    unset($t);

    file_put_contents(
        "json/tramites.json",
        json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

if($flujo == "F1" && $proceso == "P7"){

    $tramites = json_decode(
        file_get_contents("json/tramites.json"),
        true
    );

    $cupos = json_decode(
        file_get_contents("json/cupos.json"),
        true
    );

    $materias = [];
    $tramiteActual = null;

    foreach($tramites as &$t){

        if($t['nrotramite'] == $tramite){

            $tramiteActual = &$t;
            $materias = $t['materias'] ?? [];

            break;
        }
    }

    $cupoOK = true;

    foreach($materias as $m){

        foreach($cupos as &$c){

            if($c['materia'] == $m){

                if($c['inscritos'] >= $c['capacidad']){
                    $cupoOK = false;
                }

                break;
            }
        }
    }

    if(!$cupoOK){

        header("Location: bandejae.php?error=cupo");
        exit();
    }

    foreach($materias as $m){

        foreach($cupos as &$c){

            if($c['materia'] == $m){
                $c['inscritos']++; 
                break;
            }
        }
    }

    file_put_contents(
        "json/cupos.json",
        json_encode($cupos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    $tramiteActual['cupo'] = "SI";

    file_put_contents(
        "json/tramites.json",
        json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

$siguiente = $procesoActual['siguiente'];


if($siguiente == "CONDICION"){

    $condiciones = json_decode(
        file_get_contents("json/condicion.json"),
        true
    );

    foreach($condiciones as $c){

        if(
            $c['flujo'] == $flujo &&
            $c['proceso'] == $proceso
        ){

            $campo = $c['campo'];

            $valorCampo = null;

            foreach($tramites as $t){
                if($t['nrotramite'] == $tramite){
                    if(isset($t[$campo])){
                        $valorCampo = $t[$campo];
                    }
                    break;
                }
            }

            if($valorCampo == $c['valor']){
                $siguiente = $c['siguiente'];
                break;
            }
        }
    }
}


if(!empty($siguiente) && $siguiente != "CONDICION" && $siguiente != "FIN"){

    $nuevoId = count($seguimiento) + 1;

    $seguimiento[] = [
        "id" => (string)$nuevoId,
        "nrotramite" => $tramite,
        "flujo" => $flujo,
        "proceso" => $siguiente,
        "usuario" => $usuarioTramite,
        "fechaini" => $fechaActual,
        "fechafin" => ""
    ];
}

file_put_contents(
    "json/seguimiento.json",
    json_encode(
        $seguimiento,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    )
);

header("Location: bandejae.php");
exit();

?>