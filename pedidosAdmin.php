<?php
require 'nav_bar_admin.php';
session_start();

$host = "complist.mysql.database.azure.com";
$user = "complist";
$db_password = "ISI2023-2024";
$db = "sabercomer";
$conexion = new mysqli($host, $user, $db_password, $db);

$id_usuario = $_SESSION['id']; // Asegúrate de tener la sesión iniciada y el ID del usuario disponible

// Consulta para obtener los pedidos del usuario
$sql_pedidos = "SELECT pedidos.*, cliente.nombre 
                FROM pedidos 
                JOIN cliente ON pedidos.ID_Cliente = cliente.id_usuario";
$stmt_pedidos = $conexion->prepare($sql_pedidos);
$stmt_pedidos->execute();
$result_pedidos = $stmt_pedidos->get_result();
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
                    <form action="adminstracion_platos.php" method="GET" class="d-flex w-100">
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
    <h1 class="text-center text-white display-6">Historial de Pedidos</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="indexAdmin.php">Inicio</a></li>
        <li class="breadcrumb-item active text-white">Historial de Pedidos</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Pedidos Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Pedidos</h1>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Usuario</th>
                    <th scope="col">Fecha Pedido</th>
                    <th scope="col">Fecha Entrega</th>
                    <th scope="col">Domicilio Entrega</th>
                    <th scope="col">Precio Total</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Eliminar pedido</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($pedido = $result_pedidos->fetch_assoc()) {
                    echo '
                            <tr>
                                <td>' . $pedido['nombre'] . '</td>
                                <td>' . $pedido['Fecha_pedido'] . '</td>
                                <td>' . $pedido['Fecha_entrega'] . '</td>
                                <td>' . $pedido['Domicilio_entrega'] . '</td>
                                <td>' . number_format($pedido['Precio_total'], 2) . ' $</td>
                                <td>' . $pedido['Estado'] . '</td>
                                   <td><button class="btn btn-danger" onclick="eliminarPedido(' . $pedido['id'] . ')">Eliminar</button></td>
                                
                            </tr>';
                }
                $stmt_pedidos->close();
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
    function eliminarPedido(id) {
        var data = {
            id: id
        };

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "apis.php?eliminarPedido", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                var response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                    location.reload();
                } else {
                    console.error("Error: ", response.message);
                }
            } else {
                console.error("Error: ", xhr.status);
            }
        };
        xhr.send(JSON.stringify(data));
    }
</script>
</body>
</html>

