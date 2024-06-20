<?php
global $conexion;
require 'conexion.php';
require 'nav_bar.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit;
}

// Obtener el ID de la reseña a editar desde la URL
if (!isset($_GET['id'])) {
    header("Location: mis_reseñas.php");
    exit;
}
$id_resena = $_GET['id'];

// Consultar la reseña a editar
$sql = "SELECT valoracion.id AS id_resena, comidas.Nombre AS comida_nombre, valoracion.valoracion, valoracion.Comentario 
        FROM valoracion 
        INNER JOIN comidas ON valoracion.id_comida = comidas.id 
        WHERE valoracion.id = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    die('Error en prepare: ' . htmlspecialchars($conexion->error));
}
$stmt->bind_param("i", $id_resena);
if (!$stmt->execute()) {
    die('Error en execute: ' . htmlspecialchars($stmt->error));
}
$result = $stmt->get_result();
if (!$result) {
    die('Error en get_result: ' . htmlspecialchars($stmt->error));
}
$review = $result->fetch_assoc();
if (!$review) {
    die('No se encontró la reseña.');
}

$comida_nombre = htmlspecialchars($review['comida_nombre']);
$valoracion_actual = $review['valoracion'];
$comentario_actual = htmlspecialchars($review['Comentario']);
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
<div class="container-fluid py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <h1 class="text-primary display-6">Editar Reseña</h1>
            <form action="guardar_edicion_resena.php" method="post">
                <div class="form-group">
                    <label for="comida_nombre">Comida:</label>
                    <input type="text" class="form-control" id="comida_nombre" value="<?php echo $comida_nombre; ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="valoracion">Valoración:</label>
                    <select class="form-select" id="valoracion" name="valoracion">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php if ($i == $valoracion_actual) echo 'selected'; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comentario">Comentario:</label>
                    <textarea class="form-control" id="comentario" name="comentario" rows="5"><?php echo $comentario_actual; ?></textarea>
                </div>
                <input type="hidden" name="id_resena" value="<?php echo $id_resena; ?>">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="mis_reseñas.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
</body>
</html>
