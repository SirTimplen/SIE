<?php
require 'nav_bar.php';
require 'conexion.php';
?>

<!DOCTYPE html>
<html lang="en">
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


        <!-- Hero Start -->
        <div class="container-fluid py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-12 col-lg-7">
                        <h4 class="mb-3 text-secondary">100% Comida Organica</h4>
                        <h1 class="mb-5 display-3 text-primary">Catálogo de platos elaborados</h1>
                        <div class="position-relative mx-auto">
                            <form action="shop.php" method="GET">
                                <input class="form-control border-2 border-secondary w-75 py-3 px-4 rounded-pill" type="text" name="query" placeholder="Búsqueda">
                                <button type="submit" class="btn btn-primary border-2 border-secondary py-3 px-4 position-absolute rounded-pill text-white h-100" style="top: 0; right: 25%;">Buscar</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Hero End -->


        <!-- Featurs Section Start -->
        <div class="container-fluid featurs py-5">
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-car-side fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Envio Gratuito</h5>
                                <p class="mb-0">Gratis hasta los $300</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-user-shield fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Seguridad de pago</h5>
                                <p class="mb-0">100% de seguridad de pago</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-exchange-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>30 Dias de devolucion</h5>
                                <p class="mb-0">30 Dias de devolucion</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fa fa-phone-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>24/7 Atencion al cliente</h5>
                                <p class="mb-0">Rapida Atencion al cliente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featurs Section End -->


        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <div class="tab-class text-center">
                    <div class="row g-4">
                        <div class="col-lg-4 text-start">
                            <h1>Nuestros Productos</h1>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                        <?php
                                        $host = "complist.mysql.database.azure.com";
                                        $user = "complist";
                                        $db_password = "ISI2023-2024";
                                        $db = "sabercomer";
                                        global $conexion;
                                        $conexion = new mysqli($host, $user, $db_password, $db);

                                        $query = isset($_GET['query']) ? $_GET['query'] : null;

                                        $sql = "SELECT * FROM comidas";
                                        $result = $conexion->query($sql);

                                        if ($query) {
                                            $stmt = $conexion->prepare("SELECT * FROM comidas WHERE Nombre LIKE ?");
                                            $search = "%{$query}%";
                                            $stmt->bind_param("s", $search);
                                        } else {
                                            $stmt = $conexion->prepare("SELECT * FROM comidas");
                                        }

                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $sql = "SELECT * FROM imagenes WHERE IDComida = ".$row['id'];
                                                $result_img = $conexion->query($sql);
                                                if($result_img->num_rows > 0) {
                                                    $row2 = $result_img->fetch_assoc();
                                                    $imagen = base64_encode($row2['imagen']);
                                                    $img_src = "data:image/jpg;base64,$imagen";
                                                } else {
                                                    $img_src = "img/single-item.jpg";
                                                }

                                                echo '<div class="col-md-5 col-lg-5 col-xl-3">
        <div class="rounded position-relative fruite-item">
            <div class="fruite-img">
                <img src="'.$img_src.'" id="imagen" class="img-fluid w-100 rounded-top"  alt="Image">
            </div>
            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                <h4>'.$row['Nombre'].'</h4>
                <p>'.$row['Ingredientes'].'</p>
                <div class="d-flex justify-content-between flex-lg-wrap">
                    <p class="text-dark fs-5 fw-bold mb-0">$'.$row['Precio'].'</p>
                    <a onclick="aniadirCarrito('.$row['id'].')" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i>Añadir al carrito</a>
                    <a onclick="ver_detalles('.$row['id'].')" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Ver detalles</a>
                </div>
            </div>
        </div>
    </div>';
                                            }
                                        }
                                        ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fruits Shop End-->



        <!-- Copyright Start -->
        <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>SaberComer</a>, All right reserved.</span>
                    </div>
                    <div class="col-md-6 my-auto text-center text-md-end text-white">
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
                            alert("Se añadio al carrito");
                        } else {
                            console.error("Error: ", response.message);
                        }
                    } else {
                        console.error("Error: ", xhr.status);
                    }
                };
                xhr.send(JSON.stringify(data));
            }
            function ver_detalles(id){
                console.log("sksl")
                window.location.href = "shop-detail.php?id="+id;
            }
        </script>
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
                            alert("Se añadio al carrito");
                        } else {
                            console.error("Error: ", response.message);
                        }
                    } else {
                        console.error("Error: ", xhr.status);
                    }
                };
                xhr.send(JSON.stringify(data));
            }
            function ver_detalles(id){
                window.location.href = "shop-detail.php?id="+id;
            }

        </script>
    </body>

</html>