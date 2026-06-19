<?php

session_start();

$flujo = $_POST['flujo'];
$proceso = $_POST['proceso'];
$tramite = $_POST['tramite'];
$accion = $_POST['accion'];

if($accion != "siguiente" && $accion != "anterior" && $accion != "crear"){
    header("Location: bandejae.php");
    exit();
}

$seguimiento = json_decode(file_get_contents("json/seguimiento.json"), true);
$procesos = json_decode(file_get_contents("json/flujoproceso.json"), true);

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
$usuarioTramite = $_SESSION['usuario'] ?? "anonimo";

// =========================================================================
// ACCIÓN: CREAR TRÁMITE
// =========================================================================
if($accion == "crear"){
    $nuevoId = count($seguimiento) + 1;
    $seguimiento[] = [
        "id" => (string)$nuevoId,
        "nrotramite" => $tramite,
        "flujo" => $flujo,
        "proceso" => $proceso, 
        "usuario" => $usuarioTramite,
        "fechaini" => $fechaActual,
        "fechafin" => "" 
    ];

    file_put_contents("json/seguimiento.json", json_encode($seguimiento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: index.php?flujo=$flujo&proceso=$proceso&tramite=$tramite");
    exit();
}

// =========================================================================
// LÓGICA PARA EL BOTÓN "ANTERIOR"
// =========================================================================
if($accion == "anterior"){
    $procesoAnterior = null;

    foreach($procesos as $p){
        if($p['flujo'] == $flujo && $p['siguiente'] == $proceso){
            $procesoAnterior = $p['proceso'];
            break;
        }
    }

    if($procesoAnterior !== null){
        foreach($seguimiento as $key => $s){
            if(
                $s['nrotramite'] == $tramite && 
                $s['flujo'] == $flujo && 
                $s['proceso'] == $proceso && 
                empty($s['fechafin'])
            ){
                unset($seguimiento[$key]);
                break;
            }
        }
        
        $seguimientoReverso = array_reverse($seguimiento, true);
        foreach($seguimientoReverso as $key => $s){
            if(
                $s['nrotramite'] == $tramite && 
                $s['flujo'] == $flujo && 
                $s['proceso'] == $procesoAnterior
            ){
                $seguimiento[$key]['fechafin'] = ""; 
                break;
            }
        }

        $seguimiento = array_values($seguimiento);
        file_put_contents("json/seguimiento.json", json_encode($seguimiento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        header("Location: index.php?flujo=$flujo&proceso=$procesoAnterior&tramite=$tramite");
        exit();
    }

    header("Location: bandejae.php");
    exit();
}

// =========================================================================
// LÓGICA PARA EL BOTÓN "SIGUIENTE"
// =========================================================================

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

// ==========================================
// PERSISTENCIA EN TRAMITES.JSON
// ==========================================
if($flujo == "F1" && $proceso == "P1"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    
    $existe = false;
    // 1. Buscamos si el trámite ya existe en el archivo
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            // Si ya existe (porque volviste atrás), actualizamos solo los campos de P1
            $t['usuario'] = $usuarioTramite;
            $t['gestion'] = $_POST['gestion'];
            $t['semestre'] = $_POST['semestre'];
            $t['observaciones'] = $_POST['observaciones']; // Aquí se cambiará a "prueba 2" sin duplicar el objeto
            $existe = true;
            break;
        }
    }
    unset($t); // Liberamos la referencia

    // 2. Si no existe (es la primera vez real que se ejecuta), lo agregamos normalmente
    if(!$existe){
        $tramites[] = [
            "nrotramite" => $tramite,
            "usuario" => $usuarioTramite,
            "gestion" => $_POST['gestion'],
            "semestre" => $_POST['semestre'],
            "observaciones" => $_POST['observaciones']
        ];
    }
    
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if($flujo == "F1" && $proceso == "P1A"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['direccion'] = $_POST['direccion'];
            $t['telefono'] = $_POST['telefono'];
            break;
        }
    }
    unset($t);
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

if($flujo == "F1" && $proceso == "P1B"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['colegio_origen'] = $_POST['colegio_origen'];
            break;
        }
    }
    unset($t);
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
// FLUJO 2 - INTERFACES SECUNDARIAS
// ==========================================
if($flujo == "F2" && $proceso == "P1"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    
    $existe = false;
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['usuario'] = $usuarioTramite;
            $t['tipo_certificado'] = $_POST['tipo_certificado'];
            $t['observaciones'] = $_POST['observaciones'];
            $existe = true;
            break;
        }
    }
    unset($t);

    if(!$existe){
        $tramites[] = [
            "nrotramite" => $tramite,
            "usuario" => $usuarioTramite,
            "tipo_certificado" => $_POST['tipo_certificado'],
            "observaciones" => $_POST['observaciones']
        ];
    }
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
// EVALUACIÓN DE RUTAS SIGUIENTES Y CONDICIONES
// ==========================================
$siguiente = $procesoActual['siguiente'];

if($siguiente == "CONDICION"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    $condiciones = json_decode(file_get_contents("json/condicion.json"), true);

    foreach($condiciones as $c){
        if($c['flujo'] == $flujo && $c['proceso'] == $proceso){
            $campo = $c['campo'];
            $valorCampo = null;

            // Buscamos el valor guardado en el trámite actual
            foreach($tramites as $t){
                if($t['nrotramite'] == $tramite){
                    if(isset($t[$campo])){
                        $valorCampo = $t[$campo];
                    }
                    break;
                }
            }

            // CORRECCIÓN: Validamos de forma segura si la regla usa 'value' o 'valor'
            $valorRegla = null;
            if (isset($c['value'])) {
                $valorRegla = $c['value'];
            } elseif (isset($c['valor'])) {
                $valorRegla = $c['valor'];
            }

            // Comparamos de forma limpia sin generar Warnings
            if($valorCampo !== null && $valorCampo == $valorRegla){ 
                $siguiente = $c['siguiente'];
                break;
            }
        }
    }
}

// Generamos el paso siguiente solo si no existe ya un registro previo abierto
if(!empty($siguiente) && $siguiente != "CONDICION" && $siguiente != "FIN"){
    
    $existeAbierto = false;
    foreach($seguimiento as $s){
        if($s['nrotramite'] == $tramite && $s['flujo'] == $flujo && $s['proceso'] == $siguiente && empty($s['fechafin'])){
            $existeAbierto = true;
            break;
        }
    }

    if(!$existeAbierto){
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
}

file_put_contents("json/seguimiento.json", json_encode($seguimiento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// =========================================================================
// DIRECCIONAMIENTO DINÁMICO CON VALIDACIÓN DE ROL
// =========================================================================
if(!empty($siguiente) && $siguiente != "CONDICION" && $siguiente != "FIN"){
    
    // 1. Buscamos el objeto del proceso siguiente en el mapa para conocer su rol
    $procesoNextObj = null;
    foreach($procesos as $p){
        if($p['flujo'] == $flujo && $p['proceso'] == $siguiente){
            $procesoNextObj = $p;
            break;
        }
    }

    // 2. Comparamos los roles. Si el rol cambia, rompemos la continuidad enviando a la bandeja
    if ($procesoNextObj && isset($procesoActual['rol']) && isset($procesoNextObj['rol'])) {
        if ($procesoActual['rol'] !== $procesoNextObj['rol']) {
            header("Location: bandejae.php");
            exit();
        }
    }
    
    // Si tienen el mismo rol, el flujo continúa de inmediato
    header("Location: index.php?flujo=$flujo&proceso=$siguiente&tramite=$tramite");
} else {
    header("Location: bandejae.php");
}
exit();
?>