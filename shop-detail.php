<?php
global $conexion;
session_start();
require 'nav_bar.php';
require 'conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id) {
    // Consulta para obtener los detalles del producto
    if ($stmt = $conexion->prepare("SELECT * FROM comidas WHERE id = ?")) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $sql = "SELECT * FROM imagenes WHERE IDComida = ".$product['id'];
        $result_img = $conexion->query($sql);
        if($result_img->num_rows > 0) {
            $row2 = $result_img->fetch_assoc();
            $imagen = base64_encode($row2['imagen']);
            $img_src = "data:image/jpg;base64,$imagen";
        } else {
            $img_src = "img/single-item.jpg";
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta de productos: " . $conexion->error;
    }

    // Consulta para obtener las reseñas del producto
    if ($stmt = $conexion->prepare("SELECT v.*, c.nombre FROM valoracion v JOIN cliente c ON v.id_usuario = c.id WHERE v.id_comida = ?")){
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $reviews = $stmt->get_result();
    } else {
        echo "Error en la preparación de la consulta de valoraciones: " . $conexion->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<style>
    #imagen {
        width: 100%;
        height: 100%;
    }
</style>
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
        <h1 class="text-center text-white display-6">Detalles</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item"><a href="shop.php">Tienda</a></li>
            <li class="breadcrumb-item active text-white">Detalles</li>
        </ol>
    </div>
    <!-- Single Page Header End -->
    <!-- Single Product Start -->
    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-8 col-xl-9">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                <a href="#">
                                    <?php

                                    echo '<img src='.$img_src.' id="imagen" alt="">';
                                    ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <?php if ($product): ?>
                                <h4 class="fw-bold mb-3"><?php echo htmlspecialchars($product['Nombre']); ?></h4>
                                <h5 class="fw-bold mb-3">$<?php echo htmlspecialchars($product['Precio']); ?></h5>

                                <p class="mb-4"><?php echo htmlspecialchars($product['Ingredientes']); ?></p>
                                <div class="input-group quantity mb-5" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center border-0" value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <a href="#" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary">
                                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Añadir al carrito
                                </a>
                            <?php else: ?>
                                <p>Producto no encontrado.</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-12">
                            <nav>
                                <div class="nav nav-tabs mb-3">
                                    <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                            id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                            aria-controls="nav-about" aria-selected="true">Descripción</button>
                                    <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                            id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                            aria-controls="nav-mission" aria-selected="false">Reseñas</button>
                                </div>
                            </nav>
                            <div class="tab-content mb-5">
                                <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                    <p>The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc.
                                        Susp endisse ultricies nisi vel quam suscipit </p>
                                    <p>Sabertooth peacock flounder; chain pickerel hatchetfish, pencilfish snailfish filefish Antarctic
                                        icefish goldeye aholehole trumpetfish pilot fish airbreathing catfish, electric ray sweeper.</p>
                                    <div class="px-2">
                                        <div class="row g-4">
                                            <div class="col-6">
                                                <?php
                                                echo
                                                '<div class="row bg-light align-items-center text-center justify-content-center py-2">
                                                    <div class="col-6">
                                                        <p class="mb-0">Peso</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0">'.$product["Peso"].'</p>
                                                    </div>
                                                </div>
                                                <div class="row text-center align-items-center justify-content-center py-2">
                                                    <div class="col-6">
                                                        <p class="mb-0">Calorías</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0">'.$product["Calorías"].'</p>
                                                    </div>
                                                </div>
                                                <div class="row bg-light text-center align-items-center justify-content-center py-2">
                                                    <div class="col-6">
                                                        <p class="mb-0">Stock disponible</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0">'.$product["Stock"].'</p>
                                                    </div>
                                                </div>';

                                                    ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                    <?php if (isset($reviews) && $reviews->num_rows > 0): ?>
                                        <?php while ($review = $reviews->fetch_assoc()): ?>
                                            <div class="d-flex mb-4">
                                                <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                                <div class="">
                                                    <div class="d-flex justify-content-between">
                                                        <h5><?php echo htmlspecialchars($review['nombre']); ?></h5>
                                                        <div class="d-flex mb-3" style="margin-left: 10px; margin-top: 5px">
                                                            <?php for ($i = 0; $i < 5; $i++): ?>
                                                                <i class="fa fa-star <?php echo $i < $review['valoracion'] ? 'text-secondary' : ''; ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <p><?php echo htmlspecialchars($review['Comentario']); ?></p>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p>No hay reseñas.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Formulario para dejar un comentario -->
                        <form action="submit_review.php" method="POST">
                            <h4 class="mb-5 fw-bold">Deja una reseña</h4>
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="border-bottom rounded my-4">
                                        <textarea name="comentario" class="form-control border-0" cols="30" rows="8" placeholder="Tu reseña" spellcheck="false" required></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="d-flex">
                                        <div class="rating d-flex align-items-center border rounded px-3 py-2 mb-4 me-4">
                                            <span class="me-3">Tu valoración:</span>
                                            <select name="valoracion" class="form-select" required>
                                                <option value="1">1 Estrella</option>
                                                <option value="2">2 Estrellas</option>
                                                <option value="3">3 Estrellas</option>
                                                <option value="4">4 Estrellas</option>
                                                <option value="5">5 Estrellas</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn border border-secondary text-primary rounded-pill px-4 py-3">Publicar reseña</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id_comida" value="<?php echo htmlspecialchars($id); ?>">
                            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Single Product End -->


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