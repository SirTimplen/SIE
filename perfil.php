<?php
global $conexion;
require 'nav_bar.php';

// Evitar iniciar sesión si ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Perfil de Usuario</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Búsqueda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="input-group w-75 mx-auto d-flex">
                    <form action="shop.php" method="GET" class="d-flex w-100">
                        <input type="search" name="query" class="form-control p-3" placeholder="Palabras clave" aria-describedby="search-icon-1">
                        <button type="submit" id="search-icon-1" class="input-group-text p-3 border-0" style="background: none;">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Aquí va el código HTML para la estructura de la página de perfil de usuario -->
<div class="container-fluid py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <h1 class="text-primary display-6">Perfil de Usuario</h1>
            <div class="profile-info">
                <?php
                require 'conexion.php';

                // Verificar si el usuario está autenticado
                if (!isset($_SESSION['correo'])) {
                    header("Location: login.php");
                    exit;
                }

                $correo = $_SESSION['correo'];
                $sql = "SELECT usuario.*, cliente.*, datospago.Número_tarjeta, datospago.CVV, datospago.Fecha_caducidad, datospago.Domicilio 
                            FROM usuario 
                            INNER JOIN cliente ON usuario.id = cliente.id_usuario 
                            LEFT JOIN datospago ON cliente.id_datosPago = datospago.id 
                            WHERE usuario.email = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                if (!$user) {
                    echo "No se encontraron datos para este usuario.";
                    exit;
                }

                $alta = $user['fecha_alta'];
                $nombre = $user['nombre'];
                $correo = $user['email'];
                $domicilio = isset($user['Domicilio']) ? $user['Domicilio'] : ''; // Verificar si Domicilio está definido
                $num_tarjeta = $user['Número_tarjeta'];
                $cvv = $user['CVV'];
                $fecha_caducidad = $user['Fecha_caducidad'];

                echo '<h2 id="Nombre">'.$nombre.'</h2>
                          <p id="Correo">'.$correo.'</p>
                          <p id="Alta">Fecha de alta: '.$alta.'</p>';

                // Verificar si el campo Domicilio está presente y no vacío
                if (!empty($domicilio)) {
                    echo '<p id="Domicilio">Domicilio: '.$domicilio.'</p>';
                } else {
                    echo '<p id="Domicilio">Domicilio: No disponible</p>';
                }

                echo '<p id="NumTarjeta">Número de Tarjeta: '.$num_tarjeta.'</p>
                          <p id="CVV">CVV: '.$cvv.'</p>
                          <p id="FechaCaducidad">Fecha de Caducidad: '.$fecha_caducidad.'</p>';
                ?>
            </div>
        </div>
    </div>
    <!-- Aquí va el código HTML para con botones de edición y eliminación -->
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <a href="pedidos.php" class="btn btn-primary">Historial de Pedidos</a>
            <a href="mis_reseñas.php" class="btn btn-primary">Ver mis reseñas</a>
            <a href="editarperfil.php" class="btn btn-primary">Editar Perfil</a>
            <a onclick="cerrar_sesion()" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
<script>
    function cerrar_sesion() {
        console.log("Cerrando sesión...");
        $.ajax({
            type: "POST",
            url: "apis.php?cerrar",
            data: {
                cerrar_sesion: true
            },
            dataType: "text",
            success: function(data) {
                console.log("Respuesta del servidor: ", data);
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }
</script>


</body>

</html>
