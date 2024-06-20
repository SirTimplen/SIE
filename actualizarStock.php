<?php
require 'conexion.php';
$idComida = $_POST['id'];
$cantidad = $_POST['stock'];
$sql = "SELECT stock FROM comidas WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idComida);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stock = $row['stock'];
$stock += $cantidad;
$stmt->close();
$stmt = $conexion->prepare("UPDATE comidas SET stock=? WHERE id=?");
$stmt->bind_param("ii", $stock, $idComida);
$stmt->execute();
$stmt->close();
$stmt = $conexion->prepare("UPDATE pedido_stock SET estado='Recibido' WHERE id_comida=?");
$stmt->bind_param("i", $idComida);
$stmt->execute();
$stmt->close();
$conexion->close();
header('Location: indexAdmin.php');
?>
