<?php
  session_start();
  session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="static/css/style.css" rel="stylesheet">
</head>
<body>

    <div class="login-container">
        <form action="iniciologin.php" method="POST">
            <div class="form-outline mb-4">
                <label class="form-label" for="loginName">Usuario</label>
                <input type="text" id="loginName" class="form-control" name="usuario" placeholder="Introduce tu usuario" autocomplete="off" required />
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="loginPassword">Clave</label>
                <input type="password" id="loginPassword" class="form-control" name="clave" autocomplete="new-password" placeholder="Introduce tu contraseña" required />
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-4">Sign in</button>

        </form>
    </div>

</body>
</html>