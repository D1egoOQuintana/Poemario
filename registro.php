<?php
session_start(); // Iniciar la sesión

// Conectar a la base de datos MySQL
$conexion = new mysqli('localhost', 'root', '1998supre', 'poemas_db');

// Verificar si hay errores en la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Variable para almacenar el mensaje de error o éxito
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'], $_POST['email'], $_POST['contraseña'], $_POST['rol'])) {
    // Capturando los datos del formulario
    $nombre_usuario = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['contraseña'];
    $rol = $_POST['rol'];

    // Verificar que las variables no estén vacías
    if (empty($nombre_usuario) || empty($email) || empty($password) || empty($rol)) {
        $mensaje = 'Por favor completa todos los campos.';
    } else {
        // Encriptar la contraseña antes de guardarla
        $contraseña_encriptada = password_hash($password, PASSWORD_DEFAULT);

        // Verificar si el email ya está registrado
        $verificar_email = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verificar_email->bind_param('s', $email);
        $verificar_email->execute();
        $verificar_email->store_result();

        if ($verificar_email->num_rows > 0) {
            $mensaje = 'El email ya está registrado.';
        } else {
            // Insertar los datos en la base de datos
            $query = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES (?, ?, ?, ?)");
            $query->bind_param('ssss', $nombre_usuario, $email, $contraseña_encriptada, $rol);

            if ($query->execute()) {
                $mensaje = 'Registro exitoso. Ahora puedes iniciar sesión.';
            } else {
                $mensaje = 'Error: ' . $query->error;
            }

            $query->close();
        }

        $verificar_email->close();
    }
}

// Verificar si el formulario fue enviado para iniciar sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['contraseña'])) {
    $email = $_POST['email'];
    $password = $_POST['contraseña'];

    // Consulta para verificar si el usuario existe por email
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // Verificar si el usuario existe
    if (mysqli_num_rows($resultado) === 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        // Verificar si la contraseña es correcta
        if (password_verify($password, $usuario['password'])) {
            // Si la contraseña es correcta, iniciar sesión}
            $_SESSION['usuario_id'] = $usuario['id']; // Almacenar el ID del usuario en la sesión
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol']; // Almacenar el rol en la sesión
            header("Location: home.php");  // Redirigir a la página de inicio
            exit();
        } else {
            // Contraseña incorrecta
            $mensaje = 'Contraseña incorrecta.';
        }
    } else {
        // Usuario no encontrado
        $mensaje = 'Usuario no encontrado.';
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Bienvenido a mi Formulario</title>
</head>
<body>

    <div class="container-form sign-up">
        <div class="welcome-back">
            <div class="message">
                <h2>Bienvenido a Textealo</h2>
                <p>Si ya tienes una cuenta por favor inicia sesion aqui</p>
                <button class="sign-up-btn">Iniciar Sesion</button>
            </div>
        </div>
        <form class="formulario" action="registro.php" method="POST">
            <h2 class="create-account">Crear una cuenta</h2>
            <div class="iconos">
                <div class="border-icon">
                    <i class='bx bxl-instagram'></i>
                </div>
                <div class="border-icon">
                    <i class='bx bxl-linkedin'></i>
                </div>
                <div class="border-icon">
                    <i class='bx bxl-facebook-circle'></i>
                </div>
            </div>
            <p class="cuenta-gratis">Crear una cuenta gratis</p>
            <input type="text" name="nombre" placeholder="Nombre">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="contraseña" placeholder="Contraseña">
            <select name="rol" required>
                    <option value="usuario">Usuario</option>
                    <option value="admin">Admin</option>
            </select>
            <input type="submit" value="Registrarse">
           
            <!-- Mostrar mensaje de error o éxito -->
            <?php if (!empty($mensaje)) : ?>
                <p style="color: green;"><?php echo $mensaje; ?></p>
            <?php endif; ?>
        </form>
<!-- Mostrar el mensaje de error si existe -->
    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    </div>
    <div class="container-form sign-in">
    <form class="formulario" action="registro.php" method="POST">
        <h2 class="create-account">Iniciar Sesion</h2>
        <div class="iconos">
            <div class="border-icon">
                <i class='bx bxl-instagram'></i>
            </div>
            <div class="border-icon">
                <i class='bx bxl-linkedin'></i>
            </div>
            <div class="border-icon">
                <i class='bx bxl-facebook-circle'></i>
            </div>
        </div>
        <p class="cuenta-gratis">¿Aun no tienes una cuenta?</p>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="contraseña" placeholder="Contraseña" required>
        <input type="submit" value="Iniciar Sesion">
        <?php if (!empty($mensaje)) : ?>
                <p style="color: red;"><?php echo $mensaje; ?></p>
            <?php endif; ?>
    </form>
    <div class="welcome-back">
        <div class="message">
            <h2>Bienvenido de nuevo</h2>
            <p>Si aun no tienes una cuenta por favor registrese aqui</p>
            <button class="sign-in-btn">Registrarse</button>
        </div>
    </div>
</div>
    <script src="script.js"></script>
    <script>
function mostrarMensaje(mensaje) {
    var alerta = document.createElement("div");
    alerta.innerText = mensaje;
    alerta.style.position = "fixed";
    alerta.style.bottom = "10px";
    alerta.style.right = "10px";
    alerta.style.backgroundColor = "rgba(0, 0, 0, 0.7)";
    alerta.style.color = "#fff";
    alerta.style.padding = "10px 20px";
    alerta.style.borderRadius = "5px";
    alerta.style.zIndex = "1000";
    document.body.appendChild(alerta);
    
    setTimeout(function() {
        alerta.remove();
    }, 4000); // Ocultar el mensaje después de 4 segundos
}
</script>

</body>

</html>