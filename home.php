<?php
include 'conexion.php';
session_start(); // Iniciar la sesión en todas las páginas donde quieras mostrar el estado de la sesión
// Conectar a la base de datos MySQL
$conexion = new mysqli('localhost', 'root', '1998supre', 'poemas_db');

// Verificar si hay errores en la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Recuperar todos los poemas de la base de datos
$query = "SELECT poemas.titulo, poemas.contenido, usuarios.nombre_usuario FROM poemas JOIN usuarios ON poemas.usuario_id = usuarios.id";
$resultado = $conexion->query($query);

$poemas = [];
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $poemas[] = $row;
    }
}

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poemario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Asegúrate de que la ruta sea correcta -->

</head>

<body>
<nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand text-center mx-auto" href="#" style="font-family: 'Pacifico', cursive; font-weight: 400; font-style: normal; font-size:60px;">Textealo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="subir_poema.php">Poemas</a>
                    </li>
                    <?php if (isset($_SESSION['nombre_usuario'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Perfil (<?php echo $_SESSION['nombre_usuario']; ?>)</a>
                        </li>
                        <?php if ($_SESSION['rol'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php">Administración</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Login
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" href="registro.php">Inicio de Sesion</a></li>
                                <li><a class="dropdown-item" href="registro.php">Registro</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                <form class="d-flex mt-3" role="search">
                    <input class="form-control me-2" type="search" placeholder="Buscame" aria-label="Search">
                    <button class="btn btn-success" type="submit">Busqueda</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<form action="subir_poema.php" method="get">
    <button type="submit">Sube un poemita !! </button>
</form>

 <br>
 <br>
 <br>
 <br>
<div class="card">
  <div class="card-details">
    <p class="text-title">Card title</p>
    <p class="text-body">Here are the details of the card</p>
  </div>
  <button class="card-button">Leer más...</button>
</div>

<div class="container mt-5">
    <h2 class="text-center">Poemas Subidos</h2>
    <div class="card-container">
        <?php if (!empty($poemas)): ?>
            <?php foreach ($poemas as $poema): ?>
                <div class="card mb-3">
                    <div class="card-details">
                        <p class="text-title"><?php echo htmlspecialchars($poema['titulo']); ?></p>
                        <p class="text-body"><?php echo htmlspecialchars($poema['contenido']); ?></p>
                        <p class="text-body"><strong>Subido por:</strong> <?php echo htmlspecialchars($poema['nombre_usuario']); ?></p>
                    </div>
                    <button class="card-button">Leer más...</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No hay poemas subidos aún.</p>
        <?php endif; ?>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
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
function mostrarPerfil() {
    document.getElementById('login-menu').classList.add('d-none');
    document.getElementById('profile-menu').classList.remove('d-none');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<!-- Bootstrap CSS -->

<!-- Bootstrap JS (para hacer funcionar el menú dropdown) -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<footer class="footer">
        <div class="container">
            <p>&copy; 2023 Derechos de autor Diego Quintana. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>