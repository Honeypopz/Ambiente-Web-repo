
<?php
session_start();
// Español por defecto
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include __DIR__ . "/lang/" . $_SESSION['lang'] . ".php";

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

//Conexión a bd
$conexion = new mysqli("localhost", "root", "", "villasBrenes");

//Variables
$reservas = [];
$mensaje = '';
$tipo_mensaje = ''; 

//Obtener reservas de usuario
if (!$conexion->connect_error) {
    $id_usuario = $_SESSION['id_usuario'];
    $stmt = $conexion->prepare("SELECT id_reserva, nombre_Reservante, fechaEntrada, fechaSalida, adultos, ninos, habitacion FROM reservas WHERE id_usuario = ? ORDER BY fechaEntrada DESC");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $reservas[] = $row;
    }
    $stmt->close();
}

//Procesar edición de reserva
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Editar reserva
    if (isset($_POST['editar_reserva'])) {
        $id_reserva = intval($_POST['id_reserva']);
        $fechaEntrada = $_POST['fecha_entrada'];
        $fechaSalida = $_POST['fecha_salida'];
        $adultos = intval($_POST['adultos']);
        $ninos = intval($_POST['ninos']);
        $habitacion = intval($_POST['habitacion']);
        
        //Obtener nombre reserva
        $nombreReservante = '';
        
        foreach ($reservas as $reserva) {
            if ($reserva['id_reserva'] == $id_reserva) {
                $nombreReservante = $reserva['nombre_Reservante'];
                break;
            }
        }
        
        //Validaciones
        $errores = [];
        
        if (empty($fechaEntrada) || empty($fechaSalida)) {
            $errores[] = "Las fechas de entrada y salida son obligatorias";
        } elseif ($fechaEntrada >= $fechaSalida) {
            $errores[] = "La fecha de salida debe ser posterior a la fecha de entrada";
        }
        
        if ($adultos < 1) {
            $errores[] = "Debe haber al menos 1 adulto";
        }
        
        if ($ninos < 0) {
            $errores[] = "El número de niños no puede ser negativo";
        }
        
        if ($habitacion < 1) {
            $errores[] = "Debe reservar al menos 1 habitación";
        }
        
        //Actualizar reserva
        if (empty($errores)) {
            $stmt = $conexion->prepare("UPDATE reservas SET fechaEntrada = ?, fechaSalida = ?, adultos = ?, ninos = ?, habitacion = ? WHERE id_reserva = ? AND id_usuario = ?");
            $stmt->bind_param("ssiiiii", $fechaEntrada, $fechaSalida, $adultos, $ninos, $habitacion, $id_reserva, $id_usuario);
            
            if ($stmt->execute()) {
                $mensaje = "Reserva actualizada correctamente";
                $tipo_mensaje = "success";
                
                //Actualizar lista de reservas
                $stmt = $conexion->prepare("SELECT id_reserva, nombre_Reservante, fechaEntrada, fechaSalida, adultos, ninos, habitacion FROM reservas WHERE id_usuario = ? ORDER BY fechaEntrada DESC");
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $result = $stmt->get_result();
                $reservas = [];
                while ($row = $result->fetch_assoc()) {
                    $reservas[] = $row;
                }
                $stmt->close();
            } else {
                $mensaje = "Error al actualizar la reserva: " . $conexion->error;
                $tipo_mensaje = "error";
            }
        } else {
            $mensaje = implode('<br>', $errores);
            $tipo_mensaje = "error";
        }
    }
    
    //Eliminar reserva
    elseif (isset($_POST['eliminar_reserva'])) {
        $id_reserva = intval($_POST['id_reserva']);
        
        //Confirmar reserva de usuario
        $stmt = $conexion->prepare("SELECT id_reserva FROM reservas WHERE id_reserva = ? AND id_usuario = ?");
        $stmt->bind_param("ii", $id_reserva, $id_usuario);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            //Eliminar la reserva
            $stmt = $conexion->prepare("DELETE FROM reservas WHERE id_reserva = ? AND id_usuario = ?");
            $stmt->bind_param("ii", $id_reserva, $id_usuario);
            
            if ($stmt->execute()) {
                $mensaje = "Reserva eliminada correctamente";
                $tipo_mensaje = "success";
                
                //Actualizar lista de reservas
                $stmt = $conexion->prepare("SELECT id_reserva, nombre_Reservante, fechaEntrada, fechaSalida, adultos, ninos, habitacion FROM reservas WHERE id_usuario = ? ORDER BY fechaEntrada DESC");
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $result = $stmt->get_result();
                $reservas = [];
                while ($row = $result->fetch_assoc()) {
                    $reservas[] = $row;
                }
                $stmt->close();
            } else {
                $mensaje = "Error al eliminar la reserva: " . $conexion->error;
                $tipo_mensaje = "error";
            }
        } else {
            $mensaje = "Hubo un problema al acceder a las reservas";
            $tipo_mensaje = "error";
        }
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Reservas - Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloGestionReserva.css">
</head>

<body>

    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Gestionar Reservas</span>
        </div>
        <div class="user-info">
            <span class="user-name"><?php echo $_SESSION['nombre_usuario']; ?></span>
            <span class="user-role client">CLIENTE</span>
            <!-- Selector de idioma -->
            <div class="language-selector">
                <?php if ($_SESSION['lang'] == 'es'): ?>
                    <a href="CambioIdioma.php?lang=en">
                        <i class="bi bi-translate"></i> English
                    </a>
                <?php else: ?>
                    <a href="CambioIdioma.php?lang=es">
                        <i class="bi bi-translate"></i> Español
                    </a>
                <?php endif; ?>
            </div>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="reserva-container">
            <div class="reserva-header">
                <h2>Mis Reservas</h2>
                <p>Gestiona o elimina tus reservas realizadas</p>
            </div>
            
            <?php if ($mensaje): ?>
            <div class="message <?php echo $tipo_mensaje; ?>" id="message">
                <?php echo $mensaje; ?>
            </div>
            <?php endif; ?>
            
            <div class="reservas-list">
                <?php if (empty($reservas)): ?>
                    <div class="no-reservas">
                        <i class="bi bi-calendar-x"></i>
                        <p>No tienes reservas realizadas.</p>
                        <a href="ReservaEstadia.php" class="btn-reservar" style="display: inline-block; margin-top: 15px;">
                            Realizar una reserva
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($reservas as $reserva): ?>
                    <div class="reserva-item" id="reserva-<?php echo $reserva['id_reserva']; ?>">
                        <div class="reserva-header-info">
                            <div>
                                <span class="reserva-id">Reserva #<?php echo $reserva['id_reserva']; ?></span>
                                <span class="reserva-dates">
                                    (<?php echo date('d/m/Y', strtotime($reserva['fechaEntrada'])); ?> - 
                                    <?php echo date('d/m/Y', strtotime($reserva['fechaSalida'])); ?>)
                                </span>
                            </div>
                            <div class="reserva-actions">
                                <button class="btn-edit" onclick="toggleEditForm(<?php echo $reserva['id_reserva']; ?>)">
                                    Editar
                                </button>
                                <button class="btn-eliminar" onclick="mostrarConfirmacionEliminar(<?php echo $reserva['id_reserva']; ?>)">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                        
                        <div class="reserva-details">
                            <div class="detail-item">
                                <span class="detail-label">Reservante:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($reserva['nombre_Reservante']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Fecha de entrada:</span>
                                <span class="detail-value"><?php echo date('d/m/Y', strtotime($reserva['fechaEntrada'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Fecha de salida:</span>
                                <span class="detail-value"><?php echo date('d/m/Y', strtotime($reserva['fechaSalida'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Adultos:</span>
                                <span class="detail-value"><?php echo $reserva['adultos']; ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Niños:</span>
                                <span class="detail-value"><?php echo $reserva['ninos']; ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Habitaciones:</span>
                                <span class="detail-value"><?php echo $reserva['habitacion']; ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Noches de estadía:</span>
                                <span class="detail-value">
                                    <?php 
                                    $entrada = new DateTime($reserva['fechaEntrada']);
                                    $salida = new DateTime($reserva['fechaSalida']);
                                    $diferencia = $entrada->diff($salida);
                                    echo $diferencia->days;
                                    ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Formulario para gestionar -->
                        <div class="edit-form" id="edit-form-<?php echo $reserva['id_reserva']; ?>">
                            <form method="POST" action="GestionarReserva.php" onsubmit="return validateEditForm(this)">
                                <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                <input type="hidden" name="editar_reserva" value="1">
                    
                                <div class="form-group">
                                    <label for="nombre_<?php echo $reserva['id_reserva']; ?>" class="disabled-field-label">Nombre del reservante</label>
                                    <input type="text" id="nombre_<?php echo $reserva['id_reserva']; ?>" 
                                           value="<?php echo htmlspecialchars($reserva['nombre_Reservante']); ?>" 
                                           class="disabled-field" disabled readonly>
                                </div>
                                
                                <div class="form-group fecha-group">
                                    <div>
                                        <label for="fecha_entrada_<?php echo $reserva['id_reserva']; ?>">Fecha de entrada *</label>
                                        <input type="date" id="fecha_entrada_<?php echo $reserva['id_reserva']; ?>" name="fecha_entrada" 
                                               value="<?php echo $reserva['fechaEntrada']; ?>" 
                                               min="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div>
                                        <label for="fecha_salida_<?php echo $reserva['id_reserva']; ?>">Fecha de salida *</label>
                                        <input type="date" id="fecha_salida_<?php echo $reserva['id_reserva']; ?>" name="fecha_salida" 
                                               value="<?php echo $reserva['fechaSalida']; ?>" 
                                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group personas-group">
                                    <div>
                                        <label for="adultos_<?php echo $reserva['id_reserva']; ?>">Número de adultos *</label>
                                        <input type="number" id="adultos_<?php echo $reserva['id_reserva']; ?>" name="adultos" 
                                               value="<?php echo $reserva['adultos']; ?>" 
                                               min="1" max="10" required>
                                    </div>
                                    <div>
                                        <label for="ninos_<?php echo $reserva['id_reserva']; ?>">Número de niños *</label>
                                        <input type="number" id="ninos_<?php echo $reserva['id_reserva']; ?>" name="ninos" 
                                               value="<?php echo $reserva['ninos']; ?>" 
                                               min="0" max="10" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="habitacion_<?php echo $reserva['id_reserva']; ?>">Número de habitaciones *</label>
                                    <input type="number" id="habitacion_<?php echo $reserva['id_reserva']; ?>" name="habitacion" 
                                           value="<?php echo $reserva['habitacion']; ?>" 
                                           min="1" max="10" required>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn-save">
                                        Guardar Cambios
                                    </button>
                                    <button type="button" class="btn-cancel" onclick="toggleEditForm(<?php echo $reserva['id_reserva']; ?>)">
                                        Cerrar
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Formulario para eliminar -->
                        <div class="delete-form" id="delete-form-<?php echo $reserva['id_reserva']; ?>" style="display: none;">
                            <div class="delete-confirmation">
                                <h3><i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación</h3>
                                <p>¿Estás seguro de que deseas eliminar la Reserva #<?php echo $reserva['id_reserva']; ?>?</p>
                                <p><strong>Esta acción no se puede deshacer.</strong></p>
                                
                                <div class="reserva-info-delete">
                                    <p><strong>Reservante:</strong> <?php echo htmlspecialchars($reserva['nombre_Reservante']); ?></p>
                                    <p><strong>Fechas:</strong> <?php echo date('d/m/Y', strtotime($reserva['fechaEntrada'])); ?> - <?php echo date('d/m/Y', strtotime($reserva['fechaSalida'])); ?></p>
                                </div>
                                
                                <form method="POST" action="GestionarReserva.php" class="delete-form-buttons">
                                    <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                    <input type="hidden" name="eliminar_reserva" value="1">
                                    
                                    <button type="submit" class="btn-confirm-delete">
                                        <i class="bi bi-check-circle"></i> Sí, Eliminar
                                    </button>
                                    <button type="button" class="btn-cancel-delete" onclick="ocultarConfirmacionEliminar(<?php echo $reserva['id_reserva']; ?>)">
                                        Cancelar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="form-actions" style="margin-top: 30px;">
                <a href="ReservaEstadia.php" class="btn-reservar">
                    Nueva Reserva
                </a>
                <a href="dashboardCliente.php" class="btn-cancelar">
                    Cancelar
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Villas Brenes. Todos los derechos reservados.</p>
            <div class="footer-links">
                <a href="https://www.facebook.com/p/Villas-Brenes-61571505092179/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <i class="bi bi-facebook" aria-hidden="true"></i>
                </a>
                <a href="https://wa.link/qmnavx" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>
                </a>
                <a href="https://www.instagram.com/villasbrenes_/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <i class="bi bi-instagram" aria-hidden="true"></i>
                </a>
                <a href="mailto:test@gmail.com" aria-label="Email">
                    <i class="bi bi-envelope-at" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            //Ocultar mensajes después de 5 segundos
            setTimeout(() => {
                const message = document.getElementById('message');
                if (message) {
                    message.style.display = 'none';
                }
            }, 5000);
            
            //Fechas minimas para formularioss
            const today = new Date().toISOString().split('T')[0];
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            
            document.querySelectorAll('input[type="date"]:not(.disabled-field)').forEach(input => {
                if (input.id.includes('fecha_entrada')) {
                    input.min = today;
                } else if (input.id.includes('fecha_salida')) {
                    input.min = tomorrowStr;
                }
            });
        });
        
        function toggleEditForm(reservaId) {
            const form = document.getElementById('edit-form-' + reservaId);
            const deleteForm = document.getElementById('delete-form-' + reservaId);
            const btn = document.querySelector('#reserva-' + reservaId + ' .btn-edit');
            
            if (deleteForm.style.display === 'block') {
                deleteForm.style.display = 'none';
            }
            
            if (form.classList.contains('active')) {
                form.classList.remove('active');
                btn.innerHTML = '<i class="bi bi-pencil"></i> Editar';
            } else {
                document.querySelectorAll('.edit-form.active').forEach(openForm => {
                    if (openForm.id !== 'edit-form-' + reservaId) {
                        openForm.classList.remove('active');
                        const otherId = openForm.id.replace('edit-form-', '');
                        const otherBtn = document.querySelector('#reserva-' + otherId + ' .btn-edit');
                        if (otherBtn) otherBtn.innerHTML = '<i class="bi bi-pencil"></i> Editar';
                    }
                });
                
                form.classList.add('active');
                btn.innerHTML = 'Cerrar';
            }
        }
        
        function validateEditForm(form) {
            const fechaEntrada = form.querySelector('input[name="fecha_entrada"]').value;
            const fechaSalida = form.querySelector('input[name="fecha_salida"]').value;
            const adultos = form.querySelector('input[name="adultos"]').value;
            const ninos = form.querySelector('input[name="ninos"]').value;
            const habitacion = form.querySelector('input[name="habitacion"]').value;

            if (!fechaEntrada || !fechaSalida) {
                alert('Por favor completa las fechas de entrada y salida');
                return false;
            }
            
            if (new Date(fechaEntrada) >= new Date(fechaSalida)) {
                alert('La fecha de salida debe ser posterior a la fecha de entrada');
                return false;
            }
            
            if (parseInt(adultos) < 1) {
                alert('Debe haber al menos 1 adulto');
                return false;
            }
            
            if (parseInt(ninos) < 0) {
                alert('El número de niños no puede ser negativo');
                return false;
            }
            
            if (parseInt(habitacion) < 1) {
                alert('Debe reservar al menos 1 habitación');
                return false;
            }
            
            return true;
        }
        
        //Fechas minimas
        document.querySelectorAll('input[name="fecha_entrada"]:not(.disabled-field)').forEach(input => {
            input.addEventListener('change', function() {
                const fechaMin = new Date(this.value);
                fechaMin.setDate(fechaMin.getDate() + 1);
                const fechaSalidaId = this.id.replace('fecha_entrada', 'fecha_salida');
                const fechaSalida = document.getElementById(fechaSalidaId);
                
                if (fechaSalida) {
                    fechaSalida.min = fechaMin.toISOString().split('T')[0];
                    
                    //Fecha de salida cambiada en caso de ser anterior a la nueva fecha mínima
                    if (new Date(fechaSalida.value) < fechaMin) {
                        fechaSalida.value = fechaMin.toISOString().split('T')[0];
                    }
                }
            });
        });
        
        //Eliminar reservas
        function mostrarConfirmacionEliminar(reservaId) {
            const editForm = document.getElementById('edit-form-' + reservaId);
            if (editForm.classList.contains('active')) {
                editForm.classList.remove('active');
                const editBtn = document.querySelector('#reserva-' + reservaId + ' .btn-edit');
                if (editBtn) editBtn.innerHTML = '<i Editar';
            }
        
            const deleteForm = document.getElementById('delete-form-' + reservaId);
            deleteForm.style.display = 'block';
            document.querySelectorAll('.delete-form').forEach(form => {
                if (form.id !== 'delete-form-' + reservaId) {
                    form.style.display = 'none';
                }
            });
        }
        
        function ocultarConfirmacionEliminar(reservaId) {
            const deleteForm = document.getElementById('delete-form-' + reservaId);
            deleteForm.style.display = 'none';
        }
        
        //Confirmar eliminar
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('delete-form-buttons')) {
                if (!confirm('¿Confirmas que quieres eliminar esta reserva? Esta acción es irreversible.')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>

</html>