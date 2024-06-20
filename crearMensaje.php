<?php
require 'conexion.php';
require 'nav_bar_admin.php';
session_start();
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
    <title>Editar Perfil</title>
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
    <h1 class="text-center text-white display-6">Crear Mensajes</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="indexAdmin.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="mensajesAdmin.php">Mensajes</a></li>
        <li class="breadcrumb-item active text-white">Crear mensajes</li>
    </ol>
</div>
<!-- Single Page Header End -->

<form id="crearMensajeForm" action="gestionMensajes.php" method="post">
    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-8 col-xl-9">
                    <div id="prod_edit" class="row g-4">
                        <?php
                        // Fetch user details from 'usuario', 'cliente', and 'datospago' tables
                        $id = $_SESSION['id'];
                        // Display inputs for editing
                        $stmt = $conexion->prepare("SELECT usuario.*,cliente.Nombre AS cliente FROM usuario INNER JOIN cliente ON cliente.id_usuario=usuario.id WHERE usuario.id != ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        echo '
                            <div class="col-lg-6">
                                <div class="border rounded p-4">
                                    <h2>Usuario</h2>
                                    <input type="text" id="filter" placeholder="Filtrar resultados">
                                    <select name="receptor" id="receptor">';
                        echo '<option value="0" >Todos los clientes</option>';
                        echo '<option value="Jefe" >Jefe</option>';
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['cliente'].'#'. $row['id'] . '</option>';
                        }
                        echo '</select>';
                                echo '</div>
                                <div class="border rounded p-4 mt-4">
                                    <h2>Mensaje</h2>
                                    <textarea class="form-control" name="mensaje" id="mensaje" rows="5" required></textarea>
                                </div
                            </div>';

                        $conexion->close();
                        ?>
                        <!-- Submit Button -->
                        <button type="submit" id="editar" class="btn btn-warning rounded-pill px-4 py-2 mt-4"><i class="fa fa-edit me-2"></i> Mandar mensaje</button>
                    </div>
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
<script>document.getElementById('filter').addEventListener('input', function(e) {
        console.log("dadadas");
        var filter = e.target.value.toUpperCase();
        var options = document.getElementById('receptor').options;

        for (var i = 0; i < options.length; i++) {
            var optionText = options[i].text.toUpperCase();
            if (optionText.indexOf(filter) > -1) {
                options[i].style.display = "";
            } else {
                options[i].style.display = "none";
            }
        }
    });</script>

</body>

</html>