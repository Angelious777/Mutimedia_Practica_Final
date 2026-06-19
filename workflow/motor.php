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

// =========================================================================
// PERSISTENCIA EN TRAMITES.JSON (ESTRUCTURA LINEAL CORRELATIVA)
// =========================================================================

// F1 - P1: Datos Generales
if($flujo == "F1" && $proceso == "P1"){
    $tramites = json_decode(file_get_contents("json/tramites.json"), true);
    $existe = false;
    foreach($tramites as &$t){
        if($t['nrotramite'] == $tramite){
            $t['usuario'] = $usuarioTramite;
            $t['gestion'] = $_POST['gestion'];
            $t['semestre'] = $_POST['semestre'];
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
            "gestion" => $_POST['gestion'],
            "semestre" => $_POST['semestre'],
            "observaciones" => $_POST['observaciones']
        ];
    }
    file_put_contents("json/tramites.json", json_encode($tramites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// F1 - P2: Registrar Datos Socioeconomicos (Antes P1A)
if($flujo == "F1" && $proceso == "P2"){
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

// F1 - P3: Cargar Requisitos de Inscripcion (Antes P1B)
if($flujo == "F1" && $proceso == "P3"){
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

// F1 - P4: Verificar Habilitacion Academica (Antes P2)
if($flujo == "F1" && $proceso == "P4"){
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

// F1 - P6: Verificar Pago Matricula (Antes P4)
if($flujo == "F1" && $proceso == "P6"){
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

// F1 - P8: Seleccionar Materias (Antes P6)
if($flujo == "F1" && $proceso == "P8"){
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

// F1 - P9: Verificar Cupos (Antes P7)
if($flujo == "F1" && $proceso == "P9"){
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
// FLUJO 2 - PERSISTENCIA DE INTERFACES
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


// =========================================================================
// EVALUACIÓN SEGURA DE RUTAS SIGUIENTES (CONDICIONALES)
// =========================================================================
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

            $valorRegla = null;
            if (isset($c['value'])) {
                $valorRegla = $c['value'];
            } elseif (isset($c['valor'])) {
                $valorRegla = $c['valor'];
            }

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
    
    $procesoNextObj = null;
    foreach($procesos as $p){
        if($p['flujo'] == $flujo && $p['proceso'] == $siguiente){
            $procesoNextObj = $p;
            break;
        }
    }

    if ($procesoNextObj && isset($procesoActual['rol']) && isset($procesoNextObj['rol'])) {
        if ($procesoActual['rol'] !== $procesoNextObj['rol']) {
            header("Location: bandejae.php");
            exit();
        }
    }
    
    header("Location: index.php?flujo=$flujo&proceso=$siguiente&tramite=$tramite");
} else {
    header("Location: bandejae.php");
}
exit();
?>