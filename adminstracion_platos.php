<?php
require 'nav_bar_admin.php';
require 'conexion.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>

    </style>
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
    <h1 class="text-center text-white display-6">Administrar platos</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item active text-white">Administrar platos</li>
    </ol>
</div>

<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <h1 class="mb-4">Catálogo</h1>
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
                        <!--añade un boton para añadir un plato-->
                        <div class="d-flex justify-content-center mt-4">
                            <a onclick="window.location.href = 'aniadirplato.php'" class="btn btn-primary">Añadir Plato</a>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-9">
                        <div class="row g-4 justify-content-center">
                            <?php

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
                                    $nombre = $row['Nombre'];
                                    $precio = $row['Precio'];
                                    $ingredientes = $row['Ingredientes'];
                                    $id = $row['id'];
                                    echo '<div class="col-md-6 col-lg-6 col-xl-4">
                                    <div class="rounded position-relative fruite-item">
                                        <div class="fruite-img">';
                                            $sql2 = "SELECT * FROM imagenes WHERE IDComida = '$id'";
                                            $result2 = $conexion->query($sql2);
                                            if($result2->num_rows > 0) {
                                                $row2 = $result2->fetch_assoc();
                                                $imagen = base64_encode($row2['imagen']);
                                                echo "<img src='data:image/jpg;base64,$imagen' id='imagen' class='img-fluid w-100 rounded-top' alt=''>";
                                            }
                                            else{
                                                echo "<img src='img/single-item.jpg' class='img-fluid w-100 rounded-top' alt=''>";
                                            }
                                            echo '</div>
                                        <div class="p-4 border
                                        border-secondary border-top-0 rounded-bottom">
                                            <h4>'.$nombre.'</h4>
                                            <p>'.$ingredientes.'</p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold mb-0">$'.$precio.'</p>
                                                <a onclick="editar('.$id.')" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Editar Producto</a>
                                                <a onclick="eliminar('.$id.')" class="btn btn-danger rounded-pill px-4 py-2 mb-4"><i class="fa fa-trash me-2"></i> Eliminar</a>
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
//pon el id del producto a editar en la url de editar_plato.php
function editar(id) {
    window.location.href = "editarplato.php?id="+id;
}
function pedStock(id) {
    window.location.href = "pedirstock.php?id="+id;
}
function eliminar(id) {
    $.ajax({
        type: "POST",
        url: "apis.php?eliminarplatos",
        data: JSON.stringify({id: id}),
        contentType: "application/json",
        success: function(response) {
            console.log(response);
            window.location.href = "adminstracion_platos.php";
        }
    });
}
</script>
</body>

</html>