<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes Aprobadas - Regiduría de Educación y Cultura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-mortarboard"></i> <span class="d-none d-sm-inline">Regiduría de Educación y Cultura</span>
                <span class="d-sm-none">Regiduría</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="btn btn-outline-light btn-sm" href="<?php echo BASE_URL; ?>index.php?action=dashboard">
                        <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Volver al Dashboard</span>
                        <span class="d-sm-none">Volver</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-success text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h5 class="mb-2 mb-md-0"><i class="bi bi-check-circle"></i> Solicitudes Aprobadas</h5>
                <a href="<?php echo BASE_URL; ?>index.php?action=dashboard" class="btn btn-light btn-sm w-100 w-md-auto">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($solicitudes_aprobadas)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No hay solicitudes aprobadas.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Solicitante</th>
                                    <th>Tipo</th>
                                    <th>Asunto</th>
                                    <th>Localidad</th>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitudes_aprobadas as $solicitud): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($solicitud['fecha'])); ?></td>
                                        <td><?php echo htmlspecialchars($solicitud['nombre_solicitante']); ?></td>
                                        <td><?php echo htmlspecialchars($solicitud['tipo_solicitud']); ?></td>
                                        <td><?php echo htmlspecialchars($solicitud['asunto']); ?></td>
                                        <td><?php echo htmlspecialchars($solicitud['localidad']); ?></td>
                                        <td><?php echo htmlspecialchars($solicitud['telefono']); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>index.php?action=eliminar_solicitud&id=<?php echo $solicitud['id']; ?>" 
                                               class="btn btn-sm btn-danger w-100 w-md-auto" 
                                               onclick="return confirm('¿Está seguro de eliminar esta solicitud?')">
                                                <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Eliminar</span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

