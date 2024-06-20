<?php
require 'nav_bar.php';
session_start();

$host = "complist.mysql.database.azure.com";
$user = "complist";
$db_password = "ISI2023-2024";
$db = "sabercomer";
$conexion = new mysqli($host, $user, $db_password, $db);

$id_usuario = $_SESSION['id']; // Asegúrate de tener la sesión iniciada y el ID del usuario disponible

// Consulta para obtener los pedidos del usuario
$sql_mensajes = "SELECT mensajes.*,adiministrador.Nombre AS admin ,duenio.Nombre AS duenio FROM mensajes LEFT JOIN adiministrador ON 
adiministrador.id_usuario=mensajes.id_remitente LEFT JOIN duenio ON duenio.id_usuario=mensajes.id_remitente WHERE id_receptor = ? ";
$stmt_mensajes = $conexion->prepare($sql_mensajes);
$stmt_mensajes->bind_param("i", $id_usuario);
$stmt_mensajes->execute();
$result_mensajes = $stmt_mensajes->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fruitables - Pedido Histórico</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Historial de Mensajes</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item active text-white">Historial de Mensajes</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Pedidos Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Tus Mensajes</h1>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Remitente</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Mensaje</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($mensajes = $result_mensajes->fetch_assoc()) {
                    echo '
                            <tr>';
                    if($mensajes['admin']){
                        echo '<td>' . $mensajes['admin'] . '</td>';
                    }
                    else if($mensajes['duenio']){
                        echo '<td>' . $mensajes['duenio'] . '</td>';
                    }
                    echo'<td>' . $mensajes['fecha'] . '</td>
                                <td><button onclick="verMensaje('.$mensajes['id'].')">Ver mensaje</button></td>
                            </tr>';
                }
                $stmt_mensajes->close();
                $conexion->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Pedidos Page End -->

<!-- Back to Top -->
<a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/isotope/isotope.pkgd.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
<script>
    function verMensaje(id){
        window.location.href = "mensaje.php?id="+id;
    }
</script>
</body>
</html>

