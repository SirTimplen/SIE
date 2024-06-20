<?php
global $conexion;
require 'nav_bar.php';
require 'conexion.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit;
}

// Obtener el ID del usuario
$correo = $_SESSION['correo'];
$sql = "SELECT id FROM usuario WHERE email = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    die('Error en prepare: ' . htmlspecialchars($conexion->error));
}
$stmt->bind_param("s", $correo);
if (!$stmt->execute()) {
    die('Error en execute: ' . htmlspecialchars($stmt->error));
}
$result = $stmt->get_result();
if (!$result) {
    die('Error en get_result: ' . htmlspecialchars($stmt->error));
}
$user = $result->fetch_assoc();
if (!$user) {
    die('No se encontró el usuario.');
}
$id_usuario = $user['id'];

// Consulta para obtener las reseñas del usuario
$sql = "SELECT v.id, c.Nombre AS comida_nombre, v.valoracion, v.Comentario 
        FROM valoracion v
        INNER JOIN comidas c ON v.id_comida = c.id 
        WHERE v.id_usuario = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    die('Error en prepare: ' . htmlspecialchars($conexion->error));
}
$stmt->bind_param("i", $id_usuario);
if (!$stmt->execute()) {
    die('Error en execute: ' . htmlspecialchars($stmt->error));
}
$reviews = $stmt->get_result();
if (!$reviews) {
    die('Error en get_result: ' . htmlspecialchars($stmt->error));
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
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Reseñas</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="perfil.php">Perfil</a></li>
        <li class="breadcrumb-item active text-white">Reseñas</li>
    </ol>
</div>
<div class="container-fluid py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <h1 class="text-primary display-6">Mis Reseñas</h1>
            <div class="reviews">
                <?php
                if ($reviews->num_rows > 0) {
                    echo "<p>Se encontraron " . $reviews->num_rows . " reseñas.</p>";
                    while ($review = $reviews->fetch_assoc()) {
                        echo '<div class="card mb-4">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($review['comida_nombre']) . '</h5>';
                        echo '<div class="d-flex mb-3">';
                        // Mostrar valoración con estrellas
                        for ($i = 0; $i < 5; $i++) {
                            echo '<i class="fa fa-star ' . ($i < $review['valoracion'] ? 'text-secondary' : '') . '"></i>';
                        }
                        echo '</div>';
                        echo '<p class="card-text">' . htmlspecialchars($review['Comentario']) . '</p>';
                        // Enlace para editar reseña
                        echo '<a href="editar_resena.php?id=' . $review['id'] . '" class="btn btn-primary">Editar Reseña</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No has realizado ninguna reseña aún.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
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
<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
