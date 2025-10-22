<?php
session_start();

// Incluir configuración
require_once 'includes/db.php';

// Verificar si el usuario está logueado
$usuario_logueado = isset($_SESSION['usuario']);

// Conectar a la base de datos
$conn = getDBConnection();

// Procesar formulario si se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agendar_cita'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $servicio = $_POST['servicio'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $mensaje = $_POST['mensaje'];
    
    // Insertar cita en la base de datos
    $stmt = $conn->prepare('INSERT INTO citas (nombre, email, telefono, servicio, fecha, hora, mensaje) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssss', $nombre, $email, $telefono, $servicio, $fecha, $hora, $mensaje);
    
    if ($stmt->execute()) {
        $mensaje_exito = 'Cita agendada correctamente';
    } else {
        $mensaje_error = 'Error al agendar la cita: ' . $conn->error;
    }
    
    $stmt->close();
}

// Obtener citas programadas
$citas_result = $conn->query('SELECT * FROM citas ORDER BY fecha DESC, hora DESC LIMIT 10');

$conn->close();
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Agendar Cita - Sistema de Salud BELÉN</title>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap'>
    <style>
        * {
            margin: 0;
            padding: 0;           
            box-sizing: border-box;
        }

        :root {
            --color-primary: #dc2626;
            --color-primary-dark: #991b1b;
            --color-secondary: #f7f8fc;
            --color-secondary-dark: #f6f4f8;
            --color-white: #ffffff;
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-600: #6b7280;
            --color-gray-800: #374151;
            --color-gray-900: #111827;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #eeeff1 0%, #f9f8fa 100%);
            min-height: 100vh;
        }

        /* Navigation */
        .navigation {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: rgba(220, 38, 38, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .nav-content {
            display: flex;
            height: 4rem;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .nav-logo p {
            font-size: 1.125rem;
            font-weight: 700;
            color: white;
        }

        .nav-links {
            display: none;
            align-items: center;
            gap: 2rem;
        }

        .nav-links a {
            font-size: 0.875rem;
            font-weight: 500;
            color: white;
            text-decoration: none;
            transition: color 0.2s;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
        }

        .nav-links a:hover {
            color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-links a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        @media (min-width: 640px) {
            .nav-links {
                display: flex;
            }
        }
        
        /* Main Content */
        .main-content {
            margin-top: 4rem;
            padding: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Section Container */
        .section-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .section-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .section-header h1 {
            font-size: 2.5em;
            margin-bottom: 15px;
        }
        
        .section-header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .section-content {
            padding: 40px;
        }
        
        /* Form Styles */
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .form-title {
            text-align: center;
            color: var(--color-primary);
            margin-bottom: 30px;
            font-size: 2rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--color-gray-800);
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--color-gray-200);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--color-primary);
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
        }
        
        /* Messages */
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Appointments List */
        .appointments-section {
            margin-top: 50px;
        }
        
        .section-title {
            font-size: 1.8rem;
            color: var(--color-primary);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .appointments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .appointment-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border-left: 4px solid var(--color-primary);
        }
        
        .appointment-card h3 {
            color: var(--color-primary);
            margin-bottom: 10px;
        }
        
        .appointment-detail {
            margin-bottom: 8px;
            color: var(--color-gray-800);
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--color-gray-600);
        }
        
        /* Login Prompt */
        .login-prompt {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-prompt a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-prompt a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class='navigation'>
        <div class='nav-container'>
            <div class='nav-content'>
                <div class='nav-logo'>
                    <p>Sistema de Salud BELÉN</p>
                </div>
                <div class='nav-links'>
                    <a href='index.php'>Inicio</a>
                    <a href='citas.php' class='active'>Citas</a>
                    <a href='pap.php'>Resultados PAP</a>
                    <a href='reclamos.php'>Reclamos</a>
                    <?php if ($usuario_logueado): ?>
                        <a href='admin/'>Administración</a>
                        <a href='logout.php'>Cerrar Sesión</a>
                    <?php else: ?>
                        <a href='login.php'>Iniciar Sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class='main-content'>
        <div class='section-container'>
            <div class='section-header'>
                <h1>Agendar Cita Médica</h1>
                <p>Complete el formulario para programar su cita</p>
            </div>
            
            <div class='section-content'>
                <?php if (isset($mensaje_exito)): ?>
                    <div class='message success'><?php echo $mensaje_exito; ?></div>
                <?php endif; ?>
                
                <?php if (isset($mensaje_error)): ?>
                    <div class='message error'><?php echo $mensaje_error; ?></div>
                <?php endif; ?>
                
                <?php if (!$usuario_logueado): ?>
                    <div class='login-prompt'>
                        <p>Para ver sus citas programadas, por favor <a href='login.php'>inicie sesión</a>.</p>
                    </div>
                <?php endif; ?>
                
                <!-- Appointment Form -->
                <div class='form-container'>
                    <h2 class='form-title'>Formulario de Cita</h2>
                    <form method='POST' action=''>
                        <div class='form-group'>
                            <label for='nombre'>Nombre Completo *</label>
                            <input type='text' id='nombre' name='nombre' required>
                        </div>
                        
                        <div class='form-group'>
                            <label for='email'>Correo Electrónico *</label>
                            <input type='email' id='email' name='email' required>
                        </div>
                        
                        <div class='form-group'>
                            <label for='telefono'>Teléfono</label>
                            <input type='tel' id='telefono' name='telefono'>
                        </div>
                        
                        <div class='form-group'>
                            <label for='servicio'>Servicio *</label>
                            <select id='servicio' name='servicio' required>
                                <option value=''>Seleccione un servicio</option>
                                <option value='Consulta General'>Consulta General</option>
                                <option value='Revisión PAP'>Revisión PAP</option>
                                <option value='Control Embarazo'>Control de Embarazo</option>
                                <option value='Pediatría'>Pediatría</option>
                                <option value='Ginecología'>Ginecología</option>
                                <option value='Laboratorio'>Laboratorio</option>
                                <option value='Vacunación'>Vacunación</option>
                            </select>
                        </div>
                        
                        <div class='form-group'>
                            <label for='fecha'>Fecha *</label>
                            <input type='date' id='fecha' name='fecha' required min='<?php echo date('Y-m-d'); ?>'>
                        </div>
                        
                        <div class='form-group'>
                            <label for='hora'>Hora *</label>
                            <select id='hora' name='hora' required>
                                <option value=''>Seleccione una hora</option>
                                <option value='08:00:00'>08:00 AM</option>
                                <option value='08:30:00'>08:30 AM</option>
                                <option value='09:00:00'>09:00 AM</option>
                                <option value='09:30:00'>09:30 AM</option>
                                <option value='10:00:00'>10:00 AM</option>
                                <option value='10:30:00'>10:30 AM</option>
                                <option value='11:00:00'>11:00 AM</option>
                                <option value='11:30:00'>11:30 AM</option>
                                <option value='12:00:00'>12:00 PM</option>
                                <option value='12:30:00'>12:30 PM</option>
                                <option value='14:00:00'>02:00 PM</option>
                                <option value='14:30:00'>02:30 PM</option>
                                <option value='15:00:00'>03:00 PM</option>
                                <option value='15:30:00'>03:30 PM</option>
                                <option value='16:00:00'>04:00 PM</option>
                                <option value='16:30:00'>04:30 PM</option>
                                <option value='17:00:00'>05:00 PM</option>
                                <option value='17:30:00'>05:30 PM</option>
                            </select>
                        </div>
                        
                        <div class='form-group'>
                            <label for='mensaje'>Mensaje (opcional)</label>
                            <textarea id='mensaje' name='mensaje' rows='4'></textarea>
                        </div>
                        
                        <button type='submit' name='agendar_cita' class='btn'>Agendar Cita</button>
                    </form>
                </div>
                
                <!-- Appointments List -->
                <?php if ($usuario_logueado && $citas_result && $citas_result->num_rows > 0): ?>
                    <div class='appointments-section'>
                        <h2 class='section-title'>Citas Programadas Recientes</h2>
                        <div class='appointments-grid'>
                            <?php while ($cita = $citas_result->fetch_assoc()): ?>
                                <div class='appointment-card'>
                                    <h3><?php echo htmlspecialchars($cita['servicio']); ?></h3>
                                    <div class='appointment-detail'>
                                        <span class='detail-label'>Paciente:</span> 
                                        <?php echo htmlspecialchars($cita['nombre']); ?>
                                    </div>
                                    <div class='appointment-detail'>
                                        <span class='detail-label'>Fecha:</span> 
                                        <?php echo date('d/m/Y', strtotime($cita['fecha'])); ?>
                                    </div>
                                    <div class='appointment-detail'>
                                        <span class='detail-label'>Hora:</span> 
                                        <?php echo date('H:i', strtotime($cita['hora'])); ?>
                                    </div>
                                    <div class='appointment-detail'>
                                        <span class='detail-label'>Email:</span> 
                                        <?php echo htmlspecialchars($cita['email']); ?>
                                    </div>
                                    <?php if (!empty($cita['telefono'])): ?>
                                        <div class='appointment-detail'>
                                            <span class='detail-label'>Teléfono:</span> 
                                            <?php echo htmlspecialchars($cita['telefono']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($cita['mensaje'])): ?>
                                        <div class='appointment-detail'>
                                            <span class='detail-label'>Mensaje:</span> 
                                            <?php echo htmlspecialchars($cita['mensaje']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
