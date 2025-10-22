<?php
session_start();

// Si el usuario ya está logueado, redirigir al panel de administración
if (isset($_SESSION['usuario'])) {
    header('Location: admin/index.php');
    exit();
}

// Incluir configuración
require_once 'includes/db.php';

// Variables para mensajes
$error = '';

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Conectar a la base de datos
    $conn = getDBConnection();
    
    // Preparar consulta para buscar usuario
    $stmt = $conn->prepare('SELECT id, username, password, rol FROM usuarios WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        
        // Verificar contraseña
        if (password_verify($password, $usuario['password'])) {
            // Iniciar sesión
            $_SESSION['usuario'] = $usuario['username'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['usuario_id'] = $usuario['id'];
            
            // Redirigir según rol
            if ($usuario['rol'] == 'admin') {
                header('Location: admin/index.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error = 'Contraseña incorrecta';
        }
    } else {
        $error = 'Usuario no encontrado';
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Iniciar Sesión - Sistema de Salud BELÉN</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Login Container */
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            margin: 20px;
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .login-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .login-content {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--color-gray-800);
        }
        
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid var(--color-gray-200);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
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
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            text-align: center;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .credentials-info {
            background: #e9f7fe;
            border: 1px solid #b3e0f0;
            color: #0c5460;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .credentials-info h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #0c5460;
        }
        
        .credentials-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class='login-container'>
        <div class='login-header'>
            <h1>Sistema de Salud BELÉN</h1>
            <p>Iniciar Sesión</p>
        </div>
        
        <div class='login-content'>
            <?php if ($error): ?>
                <div class='error-message'><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method='POST' action=''>
                <div class='form-group'>
                    <label for='username'>Nombre de Usuario</label>
                    <input type='text' id='username' name='username' required autofocus>
                </div>
                
                <div class='form-group'>
                    <label for='password'>Contraseña</label>
                    <input type='password' id='password' name='password' required>
                </div>
                
                <button type='submit' name='login' class='btn'>Iniciar Sesión</button>
            </form>
            
            <div class='credentials-info'>
                <h3>Credenciales de Acceso</h3>
                <p><strong>Administrador:</strong> admin / medico2024</p>
                <p><strong>Nota:</strong> Por motivos de seguridad, se recomienda cambiar la contraseña por defecto.</p>
            </div>
            
            <div class='back-link'>
                <a href='index.php'>? Volver a la página principal</a>
            </div>
        </div>
    </div>
</body>
</html>
