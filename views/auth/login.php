<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Regiduría de Educación y Cultura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-background"></div>
    <div class="container px-3 position-relative">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-sm-10 col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <img src="<?php echo BASE_URL; ?>assets/img/logo2.jpeg" alt="Logo" class="img-fluid mb-3 login-logo" style="width: 200px; height: auto;"">
                            <h2 class="card-title mb-2">Regiduría de Educación y Cultura</h2>
                            <p class="text-muted mb-0">Inicio de Sesión</p>
                        </div>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=login">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </div>
                        </form>
                        <hr class="my-4">
                        <div class="text-center">
                            <p class="mb-0">¿No tienes una cuenta?</p>
                            <a href="<?php echo BASE_URL; ?>index.php?action=registrar" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="bi bi-person-plus"></i> Crear Nuevo Usuario
                            </a>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3 px-2">
                    <small class="text-white d-block d-sm-inline" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">Fernando Navarrete Cortes | 2025</small>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

