<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Regiduría de Educación y Cultura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container px-3">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="card-title mb-2">Regiduría de Educación y Cultura</h2>
                            <p class="text-muted mb-0">Crear Nuevo Usuario</p>
                        </div>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=registrar" id="formRegistro">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                                <small class="form-text text-muted">El usuario debe ser único</small>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                <small class="form-text text-muted">Mínimo 6 caracteres</small>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required minlength="6">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus"></i> Crear Usuario
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?action=login" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Volver al Inicio de Sesión
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validar que las contraseñas coincidan antes de enviar
        document.getElementById('formRegistro').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
        });
    </script>
</body>
</html>

