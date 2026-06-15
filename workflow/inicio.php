<?php
  session_start();
  session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Trámites</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="static/css/style.css" rel="stylesheet">
    <link href="static/css/login.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">

</head>
<body>

    <div class="login-container">
        
        <div class="login-header text-center mb-4">
            <i class="bi bi-shield-lock-fill"></i>
            <h3 class="mt-2 fw-bold text-dark">Portal de Trámites</h3>
            <p class="text-muted small">Ingresa tus credenciales para acceder a tu bandeja</p>
        </div>

        <form action="iniciologin.php" method="POST">
            
            <div class="mb-3">
                <label class="form-label text-secondary small fw-semibold" for="loginName">Usuario</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" id="loginName" class="form-control border-start-0 ps-0 bg-light" name="usuario" placeholder="Introduce tu usuario" autocomplete="off" required />
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-secondary small fw-semibold" for="loginPassword">Clave</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted">
                        <i class="bi bi-key"></i>
                    </span>
                    <input type="password" id="loginPassword" class="form-control border-start-0 ps-0 bg-light" name="clave" autocomplete="new-password" placeholder="Introduce tu contraseña" required />
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm mb-3">
                Iniciar Sesión <i class="bi bi-box-arrow-in-right ms-1"></i>
            </button>

        </form>

        <div class="text-center mt-4 pt-3 border-top">
            <span class="text-muted small">&copy; <?php echo date("Y"); ?> Universidad Mayor de San Andrés</span>
        </div>
        
    </div>

</body>
</html>