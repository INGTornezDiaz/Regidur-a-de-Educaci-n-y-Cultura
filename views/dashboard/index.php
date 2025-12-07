<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Regiduría de Educación y Cultura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-book"></i> Regiduría de Educación y Cultura
            </a>

            <!-- Bienvenida al usuario -->
            <div class="ms-auto text-white d-flex align-items-center">
                <?php
                    $usuario_nombre = $usuario_nombre ?? ($_SESSION['nombre'] ?? '');
                    if (!empty($usuario_nombre)): ?>
                    <span class="me-3">Bienvenido <strong><?php echo htmlspecialchars($usuario_nombre); ?></strong></span>
                <?php endif; ?>
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>index.php?action=logout">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- Mensajes de éxito/error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php
            // contador de pendientes (asegura que la variable venga del controlador)
            $pendientes_count = isset($solicitudes_pendientes) ? count($solicitudes_pendientes) : 0;
        ?>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="registro-tab" data-bs-toggle="tab" data-bs-target="#registro" type="button" role="tab" aria-controls="registro" aria-selected="true">
                    <i class="bi bi-file-earmark-plus"></i> Nuevo Registro de Solicitud
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button" role="tab" aria-controls="pendientes" aria-selected="false">
                    <i class="bi bi-hourglass-split"></i> Solicitudes Pendientes
                    <?php if ($pendientes_count > 0): ?>
                        <span class="badge bg-danger ms-2"><?php echo $pendientes_count; ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="aprobadas-tab" data-bs-toggle="tab" data-bs-target="#aprobadas" type="button" role="tab" aria-controls="aprobadas" aria-selected="false">
                    <i class="bi bi-check-square"></i> Solicitudes Aprobadas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="localidades-tab" data-bs-toggle="tab" data-bs-target="#localidades" type="button" role="tab" aria-controls="localidades" aria-selected="false">
                    <i class="bi bi-geo-alt"></i> Localidades Apoyadas
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="dashboardTabsContent">
            <!-- Tab 1: Registrar Solicitud -->
            <div class="tab-pane fade show active" id="registro" role="tabpanel" aria-labelledby="registro-tab">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-plus"></i> Nueva Solicitud</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo BASE_URL; ?>index.php?action=registrar_solicitud" method="POST">
                            <input type="hidden" name="fecha" value="<?php echo date('Y-m-d'); ?>">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre_solicitante" required>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="tipo_solicitud" class="form-label">Tipo de Solicitud <span class="text-danger">*</span></label>
                                    <select id="tipo_solicitud" name="tipo_solicitud" class="form-select" required onchange="toggleMontoApoyo()">
                                        <option value="">-- Seleccione --</option>
                                        <option value="Informativa">Informativa</option>
                                        <option value="Trámite">Trámite</option>
                                        <option value="Apoyo">Apoyo</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="localidad" class="form-label">Localidad</label>
                                    <input type="text" class="form-control" id="localidad" name="localidad">
                                </div>
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="asunto" class="form-label">Asunto <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="asunto" name="asunto" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label">Número de Teléfono <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required>
                            </div>

                            <div class="mb-3" id="montoApoyoGroup" style="display:none;">
                                <label for="monto_apoyo" class="form-label">Monto de Apoyo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="monto_apoyo" name="monto_apoyo" step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Limpiar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar Solicitud
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Solicitudes Pendientes -->
            <div class="tab-pane fade" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-hourglass-split"></i> Solicitudes Pendientes</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($solicitudes_pendientes)): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> No hay solicitudes pendientes.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive table-responsive-dashboard">
                                <table class="table table-sm table-striped align-middle table-dashboard">
                                    <colgroup>
                                        <col style="width:120px;"><!-- Fecha -->
                                        <col><!-- Solicitante -->
                                        <col style="width:110px;"><!-- Tipo -->
                                        <col class="col-asunto"><!-- Asunto -->
                                        <col style="width:140px;"><!-- Localidad -->
                                        <col style="width:120px;"><!-- Teléfono -->
                                        <col class="col-monto"><!-- Monto -->
                                        <col class="col-acciones"><!-- Acciones -->
                                    </colgroup>
                                    <thead class="table-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Solicitante</th>
                                            <th>Tipo</th>
                                            <th>Asunto</th>
                                            <th>Localidad</th>
                                            <th>Teléfono</th>
                                            <th class="text-end">Monto de Apoyo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($solicitudes_pendientes as $s): ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($s['fecha'])); ?></td>
                                                <td><?php echo htmlspecialchars($s['nombre_solicitante']); ?></td>
                                                <td><span class="badge bg-info"><?php echo htmlspecialchars($s['tipo_solicitud']); ?></span></td>
                                                <td class="col-asunto" title="<?php echo htmlspecialchars($s['asunto']); ?>"><?php echo htmlspecialchars($s['asunto']); ?></td>
                                                <td><?php echo htmlspecialchars($s['localidad']); ?></td>
                                                <td><?php echo htmlspecialchars($s['telefono']); ?></td>
                                                <td class="text-end">
                                                    <?php $m = (float)($s['monto_apoyo'] ?? 0); ?>
                                                    <?php if ($m > 0): ?>
                                                        <span class="badge bg-info monto-badge">$<?php echo number_format($m, 2, '.', ','); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <!-- Botón aprobar (aceptar) -->
                                                    <a href="<?php echo BASE_URL; ?>index.php?action=aprobar_solicitud&id=<?php echo $s['id']; ?>"
                                                       class="btn btn-sm btn-success me-1"
                                                       onclick="return confirm('¿Aprobar esta solicitud?')"
                                                       title="Aprobar">
                                                        <i class="bi bi-check-lg"></i>
                                                    </a>

                                                    <!-- Botón editar monto -->
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-primary me-1"
                                                            data-id="<?php echo $s['id']; ?>"
                                                            data-monto="<?php echo number_format((float)($s['monto_apoyo'] ?? 0), 2, '.', ''); ?>"
                                                            data-asunto="<?php echo htmlspecialchars($s['asunto'], ENT_QUOTES); ?>"
                                                            onclick="openMontoModal(this)"
                                                            title="Editar monto">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <!-- Botón eliminar -->
                                                    <a href="<?php echo BASE_URL; ?>index.php?action=eliminar_solicitud&id=<?php echo $s['id']; ?>"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('¿Eliminar solicitud?')"
                                                       title="Eliminar">
                                                        <i class="bi bi-trash"></i>
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

            <!-- Tab 3: Solicitudes Aprobadas -->
            <div class="tab-pane fade" id="aprobadas" role="tabpanel" aria-labelledby="aprobadas-tab">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-check-square"></i> Solicitudes Aprobadas</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($solicitudes_aprobadas)): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> No hay solicitudes aprobadas.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Solicitante</th>
                                            <th>Tipo</th>
                                            <th>Asunto</th>
                                            <th>Localidad</th>
                                            <th>Teléfono</th>
                                            <th class="text-end">Monto de Apoyo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($solicitudes_aprobadas as $solicitud): ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($solicitud['fecha'])); ?></td>
                                                <td><?php echo htmlspecialchars($solicitud['nombre_solicitante']); ?></td>
                                                <td><span class="badge bg-success"><?php echo htmlspecialchars($solicitud['tipo_solicitud']); ?></span></td>
                                                <td><?php echo htmlspecialchars(substr($solicitud['asunto'], 0, 30)) . '...'; ?></td>
                                                <td><?php echo htmlspecialchars($solicitud['localidad']); ?></td>
                                                <td><?php echo htmlspecialchars($solicitud['telefono']); ?></td>
                                                <td class="text-end">
                                                    <?php
                                                        $monto = isset($solicitud['monto_apoyo']) ? (float)$solicitud['monto_apoyo'] : 0;
                                                    ?>
                                                    <?php if ($monto > 0): ?>
                                                        <span class="badge bg-info">$<?php echo number_format($monto, 2, '.', ','); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo BASE_URL; ?>index.php?action=eliminar_solicitud&id=<?php echo $solicitud['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta solicitud?')" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
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

            <!-- Nueva pestaña: Localidades Apoyadas -->
            <div class="tab-pane fade" id="localidades" role="tabpanel" aria-labelledby="localidades-tab">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Localidades Apoyadas</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($localidades_apoyadas)): ?>
                            <div class="alert alert-info">No hay registros de localidades apoyadas.</div>
                        <?php else: ?>
                            <div class="table-responsive table-responsive-dashboard">
                                <table class="table table-sm table-striped align-middle table-dashboard">
                                    <colgroup>
                                        <col style="width:30%;">
                                        <col style="width:50%;">
                                        <col style="width:20%;">
                                    </colgroup>
                                    <thead class="table-light">
                                        <tr>
                                            <th>Localidad</th>
                                            <th>Asunto</th>
                                            <th class="text-end">Monto Apoyado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($localidades_apoyadas as $r): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($r['localidad']); ?></td>
                                                <td title="<?php echo htmlspecialchars($r['asunto']); ?>"><?php echo htmlspecialchars(substr($r['asunto'], 0, 80)); ?><?php echo (strlen($r['asunto'])>80)?'...':''; ?></td>
                                                <td class="text-end">
                                                    <span class="badge bg-info monto-badge">$<?php echo number_format((float)$r['monto_apoyo'], 2, '.', ','); ?></span>
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
        </div>
    </div>

    <!-- Modal editar monto -->
    <div class="modal fade" id="modalMonto" tabindex="-1" aria-labelledby="modalMontoLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <form action="<?php echo BASE_URL; ?>index.php?action=actualizar_monto" method="POST" id="formActualizarMonto">
            <div class="modal-header">
              <h5 class="modal-title" id="modalMontoLabel">Editar Monto de Apoyo</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="monto_id">
              <div class="mb-2">
                <label class="form-label">Asunto</label>
                <div id="monto_asunto" class="small text-truncate"></div> <!-- <-- elemento añadido -->
              </div>
              <div class="mb-3">
                <label for="monto_input" class="form-label">Monto (MXN)</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" step="0.01" min="0" class="form-control" id="monto_input" name="monto_apoyo" required>
                </div>
                <div class="form-text">Máx $100,000.00</div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar/ocultar campo de monto según tipo de solicitud
        function toggleMontoApoyo() {
            const tipoSolicitud = document.getElementById('tipo_solicitud').value;
            const montoGroup = document.getElementById('montoApoyoGroup');
            const montoInput = document.getElementById('monto_apoyo');
            
            if (tipoSolicitud === 'Apoyo') {
                montoGroup.style.display = 'block';
                montoInput.required = true;
            } else {
                montoGroup.style.display = 'none';
                montoInput.required = false;
                montoInput.value = '';
            }
        }

        function openMontoModal(btn) {
            try {
                const id = btn.getAttribute('data-id') || '';
                const monto = btn.getAttribute('data-monto') || '0';
                const asunto = btn.getAttribute('data-asunto') || '';

                const idEl = document.getElementById('monto_id');
                const inputEl = document.getElementById('monto_input');
                const asuntoEl = document.getElementById('monto_asunto');

                if (idEl) idEl.value = id;
                if (inputEl) inputEl.value = monto;
                if (asuntoEl) asuntoEl.textContent = asunto;

                const modalEl = document.getElementById('modalMonto');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                } else {
                    console.error('Modal element not found');
                }
            } catch (err) {
                console.error('openMontoModal error:', err);
            }
        }

        // Validación cliente opcional
        (function(){
            const form = document.getElementById('formActualizarMonto');
            if (form) {
                form.addEventListener('submit', function(e){
                    const v = parseFloat(document.getElementById('monto_input').value) || 0;
                    if (v < 0 || v > 100000) {
                        e.preventDefault();
                        alert('Ingrese un monto válido entre 0 y 100,000.00');
                    }
                });
            }
        })();
    </script>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Estas variables deben venir del controlador DashboardController:
// $solicitudes_pendientes y $solicitudes_aprobadas
$solicitudes_pendientes = $solicitudes_pendientes ?? [];
$solicitudes_aprobadas = $solicitudes_aprobadas ?? [];
$localidades_apoyadas = $localidades_apoyadas ?? [];
?>

