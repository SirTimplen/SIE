<?php
require 'conexion.php';
$idComida = $_POST['id'];
$cantidad = $_POST['stock'];
$estado = 'Pedido';
$fecha = date('Y-m-d H:i:s');
$stmt = $conexion->prepare("INSERT INTO pedido_stock (id_comida, cantidad, estado, fecha) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $idComida, $cantidad, $estado, $fecha);
$stmt->execute();
$stmt->close();
$conexion->close();
header('Location: indexAdmin.php');