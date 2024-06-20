<?php
require 'conexion.php';
session_start();

// Obtener los datos del formulario
$id_usuario = $_SESSION['id'];
$domicilio_entrega = $_POST['domicilio'];
$precio_total = $_POST['precio_total'];
$estado = "En preparación";
$fecha_pedido = date("Y-m-d H:i:s");
$fecha_entrega = date("Y-m-d H:i:s", strtotime("+14 days"));

// Preparar la consulta SQL para insertar en la tabla pedidos
$sql = "INSERT INTO pedidos (ID_Cliente, Fecha_pedido, Fecha_entrega, Domicilio_entrega, Precio_total, Estado) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if ($stmt) {
    $stmt->bind_param("isssds", $id_usuario, $fecha_pedido, $fecha_entrega, $domicilio_entrega, $precio_total, $estado);

    if ($stmt->execute()) {
        // Obtener el ID del pedido recién insertado
        $id_pedido = $stmt->insert_id;

        // Consulta para obtener los detalles del carrito
        $sql = "SELECT * FROM carrito WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($item = $result->fetch_assoc()) {
            // Insertar los detalles del carrito en la tabla comidas_de_pedidos
            $sql = "INSERT INTO comidas_de_pedidos (id_pedido, id_comida, cantidad) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("iii", $id_pedido, $item['id_comida'], $item['cantidad']);
            $stmt->execute();
        }

        // Vaciar el carrito
        $sql = "DELETE FROM carrito WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        // Redirigir a la página de historial de pedidos
        header("Location: pedidos.php");
    } else {
        echo json_encode(["status" => "error", "message" => "Error al procesar el pedido"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
}
?>
