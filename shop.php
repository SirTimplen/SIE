<?php
require 'nav_bar.php';
require 'conexion.php';
session_start();
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
            <h1 class="text-center text-white display-6">Tienda</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active text-white">Tienda</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <h1 class="mb-4">Tienda de comidas</h1>
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <div class="col-xl-3">
                                <form id="search-form" class="input-group w-100 mx-auto d-flex" method="GET">
                                    <input type="search" id="search-input" name="query" class="form-control p-3" placeholder="Palabras clave" aria-describedby="search-icon-1">
                                    <span onclick="document.getElementById('search-form').submit();" id="search-icon-1" class="input-group-text p-3" style="cursor:pointer;">
                                    <i class="fa fa-search"></i>
                                </span>
                                </form>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-9">
                                <div class="row g-4 justify-content-center">
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

    echo '<div class="col-md-6 col-lg-6 col-xl-4">
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
        <!-- Fruits Shop End-->




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
