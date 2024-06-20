<?php
require 'nav_bar.php'
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
    <!-- Modal Search End -->


    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Compra</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item"><a href="cart.php">Carrito</a></li>
            <li class="breadcrumb-item active text-white">Compra</li>
        </ol>
    </div>


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4">Resumen del Pedido</h1>
                <form id="checkout-form" action="gestionar_checkout.php" method="POST">
                    <div class="row g-5">
                        <div class="col-md-12 col-lg-6 col-xl-7">
                            <?php

                            $host = "complist.mysql.database.azure.com";
                            $user = "complist";
                            $db_password = "ISI2023-2024";
                            $db = "sabercomer";
                            $conexion = new mysqli($host, $user, $db_password, $db);

                            $id_usuario = $_SESSION['id']; // Asegúrate de tener la sesión iniciada y el ID del usuario disponible

                            // Obtener datos del cliente
                            $sql_cliente = "SELECT cliente.nombre, datospago.Domicilio, datospago.Número_tarjeta, datospago.Teléfono 
                                    FROM cliente 
                                    JOIN datospago ON cliente.id_datosPago = datospago.id 
                                    WHERE cliente.id_usuario = ?";
                            $stmt_cliente = $conexion->prepare($sql_cliente);
                            $stmt_cliente->bind_param("i", $id_usuario);
                            $stmt_cliente->execute();
                            $result_cliente = $stmt_cliente->get_result();
                            $cliente = $result_cliente->fetch_assoc();

                            // Mostrar datos del cliente
                            echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-item">
                                <label class="form-label my-3">Nombre:</label>
                                <p id="nombre">' . $cliente['nombre'] . '</p>
                                <input type="hidden" name="nombre" value="' . $cliente['nombre'] . '">
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Domicilio:</label>
                                <p id="domicilio">' . $cliente['Domicilio'] . '</p>
                                <input type="hidden" name="domicilio" value="' . $cliente['Domicilio'] . '">
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Número de Tarjeta:</label>
                                <p id="numero_tarjeta">' . $cliente['Número_tarjeta'] . '</p>
                                <input type="hidden" name="numero_tarjeta" value="' . $cliente['Número_tarjeta'] . '">
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Teléfono:</label>
                                <p id="telefono">' . $cliente['Teléfono'] . '</p>
                                <input type="hidden" name="telefono" value="' . $cliente['Teléfono'] . '">
                            </div>
                        </div>
                    </div>';

                            $stmt_cliente->close();
                            ?>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xl-5">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // Consulta para obtener los detalles del carrito
                                    $sql_carrito = "SELECT comidas.*, carrito.cantidad 
                                                FROM comidas 
                                                LEFT JOIN carrito ON carrito.id_comida = comidas.id 
                                                WHERE id_usuario = ?";
                                    $stmt_carrito = $conexion->prepare($sql_carrito);
                                    $stmt_carrito->bind_param("i", $id_usuario);
                                    $stmt_carrito->execute();
                                    $result_carrito = $stmt_carrito->get_result();

                                    // Inicializa el subtotal
                                    $subtotal = 0;

                                    while ($comida = $result_carrito->fetch_assoc()) {
                                        $total_item = $comida['Precio'] * $comida['cantidad'];
                                        $subtotal += $total_item;
                                        echo '
                                    <tr>
                                        <td>' . $comida['Nombre'] . '</td>
                                        <td>' . $comida['Precio'] . ' $</td>
                                        <td>' . $comida['cantidad'] . '</td>
                                        <td>' . $total_item . ' $</td>
                                    </tr>';
                                    }

                                    $stmt_carrito->close();

                                    // Define el coste de envío
                                    $shipping_cost = 3.00;

                                    // Calcula el total final
                                    $total = $subtotal + $shipping_cost;

                                    $conexion->close();
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end">Subtotal:</td>
                                        <td><?php echo number_format($subtotal, 2); ?> $</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">Envío:</td>
                                        <td><?php echo number_format($shipping_cost, 2); ?> $</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">Total:</td>
                                        <td><?php echo number_format($total, 2); ?> $</td>
                                        <input type="hidden" name="precio_total" value="<?=$total?>">
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                            <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Hacer compra</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Checkout Page End -->

        <script>
            document.getElementById('checkout-form').addEventListener('submit', function(event) {
                const nombre = document.getElementById('nombre').innerText.trim();
                const domicilio = document.getElementById('domicilio').innerText.trim();
                const numero_tarjeta = document.getElementById('numero_tarjeta').innerText.trim();
                const telefono = document.getElementById('telefono').innerText.trim();

                if (!nombre || !domicilio || !numero_tarjeta || !telefono) {
                    event.preventDefault();
                    alert('Por favor, complete todos los campos requeridos.');
                    return false;
                }

                alert('Pedido realizado');
            });
        </script>

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
    </body>

</html>