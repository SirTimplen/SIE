<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

require 'conexion.php';
$data = json_decode(file_get_contents('php://input'), true);
$id_comida = isset($data['id']) ? $data['id'] : null;
$id_usuario = $_SESSION['id']; // Asegúrate de tener la sesión iniciada y el ID del usuario disponible

error_log("id_comida: " . $id_comida); // Registro de depuración
error_log("id_usuario: " . $id_usuario); // Registro de depuración

if (!$id_comida || !$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

// Comprueba si el producto ya está en el carrito
$sql_check = "SELECT * FROM carrito WHERE id_usuario = ? AND id_comida = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("ii", $id_usuario, $id_comida);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Si el producto ya está en el carrito, incrementa la cantidad
    $sql_update = "UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = ? AND id_comida = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("ii", $id_usuario, $id_comida);
    $stmt_update->execute();
} else {
    // Si el producto no está en el carrito, agrégalo con cantidad 1
    $sql_insert = "INSERT INTO carrito (id_usuario, id_comida, cantidad) VALUES (?, ?, 1)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bind_param("ii", $id_usuario, $id_comida);
    $stmt_insert->execute();
}

// Obtén el nuevo número total de artículos en el carrito
$sql_count = "SELECT SUM(cantidad) AS total_items FROM carrito WHERE id_usuario = ?";
$stmt_count = $conexion->prepare($sql_count);
$stmt_count->bind_param("i", $id_usuario);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_items = $row_count['total_items'];

echo json_encode(['success' => true, 'total_items' => $total_items]);

$stmt_check->close();
$stmt_update->close();
$stmt_insert->close();
$stmt_count->close();
$conexion->close();
?>
