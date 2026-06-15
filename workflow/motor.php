<?php

session_start();

$flujo = $_POST['flujo'];
$proceso = $_POST['proceso'];
$tramite = $_POST['tramite'];
$accion = $_POST['accion'];

// Agregamos "crear" a la lista de acciones permitidas
if($accion != "siguiente" && $accion != "anterior" && $accion != "crear"){
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
    if($p['flujo'] == $flujo && $p['proceso'] == $proceso){
        $procesoActual = $p;
        break;
    }
}

if(!$procesoActual){
    die("Proceso no encontrado");
}

$fechaActual = date("Y-m-d H:i:s");
$usuarioTramite = "";

if($accion == "crear"){
    $nuevoId = count($seguimiento) + 1;
    
    $seguimiento[] = [
        "id" => (string)$nuevoId,
        "nrotramite" => $tramite,
        "flujo" => $flujo,
        "proceso" => $proceso, // Guardará "P1"
        "usuario" => $_SESSION['usuario'] ?? "anonimo",
        "fechaini" => $fechaActual,
        "fechafin" => "" // Queda vacío para que aparezca activo en la bandeja
    ];

    file_put_contents(
        "json/seguimiento.json", 
        json_encode($seguimiento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    header("Location: bandejae.php");
    exit();
}

// =========================================================================
// LÓGICA PARA EL BOTÓN "ANTERIOR" (RETROCESO DINÁMICO)
// =========================================================================
if($accion == "anterior"){
    
    $procesoAnterior = null;

    foreach($seguimiento as $s){
        if(
            $s['nrotramite'] == $tramite && 
            $s['flujo'] == $flujo && 
            !empty($s['fechafin'])
        ){
            $procesoAnterior = $s['proceso'];
        }
    }

    if($procesoAnterior !== null){
        
        foreach($seguimiento as &$s){
            if(
                $s['nrotramite'] == $tramite && 
                $s['flujo'] == $flujo && 
                $s['proceso'] == $proceso && 
                empty($s['fechafin'])
            ){
                $s['fechafin'] = $fechaActual;
                $usuarioTramite = $s['usuario'];
                break;
            }
        }
        unset($s);

        $nuevoId = count($seguimiento) + 1;
        $seguimiento[] = [
            "id" => (string)$nuevoId,
            "nrotramite" => $tramite,
            "flujo" => $flujo,
            "proceso" => $procesoAnterior,
            "usuario" => $usuarioTramite,
            "fechaini" => $fechaActual,
            "fechafin" => ""
        ];

        file_put_contents("json/seguimiento.json", json_encode($seguimiento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    header("Location: bandejae.php");
    exit();
}

// =========================================================================
// LÓGICA PARA EL BOTÓN "SIGUIENTE" (AVANCE)
// =========================================================================

$encontrado = false;

// 1. Intentamos cerrar el proceso actual en el archivo de seguimiento
foreach($seguimiento as &$s){
    if(
        $s['nrotramite'] == $tramite &&
        $s['flujo'] == $flujo &&
        $s['proceso'] == $proceso &&
        empty($s['fechafin'])
    ){
        $usuarioTramite = $s['usuario'];
        $s['fechafin'] = $fechaActual;
        $encontrado = true;
        break;
    }
}
unset($s);

// CORRECCIÓN: Si el trámite es nuevo (no se encontró fila activa de P1 en el JSON),
// forzamos la asignación del usuario en sesión y registramos retroactivamente el P1 finalizado.
if(!$encontrado){
    $usuarioTramite = $_SESSION['usuario'] ?? "anonimo";
    
    $idP1 = count($seguimiento) + 1;
    $seguimiento[] = [
        "id" => (string)$idP1,
        "nrotramite" => $tramite,
        "flujo" => $flujo,
        "proceso" => $proceso, // Guardamos P1
        "usuario" => $usuarioTramite,
        "fechaini" => $fechaActual, // Opcional: podrías usar una fecha estimada anterior
        "fechafin" => $fechaActual
    ];
}

// ==========================================
// FLUJO 1 - PROCESAMIENTO DE PASOS ESPECÍFICOS
// ==========================================

if($flujo == "F1" && $proceso == "P1"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    $tramites[] = [
        "nrotramite" => $tramite,
        "usuario" => $usuarioTramite,
        "gestion" => $_POST['gestion'],
        "semestre" => $_POST['semestre'],
        "observaciones" => $_POST['observaciones']
    ];
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if($flujo == "F1" && $proceso == "P2"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['habilitado'] = $_POST['habilitado'];
            $t['obs_habilitacion'] = $_POST['obs_habilitacion'];
            break;
        }
    }
    unset($t);
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if($flujo == "F1" && $proceso == "P4"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['pago'] = $_POST['pago'];
            $t['obs_pago'] = $_POST['obs_pago'];
            break;
        }
    }
    unset($t);
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if($flujo == "F1" && $proceso == "P6"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['materias'] = $_POST['materias'] ?? [];
            break;
        }
    }
    unset($t);
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if($flujo == "F1" && $proceso == "P7"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    $cupos = json_decode(file_get_contents("json/cupos.json"), true);

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

    file_put_contents("json/cupos.json", json_encode($cupos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $tramiteActual['cupo'] = "SI";
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


// ==========================================
// FLUJO 2 - SOLUCIÓN E INYECCIÓN DE PROCESOS
// ==========================================

if($flujo == "F2" && $proceso == "P1"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    $tramites[] = [
        "nrotramite" => $tramite,
        "usuario" => $usuarioTramite,
        "tipo_certificado" => $_POST['tipo_certificado'],
        "observaciones" => $_POST['observaciones']
    ];
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if($flujo == "F2" && $proceso == "P3"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['documentos'] = $_POST['documentos'];
            $t['obs_documentos'] = $_POST['obs_documentos'];
            break;
        }
    }
    unset($t);
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if($flujo == "F2" && $proceso == "P5"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['pago'] = $_POST['pago'];
            $t['obs_pago'] = $_POST['obs_pago'];
            break;
        }
    }
    unset($t);
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if($flujo == "F2" && $proceso == "P7"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['cuerpo_certificado'] = $_POST['cuerpo_certificado'];
            break;
        }
    }
    unset($t);
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


// ==========================================
// MOTOR DE RESOLUCIÓN DE RUTAS Y CONDICIONES
// ==========================================

$siguiente = $procesoActual['siguiente'];

if($siguiente == "CONDICION"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    $condiciones = json_decode(file_get_contents("json/condicion.json"), true);

    foreach($condiciones as $c){
        if($c['flujo'] == $flujo && $c['proceso'] == $proceso){
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

            if($valorCampo == $c['value'] || $valorCampo == $c['valor']){ 
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
    json_encode($seguimiento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

header("Location: bandejae.php");
exit();
?>