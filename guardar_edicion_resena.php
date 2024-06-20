<?php
global $conexion;
require 'conexion.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit;
}

// Verificar si se han recibido los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos del formulario
    $id_resena = $_POST['id_resena'];
    $comentario = $_POST['comentario'];
    $valoracion = $_POST['valoracion'];

    // Validación básica (puedes agregar más validaciones según sea necesario)
    if (empty($comentario)) {
        die("El comentario no puede estar vacío.");
    }

    // Actualizar la reseña en la base de datos
    $sql = "UPDATE valoracion SET Comentario = ?, valoracion = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        die('Error en prepare: ' . htmlspecialchars($conexion->error));
    }
    $stmt->bind_param("sii", $comentario, $valoracion, $id_resena);
    if (!$stmt->execute()) {
        die('Error en execute: ' . htmlspecialchars($stmt->error));
    }

    // Redirigir de vuelta a la página de mis reseñas
    header("Location: mis_reseñas.php");
    exit;
} else {
    // Si se intenta acceder a este script directamente sin datos POST, redirigir a mis_reseñas.php
    header("Location: mis_reseñas.php");
    exit;
}
?>
