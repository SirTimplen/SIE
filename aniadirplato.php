<?php
require 'nav_bar.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        #imagen {
            width: 100%;
            height: 100%;
        }
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
                <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="input-group w-75 mx-auto d-flex">
                    <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                    <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Search End -->


<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Adminsitracion de platos</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Pages</a></li>
        <li class="breadcrumb-item active text-white">Shop</li>
    </ol>
</div>
<form id="aniadirP" action="gestionAniadirPlato.php" method="post" enctype="multipart/form-data">
    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-8 col-xl-9">
                    <div id="prod_edit" class="row g-4">
                        </div>

                        <!-- create table comidas
    (
        id               int auto_increment
            primary key,
        Nombre           varchar(100)                             not null,
        Precio           decimal(10, 2)                           not null,
        Peso             decimal(10, 2)                           not null,
        Calorías         int                                      null,
        Ingredientes     text                                     null,
        Valoración_media decimal(3, 2)                            null,
        Tipo             enum ('Entrante', 'Principal', 'Postre') not null
    ); -->
                        <div class='col-lg-6'>
                                <div class='border rounded p-4'>
                                    <h2 class='mb-4'>Añadir Plato</h2>
                                    <div class='mb-3'>
                                        <label for='nombre' class='form-label'>Nombre</label>
                                        <input type='text' class='form-control' name='nombre' placeholder='Nombre'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='Category' class='form-label'>Category</label>
                                        <select name='Category'>
                                            <option value='Entrante'>Entrante</option>
                                            <option value='Principal'>Principal</option>
                                            <option value='Postre'>Postre</option>";
                                        </select>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='precio' class='form-label'>Precio</label>
                                        <input type='text' class='form-control' name='precio' placeholder='Precio'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='peso' class='form-label'>Peso</label>
                                        <input type='text' class='form-control' name='peso' placeholder='Peso'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='calorias' class='form-label'>Calorías</label>
                                        <input type='text' class='form-control' name='calorias' placeholder='Precio'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='decripcion' class='form-label'>Descripción</label>
                                        <textarea class='form-control' name='descripcion' rows='3' placeholder='Descripción' ></textarea>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='stock' class='form-label'>Stock</label>
                                        <input type='text' class='form-control' name='stock' placeholder='Stock'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='imagenes' class='form-label'>Imagen</label>
                                        <input type='file' class='btn btn-primary' name='imagenes' accept='image/jpeg, image/png, image/jpg'>
                                    </div>
                                </div>
                            </div>
                        <!-- Botones de edición y eliminación -->
                        <button type="submit" id="aniadir" class="btn btn-warning rounded-pill px-4 py-2 mb-4"><i class="fa fa-edit me-2"></i> Añadir</button>
                    </div>
                </div>
            </div>
        </div>
</form>



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