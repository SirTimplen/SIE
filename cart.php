
<?php
require 'nav_bar.php';
require 'conexion.php';
session_start();

// Contar el total de ítems en el carrito
$total_items = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_items += $item['quantity'];
    }
    $_SESSION['total_items'] = $total_items;
} else {
    $_SESSION['total_items'] = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Fruitables - Vegetable Website Template</title>
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




<!-- Modal Search Start -->
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
<!-- Modal Search End -->


<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Carrito</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item active text-white">Carrito</li>
    </ol>
</div>
<!-- Single Page Header End -->


<!-- Cart Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Total</th>
                    <th scope="col">Gestión</th>
                </tr>
                </thead>
                <tbody id="carrito">

                        <?php

                $host = "complist.mysql.database.azure.com";
                $user = "complist";
                $db_password = "ISI2023-2024";
                $db = "sabercomer";
                global $conexion;
                $conexion = new mysqli($host, $user, $db_password, $db);

                $id_usuario = $_SESSION['id']; // Asegúrate de tener la sesión iniciada y el ID del usuario disponible

                // Consulta para obtener los detalles del carrito
                $sql = "SELECT comidas.*, carrito.cantidad FROM comidas LEFT JOIN carrito ON carrito.id_comida = comidas.id WHERE id_usuario = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $result = $stmt->get_result();

                // Inicializa el subtotal
                $subtotal = 0;

                while ($comida = $result->fetch_assoc()) {
                    $total_item = $comida['Precio'] * $comida['cantidad'];
                    $subtotal += $total_item;
                    echo '
                        <tr>
                           
                            <td>
                                <p class="mb-0 mt-4">' . $comida['Nombre'] . '</p>
                            </td>
                            <td>
                                <p class="mb-0 mt-4">' . $comida['Precio'] . ' $</p>
                            </td>
                            <td>
                                <div class="input-group quantity mt-4" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button onclick="decrementarCantidad('.$comida['id'].')" class="btn btn-sm btn-minus rounded-circle bg-light border" >
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center border-0" value="' . $comida['cantidad'] . '">
                                    <div class="input-group-btn">
                                        <button onclick="aniadirCarrito('.$comida['id'].')" class="btn btn-sm btn-plus rounded-circle bg-light border">
                                            <i class="fa fa-plus "></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0 mt-4" id="precio">' . $total_item . ' $</p>
                            </td>
                            <td>
                                <button onclick="eliminarCarrito('.$comida['id'].')" class="btn btn-md rounded-circle bg-light border mt-4" >
                                    <i class="fa fa-times text-danger"></i>
                                </button>
                            </td>
                        </tr>';
                }

                $stmt->close();
                $conexion->close();

                // Define el coste de envío
                $shipping_cost = 3.00;

                // Calcula el total final
                $total = $subtotal + $shipping_cost;

                ?>

                </tbody>
            </table>
        </div>
        <div class="row g-4 justify-content-end">
            <div class="col-8"></div>
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="p-4">
                        <h1 class="display-6 mb-4">Total <span class="fw-normal">Carrito</span></h1>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Subtotal:</h5>
                            <p class="mb-0"><?php echo $subtotal; ?> $</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0 me-4">Costes de envío</h5>
                            <div class="">
                                <p class="mb-0">Precio Standard: <?php echo $shipping_cost; ?> $</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                        <h5 class="mb-0 ps-4 me-4">Total</h5>
                        <p class="mb-0 pe-4"><?php echo $total; ?> $</p>
                    </div>
                    <button onclick="window.location.href='checkout.php'" class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" type="button">Proceder a Compra</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart Page End -->

<!-- Copyright Start -->
<div class="container-fluid copyright bg-dark py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>SaberComer</a>, All right reserved.</span>
            </div>
            <div class="col-md-6 my-auto text-center text-md-end text-white">
                <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
            </div>
        </div>
    </div>
</div>
<!-- Copyright End -->



<!-- Back to Top -->
<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>


<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>

<script>
    function aniadirCarrito(id){
        var cantidad = 1;
        var data = {
            id: id,
            cantidad: cantidad
        };

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "apis.php?añadirCarrito", true);
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
    function decrementarCantidad(id){
        var cantidad = 1;
        var data = {
            id: id,
            cantidad: cantidad
        };

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "apis.php?decrementarCantidad", true);
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

    function eliminarCarrito(id){
        var data = {
            id: id
        };

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "apis.php?eliminarCarrito", true);
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
