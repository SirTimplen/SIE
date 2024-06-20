<?php
session_start();
require 'conexion.php'; // Incluye el archivo de conexión
global $conexion;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'conexion.php'; // Incluye el archivo de conexión

// Depuración: imprime las variables de sesión y POST
echo '<pre>';
print_r($_SESSION);
print_r($_POST);
echo '</pre>';

// Verifica si se han enviado todos los datos requeridos
if (isset($_POST['comentario'], $_POST['valoracion'], $_POST['id_comida'], $_POST['id_usuario'])) {
    $comentario = trim($_POST['comentario']);
    $valoracion = intval($_POST['valoracion']);
    $id_comida = intval($_POST['id_comida']);
    $id_usuario = intval($_POST['id_usuario']);

    // Verifica que los valores no son nulos o vacíos
    if (empty($comentario) || empty($valoracion) || empty($id_comida) || empty($id_usuario)) {
        echo "Uno o más valores están vacíos o no definidos.";
    } else {
        // Depuración: imprime los valores que serán insertados
        echo "Valores a insertar: comentario=$comentario, valoracion=$valoracion, id_comida=$id_comida, id_usuario=$id_usuario";

        // Prepara y ejecuta la consulta para insertar los datos en la tabla 'valoracion'
        if ($stmt = $conexion->prepare("INSERT INTO valoracion (id_comida, id_usuario, valoracion, Comentario) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param("iiis", $id_comida, $id_usuario, $valoracion, $comentario);
            if ($stmt->execute()) {
                // Redirige al usuario de nuevo a la página del producto después de insertar el comentario
                header("Location: shop-detail.php?id=$id_comida");
                exit;
            } else {
                echo "Error al insertar la valoración: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conexion->error;
        }
    }
} else {
    echo "Faltan datos necesarios para realizar la inserción.";
}
?>
