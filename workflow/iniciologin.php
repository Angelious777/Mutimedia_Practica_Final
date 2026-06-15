<?php

session_start();

$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

$archivo = "json/usuarios.json";

$usuarios = json_decode(file_get_contents($archivo), true);

$encontrado = false;

foreach($usuarios as $u){

    if(
        $u['usuario'] == $usuario &&
        $u['clave'] == $clave
    ){
        $_SESSION['ci'] = $u['ci'];
        $_SESSION['nombre'] = $u['nombre'];
        $_SESSION['apaterno'] = $u['apaterno'];
        $_SESSION['amaterno'] = $u['amaterno'];
        $_SESSION['usuario'] = $u['usuario'];
        $_SESSION['rol'] = $u['rol'];

        $encontrado = true;

        break;
    }
}

if($encontrado){
    header("Location: bandejae.php");
}else{
    echo "Usuario o contraseña incorrectos";
}

?>